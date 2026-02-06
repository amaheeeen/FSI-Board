<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryResource\Pages;
use App\Models\Inventory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    // protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Operations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('item_name')->required(),
                FileUpload::make('image')->image()->directory('inventory'),
                TextInput::make('warehouse_location')->required(),
                TextInput::make('stock')->numeric()->required(),
                TextInput::make('reorder_level')->numeric()->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image'),
                TextColumn::make('item_name')->searchable()->sortable(),
                TextColumn::make('warehouse_location'),
                TextColumn::make('stock')
                    ->sortable()
                    ->color(fn (Inventory $record) => $record->stock < 10 ? 'danger' : 'success')
                    ->weight('bold'),
                TextColumn::make('reorder_level')->label('Reorder Point'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('transfer')
                    ->label('Transfer Stock')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->form([
                        TextInput::make('amount')->numeric()->required(),
                        TextInput::make('destination_warehouse')->required(),
                    ])
                    ->action(function (Inventory $record, array $data) {
                        // Logic for transfer (simplified: just deduct from here for now, or create new record)
                        // Ideally we would have a Transaction log or StockMovement model.
                        // For now, let's just update the location if it's a full move, or decrease stock.
                        // Implemented: Decrease stock from current, create/update record at destination.
                        
                        $record->decrement('stock', $data['amount']);
                        
                        $dest = Inventory::firstOrCreate(
                            ['item_name' => $record->item_name, 'warehouse_location' => $data['destination_warehouse']],
                            ['image' => $record->image, 'reorder_level' => $record->reorder_level]
                        );
                        $dest->increment('stock', $data['amount']);
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
            'edit' => Pages\EditInventory::route('/{record}/edit'),
        ];
    }
}

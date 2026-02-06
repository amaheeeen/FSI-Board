<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JournalResource\Pages;
use App\Models\Journal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JournalResource extends Resource
{
    protected static ?string $model = Journal::class;

    // protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('reference_number')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('reference_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50),
                Tables\Columns\TextColumn::make('total_debit')
                    ->label('Debit')
                    ->money('IDR')
                    ->state(fn (Journal $record) => $record->details()->sum('debit'))
                    ->color('success'),
                Tables\Columns\TextColumn::make('total_credit')
                    ->label('Credit')
                    ->money('IDR')
                    ->state(fn (Journal $record) => $record->details()->sum('credit'))
                    ->color('danger'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Disable bulk delete for Journals usually? 
                // Allowing delete only if needed, but for now standard.
            ])
            ->checkIfRecordIsSelectableUsing(fn () => false); // Disable selection?
    }

    public static function getRelations(): array
    {
        return [
            // Can add RelationManager for Details later if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJournals::route('/'),
            // 'create' => Pages\CreateJournal::route('/create'), // Disable Create
            // 'edit' => Pages\EditJournal::route('/{record}/edit'), // Disable Edit
            'view' => Pages\ViewJournal::route('/{record}'),
        ];
    }
    
    public static function canCreate(): bool
    {
       return false;
    }
}

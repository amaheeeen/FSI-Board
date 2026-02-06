<?php

namespace App\Filament\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use App\Models\Packet;

class AgentMarketplace extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Marketplace';
    protected static ?string $title = 'Umroh & Hajj Marketplace';

    protected static string $view = 'filament.pages.agent-marketplace';

    public function table(Table $table): Table
    {
        return $table
            ->query(Packet::query()->where('status', true))
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Stack::make([
                    ImageColumn::make('image') // Assuming image field exists, or use placeholder
                        ->height('200px')
                        ->defaultImageUrl('https://placehold.co/600x400?text=Packet+Image')
                        ->extraImgAttributes(['class' => 'object-cover w-full rounded-t-lg']),
                    TextColumn::make('name')
                        ->weight('bold')
                        ->size(TextColumn\TextColumnSize::Large)
                        ->extraAttributes(['class' => 'mt-2']),
                    TextColumn::make('price')
                        ->formatStateUsing(fn (string $state): string => 'IDR ' . number_format($state, 0, ',', '.'))
                        ->color('danger') // Red for Price
                        ->weight('bold')
                        ->size(TextColumn\TextColumnSize::Large),
                    TextColumn::make('start_date')
                        ->date()
                        ->prefix('Departure: ')
                        ->color('gray'),
                ])->space(3),
            ])
            ->actions([
                Action::make('book')
                    ->label('Pesan Paket') // "Book Now" in Indonesian
                    ->button()
                    ->color('danger') // Red for "Book Now" per instructions
                    ->extraAttributes(['class' => 'w-full']) // Full width
                    ->url(fn (Packet $record) => '#'),
                Action::make('details')
                    ->label('View Details')
                    ->color('primary') // Green for Details
                    ->icon('heroicon-o-eye')
                    ->url(fn (Packet $record) => '#'), 
            ]);
    }
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\CommissionWallet::class,
        ];
    }
}

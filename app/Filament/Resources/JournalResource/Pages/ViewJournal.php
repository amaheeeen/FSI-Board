<?php

namespace App\Filament\Resources\JournalResource\Pages;

use App\Filament\Resources\JournalResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;

class ViewJournal extends ViewRecord
{
    protected static string $resource = JournalResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Journal Header')
                    ->schema([
                        TextEntry::make('date')->date(),
                        TextEntry::make('reference_number'),
                        TextEntry::make('description')->columnSpanFull(),
                    ])->columns(2),
                
                Section::make('Journal Details')
                    ->schema([
                        RepeatableEntry::make('details')
                            ->schema([
                                TextEntry::make('coa.code')->label('Code'),
                                TextEntry::make('coa.name')->label('Account'),
                                TextEntry::make('debit')->money('IDR')->color('success'),
                                TextEntry::make('credit')->money('IDR')->color('danger'),
                                TextEntry::make('description'),
                            ])->columns(5)
                    ])
            ]);
    }
}

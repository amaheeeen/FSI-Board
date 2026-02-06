<?php

namespace App\Filament\Resources\PacketResource\Pages;

use App\Filament\Resources\PacketResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPackets extends ListRecords
{
    protected static string $resource = PacketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

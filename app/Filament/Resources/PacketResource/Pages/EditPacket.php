<?php

namespace App\Filament\Resources\PacketResource\Pages;

use App\Filament\Resources\PacketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPacket extends EditRecord
{
    protected static string $resource = PacketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

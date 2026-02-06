<?php

namespace App\Filament\Pages;

use Mokhosh\FilamentKanban\Pages\KanbanBoard;
use App\Models\Lead;
use Illuminate\Support\Collection;

class LeadsBoard extends KanbanBoard
{
    protected static string $model = Lead::class;
    protected static ?string $navigationIcon = 'heroicon-o-view-columns';
    protected static ?string $navigationGroup = 'CRM';
    protected static ?string $title = 'Leads Kanban';

    protected function statuses(): Collection
    {
        return collect([
            ['id' => 'interested', 'title' => 'New Lead', 'color' => 'success'],
            ['id' => 'follow_up', 'title' => 'Follow Up', 'color' => 'warning'],
            ['id' => 'closing', 'title' => 'Closing', 'color' => 'info'],
            ['id' => 'paid', 'title' => 'Won / Paid', 'color' => 'primary'],
            ['id' => 'lost', 'title' => 'Lost', 'color' => 'danger'],
        ]);
    }

    protected function records(): Collection
    {
        return Lead::all()->map(function ($lead) {
            return [
                'id' => $lead->id,
                'title' => $lead->name,
                'status' => $lead->status,
                'description' => $lead->notes ?? $lead->phone,
            ];
        });
    }

    public function onStatusChanged(int|string $recordId, string $status, array $fromOrderedIds, array $toOrderedIds): void
    {
        Lead::find($recordId)->update(['status' => $status]);
    }
}

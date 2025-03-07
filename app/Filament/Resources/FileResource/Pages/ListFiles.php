<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use App\Models\File;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListFiles extends ListRecords
{
    protected static string $resource = FileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        $status = request()->segment(3); // Get the status from the URL

        return match ($status) {
            'pending' => File::query()->where('status', 'pending'),
            'approved' => File::query()->where('status', 'approved'),
            'rejected' => File::query()->where('status', 'rejected'),
            'deleted' => File::query()->where('status', 'deleted'),
            default => File::query(), // Show all files if no status is provided
        };
    }
}

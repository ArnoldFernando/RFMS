<?php

namespace App\Filament\Resources\FileCategoryResource\Pages;

use App\Filament\Resources\FileCategoryResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditFileCategory extends EditRecord
{
    protected static string $resource = FileCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),

        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirects to listing page after saving
    }

    protected function afterSave(): void
    {
        Notification::make()
            ->title('File updated successfully')
            ->success()
            ->send();
    }
}

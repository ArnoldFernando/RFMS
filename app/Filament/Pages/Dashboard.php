<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatusOverview;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-Home';

    protected static string $view = 'filament.pages.dashboard';




    protected function getHeaderWidgets(): array
    {
        return [
            StatusOverview::class,
        ];
    }
}

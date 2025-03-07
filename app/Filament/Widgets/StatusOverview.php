<?php

namespace App\Filament\Widgets;

use App\Models\File;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatusOverview extends BaseWidget
{
    protected ?string $heading = 'Files Status';
    protected static bool $isLazy = false;
    protected ?string $description = 'An overview of some analytics.';


    protected function getStats(): array
    {
        return [
            Stat::make('Approved', File::where('status', 'approved')->count())
                ->color('success')
                ->description('Increase from last week')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Pending', File::where('status', 'pending')->count())
                ->color('warning')
                ->description('Awaiting approval')
                ->descriptionIcon('heroicon-m-clock')
                ->chart([3, 5, 2, 8, 6, 7, 4]),

            Stat::make('Rejected', File::where('status', 'rejected')->count())
                ->color('danger')
                ->description('Decrease from last week')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->chart([8, 6, 4, 3, 2, 1, 0]),

            Stat::make('Deleted', File::where('status', 'deleted')->count())
                ->color('gray')
                ->description('Files removed')
                ->descriptionIcon('heroicon-m-trash')
                ->chart([2, 3, 1, 0, 4, 5, 2]),
        ];
    }
}

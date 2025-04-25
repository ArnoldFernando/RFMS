<?php

namespace App\Filament\Widgets;

use App\Models\File;
use Filament\Widgets\ChartWidget;

class DashboardChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';
    protected function getData(): array
    {
        // Get count of approved files
        $approved = File::where('status', 'approved')->count();

        // Get count of pending files
        $pending = File::where('status', 'pending')->count();

        // Get count of rejected files
        $rejected = File::where('status', 'rejected')->count();

        // Get count of deleted files
        $deleted = File::where('status', 'deleted')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Files Status Count',
                    'data' => [$approved, $pending, $rejected, $deleted],
                    'backgroundColor' => ['green', 'orange', 'red', 'gray'], // Different colors for each bar
                ],
            ],
            // Distribute the statuses across the x-axis
            'labels' => ['Approved', 'Pending', 'Rejected', 'Deleted'],
        ];
    }


    protected function getType(): string
    {
        return 'bar';
    }
}

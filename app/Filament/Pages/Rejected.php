<?php

namespace App\Filament\Pages;

use App\Models\File;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class Rejected extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-x-circle';
    protected static string $view = 'filament.pages.rejected';
    protected static ?string $navigationGroup = 'File Status';

    // Define the table with only pending files
    public function table(Table $table): Table
    {
        return $table
            ->query(File::where('status', 'rejected')) // Fetch only pending files
            ->columns([
                TextColumn::make('file_name')->label('File Name')->searchable()->sortable(),
                TextColumn::make('location')->label('Location')->searchable()->sortable(),
                TextColumn::make('description')->label('Description')->limit(30)->sortable(),
                TextColumn::make('status')->label('Status')->sortable(),
                TextColumn::make('file_category_id')->label('Category')->sortable(),
                TextColumn::make('user_id')->label('Uploaded By')->sortable(),
                TextColumn::make('created_at')->label('Uploaded At')->dateTime()->sortable(),
            ])
            ->paginated(10);
    }
}

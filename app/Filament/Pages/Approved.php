<?php

namespace App\Filament\Pages;

use App\Models\File;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;

class Approved extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationColor = 'success';
    protected static string $view = 'filament.pages.approved';
    protected static ?string $navigationGroup = 'File Status';

    public static function getNavigationBadge(): ?string
    {
        return File::where('status', 'approved')->count();
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'success'; // Yellow badge
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Approved files';
    }

    // Define the table with only pending files
    public function table(Table $table): Table
    {
        return $table
            ->query(File::query()->where('status', 'approved')) // Fixed Query
            ->columns([
                TextColumn::make('file_name')->label('File Name')->searchable()->sortable(),
                TextColumn::make('location')->label('Location')->searchable()->sortable(),
                TextColumn::make('description')->label('Description')->limit(30)->sortable(),
                TextColumn::make('status')->label('Status')->sortable(),
                TextColumn::make('category.name')->label('Category')->sortable(),
                TextColumn::make('user.name')->label('Uploaded By')->sortable(), // Fixed user_id display
                TextColumn::make('created_at')->label('Uploaded At')->dateTime()->sortable(),
            ])
            ->actions([
                ViewAction::make()
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('File Details')
                    ->modalWidth('xl')
                    ->mutateRecordDataUsing(fn(File $record) => [
                        'file_name' => $record->file_name,
                        'location' => $record->location,
                        'description' => $record->description,
                        'status' => $record->status,
                        'category' => optional($record->category)->name,
                        'uploaded_by' => optional($record->user)->name, // Assuming relationship with User
                        'created_at' => $record->created_at->format('F d, Y h:i A'),
                    ])
                    ->form([
                        TextInput::make('file_name')->label('File Name')->disabled(),
                        TextInput::make('location')->label('Location')->disabled(),
                        Textarea::make('description')->label('Description')->disabled(),
                        TextInput::make('status')->label('Status')->disabled(),
                        TextInput::make('category')->label('Category')->disabled(),
                        TextInput::make('uploaded_by')->label('Uploaded By')->disabled(),
                        TextInput::make('created_at')->label('Uploaded At')->disabled(),
                    ]),


                EditAction::make()
                    ->label('Update')
                    ->icon('heroicon-o-pencil')
                    ->modalHeading('Edit File')
                    ->modalWidth('xl')
                    ->form([
                        TextInput::make('file_name')->label('File Name')->required(),
                        TextInput::make('location')->label('Location')->required(),
                        Textarea::make('description')->label('Description'),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'approved' => 'approved',
                                'pending' => 'pending',
                                'deleted' => 'deleted',
                                'rejected' => 'rejected',
                            ])
                            ->required(),
                    ])
                    ->mutateRecordDataUsing(fn(File $record) => [
                        'file_name' => $record->file_name,
                        'location' => $record->location,
                        'description' => $record->description,
                        'status' => $record->status,
                    ])
                    ->action(fn(array $data, File $record) => $record->update($data))
                    ->successNotificationTitle('File updated successfully'),

                DeleteAction::make()
                    ->label('Delete')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Delete')
                    ->modalDescription('Are you sure you want to delete this file?')
                    ->modalButton('Yes, Delete')
                    ->successNotificationTitle('File deleted successfully'),
            ])

            ->defaultSort('created_at', 'desc') // Ensures recent files appear first
            ->searchPlaceholder('Search files...')
            ->paginated(10)
            ->defaultPaginationPageOption(5);
    }
}

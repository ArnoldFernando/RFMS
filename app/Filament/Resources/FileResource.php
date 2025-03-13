<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FileResource\Pages;
use App\Filament\Widgets\StatusSummary;
use App\Models\File;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class FileResource extends Resource
{
    protected static ?string $model = File::class;
    protected static ?string $navigationIcon = 'heroicon-o-document';

    // public static function getWidgets(): array
    // {
    //     return [
    //         StatusSummary::class,
    //     ];
    // }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }


    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Pending files';
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Grid::make(12)->schema([ // 12-column layout
                        TextInput::make('file_name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(6), // Half-width

                        TextInput::make('location')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(6), // Half-width

                        FileUpload::make('file')
                            ->directory('files')
                            ->preserveFilenames() // Keep the original filename
                            ->nullable() // Make the file upload nullable
                            ->columnSpanFull(), // Full-width


                        Textarea::make('description')
                            ->columnSpanFull(), // Full-width

                        TextInput::make('civil_case_number')
                            ->nullable()
                            ->columnSpan(6), // 1/3 of the row

                        TextInput::make('lot_number')
                            ->nullable()
                            ->columnSpan(6), // 1/3 of the row

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'deleted' => 'Deleted',
                            ])
                            ->default('pending')
                            ->columnSpan(6), // 1/3 of the row

                        Select::make('file_category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->columnSpan(6), // Half-width

                        Hidden::make('user_id')->default(Auth::id()), // Half-width
                    ])
                ]),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('file_name')->sortable()->searchable(),
                TextColumn::make('location')->sortable()->searchable(),
                TextColumn::make('civil_case_number')->sortable()->searchable(),
                TextColumn::make('lot_number')->sortable()->searchable(),
                IconColumn::make('status')
                    ->size(IconColumn\IconColumnSize::Medium)
                    ->icon(fn(string $state): string => match ($state) {
                        'pending' => 'heroicon-o-pencil',
                        'approved' => 'heroicon-o-clock',
                        'rejected' => 'heroicon-o-x-circle',
                        'deleted' => 'heroicon-o-trash',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',  // Yellow
                        'approved' => 'success', // Green
                        'rejected' => 'danger',  // Red
                        'deleted' => 'gray',     // Gray
                        default => 'secondary',
                    })
                    ->alignCenter() // Align the icon to the right
                    ->sortable()
                    ->searchable(),


                TextColumn::make('category.name')->label('Category'), // ✅ Removed searchable()
                TextColumn::make('user.name')->label('Uploaded By'),  // ✅ Removed searchable()
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'deleted' => 'Deleted',
                    ]),
                SelectFilter::make('file_category_id')
                    ->relationship('category', 'name')
                    ->label('Category'),
            ])
            ->actions([
                ViewAction::make()
                    ->modalHeading('File Details')
                    ->modalWidth('xl')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->extraModalFooterActions([
                        EditAction::make()->label('Edit')->icon('heroicon-o-pencil')->color('primary'),
                    ]),
                Action::make('Download')
                    ->label('Save')
                    ->icon('heroicon-o-arrow-down')
                    ->url(fn($record) => route('file.download', $record->id), true) // Uses the new route
                    ->color('success')
                    ->openUrlInNewTab(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc') // Ensures recent files appear first
            ->searchPlaceholder('Search files...')
            ->defaultPaginationPageOption(5);
    }



    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFiles::route('/'),
            'create' => Pages\CreateFile::route('/create'),
            'edit' => Pages\EditFile::route('/{record}/edit'),
        ];
    }
}

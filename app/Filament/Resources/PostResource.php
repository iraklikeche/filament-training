<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('title')
                ->label('Post Title')
                ->required()
                ->maxLength(255), // Validation for title length

            RichEditor::make('content')
                ->label('Content')
                ->required(), // Rich text editor for content

            Select::make('user_id')
                ->label('Author')
                ->relationship('user', 'name')
                ->searchable()
                ->required(), // Dropdown to select an author

            DateTimePicker::make('created_at')
                ->label('Created At')
                ->default(now()), // Pre-fill with the current date/time
        ]);
}


    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('id')->sortable(), // Display the post ID
            TextColumn::make('title')->searchable()->sortable(), // Display the title
            TextColumn::make('user.name')->label('Author'), // Display the author's name
            TextColumn::make('created_at')
                ->label('Created')
                ->dateTime('M d, Y')->sortable(), // Format and sort by creation date
        ])
        ->filters([  Filter::make('Published This Month')
        ->query(fn (Builder $query) => $query->whereMonth('created_at', now()->month)),

    SelectFilter::make('author')
        ->relationship('user', 'name')
        ->label('Filter by Author'),]) // We will add filters later
        ->actions([
            Tables\Actions\EditAction::make(), // Enable editing posts
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(), // Enable bulk deletion
        ]);
}


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}

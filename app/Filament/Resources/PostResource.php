<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';


    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Card::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label(label: 'Titulo')
                                    ->required()
                                    ->maxLength(2048)
                                    ->reactive()
                                    ->afterStateUpdated(function (Closure $set, $state) {
                                        $set('slug', Str::slug($state));
                                    }),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(2048),

                            ]),
                        Forms\Components\RichEditor::make('body')
                            ->label(label: 'Corpo do texto')
                            ->required(),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label(label: 'Data')
                                    ->required(),
                            ]),
                    ])->columnSpan(8),

                Card::make()
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail'),
                        Forms\Components\Select::make('category_id')
                            ->multiple()
                            ->label(label: 'Categoria')
                            ->relationship('categories', 'title'),
                        Forms\Components\Toggle::make('active')
                            ->label(label: 'Ativo')
                            ->required(),
                    ])->columnSpan(4),


            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail'),
                Tables\Columns\TextColumn::make('title')
                    ->label(label: 'Titulo'),
                Tables\Columns\IconColumn::make('active')
                    ->label(label: 'Ativo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label(label: 'Publicado')
                    ->dateTime(format: 'd/m/y H:i:s'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JamaahResource\Pages;
use App\Filament\Resources\JamaahResource\RelationManagers;
use App\Models\Jamaah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JamaahResource extends Resource
{
    protected static ?string $model = Jamaah::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Operations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('gender')
                            ->options([
                                'Male' => 'Male',
                                'Female' => 'Female',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('nik')
                            ->label('NIK (Identity Number)')
                            ->unique(ignoreRecord: true)
                            ->maxLength(16),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('address')
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Travel Documents')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('passport_number')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('passport_expiry'),
                    ]),
                Forms\Components\Section::make('Status & Relations')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('agent_id')
                            ->relationship('agent', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('mahram_id')
                            ->relationship('mahram', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Mahram (if any)'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'Registered' => 'Registered',
                                'Documents Complete' => 'Documents Complete',
                                'Visa Issued' => 'Visa Issued',
                                'Departed' => 'Departed',
                                'Returned' => 'Returned',
                            ])
                            ->default('Registered')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('passport_number')
                    ->searchable()
                    ->icon('heroicon-m-document-text')
                    ->copyable(),
                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('agent.name')
                    ->label('Agent')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Registered' => 'gray',
                        'Documents Complete' => 'info',
                        'Visa Issued' => 'success',
                        'Departed' => 'warning',
                        'Returned' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Registered' => 'Registered',
                        'Documents Complete' => 'Documents Complete',
                        'Visa Issued' => 'Visa Issued',
                        'Departed' => 'Departed',
                        'Returned' => 'Returned',
                    ]),
                Tables\Filters\SelectFilter::make('agent_id')
                    ->relationship('agent', 'name')
                    ->label('Agent'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListJamaahs::route('/'),
            'create' => Pages\CreateJamaah::route('/create'),
            'edit' => Pages\EditJamaah::route('/{record}/edit'),
        ];
    }
}

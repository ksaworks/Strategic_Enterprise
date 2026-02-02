<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResourceBookingResource\Pages;
use App\Models\ResourceBooking;
use BackedEnum;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Closure;

class ResourceBookingResource extends Resource
{
    protected static ?string $model = ResourceBooking::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    protected static string|\UnitEnum|null $navigationGroup = 'Recursos';

    public static function getModelLabel(): string
    {
        return 'Reserva de Recurso';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Reservas de Recursos';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('resource_id')
                    ->label('Recurso')
                    ->relationship('resource', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('Usuário')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required()
                    ->default(auth()->id()),
                Forms\Components\Select::make('project_id')
                    ->label('Projeto')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->nullable(),
                Forms\Components\DateTimePicker::make('start_time')
                    ->label('Início')
                    ->required()
                    ->seconds(false),
                Forms\Components\DateTimePicker::make('end_time')
                    ->label('Fim')
                    ->required()
                    ->seconds(false)
                    ->after('start_time')
                    ->rule(function ($get) {
                        return function (string $attribute, $value, Closure $fail) use ($get) {
                            $start = $get('start_time');
                            $end = $value;
                            $resourceId = $get('resource_id');
                            $bookingId = $get('id'); // null on create
            
                            if (!$start || !$end || !$resourceId) {
                                return;
                            }

                            $exists = ResourceBooking::where('resource_id', $resourceId)
                                ->where(function (Builder $query) use ($start, $end) {
                                    $query->whereBetween('start_time', [$start, $end])
                                        ->orWhereBetween('end_time', [$start, $end])
                                        ->orWhere(function (Builder $q) use ($start, $end) {
                                            $q->where('start_time', '<', $start)
                                                ->where('end_time', '>', $end);
                                        });
                                })
                                ->when($bookingId, fn($q) => $q->where('id', '!=', $bookingId))
                                ->exists();

                            if ($exists) {
                                $fail('Este recurso já está reservado nesse horário (Conflito detectado).');
                            }
                        };
                    }),
                Forms\Components\TextInput::make('purpose')
                    ->label('Propósito/Motivo')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('resource.name')
                    ->label('Recurso')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Reservado por')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Início')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Fim')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('purpose')
                    ->label('Motivo')
                    ->limit(30)
                    ->searchable(),
            ])
            ->defaultSort('start_time', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('resource_id')
                    ->label('Recurso')
                    ->relationship('resource', 'name'),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListResourceBookings::route('/'),
            'create' => Pages\CreateResourceBooking::route('/create'),
            'edit' => Pages\EditResourceBooking::route('/{record}/edit'),
        ];
    }
}

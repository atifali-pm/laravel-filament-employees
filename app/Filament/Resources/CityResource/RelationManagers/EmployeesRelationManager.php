<?php

namespace App\Filament\Resources\CityResource\RelationManagers;

use App\Models\Country;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    protected static ?string $recordTitleAttribute = 'first_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('firstname')->required()->maxLength(255),
                TextInput::make('lastname')->required()->maxLength(255),
                TextInput::make('address')->required()->maxLength(255),
                TextInput::make('zip_code')->required()->maxLength(5),
                DatePicker::make('birth_date')->required(),
                DatePicker::make('date_hired')->required(),
                Select::make('department_id')->relationship('department', 'name')->required(),
                Select::make('country_id')
                    ->label('Country')
                    ->reactive()
                    ->options(Country::all()->pluck('name', 'id')->toArray())
                    ->reactive(),
                Select::make('state_id')
                    ->label('State')
                    ->required()
                    ->options(function (callable $get) {
                        $country = Country::find($get('country_id'));
                        if (!$country) {
                            return State::all()->pluck('name', 'id');
                        }
                        return $country->states->pluck('name', 'id');
                    })
                    ->reactive(),
                Select::make('city_id')
                    ->label('City')
                    ->required()
                    ->options(function (callable $get) {
                        $state = State::find($get('state_id'));
                        if (!$state) {
                            return State::all()->pluck('name', 'id');
                        }
                        return $state->cities->pluck('name', 'id');
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('firstname')->sortable()->searchable(),
                TextColumn::make('lastname')->sortable()->searchable(),
                TextColumn::make('department.name')->sortable()->searchable(),
                TextColumn::make('date_hired')->date()->sortable()->searchable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('department')->relationship('department', 'name'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}

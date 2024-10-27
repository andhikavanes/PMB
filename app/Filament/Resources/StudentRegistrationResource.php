<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\StudentRegistration;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StudentRegistrationResource\Pages;
use App\Filament\Resources\StudentRegistrationResource\RelationManagers;
use Filament\Tables\Filters\SelectFilter;

class StudentRegistrationResource extends Resource
{
    protected static ?string $model = StudentRegistration::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->default(Auth::user()->name)
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->default(Auth::user()->email)
                    ->email()
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\Textarea::make('ktp_address')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('current_address')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Select::make('province_id')
                    ->relationship('province', 'name')
                    ->required()
                    ->columnSpanFull()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(fn (callable $set) => $set('regency_id', null)),
                Forms\Components\Select::make('regency_id')
                    ->relationship('regency', 'name', fn (Builder $query, callable $get) => 
                        $query->where('province_id', $get('province_id')))
                    ->required()
                    ->searchable()
                    ->columnSpanFull()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(fn (callable $set) => $set('district_id', null))
                    ->visible(fn (callable $get) => filled($get('province_id'))),
                Forms\Components\Select::make('district_id')
                    ->relationship('district', 'name', fn (Builder $query, callable $get) => 
                        $query->where('regency_id', $get('regency_id')))
                    ->searchable()
                    ->columnSpanFull()
                    ->preload()
                    ->visible(fn (callable $get) => filled($get('regency_id'))),
                
                Forms\Components\DatePicker::make('birth_date')
                    ->required(),
                Forms\Components\TextInput::make('birth_place')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Radio::make('nationality')
                    ->options([
                        'WNI' => 'WNI',
                        'WNA' => 'WNA',
                    ])
                    ->required(),
                Forms\Components\Radio::make('gender')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'Belum Menikah' => 'Belum Menikah',
                        'Menikah' => 'Menikah',
                        'Cerai' => 'Cerai',
                        ])
                    ->required(),
                Forms\Components\Select::make('religion')
                    ->options([
                        'Islam' => 'Islam',
                        'Kristen' => 'Kristen',
                        'Katolik' => 'Katolik',
                        'Hindu' => 'Hindu',
                        'Buddha' => 'Buddha',
                        'Konghucu' => 'Konghucu',
                    ])
                    ->required(),
                Forms\Components\FileUpload::make('photo')
                    ->image()
                    ->columnSpanFull()
                    ->directory('student-photos')
                    ->visibility('public'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birth_date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->searchable(),
                Tables\Columns\TextColumn::make('religion')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('photo'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->timezone('Asia/Jakarta')
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                SelectFilter::make('status')
                    ->multiple()
                    ->options([
                        'gender' => 'Gender',
                        'religion' => 'Religion',
                        'status' => 'Status',
                    ])
                        
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
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
            'index' => Pages\ListStudentRegistrations::route('/'),
            'create' => Pages\CreateStudentRegistration::route('/create'),
            'edit' => Pages\EditStudentRegistration::route('/{record}/edit'),
        ];
    }

}

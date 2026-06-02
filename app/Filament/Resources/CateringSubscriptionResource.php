<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CateringSubscriptionResource\Pages;
use App\Filament\Resources\CateringSubscriptionResource\RelationManagers;
use App\Models\CateringSubscription;
use App\Models\CateringTier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Laravel\Prompts\Grid;

class CateringSubscriptionResource extends Resource
{
    protected static ?string $model = CateringSubscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Product and Price')
                        ->icon('heroicon-,-shopping-bag')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->description('Which catering you choose')
                        ->schema([

                            Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('catering_package_id')
                                    ->relationship('cateringPackage', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('catering_tier_id', null); // Reset the tier selection when packages changes
                                        $set('price', null);
                                        $set('total_amount', null);
                                        $set('total_tax_amount', null);
                                        $set('quality', null);
                                        $set('duration', null);
                                        $set('ended_at', null);
                                    }),

                                    Forms\Components\Select::make('catering_tier_id')
                                        ->label('Catering Tier')
                                        ->options(function (callable $get) {
                                            $cateringPackageId = $get('catering_package_id');
                                            if ($cateringPackageId) {
                                                return CateringTier::where('catering_package_id', $cateringPackageId)
                                                    ->pluck('name', 'id');
                                            }
                                            return [];
                                        })
                                        ->searchable()
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            $cateringTier = CateringTier::find($state);
                                            $price = $cateringTier ? $cateringTier->price : 0;

                                            $quality = $cateringTier ? $cateringTier->quality : 0;
                                            $duration = $cateringTier ? $cateringTier->duration : 0;

                                            $set('price', $price);
                                            $set('quality', $quality);
                                            $set('duration', $duration);

                                            $tax = 0.11;
                                            $totalTaxAmount = $tax * $price;

                                            $totalAmount = $price + $totalTaxAmount;
                                            $set('total_amount', number_format($totalAmount, 0, '', ''));
                                            $set('total_tax_amount', number_format($totalTaxAmount, 0, '', ''));
                                        }),

                                        Forms\Components\TextInput::make('price')
                                            ->required()
                                            ->readOnly()
                                            ->numeric()
                                            ->prefix('IDR'),

                                        Forms\Components\TextInput::make('total_amount')
                                            ->required()
                                            ->readOnly()
                                            ->numeric()
                                            ->prefix('IDR'),

                                        Forms\Components\TextInput::make('total_tax_amount')
                                            ->required()
                                            ->readOnly()
                                            ->numeric()
                                            ->helperText('Pajak 11%')
                                            ->prefix('IDR'),

                                        Forms\Components\TextInput::make('quality')
                                            ->required()
                                            ->readOnly()
                                            ->numeric()
                                            ->prefix('People'),

                                        Forms\Components\TextInput::make('duration')
                                            ->required()
                                            ->readOnly()
                                            ->numeric()
                                            ->prefix('Days'),
                                        
                                        Forms\Components\DatePicker::make('started_at')
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                $duration = $get('duration');
                                                if ($state && $duration) {
                                                    $endedAt = \Carbon\Carbon::parse($state)->addDays($duration);
                                                    $set('ended_at', $endedAt->format('Y d m'));
                                                } else {
                                                    $set('ended_at', null);
                                                }
                                            }),

                                        Forms\Components\DatePicker::make('ended_at')
                                            ->required(),
                            ])
                        ]),

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListCateringSubscriptions::route('/'),
            'create' => Pages\CreateCateringSubscription::route('/create'),
            'edit' => Pages\EditCateringSubscription::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
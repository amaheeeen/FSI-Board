<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                     ->label('Marketplace')
                     ->icon('heroicon-o-shopping-bag'),
                NavigationGroup::make()
                     ->label('Finance')
                     ->icon('heroicon-o-banknotes'), // Icon for Finance
                NavigationGroup::make()
                     ->label('Operations')
                     ->icon('heroicon-o-briefcase'),
                NavigationGroup::make()
                     ->label('CRM')
                     ->icon('heroicon-o-users'),
                NavigationGroup::make()
                     ->label('Settings')
                     ->icon('heroicon-o-cog-6-tooth'),
            ]);
        });
    }
}

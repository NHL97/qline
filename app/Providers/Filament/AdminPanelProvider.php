<?php

namespace App\Providers\Filament;

use App\Filament\Admin\AdminAnalyticsWidget;
use App\Filament\Admin\AdminStatsWidget;
use App\Filament\Admin\TopBusinessesWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors(['primary' => Color::hex('#14B8A6')])
            ->brandLogo(view('filament.brand'))

            ->discoverResources(
                in: app_path('Filament/Admin'),
                for: 'App\\Filament\\Admin'
            )
            ->discoverPages(
                in: app_path('Filament/Admin'),
                for: 'App\\Filament\\Admin'
            )
            ->discoverWidgets(
                in: app_path('Filament/Admin'), 
                for: 'App\\Filament\\Admin'
            )
            ->pages([Dashboard::class])
            ->widgets([
                AdminStatsWidget::class,
                AdminAnalyticsWidget::class,
                TopBusinessesWidget::class,
            ])

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

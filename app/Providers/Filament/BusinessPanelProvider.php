<?php

namespace App\Providers\Filament;

use App\Filament\Business\AnalyticsWidget;
use App\Filament\Business\QueueStatsWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class BusinessPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('business')
            ->path('business')
            ->login()
            ->colors(['primary' => Color::hex('#14B8A6')])
            ->brandLogo(view('filament.brand'))
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn () => view('filament.echo-script'),
            )
            ->discoverResources(
                in: app_path('Filament/Business'),
                for: 'App\\Filament\\Business'
            )
            ->discoverPages(
                in: app_path('Filament/Business'),
                for: 'App\\Filament\\Business'
            )
            ->discoverWidgets(
                in: app_path('Filament/Business'),
                for: 'App\\Filament\\Business'
            )
            ->pages([Dashboard::class])
            ->widgets([
                QueueStatsWidget::class,
                AnalyticsWidget::class,
            ])

            ->navigationGroups([
                'Queue',
                'Management',
                'Account',
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

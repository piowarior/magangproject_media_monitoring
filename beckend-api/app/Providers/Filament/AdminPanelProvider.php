<?php

namespace App\Providers\Filament;

use App\Models\User;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Blue,
                'danger'  => Color::Red,
                'success' => Color::Green,
                'warning' => Color::Amber,
                'info'    => Color::Sky,
            ])
            ->brandName('Media Monitoring AI')
            ->brandLogo(null)
            ->favicon(null)
            // ─── Proteksi: hanya role Admin yang boleh masuk ───────────────
            ->authGuard('web')
            ->authPasswordBroker('users')
            // ─── Auto-discover semua resource, page, widget ────────────────
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            // ─── Grup Navigasi (urutan tampil di sidebar) ─────────────────
            ->navigationGroups([
                NavigationGroup::make('User Management')->collapsible(false),
                NavigationGroup::make('Keyword Management')->collapsible(false),
                NavigationGroup::make('Crawling Center')->collapsible(false),
                NavigationGroup::make('News Center')->collapsible(false),
                NavigationGroup::make('AI Monitoring')->collapsible(false),
                NavigationGroup::make('Analytics')->collapsible(false),
                NavigationGroup::make('Geographic Intel')->collapsible(true),
                NavigationGroup::make('Alert Center')->collapsible(false),
                NavigationGroup::make('Reporting')->collapsible(false),
                NavigationGroup::make('System')->collapsible(true),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

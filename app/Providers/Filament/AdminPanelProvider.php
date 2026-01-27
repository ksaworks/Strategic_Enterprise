<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Blade;
use Filament\View\PanelsRenderHook;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->spa() // Single Page Application mode
            ->colors([
                'primary' => Color::hex('#d58f05'), // Gold
                'gray' => [ // Navy-tinted grays
                    50 => '#f9fafb',
                    100 => '#f3f4f6',
                    200 => '#e5e7eb',
                    300 => '#d1d5db',
                    400 => '#9ca3af',
                    500 => '#6b7280',
                    600 => '#4b5563',
                    700 => '#374151',
                    800 => '#1f2937',
                    900 => '#111827',
                    950 => '#0f1729', // Navy base
                ],
            ])
            ->brandName('Strategic Enterprise')
            ->brandLogo(asset('images/logo-strategic.svg'))
            ->darkModeBrandLogo(asset('images/logo-strategic.svg'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('images/favicon.png'))
            ->font('Instrument Sans')
            ->globalSearch(true)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->navigationGroups([
                \Filament\Navigation\NavigationGroup::make('Gestão')
                    ->collapsible(false),
                \Filament\Navigation\NavigationGroup::make('Projetos')
                    ->collapsible(false),
                \Filament\Navigation\NavigationGroup::make('Sistema')
                    ->collapsible(false),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // AccountWidget::class,
                // FilamentInfoWidget::class,
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
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
            ])
            ->renderHook(
                PanelsRenderHook::FOOTER,
                fn() => Blade::render('filament.footer')
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn(): string => Blade::render(<<<'BLADE'
                    <style>
                        /* Safe Layout Fixes */
                        .fi-footer { display: flex !important; flex-direction: column !important; align-items: center !important; text-align: center !important; }
                        .fi-footer-content { text-align: center !important; width: 100% !important; }
                        .fi-sidebar-nav { border-bottom: 1px solid rgba(255, 255, 255, 0.1); padding-bottom: 1rem; margin-bottom: 1rem; }
                        .fi-select-input { white-space: nowrap !important; }
                        .fi-form > .grid { gap: 1.5rem !important; }
                    </style>
                BLADE)
            );
    }
}

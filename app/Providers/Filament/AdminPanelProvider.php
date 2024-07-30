<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Resources\DespesaResource;
use App\Filament\Resources\PlanoResource;
use App\Filament\Resources\ProdutoResource;
use App\Filament\Resources\RendaResource;
use App\Filament\Resources\StatusDespesaResource;
use App\Filament\Resources\TipoDespesaResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {



        return $panel
            ->brandName('Meu Financeiro')
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->registration()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([

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
            ->navigation(fn (NavigationBuilder $builder): NavigationBuilder => $builder->groups([
                NavigationGroup::make('Dashboard')
                    ->items([
                        ...Pages\Dashboard::getNavigationItems(),
                    ])
                    ->collapsed(false),
                NavigationGroup::make('Finança')
                    ->items([
                        ...RendaResource::getNavigationItems(),
                        ...DespesaResource::getNavigationItems(),
                    ])->collapsed(false),
                NavigationGroup::make('Lista de compra')
                    ->items([
                        ...ProdutoResource::getNavigationItems(),
                    ])->collapsed(true),

                NavigationGroup::make('Metrica')
                    ->items([
                        NavigationItem::make()
                            ->visible(self::authorized(config('filament-debugger.permissions.telescope')))
                            ->group(config('filament-debugger.group'))
                            ->url(url: url()->to(config('filament-debugger.url.telescope')), shouldOpenInNewTab: true)
                            ->icon('heroicon-o-sparkles')
                            ->label('Telescope'),
                        NavigationItem::make()
                            ->visible(self::authorized(config('filament-debugger.permissions.horizon')))
                            ->group(config('filament-debugger.group'))
                            ->icon('heroicon-o-globe-europe-africa')
                            ->url(url: url()->to(config('filament-debugger.url.horizon')), shouldOpenInNewTab: true)
                            ->label('Horizon'),
                    ]),
                NavigationGroup::make('Configuracao')
                    ->items([
                        ...PlanoResource::getNavigationItems(),
                        ...TipoDespesaResource::getNavigationItems(),
                        ...StatusDespesaResource::getNavigationItems(),
                    ])->collapsed(true),

            ]),
            )
            ->spa()
            ->sidebarCollapsibleOnDesktop();

    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        unset($data['is_admin']);

        return $data;
    }

    private static function authorized(string $ability): bool
    {
        if (config('filament-debugger.authorization')) {
            return (bool) auth(config('filament.auth.guard'))
                ->user()
                ?->can($ability);
        }

        return true;
    }
}

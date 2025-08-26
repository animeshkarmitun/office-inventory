<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Use a custom public path only in production and only when explicitly provided via PUBLIC_PATH
        $customPublicPath = env('PUBLIC_PATH');

        if ($this->app->environment('production') && !empty($customPublicPath) && is_dir($customPublicPath)) {
            $this->app->bind('path.public', function () use ($customPublicPath) {
                return $customPublicPath;
            });
        }
        
        // Set PUBLIC_PATH for shared hosting if not already set
        if ($this->app->environment('production') && empty($customPublicPath)) {
            // Try common shared-hosting structure: app in `inventory`, public in sibling `public_html`
            $siblingPublicHtml = realpath(base_path('..' . DIRECTORY_SEPARATOR . 'public_html'));
            if ($siblingPublicHtml && is_dir($siblingPublicHtml)) {
                putenv('PUBLIC_PATH=' . $siblingPublicHtml);
            }
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

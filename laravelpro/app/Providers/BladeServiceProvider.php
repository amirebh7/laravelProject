<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('recaptcha' , function() {
            return '
                <script src=\'https://www.google.com/recaptcha/api.js?hl=fa\' async defer></script>
                <div class=\'g-recaptcha <?php if($errors->has(\'g-recaptcha-response\')) : ?> is-invalid <?php endif; ?>\' data-sitekey=\'{{ env(\'GOOGLE_RECAPTCHA_SITE_KEY\') }}\'></div>
            ';
        });
    }
}

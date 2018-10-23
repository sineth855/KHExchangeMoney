<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    // public function __construct(Application $app){

    // }

    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Laravel\Passport\PassportServiceProvider',function($app){
            return new Previewr($app);
        });
        //
         // $this->app->bind(NumberInterface::class, NumberServiceProvider::class);
    }
}

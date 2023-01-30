<?php

namespace App\Providers;

use App\Models\Company;
use Illuminate\Support\Facades\View;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('layouts.sidebar', function ($view) {
            $userCompanies = auth('web')->user()->companies;
            $view->with('companiesNav',$userCompanies);
        });
    }
}

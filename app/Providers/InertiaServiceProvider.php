<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class InertiaServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        Inertia::setRootView('layouts.app');
    }
}

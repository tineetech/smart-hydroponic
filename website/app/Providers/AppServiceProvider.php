<?php

namespace App\Providers;

use App\Models\Notifikasi;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.admin', function ($view) {
            if (auth()->check()) {
                $view->with('notifications', Notifikasi::latest()->limit(5)->get());
                $view->with('notifUnreadCount', Notifikasi::belumDibaca()->count());
            }
        });
    }
}

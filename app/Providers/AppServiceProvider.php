<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(Registered::class, function ($event) {
            if (User::count() === 1) {
                $event->user->update(['is_admin' => true]);
            }
        });
    }
}
<?php

namespace App\Providers;

use App\Models\Vehicle;
use App\View\Composers\NotificationComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', NotificationComposer::class);

        View::composer('layouts.app', function ($view) {
            $notifikasiServis = collect();
            $jumlahNotifServis = 0;

            if (auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin', 'teknisi', 'pimpinan'])) {
                // Get all vehicles and filter using the model's accessors
                $readNotifications = session()->get('read_notifications', []);

                $notifikasiServis = Vehicle::all()->filter(function ($v) use ($readNotifications) {
                    $status = $v->status_servis_berkala;
                    // Check if this specific vehicle and status has been marked as read
                    $isRead = isset($readNotifications[$v->id]) && $readNotifications[$v->id] === $status;

                    return in_array($status, ['merah', 'kuning']) && ! $isRead;
                })->values();

                $jumlahNotifServis = $notifikasiServis->count();
            }

            $view->with(compact('notifikasiServis', 'jumlahNotifServis'));
        });
    }
}

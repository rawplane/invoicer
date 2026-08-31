<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTrialPeriod
{
    /**
     * Cek apakah user masih dalam masa free trial.
     *
     * - Jika user belum punya `trial_ends_at`, izinkan akses (fallback aman).
     * - Jika trial sudah kedaluwarsa dan subscription_plan masih 'free_trial',
     *   arahkan ke halaman peringatan trial berakhir.
     *
     * Route 'profile' dan 'logout' tetap diizinkan agar user bisa keluar/memperbarui data.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $trialEndsAt = $user->trial_ends_at;

        // Tidak ada batas trial → akses terbuka
        if (! $trialEndsAt) {
            return $next($request);
        }

        // Trial masih aktif
        if ($trialEndsAt->isFuture()) {
            return $next($request);
        }

        // Subscription sudah aktif (bukan free_trial) → akses terbuka
        if ($user->subscription_plan && $user->subscription_plan !== 'free_trial') {
            return $next($request);
        }

        // Trial kedaluwarsa — izinkan akses ke profile & logout
        $allowedRoutes = ['profile', 'profile.edit', 'logout', 'billing', 'subscription'];
        if ($request->route() && in_array($request->route()->getName(), $allowedRoutes, true)) {
            return $next($request);
        }

        return redirect()->route('trial.expired');
    }
}

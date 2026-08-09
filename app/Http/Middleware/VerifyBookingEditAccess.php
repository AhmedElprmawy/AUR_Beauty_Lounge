<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyBookingEditAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('booking_edit_verified')) {
            return redirect()->route('booking.edit.password');
        }

        return $next($request);
    }
}
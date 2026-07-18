<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * Home path after login/register based on role.
     */
    protected function redirectPath(?User $user = null): string
    {
        $user ??= Auth::user();

        if ($user && $user->isStaff()) {
            return route('admin.dashboard', absolute: false);
        }

        return route('account.dashboard', absolute: false);
    }
}

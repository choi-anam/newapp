<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PasswordMethodController extends Controller
{
    /**
     * Display password reset method selection page
     */
    public function show(): View
    {
        return view('auth.password-method');
    }
}

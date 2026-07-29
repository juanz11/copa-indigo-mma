<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserPaymentController extends Controller
{
    public function index()
    {
        $registrations = auth()->user()
            ->registrations()
            ->with('whatsappNotifications')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.registrations', compact('registrations'));
    }
}

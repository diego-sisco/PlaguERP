<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $email = null;
        $password = null;
        $route = "";
        $user = new User();

        $email = User::where('email', $request->email)->get();
        $password = User::where('password', bcrypt($request->password))->get();
        
        if(count($email) > 0) {
            $user = (User::where('email', $request->email)->get())[0];
        }

        if($user->status_id != '2') {
            return redirect('/login')->withErrors([
                'error' => __('auth.failed_user'),
            ]);
        } else {
            if(count($email) <= 0) {
                return redirect('/login')->withErrors([
                    'error' => __('auth.failed_email'),
                ]);
            } else {
                $request->authenticate();
                $request->session()->regenerate();
                return !auth()->user()->hasRole('Cliente') ? redirect()->route('dashboard') : redirect()->route('client.index', ['section' => 1]);
            };
        }  
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        /*Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();*/
        Auth::logout();

        return redirect('/login');
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\SidebarMenuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    protected SidebarMenuService $sidebarMenuService;

    public function __construct(SidebarMenuService $sidebarMenuService)
    {
        $this->sidebarMenuService = $sidebarMenuService;
    }
    /**
     * Show the admin signin view.
     *
     * @return View|RedirectResponse
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.signin');
    }

    /**
     * Handle admin login form submission.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');
        $loginInput = $validated['username'];

        // Determine if input is email or username
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $fieldType  => $loginInput,
            'password'  => $validated['password'],
            'is_active' => true,
        ];

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Generate sidebarmenu.html in storage/app/private using logged in user's m_role_id
            $user = Auth::user();
            $roleId = $user?->m_role_id;
            try {
                $this->sidebarMenuService->generate($roleId, $user?->id);
            } catch (\Throwable $e) {
                // Keep fallback in case of database or permission exceptions
                report($e);
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'username' => __('The provided credentials do not match our records or the account is inactive.'),
        ])->onlyInput('username');
    }

    /**
     * Log the admin user out.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

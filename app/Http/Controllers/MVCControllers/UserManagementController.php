<?php

namespace App\Http\Controllers\MVCControllers;
use \App\Http\Controllers\RestControllers\UserManagementController as RestUserManagementController;
use App\Http\Requests\LoginForm;
use App\Http\Requests\ResetPasswordForm;
use App\Services\UserService\IUserService;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class UserManagementController extends RestUserManagementController {

    public function __construct(IUserService $userService) {
        parent::__construct($userService);
    }

    public function loginPage(): Factory|View|Application {
        return view('auth.login');
    }

    public function login(LoginForm $form): RedirectResponse {
        $this->getUserService()->login($form);
        session()->regenerate();
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function logout(Request $request): Redirector|Application|RedirectResponse {
        $this->getUserService()->logout($request);
        return redirect()->route('login');
    }

    public function forgotPasswordPage(): Factory|View|Application {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request): RedirectResponse {
        $this->getUserService()->forgotPassword($request);
        return redirect()->to('/');
    }

    public function resetPasswordPage(Request $request): Factory|View|Application {
        return view('auth.reset-password', ['request' => $request]);
    }

    public function resetPassword(Request $request): RedirectResponse {
        $this->getUserService()->resetPassword($request);
        return redirect()->route('login');
    }

}

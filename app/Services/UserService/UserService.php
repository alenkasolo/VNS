<?php

namespace App\Services\UserService;

use App\Exceptions\EntityNotFoundException;
use App\Exceptions\TooManyAttemptsException;
use App\Repositories\UserRepository\IUserRepository;
use App\Services\BaseService;
use App\Services\RoleService\IRoleService;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use JetBrains\PhpStorm\Pure;
use Password;
use RateLimiter;
use Str;

class UserService extends BaseService implements IUserService {

    #[Pure] public function __construct(IUserRepository $repository) {
        parent::__construct($repository);
    }

    public function search(): LengthAwarePaginator
    {

        $roleId = $this->filterQueryString('role_id', $this->getRoleIdQueryStringWhiteList());
        $status = $this->filterQueryString('status', $this->getUserStatusQueryStringWhiteList());
        $sortBy = $this->filterQueryString('sortBy', $this->getUserSortByQueryStringWhiteList());
        $order = $this->filterQueryString('order', $this->getOrderQueryStringWhiteList());
        return $this->getRepository()->search(request()->input('keyword'), $roleId, $status, $sortBy, $order);
    }

    public function getAllRoles(IRoleService $roleService): Collection
    {
        return $roleService->getAll();
    }

    public function create(Request $request): User
    {

        $attributes = $this->validate($request, [
            'id' => ['string', 'required', 'regex:/^[A-Za-z]{1}([A-Za-z]|[0-9]){5,7}$/im', 'unique:users'],
            'user_name' => ['string', 'required', 'unique:users'],
            'email' => ['string', 'required', 'email', 'unique:users'],
            'password' => [\Illuminate\Validation\Rules\Password::min(8)->letters()->mixedCase()->numbers()->symbols(), 'max:32', 'sometimes'],
            'role_id' => ['integer', 'required'],
            'status' => ['integer', 'required', 'in:1,2']
        ]);

        if (!array_key_exists('password', $attributes)) {
            $attributes['password'] = $this->randomPassword();
        }

        return parent::baseCreate($attributes);
    }

    /**
     * @throws TooManyAttemptsException
     */
    public function login(Request $request): void
    {
        if (RateLimiter::tooManyAttempts($request->ip(), 5)) {
            throw new TooManyAttemptsException();
        }
        RateLimiter::hit($request->ip());
        $data = $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        $fieldType = filter_var($data['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';
        if ($fieldType === 'email') {
            $user = $this->getRepository()->loginViaEmail($data['email'], $data['password']);
        } else {
            $user = $this->getRepository()->loginViaUserName($data['email'], $data['password']);
        }

        if ($user) {
            Auth::loginUsingId($user->id, $request->boolean('remember'));
            return;
        }
        throw ValidationException::withMessages(
            ['email' => trans('auth.failed')]
        );
    }

    public function createMany(Request $request): Collection
    {

        $data = $this->validate($request, [
            '*.id' => ['string', 'required', 'regex:/^[A-Za-z]{1}([A-Za-z]|[0-9]){5,7}$/im', 'unique:users', 'distinct'],
            '*.user_name' => ['string', 'required', 'unique:users', 'distinct'],
            '*.email' => ['string', 'required', 'email', 'unique:users', 'distinct'],
            '*.password' => [\Illuminate\Validation\Rules\Password::min(8)->letters()->mixedCase()->numbers()->symbols(), 'max:32', 'sometimes'],
            '*.role_id' => ['integer', 'required']
        ]);

        $newUserCollection = new Collection();
        foreach ($data as $newUserForm) {
            $newUser = new User();
            $currentTime = Carbon::now();

            $newUser->setAttribute('id', $newUserForm['id']);
            $newUser->setAttribute('user_name', $newUserForm['user_name']);
            $newUser->setAttribute('email', $newUserForm['email']);
            $newUser->setAttribute('password', $newUserForm['password'] ?? $this->randomPassword());
            $newUser->setAttribute('role_id', $newUserForm['role_id']);
            $newUser->setAttribute('created_at', $currentTime);
            $newUser->setAttribute('updated_at', $currentTime);

            $newUserCollection->add($newUser);
        }
        app()->call([$this->getRepository(), 'createMany'],
            ['data' => $newUserCollection]);
        return $newUserCollection;
    }

    public function logout(Request $request): void
    {
        if (!Auth::check()) {
            return;
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function forgotPassword(Request $request): void
    {
        $this->validate($request,
            [
            'email' => ['required', 'email']
            ]
        );
        Password::sendResetLink($request->only('email'));
    }

    public function resetPassword(Request $request): void
    {
        $data = $this->validate($request, [
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => [\Illuminate\Validation\Rules\Password::min(8)->letters()->mixedCase()->numbers()->symbols(), 'max:32', 'confirmed']
        ]);


        Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($data) {
                $user->forceFill([
                    'password' => $data['password'],
                    'remember_token' => Str::random(60),
                ])->save();
//                event(new PasswordReset($user));
            }
        );
    }

    private function randomPassword(): string
    {
        return Str::random(25);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function update(mixed $id, Request $request): User
    {
        $attributes = $this->validate($request,
            [
            'id' => ['string', 'required', 'regex:/^[A-Za-z]{1}([A-Za-z]|[0-9]){5,7}$/im', Rule::unique('users')->ignore($id, 'id')],
            'user_name' => ['string', 'required', Rule::unique('users')->ignore($request->input('user_name'), 'user_name')],
            'email' => ['string', 'required', 'email', Rule::unique('users')->ignore($request->input('email'), 'email')],
            'password' => [\Illuminate\Validation\Rules\Password::min(8)->letters()->mixedCase()->numbers()->symbols(), 'max:32'],
            'role_id' => ['integer', 'required'],
            'status' => ['integer', 'required', 'in:1,2']
            ]
        );
        return $this->baseUpdate($id, $attributes);
    }
}

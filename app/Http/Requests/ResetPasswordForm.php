<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;
use JetBrains\PhpStorm\ArrayShape;

class ResetPasswordForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['token' => "string[]", 'email' => "string[]", 'password' => "array"])]
    public function rules(): array
    {
        return [
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => [Password::min(8)->letters()->mixedCase()->numbers()->symbols(), 'max:32']
        ];
    }

    protected function failedAuthorization() {
        throw new HttpResponseException(response()->redirectTo('/'));
    }
}

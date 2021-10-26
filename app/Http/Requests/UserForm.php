<?php

namespace App\Http\Requests;

use App\Exceptions\NotAdminException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use JetBrains\PhpStorm\ArrayShape;

class UserForm extends FormRequest
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
    #[ArrayShape(['id' => "string[]", 'user_name' => "string[]", 'email' => "string[]", 'password' => "array", 'role_id' => "string[]"])]
    public function rules(): array {
        return [
            'id' => ['string', 'required', 'regex:/^[A-Za-z]{1}([A-Za-z]|[0-9]){5,7}$/g'],
            'user_name' => ['string', 'required'],
            'email' => ['string', 'required', 'email'],
            'password' => [Password::min(8)->letters()->mixedCase()->numbers()->symbols(), 'max:32', 'sometimes'],
            'role_id' => ['integer', 'required']
        ];
    }

    /**
     * @throws NotAdminException
     */
    protected function failedAuthorization() {
        throw new NotAdminException();
    }
}

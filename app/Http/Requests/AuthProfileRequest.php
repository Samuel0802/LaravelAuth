<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => 'required|min:6|max:32|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'new_password' => 'required|min:6|max:32|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/|different:current_password',
            'new_password_confirmation' => 'required|same:new_password',
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'A senha atual é obrigatória',
            'current_password.min' => 'A senha atual deve ter no mínimo :min caracteres',
            'current_password.max' => 'A senha atual deve ter no máximo :max caracteres',
            'current_password.regex' => 'A Senha atual deve conter pelo menos uma letra maiúscula, uma letra minúscula e um número',
             'new_password.required' => 'A nova senha é obrigatória',
            'new_password.min' => 'A nova senha deve ter no mínimo :min caracteres',
            'new_password.max' => 'A nova senha deve ter no máximo :max caracteres',
            'new_password.regex' => 'A Nova Senha deve conter pelo menos uma letra maiúscula, uma letra minúscula e um número',
            'new_password_confirmation.required' => 'A confirmação da nova senha é obrigatória',
            'new_password_confirmation.same' => 'A nova senha e a confirmação da nova senha devem ser iguais'

        ];
    }
}

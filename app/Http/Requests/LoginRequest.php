<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    //Regras do Formulário
    public function rules(): array
    {
        return [
            'username' => 'required|string|min:3|max:50',
            'password' => 'required|min:6|max:32|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'O usuário é Obrigatório',
            'username.string' => 'O usuário deve ter apenas Letras',
            'username.min' => 'O usuário deve ter no mínimo :min caracteres',
            'username.max' => 'O usuário deve ter no máximo :max caracteres',

            'password.required' => 'A senha é Obrigatória',
            'password.min' => 'A senha deve ter no mínimo :min caracteres',
            'password.max' => 'A senha deve ter no máximo :max caracteres',
            'password.regex' => 'A Senha deve conter pelo menos uma letra maiúscula, uma letra minúscula e um número',
        ];
    }
}

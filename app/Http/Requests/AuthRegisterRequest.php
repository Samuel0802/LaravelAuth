<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRegisterRequest extends FormRequest
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
            'username' => 'required|string|min:3|max:50|regex:/^\S*$/u|unique:App\Models\User,username',
            'email' => 'required|email|unique:App\Models\User,email',
            'data_nascimento' => 'required|date|before:-18 years',
            'genero' => 'required|in:masculino,feminino,outro',

            'password' => 'required|min:6|max:32|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'password_confirmation' => 'required|same:password',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'O usuário é Obrigatório',
            'username.regex' => 'O usuário não pode conter espaços em branco',
            'username.unique' => 'O usuário já existe',
            'username.string' => 'O usuário deve ter apenas Letras',
            'username.min' => 'O usuário deve ter no mínimo :min caracteres',
            'username.max' => 'O usuário deve ter no máximo :max caracteres',

            'email.required' => 'O email é Obrigatório',
            'email.email' => 'O email deve ser válido',
            'email.unique' => 'O email já existe',

            'data_nascimento.required' => 'A data de nascimento é obrigatória.',
            'data_nascimento.date' => 'Digite uma data válida.',
            'data_nascimento.before' => 'Você deve ter pelo menos 18 anos.',

            'genero.required' => 'O gênero é obrigatório.',
            'genero.in' => 'Selecione um gênero válido.',

            'password.required' => 'A senha é Obrigatória',
            'password.min' => 'A senha deve ter no mínimo :min caracteres',
            'password.max' => 'A senha deve ter no máximo :max caracteres',
            'password.regex' => 'A Senha deve conter pelo menos uma letra maiúscula, uma letra minúscula e um número',
        ];
    }
}

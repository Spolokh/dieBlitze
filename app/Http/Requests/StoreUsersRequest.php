<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'      => 'nullable|between:3,20',
            'mail'      => 'required|email|unique:users,mail',
            'deleted'   => 'required|boolean',
            'username'  => 'required|string|between:6,20',
            'password'  => 'required|confirmed|between:6,20',
            'usergroup' => 'required',
        ];
    }

    // Опционально: кастомные сообщения
    public function messages(): array
    {
        return [
            'mail.unique'        => 'Такой "Email" уже зарегистрирован',
            'mail.required'      => 'Поле "Email" обязательно для заполнения.',
            'name.between'       => 'Поле "Имя" должно содержать от 3-х до 50-ти символов',
            'username.between'   => 'Поле "Логин" должно содержать от 6-и до 20-ти символов.',
            'username.required'  => 'Поле "Логин" обязательно для заполнения.',
            'password.required'  => 'Поле "Пароль" обязательно для заполнения.',
            'password.between'   => 'Пароль должен содержать от 8-и до 20-ти символов',
            'password.confirmed' => 'Ведённые вами пароли не совпадают',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'mail' => str($this->mail)->lower(),
        ]);
    }
}

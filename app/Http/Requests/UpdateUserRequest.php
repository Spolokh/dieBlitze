<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name'      => 'nullable|between:3,20',
            'mail'      => 'required|email|min:6|max:50|unique:users,mail,'. $this->route('user')->id,
            'deleted'   => 'required|boolean',
            'password'  => 'nullable|confirmed|between:8,50',
            'usergroup' => 'required|integer',
        ];
    }

    // Опционально: кастомные сообщения
    public function messages(): array
    {
        return [
            'name.between'       => 'Поле "Имя" должно содержать от 3-х до 50-ти символов',
            'mail.unique'        => 'Такой "Email" уже зарегистрирован',
            'mail.required'      => 'Поле "Email" обязательно для заполнения.',
            'password.between'   => 'Пароль должен содержать от 8-и до 20-ти символов',
            'password.confirmed' => 'Ведённые вами пароли не совпадают',
            'usergroup.required' => 'Поле "Группа" обязательно для заполнения.'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'mail' => str($this->mail)->lower(),
        ]);
    }
}

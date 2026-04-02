<?php

namespace App\Http\Requests;

use Str;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class LoginRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'password' => 'required|string|between:6,20',
            'username' => 'required|string|between:3,20|exists:users,username',
        ];
    }

    // Опционально: кастомные сообщения
    public function messages(): array
    {
        return [
            'username.exists'   => 'Такого пользователя не существует.',
            'username.required' => 'Поле :attribute обязателено для заполнения.',
            'password.required' => 'Поле :attribute обязателено для заполнения.',
            'username.between'  => ':attribute должен содержать от 3 до 20 символов.',
            'password.between'  => ':attribute должен содержать от 6 до 20 символов.',
        ];
    }

    public function attributes(): array
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
        ];
    }

    protected function prepareForValidation()
    {
        // $this->merge([
        //     'mail'  => strtolower($this->mail),
        //     'phone' => Str::of($this->phone)->replaceMatches('/\D/', ''),
        // ]);
    }

    // Кастомная обработка ошибок валидации
    // protected function failedValidation(Validator $validator)
    // {
    //     throw new HttpResponseException(response()->json([
    //             'success' => false,
    //             'message' => 'Ошибка валидации',
    //             'errors'  => $validator->errors(),
    //         ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY) // 422
    //     );
    // }
}

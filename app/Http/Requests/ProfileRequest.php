<?php

namespace App\Http\Requests;

use Str;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name'     => 'nullable|between:3,50',
            'mail'     => 'required|email|max:50|unique:users,mail,' .auth()->id(),
            'phone'    => 'required|max:20|regex:/^\+7\d{10}$/|unique:users,phone,' .auth()->id(),
            'password' => 'confirmed',
            'about'    => 'nullable|string',
        ];
    }

    // Опционально: кастомные сообщения
    public function messages(): array
    {
        return [
            'name.between'       => 'Поле "Имя" должно содержать от 3-х до 50-ти символов',
            'mail.unique'        => 'Этот Email уже используется другим пользователем.',
            'mail.required'      => 'Поле "Email" обязательно для заполнения.',
            'phone.unique'       => 'Этот номер телефона используется другим пользователем.',
            'password.confirmed' => 'Ведённые вами пароли не совпадают',
        ];
    }

    public function attributes(): array
    {
        return [];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'mail' => Str::lower($this->mail),
        ]);
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

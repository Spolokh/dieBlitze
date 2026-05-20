<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

use App\Mail\VerifyEmail;
use App\Http\Requests\RegistrationRequest;
use App\Models\{
    User,
    Country
};

class RegistrationController extends Controller
{
    public function index(RegistrationRequest $request) //  RegistrationRequest
    {
        if ( !$request->isMethod('post') ) {
            return view ('registration', ['countries' => cache()->remember('countries', 3600, fn() => 
                Country::isHidden(false)->pluck('name', 'zone')
            )] );
        }

        $data = $request->validated();

        try {
            $user = User::create($data);
            $link = URL::temporarySignedRoute('registration.verify', now()->addMinutes(60), [
                'id'   => $user->id, 
                'hash' => sha1($user->mail)
            ]);

            Mail::to($user->mail)->send(new VerifyEmail($user, $link, 60));

            $request->session()->put('pending_user_id', $user->id);

            $status = 200;
            $result = 'Ваша регистрация прошла успешно';
            return redirect()->route('registration')
                ->with('success', "Регистрация прошла успешно!\n Проверьте почту для подтверждения.");

        } catch (\Throwable $e) {
            logger()->error('Ошибка отправки письма: ', [
                'exception' => $e
            ]);
            $status = 403;
            $result = 'Произошла ошибка при регистрации. Попробуйте позже.';
            return redirect()->route('registration')
                ->with('error', $result);
        }
    }

    public function verify(Request $request, $id)
    {
        $user = User::isActive(true)->find($id);

        if (!hash_equals((string) $id, (string) $user->getKey())) {
            throw ValidationException::withMessages([
                'mail' => ['Неверная ссылка верификации.'],
            ]);
        }

        if ( $user->deleted == 0 ) {
            return redirect()->route('home')->with('info', 'Ваш аккаунт уже активен.');
        }
        
        if ( !hash_equals((string) $request->route('hash'), sha1($user->mail)) ) {
            logger()->warning('Неверная ссылка верификации', [
                'id' => $id, 
                'ip' => $request->ip(),
                'agent' => $request->userAgent()
            ]);
            throw ValidationException::withMessages([
                'mail' => ['Неверная или устаревшая ссылка.'],
            ]);
        }

        $user->forceFill([
            'deleted'    => 0,
            'last_visit' => now(),
        ])->save();

        auth()->login($user);
        $request->session()->regenerate();
        return redirect()->route('home')->with('success', 'Email подтверждён!');
    }

    public function resend(Request $request)
    {
        if ( !$id = $request->session()->get('pending_user_id') ) {
            return back()->with('error', 'Нет данных для повторной отправки. Попробуйте зарегистрироваться снова.');
        }

        $user = User::isActive(true)->find($id);  // Ищем пользователя (без скоупов, т.к. он еще не активен - deleted=1)

        if (!$user || $user->deleted == 0) {  // Если пользователь не найден или уже активен
            $request->session()->forget('pending_user_id');
            return redirect()->route('login')->with('info', 'Ваш аккаунт уже активирован. Войдите в систему.');
        }

        try {
            $link = URL::temporarySignedRoute('registration.verify', now()->addMinutes(60), [
                'id'   => $user->id, 
                'hash' => sha1($user->mail)
            ]);

            Mail::to($user->mail)->send(new VerifyEmail($user, $link, 60));

            return back()->with('success', 'Письмо отправлено повторно! Проверьте папку "Спам".');
            
        } catch (\Throwable $e) {
            return back()->with('error', 'Не удалось отправить письмо. Попробуйте позже.');
        }
    }
}

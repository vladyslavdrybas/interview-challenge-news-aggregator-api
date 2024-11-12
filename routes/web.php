<?php

use App\Http\Requests\Auth\PasswordResetRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('api.home');
})->middleware('guest')->name('home');

// Forgot password. Without UI I can leave it here. Can be moved to invoke controller later.
Route::get('/password-reset/{token}/{email}', function (string $token, string $email) {
    return view('auth.password-reset', ['token' => $token, 'email' => $email]);
})->middleware('guest')->name('password.reset');

Route::post('/password-reset', function (PasswordResetRequest $request) {
    $validated = $request->validated();

    $validated['password_confirmation'] = $validated['password'];

    $status = Password::reset($validated, function (User $user, string $password) {
        $user->forceFill([
            'password' => Hash::make($password),
            $user->getRememberTokenName() => null,
        ])->save();

        event(new PasswordReset($user));
    });

    if ($status === Password::PASSWORD_RESET) {
        return 'Your password has been reset. Come back to the app.';
    }

    return back()->withErrors(['password' => [__($status)]]);
})->middleware('guest')->name('password.reset.background');

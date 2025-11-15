<?php

namespace WhitePage\Auth\Methods;

use WhitePage\Facades\WhitePage;
use WhitePage\Components\AuthMethod;

class LoginMethod extends AuthMethod
{
    public function get()
    {
        return view('whitepage::modules.auth.login');
    }

    public function post()
    {
        $credentials = $this->request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->attempt($credentials, $this->request->boolean('remember'))) {
            $this->request->session()->regenerate();

            return redirect()->intended(WhitePage::CMS_ROOT_PREFIX);
        }

        return back()->withErrors([
            'email' => 'Невірні дані для входу.',
        ])->onlyInput('email');
    }
}

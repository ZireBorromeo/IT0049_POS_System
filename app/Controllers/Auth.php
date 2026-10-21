<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/dashboard');
        }

        if (strtolower($this->request->getMethod()) === 'post') {
            $rules = [
                'username' => 'required|min_length[3]|max_length[50]',
                'password' => 'required',
            ];

            if (! $this->validate($rules)) {
                return $this->render('auth/login', [
                    'pageTitle' => 'Staff Login',
                    'validation' => $this->validator,
                ]);
            }

            $userModel = new UserModel();

            $user = $userModel
                ->where('username', trim((string) $this->request->getPost('username')))
                ->first();

            if (
                ! $user ||
                ! password_verify(
                    (string) $this->request->getPost('password'),
                    $user['password']
                )
            ) {
                return $this->render('auth/login', [
                    'pageTitle' => 'Staff Login',
                    'error' => 'Invalid username or password.',
                ]);
            }

            session()->regenerate();

            session()->set([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'full_name' => $user['full_name'],
                'avatar' => $user['avatar'],
                'logged_in' => true,
            ]);

            return redirect()->to('/dashboard')
                ->with('success', 'Welcome back, ' . $user['full_name'] . '!');
        }

        return $this->render('auth/login', [
            'pageTitle' => 'Staff Login',
        ]);
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')
            ->with('success', 'You have been logged out.');
    }
}
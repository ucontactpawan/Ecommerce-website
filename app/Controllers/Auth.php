<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function register()
    {
        return view('auth/register');
    }

    public function registerSave()
    {
        $users = new UserModel();

        // Get the full name and split it into first and last name
        $fullName = trim($this->request->getPost('name'));
        $nameParts = explode(' ', $fullName, 2); // Split into max 2 parts

        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

        // Generate username from email (part before @)
        $email = $this->request->getPost('email');
        $username = substr($email, 0, strpos($email, '@'));

        $data = [
            'username' => $username,
            'email' => $email,
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'is_active' => true
        ];

        try {
            $users->save($data);
            return redirect()->to('/auth/login')->with('success', 'Registration successful! Please login.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    public function login()
    {
        helper('url');
        return view('auth/login');
    }

    public function loginAuth()
    {
        $session = session();
        $users = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $users->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            $session->set([
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'logged_in' => true
            ]);
            return redirect()->to('/')->with('success', 'Welcome back!');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'You have been logged out successfully.');
    }
}

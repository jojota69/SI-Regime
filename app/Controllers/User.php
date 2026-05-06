<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    public function index(): string
    {
        return view('pages/login');
    }


    public function connecter() {

        $mail = trim((string) $this->request->getPost('mail'));
        $password = (string) $this->request->getPost('password');

        $rules = [
            'mail' => 'required|valid_email',
            'password' => 'required',
        ];

        $messages = [
            'mail' => [
                'required' => 'Email obligatoire.',
                'valid_email' => 'Email invalide.',
            ],
            'password' => [
                'required' => 'Mot de passe obligatoire.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return view('pages/login', [
                'validation' => $this->validator,
                'mail' => $mail,
            ]);
        }

        $userModel = new UserModel();
        $login = $userModel->connectUser($mail, $password);

        if ($login === true){
            return redirect()->to('/home');
        }

        return view('pages/login', [
            'error' => 'Email ou mot de passe incorrect.',
            'mail' => $mail,
        ]);

    }


}

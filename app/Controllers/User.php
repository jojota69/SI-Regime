<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProfilSanteModel;

class User extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/home');
        }
        return view('pages/login');
    }

    public function connecter() 
    {
        $mail = trim((string) $this->request->getPost('mail'));
        $password = (string) $this->request->getPost('password');

        if (!$this->validate(['mail' => 'required|valid_email', 'password' => 'required'])) {
            return view('pages/login', ['validation' => $this->validator, 'mail' => $mail]);
        }

        $userModel = new UserModel();
        $user = $userModel->getUserByEmail($mail);

        if ($user && $user['mot_de_passe'] === $password) {
            $this->setUserSession($user);
            return redirect()->to('/home');
        }

        return view('pages/login', ['error' => 'Email ou mot de passe incorrect.', 'mail' => $mail]);
    }

    public function inscrire() {
        return view('pages/inscription');
    }

    // Étape 1 : Nom, Prénom, Email, Genre, Password
    public function validerEtape1() {
        $rules = [
            'nom'      => 'required|min_length[2]',
            'prenom'   => 'required|min_length[2]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'genre'    => 'required|in_list[homme,femme]',
            'password' => 'required|min_length[4]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
        }

        session()->set('temp_user', $this->request->getPost());
        return $this->response->setJSON(['success' => true]);
    }

    // Étape 2 : Taille, Poids + Calcul IMC + Insertion finale
    public function finaliserInscription() {
        $rules = [
            'taille' => 'required|numeric|greater_than[50]',
            'poids'  => 'required|numeric|greater_than[20]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()]);
        }

        $dataP1 = session()->get('temp_user');
        $taille = (float) $this->request->getPost('taille');
        $poids  = (float) $this->request->getPost('poids');

        // Calcul IMC : poids / taille_en_metre^2
        $tailleM = $taille / 100;
        $imc = $poids / ($tailleM * $tailleM);

        $userModel = new UserModel();
        $profilModel = new ProfilSanteModel();
        $db = \Config\Database::connect();

        $db->transStart(); // Début transaction

        // 1. Insertion User
        $userId = $userModel->insert([
            'nom'          => $dataP1['nom'],
            'prenom'       => $dataP1['prenom'],
            'email'        => $dataP1['email'],
            'genre'        => $dataP1['genre'],
            'mot_de_passe' => $dataP1['password'],
            'role'         => 'user'
        ]);

        // 2. Insertion Profil Santé
        $profilModel->insert([
            'user_id'         => $userId,
            'taille_cm'       => $taille,
            'poids_actuel_kg' => $poids,
            'imc_actuel'      => round($imc, 2)
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Erreur lors de l\'enregistrement.']);
        }

        session()->remove('temp_user');
        return $this->response->setJSON(['success' => true]);
    }

    private function setUserSession($user) {
        session()->set([
            'id' => $user['id'], 
            'email' => $user['email'], 
            'isLoggedIn' => true,
            'role' => $user['role']
        ]);
    }

    public function logout() {
        session()->destroy();
        return redirect()->to('/');
    }
}
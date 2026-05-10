<?php

namespace App\Controllers;

use App\Models\CodePortefeuilleModel;

class CodePortefeuille extends BaseController
{
    /**
     * Recharger le portefeuille d'un utilisateur avec un code
     */

    public function gererCodes(){
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/home'); // Redirige les intrus
        }

        $codeModel = new CodePortefeuilleModel();
        $codesInvalide = $codeModel->getCodesInvalide();

        $codes = $codeModel->findAll();

        return view('pages/admin/gererCodes', ['codes' => $codes, 'codesInvalide' => $codesInvalide, 'title' => 'Gérer les codes']);
    }

    public function recharger()
    {
        // Vérification de session
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        // Récupération et nettoyage de la saisie
        $code = trim((string) $this->request->getPost('code'));
        $userId = (int) session()->get('id');

        if ($code === '') {
            session()->setFlashdata('wallet_error', 'Veuillez entrer un code.');
            return redirect()->to('/home');
        }

        $codeModel = new CodePortefeuilleModel();

        // Appel de la logique métier située dans le modèle
        if ($codeModel->utiliserCode($code, $userId)) {
            session()->setFlashdata('wallet_message', 'Recharge effectuée avec succès !');
        } else {
            session()->setFlashdata('wallet_error', 'Code invalide, déjà utilisé ou en attente de validation.');
        }

        return redirect()->to('/home');
    }


    public function validerCode(){

        $code = $this->request->getPost('code');

        $codeModel = new CodePortefeuilleModel();
        $codeModel->validerCode($code);

        return redirect()->to('/admin/codes')->with('message', 'Code validé avec succès !');
    }
}
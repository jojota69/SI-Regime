<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ProfilSanteModel;

class Admin extends BaseController
{

    public function index(){
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/home'); // Redirige les intrus
        }
        return view('pages/admin/admin');
    }

}
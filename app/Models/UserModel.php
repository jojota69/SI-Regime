<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model {
    protected $table = 'users';
    protected $primaryKey = 'id';
    // Champs adaptés à la nouvelle BD (role, est_gold et solde ont des valeurs par défaut en BD)
    protected $allowedFields = ['nom', 'prenom', 'email', 'mot_de_passe', 'genre', 'role', 'est_gold', 'solde_ariary'];

    public function getUserByEmail($mail) {
        return $this->where('email', $mail)->first();
    }
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilSanteModel extends Model {
    protected $table = 'profils_sante';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['user_id', 'taille_cm', 'poids_actuel_kg', 'imc_actuel'];
}
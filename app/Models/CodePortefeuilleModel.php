<?php

namespace App\Models;

use CodeIgniter\Model;

class CodePortefeuilleModel extends Model
{
    protected $table = 'codes_portefeuille';
    protected $primaryKey = 'id';
    protected $allowedFields = ['code', 'montant', 'est_utilise', 'est_valide', 'user_id'];
}

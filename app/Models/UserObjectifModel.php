<?php

namespace App\Models;

use CodeIgniter\Model;

class UserObjectifModel extends Model
{
    protected $table = 'user_objectifs';
    protected $primaryKey = 'user_id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['user_id', 'objectif_id', 'poids_cible_kg'];
}

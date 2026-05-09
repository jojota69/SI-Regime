<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeActiviteModel extends Model
{
    protected $table = 'regime_activites';
    protected $primaryKey = 'regime_id';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['regime_id', 'activite_id'];
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class PrixRegimeModel extends Model
{
    protected $table = 'prix_regimes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['regime_id', 'duree_jours', 'prix_ariary'];

    public function getPrixPourDuree(int $regimeId, int $dureeJours): ?array
    {
        $row = $this->where('regime_id', $regimeId)
            ->orderBy('ABS(duree_jours - ' . $dureeJours . ')', 'ASC', false)
            ->first();

        return $row ?: null;
    }
}

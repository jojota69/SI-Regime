<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeModel extends Model
{
    protected $table = 'regimes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nom',
        'description',
        'pct_viande',
        'pct_poisson',
        'pct_volaille',
        'variation_poids_kg',
        'objectif_id'
    ];

    public function getSuggestionForObjectif(int $objectifId): ?array
    {
        $row = $this->db->table('regimes')
            ->select('regimes.*, activites_sportives.nom as activite_nom')
            ->join('regime_activites', 'regime_activites.regime_id = regimes.id', 'left')
            ->join('activites_sportives', 'activites_sportives.id = regime_activites.activite_id', 'left')
            ->where('regimes.objectif_id', $objectifId)
            ->orderBy('regimes.id', 'ASC')
            ->get()
            ->getRowArray();

        return $row ?: null;
    }
}

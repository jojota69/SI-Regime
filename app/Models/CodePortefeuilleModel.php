<?php

namespace App\Models;

use CodeIgniter\Model;

class CodePortefeuilleModel extends Model
{
    protected $table = 'codes_portefeuille';
    protected $primaryKey = 'id';
    protected $allowedFields = ['code', 'montant', 'est_utilise', 'est_valide', 'user_id'];

    /**
     * Tente d'appliquer un code au portefeuille d'un utilisateur
     * @return bool True si succès, False sinon
     */

    public function getCodesInvalide(){
        return $this->where('est_valide', 0)->findAll();
    }


    public function utiliserCode(string $code, int $userId): bool
    {
        // 1. Rechercher si le code existe, est valide et non utilisé
        $codeRow = $this->where('code', $code)
                        ->where('est_valide', 1)
                        ->where('est_utilise', 0)
                        ->first();

        if (!$codeRow) {
            return false;
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 2. Mettre à jour le solde de l'utilisateur (on utilise increment pour éviter les problèmes de concurrence)
        $db->table('users')
           ->where('id', $userId)
           ->increment('solde_ariary', (float) $codeRow['montant']);

        // 3. Marquer le code comme utilisé
        $this->update($codeRow['id'], [
            'est_utilise' => 1,
            'user_id'     => $userId
        ]);

        $db->transComplete();

        return $db->transStatus();
    }

    public function trouverCode($code){

        return $this->where('code', $code)->first();

    }

    public function validerCode($code){

        $codeAValider = $this->trouverCode($code);

        return $this->update($codeAValider['id'], ['est_valide' => 1]);

    }

}
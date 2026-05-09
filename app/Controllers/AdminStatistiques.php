<?php

namespace App\Controllers;

class AdminStatistiques extends BaseController
{
    private function guardAdmin()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/home');
        }

        return null;
    }

    public function index()
    {
        if ($response = $this->guardAdmin()) {
            return $response;
        }

        $db = \Config\Database::connect();

        $stats = [
            'users_total' => $db->table('users')->countAllResults(),
            'admins_total' => $db->table('users')->where('role', 'admin')->countAllResults(),
            'regimes_total' => $db->table('regimes')->countAllResults(),
            'activites_total' => $db->table('activites_sportives')->countAllResults(),
            'codes_total' => $db->table('codes_portefeuille')->countAllResults(),
            'codes_pending' => $db->table('codes_portefeuille')->where('est_valide', 0)->countAllResults(),
            'codes_used' => $db->table('codes_portefeuille')->where('est_utilise', 1)->countAllResults(),
            'codes_valides' => $db->table('codes_portefeuille')->where('est_valide', 1)->countAllResults(),
            'codes_disponibles' => $db->table('codes_portefeuille')->where('est_valide', 1)->where('est_utilise', 0)->countAllResults(),
        ];

        $revenuSouscriptionsRow = $db->table('souscriptions')->selectSum('montant_paye')->get()->getRowArray();
        $revenuGoldRow = $db->table('achats_gold')->selectSum('montant_paye')->get()->getRowArray();

        $stats['revenu_souscriptions'] = (float) ($revenuSouscriptionsRow['montant_paye'] ?? 0);
        $stats['revenu_gold'] = (float) ($revenuGoldRow['montant_paye'] ?? 0);

        $objectifs = $db->table('objectifs')->select('id, nom')->orderBy('id', 'ASC')->get()->getResultArray();

        $regimesByObj = $this->mapCounts(
            $db->table('regimes')->select('objectif_id, COUNT(*) as total')->groupBy('objectif_id')->get()->getResultArray(),
            'objectif_id'
        );

        $activitesByObj = $this->mapCounts(
            $db->table('activites_sportives')->select('objectif_id, COUNT(*) as total')->groupBy('objectif_id')->get()->getResultArray(),
            'objectif_id'
        );

        $souscriptionsByObj = $this->mapCounts(
            $db->table('souscriptions')
                ->select('regimes.objectif_id as objectif_id, COUNT(souscriptions.id) as total')
                ->join('regimes', 'regimes.id = souscriptions.regime_id', 'left')
                ->groupBy('regimes.objectif_id')
                ->get()
                ->getResultArray(),
            'objectif_id'
        );

        $chartLabels = [];
        $chartRegimes = [];
        $chartActivites = [];

        $pivotRows = [];
        foreach ($objectifs as $objectif) {
            $objectifId = (int) $objectif['id'];
            $chartLabels[] = $objectif['nom'];
            $chartRegimes[] = $regimesByObj[$objectifId] ?? 0;
            $chartActivites[] = $activitesByObj[$objectifId] ?? 0;

            $pivotRows[] = [
                'objectif' => $objectif['nom'],
                'regimes' => $regimesByObj[$objectifId] ?? 0,
                'activites' => $activitesByObj[$objectifId] ?? 0,
                'souscriptions' => $souscriptionsByObj[$objectifId] ?? 0
            ];
        }

        $activitesSansObjectif = $db->table('activites_sportives')->where('objectif_id', null)->countAllResults();
        if ($activitesSansObjectif > 0) {
            $pivotRows[] = [
                'objectif' => 'Sans objectif',
                'regimes' => 0,
                'activites' => $activitesSansObjectif,
                'souscriptions' => 0
            ];
        }

        return view('pages/admin/statistiques', [
            'stats' => $stats,
            'chartLabels' => $chartLabels,
            'chartRegimes' => $chartRegimes,
            'chartActivites' => $chartActivites,
            'pivotRows' => $pivotRows
        ]);
    }

    private function mapCounts(array $rows, string $keyField): array
    {
        $map = [];
        foreach ($rows as $row) {
            $key = $row[$keyField] ?? null;
            if ($key === null) {
                continue;
            }
            $map[(int) $key] = (int) ($row['total'] ?? 0);
        }

        return $map;
    }
}

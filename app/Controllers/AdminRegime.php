<?php

namespace App\Controllers;

use App\Models\ActiviteSportiveModel;
use App\Models\ObjectifModel;
use App\Models\PrixRegimeModel;
use App\Models\RegimeModel;

class AdminRegime extends BaseController
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

        $regimes = $db->table('regimes')
            ->select('regimes.*, objectifs.nom as objectif_nom')
            ->join('objectifs', 'objectifs.id = regimes.objectif_id', 'left')
            ->orderBy('regimes.id', 'DESC')
            ->get()
            ->getResultArray();

        $activiteRows = $db->table('regime_activites')
            ->select('regime_activites.regime_id, activites_sportives.nom')
            ->join('activites_sportives', 'activites_sportives.id = regime_activites.activite_id', 'left')
            ->get()
            ->getResultArray();

        $activitesByRegime = [];
        foreach ($activiteRows as $row) {
            if (!empty($row['nom'])) {
                $activitesByRegime[$row['regime_id']][] = $row['nom'];
            }
        }

        $prixRows = $db->table('prix_regimes')
            ->select('regime_id, duree_jours, prix_ariary')
            ->orderBy('duree_jours', 'ASC')
            ->get()
            ->getResultArray();

        $prixByRegime = [];
        foreach ($prixRows as $row) {
            $prixByRegime[$row['regime_id']][] = $row;
        }

        return view('pages/admin/regimes/index', [
            'regimes' => $regimes,
            'activitesByRegime' => $activitesByRegime,
            'prixByRegime' => $prixByRegime
        ]);
    }

    public function create()
    {
        if ($response = $this->guardAdmin()) {
            return $response;
        }

        return view('pages/admin/regimes/form', $this->buildFormData());
    }

    public function store()
    {
        if ($response = $this->guardAdmin()) {
            return $response;
        }

        $payload = $this->getRegimePayload();
        $errors = $this->validateRegimePayload($payload);
        [$prixRows, $prixErrors] = $this->parsePrixFromRequest();
        $errors = array_merge($errors, $prixErrors);

        $activiteIds = $this->normalizeActiviteIds((array) $this->request->getPost('activite_ids'));

        if (!empty($errors)) {
            return view('pages/admin/regimes/form', $this->buildFormData($payload, $activiteIds, $prixRows, $errors));
        }

        $regimeModel = new RegimeModel();
        $prixModel = new PrixRegimeModel();
        $db = \Config\Database::connect();

        $db->transStart();

        $regimeId = $regimeModel->insert($payload);

        foreach ($prixRows as $row) {
            $prixModel->insert([
                'regime_id' => $regimeId,
                'duree_jours' => $row['duree_jours'],
                'prix_ariary' => $row['prix_ariary']
            ]);
        }

        if (!empty($activiteIds)) {
            foreach ($activiteIds as $activiteId) {
                $db->table('regime_activites')->insert([
                    'regime_id' => $regimeId,
                    'activite_id' => $activiteId
                ]);
            }
        }

        $db->transComplete();

        return redirect()->to('/admin/regimes')->with('message', 'Regime cree avec succes.');
    }

    public function edit($id)
    {
        if ($response = $this->guardAdmin()) {
            return $response;
        }

        $regimeModel = new RegimeModel();
        $prixModel = new PrixRegimeModel();
        $db = \Config\Database::connect();

        $regime = $regimeModel->find((int) $id);
        if (empty($regime)) {
            return redirect()->to('/admin/regimes')->with('error', 'Regime introuvable.');
        }

        $prixRows = $prixModel->getPrixForRegime((int) $id);

        $activiteRows = $db->table('regime_activites')
            ->select('activite_id')
            ->where('regime_id', (int) $id)
            ->get()
            ->getResultArray();

        $selectedActivites = array_map('intval', array_column($activiteRows, 'activite_id'));

        return view('pages/admin/regimes/form', $this->buildFormData($regime, $selectedActivites, $prixRows));
    }

    public function update($id)
    {
        if ($response = $this->guardAdmin()) {
            return $response;
        }

        $regimeModel = new RegimeModel();
        $prixModel = new PrixRegimeModel();

        $regime = $regimeModel->find((int) $id);
        if (empty($regime)) {
            return redirect()->to('/admin/regimes')->with('error', 'Regime introuvable.');
        }

        $payload = $this->getRegimePayload();
        $errors = $this->validateRegimePayload($payload);
        [$prixRows, $prixErrors] = $this->parsePrixFromRequest();
        $errors = array_merge($errors, $prixErrors);

        $activiteIds = $this->normalizeActiviteIds((array) $this->request->getPost('activite_ids'));

        if (!empty($errors)) {
            $payload['id'] = (int) $id;
            return view('pages/admin/regimes/form', $this->buildFormData($payload, $activiteIds, $prixRows, $errors));
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $regimeModel->update((int) $id, $payload);

        $prixModel->where('regime_id', (int) $id)->delete();
        foreach ($prixRows as $row) {
            $prixModel->insert([
                'regime_id' => (int) $id,
                'duree_jours' => $row['duree_jours'],
                'prix_ariary' => $row['prix_ariary']
            ]);
        }

        $db->table('regime_activites')->where('regime_id', (int) $id)->delete();
        if (!empty($activiteIds)) {
            foreach ($activiteIds as $activiteId) {
                $db->table('regime_activites')->insert([
                    'regime_id' => (int) $id,
                    'activite_id' => $activiteId
                ]);
            }
        }

        $db->transComplete();

        return redirect()->to('/admin/regimes')->with('message', 'Regime mis a jour.');
    }

    public function delete($id)
    {
        if ($response = $this->guardAdmin()) {
            return $response;
        }

        $regimeModel = new RegimeModel();
        $regimeModel->delete((int) $id);

        return redirect()->to('/admin/regimes')->with('message', 'Regime supprime.');
    }

    private function buildFormData(array $regime = [], array $selectedActivites = [], array $prixRows = [], array $errors = []): array
    {
        $objectifModel = new ObjectifModel();
        $activiteModel = new ActiviteSportiveModel();

        if (empty($prixRows)) {
            $prixRows = [
                ['duree_jours' => '', 'prix_ariary' => '']
            ];
        }

        return [
            'regime' => $regime,
            'objectifs' => $objectifModel->orderBy('nom', 'ASC')->findAll(),
            'activites' => $activiteModel->orderBy('nom', 'ASC')->findAll(),
            'selectedActivites' => $selectedActivites,
            'prixRows' => $prixRows,
            'errors' => $errors
        ];
    }

    private function getRegimePayload(): array
    {
        return [
            'nom' => trim((string) $this->request->getPost('nom')),
            'pct_viande' => (float) $this->request->getPost('pct_viande'),
            'pct_poisson' => (float) $this->request->getPost('pct_poisson'),
            'pct_volaille' => (float) $this->request->getPost('pct_volaille'),
            'variation_poids_kg' => (float) $this->request->getPost('variation_poids_kg'),
            'duree_standard_jours' => (int) $this->request->getPost('duree_standard_jours'),
            'objectif_id' => (int) $this->request->getPost('objectif_id')
        ];
    }

    private function validateRegimePayload(array $payload): array
    {
        $errors = [];

        if ($payload['nom'] === '') {
            $errors[] = 'Le nom du regime est requis.';
        }

        $sum = $payload['pct_viande'] + $payload['pct_poisson'] + $payload['pct_volaille'];
        if (abs($sum - 100) > 0.01) {
            $errors[] = 'Le total des pourcentages (viande, poisson, volaille) doit faire 100%.';
        }

        if ($payload['duree_standard_jours'] <= 0) {
            $errors[] = 'La duree standard doit etre superieure a 0.';
        }

        if ($payload['objectif_id'] <= 0) {
            $errors[] = 'Veuillez choisir un objectif valide.';
        }

        $objectifModel = new ObjectifModel();
        if ($payload['objectif_id'] > 0 && empty($objectifModel->find($payload['objectif_id']))) {
            $errors[] = 'Objectif introuvable.';
        }

        return $errors;
    }

    private function parsePrixFromRequest(): array
    {
        $durations = (array) $this->request->getPost('duree_jours');
        $prices = (array) $this->request->getPost('prix_ariary');

        $rows = [];
        $errors = [];
        $seenDurations = [];

        foreach ($durations as $index => $dureeRaw) {
            $prixRaw = $prices[$index] ?? '';

            $dureeRaw = trim((string) $dureeRaw);
            $prixRaw = trim((string) $prixRaw);

            if ($dureeRaw === '' && $prixRaw === '') {
                continue;
            }

            if (!is_numeric($dureeRaw) || (int) $dureeRaw <= 0) {
                $errors[] = 'Chaque duree doit etre un nombre positif.';
                continue;
            }

            if (!is_numeric($prixRaw) || (float) $prixRaw <= 0) {
                $errors[] = 'Chaque prix doit etre un nombre positif.';
                continue;
            }

            $duree = (int) $dureeRaw;
            if (isset($seenDurations[$duree])) {
                $errors[] = 'Les durees de prix doivent etre uniques.';
                continue;
            }

            $seenDurations[$duree] = true;
            $rows[] = [
                'duree_jours' => $duree,
                'prix_ariary' => (float) $prixRaw
            ];
        }

        if (empty($rows)) {
            $errors[] = 'Ajoutez au moins un prix pour ce regime.';
        }

        return [$rows, $errors];
    }

    private function normalizeActiviteIds(array $rawIds): array
    {
        $ids = array_filter(array_map('intval', $rawIds));
        return array_values(array_unique($ids));
    }
}

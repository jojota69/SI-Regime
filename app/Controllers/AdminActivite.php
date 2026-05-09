<?php

namespace App\Controllers;

use App\Models\ActiviteSportiveModel;
use App\Models\ObjectifModel;

class AdminActivite extends BaseController
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

        $activites = $db->table('activites_sportives')
            ->select('activites_sportives.*, objectifs.nom as objectif_nom')
            ->join('objectifs', 'objectifs.id = activites_sportives.objectif_id', 'left')
            ->orderBy('activites_sportives.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('pages/admin/activites/index', [
            'activites' => $activites
        ]);
    }

    public function create()
    {
        if ($response = $this->guardAdmin()) {
            return $response;
        }

        return view('pages/admin/activites/form', $this->buildFormData());
    }

    public function store()
    {
        if ($response = $this->guardAdmin()) {
            return $response;
        }

        $payload = $this->getActivitePayload();
        $errors = $this->validateActivitePayload($payload);

        if (!empty($errors)) {
            return view('pages/admin/activites/form', $this->buildFormData($payload, $errors));
        }

        $model = new ActiviteSportiveModel();
        $model->insert($payload);

        return redirect()->to('/admin/activites')->with('message', 'Activite creee avec succes.');
    }

    public function edit($id)
    {
        if ($response = $this->guardAdmin()) {
            return $response;
        }

        $model = new ActiviteSportiveModel();
        $activite = $model->find((int) $id);

        if (empty($activite)) {
            return redirect()->to('/admin/activites')->with('error', 'Activite introuvable.');
        }

        return view('pages/admin/activites/form', $this->buildFormData($activite));
    }

    public function update($id)
    {
        if ($response = $this->guardAdmin()) {
            return $response;
        }

        $model = new ActiviteSportiveModel();
        $activite = $model->find((int) $id);

        if (empty($activite)) {
            return redirect()->to('/admin/activites')->with('error', 'Activite introuvable.');
        }

        $payload = $this->getActivitePayload();
        $errors = $this->validateActivitePayload($payload);

        if (!empty($errors)) {
            $payload['id'] = (int) $id;
            return view('pages/admin/activites/form', $this->buildFormData($payload, $errors));
        }

        $model->update((int) $id, $payload);

        return redirect()->to('/admin/activites')->with('message', 'Activite mise a jour.');
    }

    public function delete($id)
    {
        if ($response = $this->guardAdmin()) {
            return $response;
        }

        $model = new ActiviteSportiveModel();
        $model->delete((int) $id);

        return redirect()->to('/admin/activites')->with('message', 'Activite supprimee.');
    }

    private function buildFormData(array $activite = [], array $errors = []): array
    {
        $objectifModel = new ObjectifModel();

        return [
            'activite' => $activite,
            'objectifs' => $objectifModel->orderBy('nom', 'ASC')->findAll(),
            'errors' => $errors
        ];
    }

    private function getActivitePayload(): array
    {
        $objectifId = $this->request->getPost('objectif_id');
        $objectifId = ($objectifId === '' || $objectifId === null) ? null : (int) $objectifId;

        return [
            'nom' => trim((string) $this->request->getPost('nom')),
            'objectif_id' => $objectifId
        ];
    }

    private function validateActivitePayload(array $payload): array
    {
        $errors = [];

        if ($payload['nom'] === '') {
            $errors[] = 'Le nom de l\'activite est requis.';
        }

        if ($payload['objectif_id'] !== null) {
            $objectifModel = new ObjectifModel();
            if (empty($objectifModel->find((int) $payload['objectif_id']))) {
                $errors[] = 'Objectif introuvable.';
            }
        }

        return $errors;
    }
}

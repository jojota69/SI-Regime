<?php

namespace App\Controllers;

use App\Models\ParametreModel;

class AdminParametre extends BaseController
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

        $model = new ParametreModel();
        $parametres = $model->orderBy('cle', 'ASC')->findAll();

        return view('pages/admin/parametres/index', [
            'parametres' => $parametres
        ]);
    }

    public function create()
    {
        if ($response = $this->guardAdmin()) {
            return $response;
        }

        return view('pages/admin/parametres/form', [
            'parametre' => [],
            'errors' => []
        ]);
    }

    public function store()
    {
        if ($response = $this->guardAdmin()) {
            return $response;
        }

        $payload = $this->getParametrePayload();
        $errors = $this->validateParametrePayload($payload, true);

        if (!empty($errors)) {
            return view('pages/admin/parametres/form', [
                'parametre' => $payload,
                'errors' => $errors
            ]);
        }

        $model = new ParametreModel();
        $model->insert($payload);

        return redirect()->to('/admin/parametres')->with('message', 'Parametre cree avec succes.');
    }

    public function edit($cle)
    {
        if ($response = $this->guardAdmin()) {
            return $response;
        }

        $decoded = rawurldecode((string) $cle);
        $model = new ParametreModel();
        $parametre = $model->find($decoded);

        if (empty($parametre)) {
            return redirect()->to('/admin/parametres')->with('error', 'Parametre introuvable.');
        }

        return view('pages/admin/parametres/form', [
            'parametre' => $parametre,
            'errors' => []
        ]);
    }

    public function update($cle)
    {
        if ($response = $this->guardAdmin()) {
            return $response;
        }

        $decoded = rawurldecode((string) $cle);
        $model = new ParametreModel();
        $parametre = $model->find($decoded);

        if (empty($parametre)) {
            return redirect()->to('/admin/parametres')->with('error', 'Parametre introuvable.');
        }

        $payload = $this->getParametrePayload(false);
        $payload['cle'] = $decoded;
        $errors = $this->validateParametrePayload($payload, false);

        if (!empty($errors)) {
            return view('pages/admin/parametres/form', [
                'parametre' => $payload,
                'errors' => $errors
            ]);
        }

        $model->update($decoded, $payload);

        return redirect()->to('/admin/parametres')->with('message', 'Parametre mis a jour.');
    }

    public function delete($cle)
    {
        if ($response = $this->guardAdmin()) {
            return $response;
        }

        $decoded = rawurldecode((string) $cle);
        $model = new ParametreModel();
        $model->delete($decoded);

        return redirect()->to('/admin/parametres')->with('message', 'Parametre supprime.');
    }

    private function getParametrePayload(bool $includeCle = true): array
    {
        $payload = [
            'valeur' => trim((string) $this->request->getPost('valeur')),
            'description' => trim((string) $this->request->getPost('description'))
        ];

        if ($includeCle) {
            $payload['cle'] = trim((string) $this->request->getPost('cle'));
        }

        return $payload;
    }

    private function validateParametrePayload(array $payload, bool $isCreate): array
    {
        $errors = [];

        if ($isCreate && ($payload['cle'] ?? '') === '') {
            $errors[] = 'La cle du parametre est requise.';
        }

        if ($payload['valeur'] === '') {
            $errors[] = 'La valeur est requise.';
        }

        if ($isCreate) {
            $model = new ParametreModel();
            if (!empty($payload['cle']) && !empty($model->find($payload['cle']))) {
                $errors[] = 'Cette cle existe deja.';
            }
        }

        return $errors;
    }
}

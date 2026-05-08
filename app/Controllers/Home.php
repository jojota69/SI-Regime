<?php

namespace App\Controllers;

use App\Models\ProfilSanteModel;
use App\Models\PrixRegimeModel;
use App\Models\UserModel;
use App\Models\ObjectifModel;
use App\Models\UserObjectifModel;
use App\Models\RegimeModel;
use Dompdf\Dompdf;

class Home extends BaseController
{
    private const GOLD_PRICE_ARIARY = 100000;

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $userId = (int) session()->get('id');
        $data = $this->chargerDonneesUtilisateur($userId);
        $data['walletMessage'] = session()->getFlashdata('wallet_message');
        $data['walletError'] = session()->getFlashdata('wallet_error');
        $data['goldPrice'] = self::GOLD_PRICE_ARIARY;

        return view('pages/home', $data);
    }

    public function enregistrerObjectif()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $objectifId = (int) $this->request->getPost('objectif_id');
        $objectifModel = new ObjectifModel();

        if (!$objectifId || !$objectifModel->find($objectifId)) {
            return redirect()->to('/home');
        }

        $userObjectifModel = new UserObjectifModel();
        $payload = [
            'user_id' => (int) session()->get('id'),
            'objectif_id' => $objectifId
        ];

        if ($userObjectifModel->find($payload['user_id'])) {
            $userObjectifModel->update($payload['user_id'], $payload);
        } else {
            $userObjectifModel->insert($payload);
        }

        return redirect()->to('/home');
    }

    public function activerGold()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $userId = (int) session()->get('id');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (empty($user)) {
            session()->setFlashdata('wallet_error', 'Utilisateur introuvable.');
            return redirect()->to('/home');
        }

        if ((int) $user['est_gold'] === 1) {
            session()->setFlashdata('wallet_message', 'Option Gold deja active.');
            return redirect()->to('/home');
        }

        if ((float) $user['solde_ariary'] < self::GOLD_PRICE_ARIARY) {
            session()->setFlashdata('wallet_error', 'Solde insuffisant pour activer Gold.');
            return redirect()->to('/home');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $nouveauSolde = (float) $user['solde_ariary'] - self::GOLD_PRICE_ARIARY;
        $userModel->update($userId, [
            'est_gold' => 1,
            'solde_ariary' => $nouveauSolde
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            session()->setFlashdata('wallet_error', 'Erreur lors de l\'activation Gold.');
        } else {
            session()->setFlashdata('wallet_message', 'Option Gold activee.');
        }

        return redirect()->to('/home');
    }

    public function exporterPdf()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $userId = (int) session()->get('id');
        $data = $this->chargerDonneesUtilisateur($userId);
        $data['dateExport'] = date('Y-m-d');
        $data['goldPrice'] = self::GOLD_PRICE_ARIARY;

        $html = view('pages/export_pdf', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="rapport_regime.pdf"')
            ->setBody($dompdf->output());
    }

    private function chargerDonneesUtilisateur(int $userId): array
    {
        $userModel = new UserModel();
        $profilModel = new ProfilSanteModel();
        $objectifModel = new ObjectifModel();
        $userObjectifModel = new UserObjectifModel();
        $regimeModel = new RegimeModel();
        $prixModel = new PrixRegimeModel();

        $user = $userModel->find($userId);
        $profil = $profilModel->find($userId);
        $objectifs = $objectifModel->findAll();
        $userObjectif = $userObjectifModel->find($userId);

        $objectif = null;
        $suggestion = null;
        $dureeJours = null;
        $prixBase = null;
        $prixFinal = null;
        $prixDuree = null;

        if (!empty($userObjectif)) {
            $objectif = $objectifModel->find($userObjectif['objectif_id']);
            $suggestion = $regimeModel->getSuggestionForObjectif((int) $userObjectif['objectif_id']);

            if (!empty($suggestion)) {
                $dureeJours = $this->calculerDureeJours(
                    (float) $suggestion['variation_poids_kg'],
                    (string) ($suggestion['intensite'] ?? '')
                );

                if ($dureeJours !== null) {
                    $prix = $prixModel->getPrixPourDuree((int) $suggestion['id'], (int) $dureeJours);
                    if (!empty($prix)) {
                        $prixBase = (float) $prix['prix_ariary'];
                        $prixDuree = (int) $prix['duree_jours'];
                        $estGold = !empty($user) && (int) $user['est_gold'] === 1;
                        $prixFinal = $estGold ? round($prixBase * 0.85, 2) : $prixBase;
                    }
                }
            }
        }

        return [
            'user' => $user,
            'profil' => $profil,
            'objectifs' => $objectifs,
            'userObjectif' => $userObjectif,
            'objectif' => $objectif,
            'suggestion' => $suggestion,
            'dureeJours' => $dureeJours,
            'prixBase' => $prixBase,
            'prixFinal' => $prixFinal,
            'prixDuree' => $prixDuree
        ];
    }

    private function calculerDureeJours(float $variationKg, string $intensite): int
    {
        $variationKg = abs($variationKg);
        $baseJours = (int) max(7, round($variationKg * 14));

        $facteur = 1.0;
        $intensiteTrim = strtolower(trim($intensite));

        if ($intensiteTrim !== '' && is_numeric($intensiteTrim)) {
            $val = (float) $intensiteTrim;
            if ($val >= 600) {
                $facteur = 1.3;
            } elseif ($val >= 350) {
                $facteur = 1.1;
            } else {
                $facteur = 0.9;
            }
        } else {
            if (str_contains($intensiteTrim, 'eleve')) {
                $facteur = 1.3;
            } elseif (str_contains($intensiteTrim, 'moyen')) {
                $facteur = 1.1;
            } elseif (str_contains($intensiteTrim, 'faible')) {
                $facteur = 0.9;
            }
        }

        $duree = (int) round($baseJours / $facteur);

        return (int) min(180, max(7, $duree));
    }
}

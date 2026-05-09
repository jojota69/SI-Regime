<?php

namespace App\Models;

use CodeIgniter\Model;

class ParametreModel extends Model
{
    protected $table = 'parametres';
    protected $primaryKey = 'cle';
    protected $useAutoIncrement = false;
    protected $allowedFields = ['cle', 'valeur', 'description'];

    public function getValue(string $cle, ?string $default = null): ?string
    {
        $row = $this->find($cle);
        if (empty($row)) {
            return $default;
        }

        return (string) $row['valeur'];
    }

    public function getFloat(string $cle, float $default): float
    {
        $value = $this->getValue($cle);
        if ($value === null || $value === '') {
            return $default;
        }

        $normalized = str_replace(',', '.', $value);
        if (!is_numeric($normalized)) {
            return $default;
        }

        return (float) $normalized;
    }
}

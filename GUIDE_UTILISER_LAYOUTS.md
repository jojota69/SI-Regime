# 🎮 Guide: Utiliser les Layouts dans les Contrôleurs

## Vue d'Ensemble

CodeIgniter 4 gère automatiquement les layouts quand vous utilisez `view()` avec `extend()` et `section()`.

---

## 📝 Exemple 1: Page Utilisateur Simple (home)

### Contrôleur
```php
<?php namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Accueil',
            'user' => $this->userModel->find(session()->get('id')),
            'profil' => $this->profileModel->getByUserId(session()->get('id')),
            // ... autres données
        ];
        
        return view('pages/home', $data);
    }
}
```

### Vue (pages/home.php)
```php
<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>

<style>
    /* Styles page-spécifiques */
</style>

<!-- Contenu -->
<div class="hero">
    <h1><?= esc($title) ?></h1>
</div>

<?php $this->endSection(); ?>
```

### Rendu Résultant
```
↓ Layout main.php reçoit la vue
├── <!DOCTYPE html>
├── <head><title>Accueil - HealthyRegime</title></head>
├── <body>
├── <header> (depuis header.php)
├── <main>
│   └── [contenu de pages/home.php]
├── </main>
├── <footer> (depuis footer.php)
└── </body>
```

---

## 🔐 Exemple 2: Page d'Authentification (login)

### Contrôleur
```php
<?php namespace App\Controllers;

class Auth extends BaseController
{
    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            // Logique de connexion...
        }
        
        return view('pages/login', [
            'title' => 'Connexion',
            'validation' => $this->validator,
            'error' => $error ?? null,
        ]);
    }
}
```

### Vue (pages/login.php)
```php
<?php $this->extend('layouts/auth'); ?>

<?php $this->section('content'); ?>

<style>
    .login-container { /* ... */ }
    .login-card { /* ... */ }
</style>

<div class="login-container">
    <div class="login-card">
        <!-- Formulaire -->
        <form action="/login" method="post">
            <input type="email" name="mail" required>
            <input type="password" name="password" required>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</div>

<?php $this->endSection(); ?>
```

### Rendu Résultant
```
↓ Layout auth.php (pas de header/footer)
├── <!DOCTYPE html>
├── <head><title>Connexion - HealthyRegime</title></head>
├── <body>
├── [contenu de pages/login.php]
└── </body>
```

---

## ⚙️ Exemple 3: Page Admin (admin)

### Contrôleur
```php
<?php namespace App\Controllers;

class Admin extends BaseController
{
    public function index()
    {
        // Vérifier que l'utilisateur est admin
        if (!$this->userModel->isAdmin(session()->get('id'))) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
        
        return view('pages/admin/admin', [
            'title' => 'Panneau Admin',
        ]);
    }
}
```

### Vue (pages/admin/admin.php)
```php
<?php $this->extend('layouts/admin'); ?>

<?php $this->section('content'); ?>

<style>
    .admin-grid { /* ... */ }
    .admin-card { /* ... */ }
</style>

<div class="admin-grid">
    <a href="/admin/codes" class="admin-card">
        <div class="card-icon">📋</div>
        <div class="card-title">Gérer les codes</div>
    </a>
    <!-- ... autres cartes -->
</div>

<?php $this->endSection(); ?>
```

### Rendu Résultant
```
↓ Layout admin.php
├── <!DOCTYPE html>
├── <head><title>Panneau Admin - HealthyRegime</title></head>
├── <body>
├── <div class="admin-header"> (depuis admin_header.php)
├── <main class="container mb-4">
│   └── [contenu de pages/admin/admin.php]
├── </main>
├── <footer> (depuis footer.php)
└── </body>
```

---

## 🎯 Transmission de Données

### Données Simples
```php
// Contrôleur
return view('pages/home', [
    'user' => $userData,
    'balance' => 50000,
    'isGold' => true,
]);

// Vue - Accès direct à $user, $balance, $isGold
<?= esc($user['nom']) ?>
<?= number_format($balance) ?>
<?php if ($isGold): ?> ✨ <?php endif; ?>
```

### Données Complexes
```php
// Contrôleur
$data = [
    'title' => 'Mon Profil',
    'user' => $this->userModel->findWithDetails(1),
    'transactions' => $this->transactionModel->getRecent(1, 10),
    'stats' => [
        'totalSpent' => 150000,
        'totalRegimes' => 5,
    ],
];
return view('pages/profile', $data);

// Vue - Accès aux données
<?= $title ?>
<?= $user['nom'] ?>
<?php foreach ($transactions as $tx): ?>
    <p><?= esc($tx['description']) ?></p>
<?php endforeach; ?>
<?= number_format($stats['totalSpent']) ?>
```

---

## 🎨 Variables de Titre Dynamique

### Option 1: Titre dans les données
```php
// Contrôleur
return view('pages/home', [
    'title' => 'Page d\'Accueil',
]);

// Rendu
<!-- Dans main.php -->
<title><?= isset($title) ? esc($title) . ' - HealthyRegime' : 'HealthyRegime' ?></title>
<!-- Résultat: Page d'Accueil - HealthyRegime -->
```

### Option 2: Titre dans la vue
```php
<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>

<!-- Pas de titre, peut être défini dans le layout -->

<?php $this->endSection(); ?>
```

---

## ⚡ Bonnes Pratiques

### ✅ Faire

**Toujours passer `title` pour cohérence:**
```php
return view('pages/home', [
    'title' => 'Accueil',
    'data' => $data,
]);
```

**Utiliser les partials pour la réutilisation:**
```php
<!-- Dans une vue -->
<?= view('partials/user_card', ['user' => $user]) ?>
<?= view('partials/stat_box', ['stat' => $stat]) ?>
```

**Nommer clairement les sections:**
```php
<?php $this->section('content'); ?>
<!-- Pas confus sur le contenu attendu -->
<?php $this->endSection(); ?>
```

---

### ❌ À Éviter

**Ne pas mélanger layouts:**
```php
// ❌ MAUVAIS
<?php $this->extend('layouts/main'); ?>
<!-- ... puis -->
<?php $this->extend('layouts/admin'); ?> <!-- Conflit! -->

// ✅ BON
<?php $this->extend('layouts/admin'); ?>
<!-- Une seule déclaration extend -->
```

**Ne pas dupliquer le header/footer:**
```php
// ❌ MAUVAIS
<?php $this->extend('layouts/main'); ?>
<?php $this->section('content'); ?>

<header> <!-- Déjà dans main.php! -->
  <!-- ... -->
</header>

<!-- ✅ BON: Le header vient du layout -->
```

**Ne pas oublier endSection():**
```php
// ❌ MAUVAIS
<?php $this->section('content'); ?>
<div>Content</div>
<!-- Manque endSection! -->

// ✅ BON
<?php $this->section('content'); ?>
<div>Content</div>
<?php $this->endSection(); ?>
```

---

## 🔄 Pattern Commun: CRUD Admin

### Contrôleur
```php
public function index()
{
    $items = $this->itemModel->findAll();
    return view('pages/admin/items/index', [
        'title' => 'Gestion des Articles',
        'items' => $items,
    ]);
}

public function create()
{
    return view('pages/admin/items/form', [
        'title' => 'Créer un Article',
        'item' => null,
    ]);
}

public function edit($id)
{
    $item = $this->itemModel->find($id);
    return view('pages/admin/items/form', [
        'title' => 'Modifier: ' . $item['name'],
        'item' => $item,
    ]);
}
```

### Vue List (pages/admin/items/index.php)
```php
<?php $this->extend('layouts/admin'); ?>
<?php $this->section('content'); ?>

<h1><?= esc($title) ?></h1>

<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= esc($item['name']) ?></td>
                <td>
                    <a href="/admin/items/<?= $item['id'] ?>/edit">Modifier</a>
                    <a href="/admin/items/<?= $item['id'] ?>/delete">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php $this->endSection(); ?>
```

### Vue Form (pages/admin/items/form.php)
```php
<?php $this->extend('layouts/admin'); ?>
<?php $this->section('content'); ?>

<h1><?= esc($title) ?></h1>

<form action="<?= $item ? '/admin/items/' . $item['id'] : '/admin/items' ?>" method="post">
    <?= csrf_field() ?>
    
    <div class="form-group">
        <label for="name">Nom</label>
        <input type="text" name="name" id="name" 
               value="<?= esc($item['name'] ?? '') ?>" required>
    </div>
    
    <button type="submit">
        <?= $item ? 'Mettre à jour' : 'Créer' ?>
    </button>
</form>

<?php $this->endSection(); ?>
```

---

## 📊 Résumé

| Contexte | Layout | Header | Footer |
|----------|--------|--------|--------|
| Page utilisateur | `main` | ✅ | ✅ |
| Login/Inscription | `auth` | ❌ | ❌ |
| Admin panel | `admin` | ✅ (admin) | ✅ |

---

## 🚀 Conclusion

Les layouts permettent:
- **Centralisation** du structure HTML
- **Réutilisabilité** des composants
- **Maintenabilité** simplifiée
- **Cohérence** garantie entre pages


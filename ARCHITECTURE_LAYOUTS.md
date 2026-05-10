# 🏗️ Architecture des Vues Restructurées

## Vue d'Ensemble

La nouvelle architecture sépare les préoccupations en trois niveaux :

### 1️⃣ **Layouts** - Structure HTML de base
- Contiennent DOCTYPE, <html>, <head>, <body>
- Définissent les sections avec `renderSection()`
- Gèrent l'intégration des partials

### 2️⃣ **Partials** - Composants réutilisables
- Header, Footer, Navigation
- Styles et logique spécifiques à chaque composant
- Peuvent être inclus dans plusieurs layouts

### 3️⃣ **Pages/Vues** - Contenu spécifique
- `extend()` un layout
- `section()` pour le contenu
- Styles page-spécifiques dans `<style>`

---

## 📁 Arborescence Créée

```
app/Views/
│
├── 📁 layouts/
│   ├── main.php              [Header + Footer]
│   ├── auth.php              [Minimal - Auth pages]
│   └── admin.php             [Admin Header + Footer]
│
├── 📁 partials/
│   ├── header.php            [Logo, User Info, Logout]
│   ├── admin_header.php      [Admin Navigation Header]
│   └── footer.php            [Copyright + Footer]
│
└── 📁 pages/
    ├── home.php              [✅ TRANSFORMÉE - avec extend/section]
    ├── login.php             [✅ TRANSFORMÉE - avec extend/section]
    ├── inscription.php       [✅ TRANSFORMÉE - avec extend/section]
    ├── export_pdf.php
    │
    └── 📁 admin/
        ├── admin.php         [✅ TRANSFORMÉE - avec extend/section]
        ├── gererCodes.php
        ├── statistiques.php
        ├── 📁 regimes/
        ├── 📁 activites/
        └── 📁 parametres/
```

---

## 🔀 Flux de Rendu

### Avant (Structure Ancienne)
```
home.php
  ├── <!DOCTYPE html>
  ├── <head> + <style>
  ├── <body>
  ├── <header> (inline)
  ├── <main> (contenu)
  ├── <footer> (inline)
  └── </html>
```

### Après (Structure Nouvelle)
```
main.php (Layout)
  ├── <!DOCTYPE html>
  ├── <head> + <?= $title ?>
  ├── <body>
  ├── view('partials/header') ← header.php
  ├── <main>
  │   └── section('content')
  │       └── [contenu de home.php]
  ├── view('partials/footer') ← footer.php
  └── </html>
```

---

## 📋 Détail des Fichiers Créés

### `layouts/main.php`
**Utilisé par:** Pages utilisateur authentifiées (home, etc.)

```php
<layout>
  <head>
    <title><?= isset($title) ? esc($title) : 'HealthyRegime' ?></title>
    <link href="/css/style.css">
    <?php if (isset($styles)): ?>
      <style><?= $styles ?></style>
    <?php endif; ?>
  </head>
  <body>
    <?= view('partials/header') ?>
    <main class="container">
      <?= $this->renderSection('content') ?>
    </main>
    <?= view('partials/footer') ?>
  </body>
</layout>
```

### `layouts/auth.php`
**Utilisé par:** Pages d'authentification (login, inscription)

```php
<layout>
  <head>
    <title><?= isset($title) ? esc($title) : 'HealthyRegime' ?></title>
    <link href="/css/style.css">
  </head>
  <body>
    <?= $this->renderSection('content') ?>
  </body>
</layout>
```

### `layouts/admin.php`
**Utilisé par:** Pages d'administration

```php
<layout>
  <head>
    <title><?= isset($title) ? esc($title) : 'Panneau Admin' ?></title>
    <link href="/css/style.css">
  </head>
  <body>
    <?= view('partials/admin_header') ?>
    <main class="container mb-4">
      <?= $this->renderSection('content') ?>
    </main>
    <?= view('partials/footer') ?>
  </body>
</layout>
```

### `partials/header.php`
- Classe `.header-content` avec flex layout
- Logo emoji + nom application
- User info avec ID session
- Bouton déconnexion
- Styles inclus pour `.header`, `.logo`, `.header-right`

### `partials/admin_header.php`
- Gradient background (dark)
- Logo admin + breadcrumb
- Styles inclus pour `.admin-header`, `.admin-logo`

### `partials/footer.php`
- Copyright
- Style au background secondary
- Padding et border-top

---

## 🎨 Styles

### Styles Centralisés (dans les partials)
```
- header.php: .header, .logo, .header-content, .user-info
- admin_header.php: .admin-header, .admin-header-content, .admin-logo
- footer.php: footer, p (dans footer)
```

### Styles Page-Spécifiques (dans les pages)
```
- home.php: .hero, .stats, .section, .wallet-status, etc.
- login.php: .login-container, .login-card, .login-form, etc.
- admin.php: .admin-grid, .admin-card, .card-icon, etc.
```

---

## 🔗 Flux de Contrôleur

### Exemple: Controller Home

```php
public function index()
{
    return view('pages/home', [
        'title' => 'Accueil',
        'user' => $userData,
        'profil' => $profilData,
        // ... autres données
    ]);
}
```

**Exécution:**
1. CodeIgniter charge `pages/home.php`
2. home.php appelle `$this->extend('layouts/main')`
3. Layout main rend:
   - header.php
   - section('content') du home.php
   - footer.php

---

## ✅ Pages Transformées

| Page | Layout | Status |
|------|--------|--------|
| pages/home.php | main | ✅ DONE |
| pages/login.php | auth | ✅ DONE |
| pages/inscription.php | auth | ✅ DONE |
| pages/admin/admin.php | admin | ✅ DONE |

---

## ⏳ Pages en Attente

| Page | Recommandé |
|------|------------|
| pages/admin/gererCodes.php | admin |
| pages/admin/statistiques.php | admin |
| pages/admin/regimes/index.php | admin |
| pages/admin/regimes/form.php | admin |
| pages/admin/activites/index.php | admin |
| pages/admin/activites/form.php | admin |
| pages/admin/parametres/index.php | admin |
| pages/admin/parametres/form.php | admin |
| pages/export_pdf.php | ? |

---

## 💡 Avantages Immédiate

### Maintenance
- ❌ Avant: Modifier header = 10+ fichiers
- ✅ Après: Modifier header.php = 1 fichier

### Performance
- ❌ Avant: Redondance de code HTML
- ✅ Après: Chaque layout rendu une seule fois

### Scalabilité
- ❌ Avant: Difficile d'ajouter nouvelle page
- ✅ Après: Template clair et reproductible

### Cohérence
- ❌ Avant: Risque d'incohérence (oubli de section)
- ✅ Après: Structure garantie par le layout

---

## 🚀 Prochaines Étapes

1. **Transformer les pages restantes** en suivant le modèle
2. **Tester chaque page** transformée
3. **Ajouter des layouts supplémentaires** si besoin (dashboard, etc.)
4. **Considérer des partials additionnels** (sidebar, nav, etc.)


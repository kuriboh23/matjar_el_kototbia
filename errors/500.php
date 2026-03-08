<?php
/**
 * FILE: errors/500.php
 * PURPOSE: Custom 500 Server Error page.
 */
http_response_code(500);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Erreur Serveur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh;">
    <div class="text-center">
        <h1 class="display-1 text-danger">500</h1>
        <p class="lead">Erreur serveur / خطأ في الخادم</p>
        <a href="/" class="btn btn-success btn-lg mt-3">Retour à l'accueil</a>
    </div>
</body>
</html>

<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Nettoyer le chemin du projet
$basePath = '/touche_pas_au_klaxon/public';
$uri = preg_replace('#^' . $basePath . '#', '', $uri);

switch (true) {

    case $uri === '/':
        $homeController->index();
        break;

    case $uri === '/login':
        $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $authController->login()
            : $authController->loginForm();
        break;

    case $uri === '/logout':
        $authController->logout();
        break;

    case $uri === '/trajets':
        $trajetController->index();
        break;

    case $uri === '/trajets/create':
        if (!$authService->isLogged()) { header('Location: /login'); exit; }
        $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $trajetController->store()
            : $trajetController->createForm();
        break;

    case preg_match('#^/trajets/edit/(\d+)$#', $uri, $m):
        if (!$authService->isLogged()) { header('Location: /login'); exit; }
        $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $trajetController->update($m[1])
            : $trajetController->editForm($m[1]);
        break;

    case preg_match('#^/trajets/delete/(\d+)$#', $uri, $m):
        if (!$authService->isLogged()) { header('Location: /login'); exit; }
        $trajetController->delete($m[1]);
        break;

    case $uri === '/admin':
        if (!$authService->isAdmin()) { header('Location: /'); exit; }
        $adminController->dashboard();
        break;

    case $uri === '/admin/users':
        if (!$authService->isAdmin()) { header('Location: /'); exit; }
        $adminController->listUsers();
        break;

    case $uri === '/admin/agences':
        if (!$authService->isAdmin()) { header('Location: /'); exit; }
        $adminController->listAgences();
        break;

    case $uri === '/admin/agences/create':
        if (!$authService->isAdmin()) { header('Location: /'); exit; }
        $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $agenceController->store()
            : $agenceController->create();
        break;

    case preg_match('#^/admin/agences/edit/(\d+)$#', $uri, $m):
        if (!$authService->isAdmin()) { header('Location: /'); exit; }
        $_SERVER['REQUEST_METHOD'] === 'POST'
            ? $agenceController->update($m[1])
            : $agenceController->edit($m[1]);
        break;

    case preg_match('#^/admin/agences/delete/(\d+)$#', $uri, $m):
        if (!$authService->isAdmin()) { header('Location: /'); exit; }
        $agenceController->delete($m[1]);
        break;

    case $uri === '/admin/trajets':
        if (!$authService->isAdmin()) { header('Location: /'); exit; }
        $adminController->listTrajets();
        break;

    case preg_match('#^/admin/trajets/delete/(\d+)$#', $uri, $m):
        if (!$authService->isAdmin()) { header('Location: /'); exit; }
        $trajetController->delete((int)$m[1]);
        break;

    default:
        http_response_code(404);
        echo "Page non trouvée";
}

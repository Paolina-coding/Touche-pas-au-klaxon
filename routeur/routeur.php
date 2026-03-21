<?php

// récupérer l'URL
$uri = $_SERVER['REQUEST_URI'];
//nettoyer (enlever le chemin du projet + public) et découper l'url 
$uri = preg_replace('#^/touche_pas_au_klaxon/public/#', '', $uri);
$uri = trim($uri, '/');
$segments = explode('/', $uri);

// récupérer le controller, la méthode et les paramètres de l'URL 
$controller = $segments[0] ?? 'home';
$method = $segments[1] ?? 'index';
$params = array_slice($segments, 2);


//construire le nom complet du controller et vérifier que le controlleur existe bien 
$controllerClass = "App\\Controllers\\" . ucfirst($controller) . 'Controller';
if (!class_exists($controllerClass)) {
    echo "404 - Controller introuvable";
    exit;
}

//instancier et appeler la méthode après avoir vérifié qu'elle existe
$instance = new $controllerClass();
if (!method_exists($instance, $method)) {
    echo "404 - Méthode introuvable";
    exit;
} else {
    call_user_func_array([$instance, $method], $params);
}
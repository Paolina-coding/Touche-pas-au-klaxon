<?php

//autoloader
require_once __DIR__ . '/../vendor/autoload.php';

//charger le .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

use App\Repositories\UserRepository;
use App\Repositories\TrajetRepository;
use App\Repositories\AgenceRepository;

use App\Services\AuthService;

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\TrajetController;
use App\Controllers\AdminController;
use App\Controllers\AgenceController;

session_start();

// Connexion DB
$db = new PDO(
    "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset={$_ENV['DB_CHARSET']}",
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Dépendances
$userRepo   = new UserRepository($db);
$trajetRepo = new TrajetRepository($db);
$agenceRepo = new AgenceRepository($db);

$authService = new AuthService($userRepo);

// Controllers
$authController   = new AuthController($userRepo, $authService);
$homeController   = new HomeController($trajetRepo, $authService);
$trajetController = new TrajetController($trajetRepo, $agenceRepo, $authService);
$adminController  = new AdminController($userRepo, $agenceRepo, $trajetRepo, $authService);
$agenceController = new AgenceController($agenceRepo, $authService);

// Routeur
require_once __DIR__ . '/../routeur/routeur.php';
<?php
/**
 * Router — maps clean URLs to controllers
 */

require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/TerrainController.php';
require_once __DIR__ . '/../controllers/ReservationController.php';
require_once __DIR__ . '/../controllers/DashboardController.php';
require_once __DIR__ . '/../controllers/MatchController.php';
require_once __DIR__ . '/../controllers/AdminController.php';
require_once __DIR__ . '/../controllers/AvisController.php';
require_once __DIR__ . '/../controllers/PreferenceController.php';

// Get the URL path (strip query string)
$request = strtok($_SERVER['REQUEST_URI'], '?');

// Strip BASE_URL prefix
if (strpos($request, BASE_URL) === 0) {
    $request = substr($request, strlen(BASE_URL));
}
$request = trim($request, '/');

// Route table: 'url' => [Controller, method]
$routes = [
    // Public
    ''                       => [HomeController::class,        'index'],
    'index.php'              => [HomeController::class,        'index'],
    'about'                  => [HomeController::class,        'about'],
    'terrains'               => [TerrainController::class,     'index'],
    'terrains.php'           => [TerrainController::class,     'index'],
    'terrain'                => [TerrainController::class,     'show'],
    'terrain-detail.php'     => [TerrainController::class,     'show'],
    'matches'                => [MatchController::class,       'index'],
    'matches.php'            => [MatchController::class,       'index'],

    // Auth
    'login'                  => [AuthController::class,        'showLogin'],
    'login.php'              => [AuthController::class,        'showLogin'],
    'login/submit'           => [AuthController::class,        'login'],
    'register'               => [AuthController::class,        'register'],
    'logout'                 => [AuthController::class,        'logout'],

    // User area
    'dashboard'              => [DashboardController::class,   'index'],
    'dashboard.php'          => [DashboardController::class,   'index'],
    'profile/update'         => [DashboardController::class,   'updateProfile'],

    // Reservations
    'reservation/create'     => [ReservationController::class, 'create'],
    'reservation/cancel'     => [ReservationController::class, 'cancel'],

    // Matches
    'match/create'           => [MatchController::class,       'create'],
    'match/join'             => [MatchController::class,       'join'],

    // Reviews
    'avis/store'             => [AvisController::class,        'store'],

    // Preferences
    'preferences/set'        => [PreferenceController::class,  'set'],

    // Admin
    'admin'                          => [AdminController::class, 'dashboard'],
    'admin.php'                      => [AdminController::class, 'dashboard'],
    'admin/terrains'                 => [AdminController::class, 'terrains'],
    'admin/terrain/add'              => [AdminController::class, 'addTerrain'],
    'admin/terrain/delete'           => [AdminController::class, 'deleteTerrain'],
    'admin/reservations'             => [AdminController::class, 'reservations'],
    'admin/reservation/validate'     => [AdminController::class, 'validateReservation'],
    'admin/users'                    => [AdminController::class, 'users'],
    'admin/user/toggle'              => [AdminController::class, 'toggleUser'],
];

if (isset($routes[$request])) {
    [$controller, $method] = $routes[$request];
    (new $controller())->$method();
} elseif (preg_match('#^terrain/(\d+)$#', $request, $m)) {
    // /terrain/{id} — clean URL route
    $_GET['id'] = (int)$m[1];
    (new TerrainController())->show();
} else {
    http_response_code(404);
    echo "<!DOCTYPE html><html><head><title>404</title>";
    echo "<style>body{background:#0d0d0d;color:#fff;font-family:sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;text-align:center}";
    echo "h1{font-size:120px;margin:0;color:#4cff72}p{color:#888}</style></head><body>";
    echo "<div><h1>404</h1><p>Page not found: <code>{$request}</code></p>";
    echo "<p><a href='" . url('') . "' style='color:#4cff72'>← Back to home</a></p></div></body></html>";
}

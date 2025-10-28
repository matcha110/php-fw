<?php
declare(strict_types=1);


// ルートからのベースパス調整
$basePath = dirname(__DIR__);


// セッション & エラーレポート
ini_set('display_errors', '1');
error_reporting(E_ALL);
session_start();


// 簡易.envロード
$envFile = $basePath.'/.env';
if (is_file($envFile)) {
foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
if (str_starts_with(trim($line), '#')) continue;
[$k,$v] = array_map('trim', explode('=', $line, 2));
$_ENV[$k] = $v;
putenv("$k=$v");
}
}


// オートロード
require $basePath.'/app/Core/Autoload.php';
App\Core\Autoload::register($basePath.'/app');


use App\Core\Request;
use App\Core\Response;
use App\Core\Router;


$request = Request::capture();
$response = new Response();


$router = new Router($request, $response);
require $basePath.'/config/routes.php';


$router->dispatch();
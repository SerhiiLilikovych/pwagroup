<?php

error_reporting(E_ALL);

require 'vendor/autoload.php';

function unparse_url($parsed_url) {
  $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
  $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
  $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
  $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
  $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
  $pass     = ($user || $pass) ? "$pass@" : '';
  $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
  $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
  $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
  return "$scheme$user$pass$host$port$path$query$fragment";
}

function appendQueryParams($url, $newParams){
  $parsed = parse_url($url);
  parse_str($parsed['query']??'', $query);
  $query = array_merge($query, $newParams);
  $query = http_build_query($query);
  $parsed['query'] = $query;
  return unparse_url($parsed);
}

$router = new AltoRouter();

$router->map('GET', '/', function() {
    require 'config.php';
    if(isset($_GET['pwauid']) && isset($_GET['os_user_id'])){
        $myOfferUrl = appendQueryParams($OFFER_URL, $_GET);
        header('Location: '.$myOfferUrl, true, 302);
        exit;
    }
    include('./templates/index.php');
});

$router->map('GET', '/manifest.json', function() {
    require 'config.php';
    include('./templates/manifest.php');
});

$router->map('GET', '/analytic/[:id]', function($id) {
    require 'config.php';
    $TARGET_URL = appendQueryParams($TARGET_URL, $_GET);
    include('./templates/api_response.php');
});

$router->map('GET', '/destination', function() {
    include('./templates/destination.php');
});

$router->map('POST', '/analytic/[:id]/install', function($id) {
    print("{}");
});

$router->map('POST', '/analytic/[:id]/push', function($id) {
    print("{}");
});

$router->map('POST', '/analytic/[:id]/open', function($id) {
    print("{}");
});

$match = $router->match();

if( is_array($match) && is_callable( $match['target'] ) ) {
  call_user_func_array( $match['target'], $match['params'] );
} else {
  header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}

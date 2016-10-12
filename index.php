<?php

require 'vendor/autoload.php';

use App\Pfw\Main as App;

$app = new App([
	'debug' => true
]);


$app->_error(function() use($app){

	$app->error_page();
});

$app->get('/', function() use($app){

	$app->controller('HomeController@index');
});

$app->submit();
//
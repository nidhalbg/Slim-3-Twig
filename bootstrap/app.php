<?php

session_start();

require __DIR__ .'/../vendor/autoload.php';


$app = new \Slim\App([
	'setting' => [
		'displayErrorDetails' => true,
		'debug' => true,
		'log.enabled' => true
        //'templates.path' => '../templates'
	]
	]);




$container = $app->getContainer();

$container['view'] = function($container){
	$view = new \Slim\Views\Twig(__DIR__  . '/../resources/views', [
		'cache' => false,
	]);

	$view->addExtension(new \Slim\Views\TwigExtension(
		$container->router,
		$container->request->getUri()

	));	

	return $view;
};


$container['HomeController'] = function($container){
	return new \App\Controllers\HomeController($container);
};

$container['ContactController'] = function($container){
	return new \App\Controllers\ContactController($container);
};

$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container['view']->render($response, '404.html', [
            "myMagic" => "Let's roll",
            "base_url" =>  $request->getUri()->withPath('.')
        ]);
    };
};

require __DIR__ .'/../app/routes.php';
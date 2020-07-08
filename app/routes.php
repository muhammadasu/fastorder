<?php
$app->get('/home', function($request, $response){
	return $this->view->render($response,'home.twig');
});

$app->get('/', 'HomeController:index');
$app->get('/get-product', 'HomeController:getProduct');
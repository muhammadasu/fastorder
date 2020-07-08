<?php
$app->get('/', 'HomeController:index');
$app->get('/get-product', 'HomeController:getProduct');
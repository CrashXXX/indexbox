<?php

require('config.php');
require('router/router.php');
require('model/model.php');
require('controller/header.php');
require('controller/blog.php');
require('controller/products.php');

$route = new Router($_GET['url']);
$header = new Header();
$footer = new Footer();
$catalog = new Catalog();




//$a = $model->test();

/*if (isset($_GET['action']) && !empty($_GET['action'])) {
	//$controller->{$_GET['action']}();
}*/

$header->output();

$footer->output();

//echo $view->output();

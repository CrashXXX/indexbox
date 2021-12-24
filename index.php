<?php

require('config.php');
require('router/router.php');
require('model/model.php');
require('controller/header.php');
require('controller/footer.php');
require('controller/blog.php');
require('controller/products.php');

$router = new Router($_GET['url']);

switch ($router->route['path']) {
	case 'products' :
	{
		$data = [];
		$data['title'] = 'Products';
		$data['description'] = 'Description';
		$data['h1'] = 'Products list';
		$body = new Products();
		break;
	}
	case 'blog' :
	{
		$data = $router->route['data'];
		$data['h1'] = $data['product'];
		$body = new Blog($data);
		break;
	}
	default :
	{
		$data = [];
		$data['title'] = 'Products';
		$data['description'] = 'Description';
		$data['h1'] = 'Products list';
		$body = new Products();
	}
}

$footer = '@copyright 2021';

$header = new Header($data['title'], $data['description']);

$footer = new Footer($footer);

$header->output();

$body->output();

$footer->output();

//echo $view->output();

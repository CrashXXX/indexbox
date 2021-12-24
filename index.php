<?php

require('config.php');
require('router/router.php');
require('model/model.php');
require('controller/header.php');
require('controller/footer.php');
require('controller/blog.php');
require('controller/common.php');
require('controller/column-left.php');

$router = new Router($_GET['url']);

if ($router->route) {
	$data = $router->route;
	$data['h1'] = $data['product'];
	$body = new Blog($data);
    $left = false;
} else {
	$data = [];
	$data['title'] = 'Products';
	$data['description'] = 'Description';
	$data['h1'] = 'Common blog list';
	$body = new Common($data['h1']);
    $left = new ColumnLeft();
}

$footerContent = '<a href="/">INDEXBOX</a> @copyright 2021. All right reserved.';
$header = new Header($data['title'], $data['description']);
$footer = new Footer($footerContent);

$header->output();
if ($left) {
    $left->output();
}
$body->output();
$footer->output();

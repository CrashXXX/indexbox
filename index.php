<?php

require('config.php');
require('router/router.php');
require('model/model.php');
require('controller/add-tables.php');
require('controller/header.php');
require('controller/footer.php');
require('controller/blog.php');
require('controller/common.php');
require('controller/column-left.php');

$tablesFromJson = new AddTables(); // Класс для создания и наполнения таблиц из JSON-афйлов
$tablesFromJson->createTablesFromFiles('/../json');

$router = new Router($_GET['url']);

if ($router->route['path'] === 'blog') {
	$data = $router->route['data'];
	$data['h1'] = $data['title'];
	$body = new Blog($data);
	$left = false;
} elseif ($router->route['path'] === 'common') {
	if ($router->route['data']) {
		$data = $router->route['data'];
		$headers['title'] = $router->route['data'][0]['name'];
		$headers['description'] = $router->route['data'][0]['name'];
		$headers['h1'] = $router->route['data'][0]['name'];
		$headers['href'] = $router->route['data'][0]['href'];
	} else {
		$data = false;
		$headers['title'] = 'Main Page';
		$headers['description'] = 'Description';
		$headers['h1'] = 'Common blog list';
		$headers['href'] = false;
	}
	$body = new Common($data , $headers['h1'], $headers['href']);
	$left = new ColumnLeft($headers['href']);
}

$footerContent = '<a href="/">INDEXBOX</a> @copyright 2021. All right reserved.';
$header = new Header($headers['title'], $headers['description']);
$footer = new Footer($footerContent);

$header->output();
if ($left) {
	$left->output();
}
$body->output();
$footer->output();

<?php

require('config.php');
require('router/router.php');
require('model/model.php');
require('controller/tables-actions.php');
require('controller/header.php');
require('controller/footer.php');
require('controller/blog.php');
require('controller/common.php');
require('controller/column-left.php');

$tables = new TablesActions(); // Класс для создания и наполнения таблиц из JSON-афйлов
$tables->createTablesFromFiles('/../json');

$router = new Router($_GET['url']);

if ($router->route['path'] === 'common') {
    $body = new Common($router->route['href']);
    $left = new ColumnLeft($router->route['href']);
} else {
    $body = new Blog($router->route['href']);
    $left = false;
}

$footerContent = '<a href="/">INDEXBOX</a> @copyright 2021. All right reserved.';
$header = new Header($body->data['title'], $body->data['description']);
$footer = new Footer($footerContent);

$header->output();
if ($left) {
    $left->output();
}
$body->output();
$footer->output();

// Пример использоватения методов получения информации о блоге и ее записи в БД в текущих условиях
/*
$blog = new Blog();
$data = $blog->getBlogData('global-paperboard-case-material-market-2020-key-insights');
$data['views'] = (int)$data['views'] + 1000;
$tables->editBlog($data);
*/
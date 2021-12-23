<?php

require('config.php');
require('model/model.php');
require('controller/header.php');
require('controller/footer.php');
require('controller/catalog.php');


if (!isset($_GET['action']) || empty($_GET['action'])) {
	$title = 'Главная страница';
} elseif (isset($_GET['action']) && $_GET['action'] === 'view') {
	$title = 'Статья';
} else {
	$title = 'Главная страница';
}
$copyright = '@copyright 2021';


$header = new Header($title);
$footer = new Footer($copyright);
$catalog = new Catalog();




//$a = $model->test();

/*if (isset($_GET['action']) && !empty($_GET['action'])) {
	//$controller->{$_GET['action']}();
}*/

$header->output();

$footer->output();

//echo $view->output();

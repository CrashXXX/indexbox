<?php

require('../config.php');

if ($_GET['action']) {
	$method = $_GET['action'];
	if ($_POST && function_exists($method)) {
		$method($_POST);
	}
}


// Запрос
function sortPreviews($post)
{
	require('../model/model.php');
	$model = new Model();
	switch ($post['sort']) {
		case 'view-max' :
		{
			$order = 'views';
			$type = 'DESC';
			break;
		}
		case 'view-min' :
		{
			$order = 'views';
			$type = 'ASC';
			break;
		}
		case 'date-max' :
		{
			$order = 'time_create';
			$type = 'DESC';
			break;
		}
		case 'date-min' :
		{
			$order = 'time_create';
			$type = 'ASC';
			break;
		}
		default :
		{
			$order = 'views';
			$type = 'DESC';
		}
	}
	$limit = (int)$post['limit'];

	$results = $model->getAllBlogs($limit, $order, $type);
	if ($results) {
		foreach ($results as $result) {
			$data['reviews'][] = [
				'title' => html_entity_decode($result['title']),
				'description' => html_entity_decode($result['description']),
				'views' => $result['views'],
				'time_create' => date('d.m.Y', $result['time_create']),
				'href' => $result['href']
			];
		}
	}
	$data['h1'] = $post['h1'];
	ob_start();
	include('../view/common.php');
	$result = ob_get_clean();
	echo $result;
}

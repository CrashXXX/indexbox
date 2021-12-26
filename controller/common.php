<?php

class Common
{
	protected $data;
	private Model $model;

	public function __construct($products, $h1, $href)
	{
		$this->model = new Model();
		$this->index($products);
		$this->data['h1'] = $h1;
		$this->data['href'] = $href;
	}


	public function index($products)
	{
		$blogs = $this->getBlogs($products);
		if ($blogs) {
			$data['reviews'] = $blogs;
		} else {
			$data['reviews'] = false;
		}
		$this->data = $data;
	}


	// Получение списка блогов
	public function getBlogs($products)
	{
		if ($products) {
			$results = $this->model->getProductBlogs($products);
		} else {
			$results = $this->model->getAllBlogs();
		}
		if ($results) {
			$blogs = [];
			foreach ($results as $result) {
				$blogs[] = [
					'title' => html_entity_decode($result['title']),
					'description' => html_entity_decode($result['description']),
					'views' => $result['views'],
					'time_create' => date('d.m.Y', $result['time_create']),
					'href' => $result['href']
				];
			}
		} else {
			$blogs = false;
		}
		return $blogs;
	}

	public function output()
	{
		$data = $this->data;
		include('view/common.php');
	}
}
<?php

class Router
{
	private Model $model;
	public $route;


	public function __construct($url)
	{
		$route = [
			'path' => 'products',
			'data' => false
		];
		if (!is_null($url) && $url) {
			$this->model = new Model();
			$url = rtrim($url, '/');
			$url = explode('/', $url);
			$url = array_pop($url);
			$name = $this->model->getPath($url);
			if ($name) {
				$route['path'] = 'blog';
				$route['data'] = $this->model->getBlogData($name);
			}
		}
		$this->route = $route;
	}
}
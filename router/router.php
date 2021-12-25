<?php

class Router
{
	public $route;
	private Model $model;

	public function __construct($url)
	{
		$route = [];
		$route['path'] = 'common';
		if (!is_null($url) && $url) {
			$this->model = new Model();
			$url = rtrim($url, '/');
			$url = explode('/', $url);
			$url = array_pop($url);
			$route['data'] = $this->model->getBlogData($url); // Вернет false, если блога по такому URL нет
			if (!$route['data']) {
				$route['data'] = $this->model->getProductsData($url);
				if ($route['data']) {
					$route['path'] = 'common';
				}
			} else {
				$route['path'] = 'blog';
			}
		}
		$this->route = $route;
	}
}
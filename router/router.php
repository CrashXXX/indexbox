<?php

class Router
{
	private Model $model;
	public $route;


	public function __construct($url)
	{
		$route = false;
		if (!is_null($url) && $url) {
			$this->model = new Model();
			$url = rtrim($url, '/');
			$url = explode('/', $url);
			$url = array_pop($url);
			$route = $this->model->getBlogData($url); // Вернет false, если блога по такому URL нет
		}
		$this->route = $route;
	}
}
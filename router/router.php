<?php

class Router
{
	public $route;
	private Model $model;

	public function __construct($url)
	{
		if (!is_null($url) && $url) {
			$url = rtrim($url, '/');
			$url = explode('/', $url);
			$href = $url[0];
		} else {
			$href = false;
		}
		if (isset($url[1])) {
			if (file_exists('controller/' . $url[0] . '.php')) {
				if (method_exists($url[0], $url[1]) && is_callable(array($url[0], $url[1]))) {
					$controller = new $url[0](false);
					$result = call_user_func(array($controller, $url[1]));
					echo $result;
					exit;
				}
			}
		}
		$this->model = new Model();
		if ($this->model->getRouteBlog($href)) {
			$route['path'] = 'blog';
			$route['href'] = $href;
		} elseif ($this->model->getRouteCommon($href)) {
			$route['path'] = 'common';
			$route['href'] = $href;
		} else {
			$route['path'] = 'common';
			$route['href'] = false;
		}
		$this->route = $route;
	}
}
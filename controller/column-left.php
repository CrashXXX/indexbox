<?php

class ColumnLeft
{
	public $data;
	private Model $model;

	public function __construct($href)
	{
		$this->model = new Model();
		$results = $this->model->getProductsData();
		if ($results) {
			$this->data['products'] = [];
			foreach ($results as $result) {
				$this->data['products'][] = [
					'name' => $result['name'],
					'href' => $result['href']
				];
			}
		} else {
			$this->data['products'] = false;
		}
		$this->data['href'] = $href;
	}


	public function output()
	{
		$data['products'] = $this->data['products'];
		$data['href'] = $this->data['href'];
		include('view/column-left.php');
	}
}
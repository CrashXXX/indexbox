<?php

class ColumnLeft
{
    private Model $model;
    protected $products;
	protected $href;

    public function __construct($href)
    {
        $this->model = new Model();
        $results = $this->model->getProductsData();
        if ($results) {
            $products = [];
            foreach ($results as $result) {
                $products[] = [
                    'name' => $result['name'],
                    'href' => $result['href']
                ];
            }
        } else {
            $products = false;
        }
        $this->products = $products;
		$this->href = $href;
    }


    public function output()
    {
        $data['products'] = $this->products;
        $data['href'] = $this->href;
		include('view/column-left.php');
    }
}
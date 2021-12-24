<?php

class ColumnLeft
{
    private Model $model;
    protected $products;

    public function __construct()
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
    }


    public function output()
    {
        include('view/column-left.php');
    }
}
<?php

class Common
{
	public $data;
	private Model $model;

	public function __construct($href)
	{
		$this->model = new Model();
		if ($_POST && $_POST['sort'] && $_POST['limit']) {
			switch ($_POST['sort']) {
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
			$limit = (int)$_POST['limit'];
			if ($_POST['href']) {
				$href = $_POST['href'];
			} else {
				$href = false;
			}
			$blogReviews = $this->model->getBlogReviews($href, $limit, $order, $type);
		} else {
			$blogReviews = $this->model->getBlogReviews($href);
		}
		$data = [];
		if ($blogReviews) {
			foreach ($blogReviews as $review) {
				$data[] = [
					'title' => html_entity_decode($review['title']),
					'description' => html_entity_decode($review['description']),
					'views' => $review['views'],
					'time_create' => date('d.m.Y', $review['time_create']),
					'href' => $review['href']
				];
			}
		}
		if (isset($blogReviews[0]['product_name'])) {
			$h1 = $blogReviews[0]['product_name'];
			$title = $blogReviews[0]['product_name'];
			$description = $blogReviews[0]['product_name'];
		} else {
			$h1 = 'Common blog list';
			$title = 'Main Page';
			$description = 'Description';
		}
		$this->data['reviews'] = $data;
		$this->data['h1'] = $h1;
		$this->data['title'] = $title;
		$this->data['description'] = $description;
		$this->data['href'] = $href;
	}


	public function sort()
	{
		ob_start();
		$this->output();
		return ob_get_clean();
	}


	public function output()
	{
		$data = $this->data;
		include('view/common.php');
	}
}
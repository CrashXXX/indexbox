<?php

class Products
{
	private Model $model;


	public function __construct()
	{
		$this->model = new Model();
		$this->index();
	}


	public function index()
	{
		$data = [];

		$this->createTablesFromFiles('/../json');

	}


	// Создаем таблицы и наполняем их на основе JSON-файлов
	public function createTablesFromFiles($jsonPath)
	{
		//Смотрим папку /json в корне на предмет наличия файлов для загрузки в БД
		$files = $this->getJsonFiles(realpath(__DIR__ . $jsonPath));
		if ($files) {
			$createdTables = 0;
			foreach ($files as $file) {
				$fileName = pathinfo($file, PATHINFO_FILENAME);
				if ($this->model->checkTable($fileName)) {
					continue;
				}
				$json = $this->getJsonDataFromFile($file);
				if ($json) {
					$columns = $json['columns'];
					$rows = $json['data'];
					$rows = $this->arrayUnique($rows, ['product', 'name']);
					if ($columns && $rows) {
						$this->model->addTable($fileName, $columns, $rows);
						$createdTables += 1;
					}
				}
			}
			// Так как машина не сможет точно узнать, какие колонки надо индексировать и связывать внешним ключом, подставим их вручную, в будущем их можно передавать из админки
			// Связываем таблицы только если их минимум 2
			if ($createdTables > 1) {
				$this->model->addTableIndex('blog', 'product_idx', 'product', true);
				$this->model->addTableIndex('blog', 'time_create_idx', 'time_create');
				$this->model->addTableIndex('blog', 'views_idx', 'views');
				$this->model->addTableIndex('products', 'name_idx', 'name');
				$this->model->delNotEqualRows('products', 'blog', 'name', 'product');
				$this->model->addForeignKey('blog', 'product', 'products', 'name');
			}
		}
	}


	// Получение массива данных из JSON-файла
	public function getJsonFiles($path)
	{
		$files = glob($path . '/*.json');
		if ($files) {
			return $files;
		}
		return false;
	}


	// Получение списка JSON-файла из указанной категории
	public function getJsonDataFromFile($file)
	{
		return json_decode(file_get_contents($file), true);
	}


	// Поиск и удаление дублей в массиве, полученном из JSON-файла, перед отправкой в БД. Дубли допускать нельзя
	// Функция универсальная, может искать дубли по разным ключам ассоциативного массива
	function arrayUnique($array, $keys): array
	{
		$temp_array = array();
		$i = 0;
		$key_array = array();

		foreach ($keys as $key) {
			foreach ($array as $val) {
				if (is_null($val[$key])) {
					break;
				}
				if (!in_array($val[$key], $key_array)) {
					$key_array[$i] = $val[$key];
					$temp_array[$i] = $val;
				}
				$i++;
			}
		}
		return $temp_array;
	}
}
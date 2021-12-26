<?php

class TablesActions
{
	private Model $model;

	public function __construct()
	{
		$this->model = new Model();
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
				// Если таблица уже есть, то не создаем ее и не заполняем
				if ($this->model->checkTable($fileName)) {
					continue;
				}
				$json = $this->getJsonDataFromFile($file);
				if ($json) {
					$columns = $json['columns'];
					$rows = $json['data'];
					$rows = $this->arrayUnique($rows, ['name']); // name - столбец в главной таблице для связки внешним ключом, значения должны быть уникальными
					if ($columns && $rows) {
						$this->model->addTable($fileName, $columns, $rows);
						$createdTables += 1;
					}
				}
			}
			// Так как машина не сможет точно узнать, какие колонки надо индексировать и связывать внешним ключом, подставим их значения вручную, в будущем их можно передавать из админки
			// Связываем таблицы только если их минимум 2
			if ($createdTables > 1) {
				$this->model->addTableIndex('blog', 'product_idx', 'product');
				$this->model->addTableIndex('blog', 'time_create_idx', 'time_create');
				$this->model->addTableIndex('blog', 'views_idx', 'views');
				$this->model->addTableIndex('products', 'name_idx', 'name', true);
				$this->model->addForeignKey('blog', 'product', 'products', 'name');
			}
		}
	}


	// Получение списка JSON-файла из указанной категории
	public function getJsonFiles($path)
	{
		$files = glob($path . '/*.json');
		if ($files) {
			return $files;
		}
		return false;
	}


	// Получение массива данных из JSON-файла
	public function getJsonDataFromFile($file)
	{
		return json_decode(file_get_contents($file), true);
	}


	// Поиск и удаление дублей в массиве, полученном из JSON-файла, перед отправкой в БД. Дубли допускать нельзя
	// Функция универсальная, может искать дубли по указанным ключам ассоциативного массива
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
		if (!$temp_array) {
			$temp_array = $array;
		}
		return $temp_array;
	}


	// Функция обновления данных о статье. Она нигде не используется, но создана на будущее
	// Бедум считать, что все данные уже получены из контроллера, изменены в админке и отправлены из формы на сайте
	public function editBlog($data)
	{
		if ($data && is_array($data)) {
			$this->model->editBlog($data); // можно добавить в начале return, чтобы реагировать на результат в админке
		}
	}
}
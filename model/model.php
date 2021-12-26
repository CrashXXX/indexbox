<?php

class Model
{
	public $connection;


	// При обращении к классу создадим подключение к БД
	public function __construct()
	{
		$this->connection = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
		if ($this->connection->connect_error) {
			echo($this->connection->error . ' ' . $this->connection->errno);
			exit;
		} else {
			$this->connection->set_charset("utf8");
		}
	}


	// Запрос к БД. В зависимости от типа запроса функция выдает нужные данные (массив или true/false)
	public function query($sql)
	{
		$query = $this->connection->query($sql);

		if (!$this->connection->errno) {
			if ($query instanceof mysqli_result) {
				$data = array();

				while ($row = $query->fetch_assoc()) {
					$data[] = $row;
				}

				$result = new stdClass();
				$result->num_rows = $query->num_rows;
				$result->row = isset($data[0]) ? $data[0] : array();
				$result->rows = $data;

				$query->close();

				return $result;
			} else {
				return true;
			}
		} else {
			echo($this->connection->error . ' ' . $this->connection->errno);
			exit;
		}
	}


	// Проверка существования таблицы в БД. Если она уже есть, то перезаписывать ее из файла не надо и возвращаем true
	public function checkTable($name): bool
	{
		$sql = "SHOW TABLES FROM " . DATABASE . " LIKE '" . $name . "'";
		$result = $this->query($sql);
		if ($result->num_rows) {
			return true;
		} else {
			return false;
		}
	}


	// Добавление таблицы и ее заполнение. $tableName - имя таблицы, $columns - создаваемые колонки, $rows - строки для их записи в БД
	public function addTable($tableName, $columns, $rows)
	{
		$sql = "CREATE TABLE " . $tableName . " ( ";
		foreach ($columns as $name => $value) {
			$sql .= $name . ' ' . $value . ', ';
		}
		$sql = substr($sql, 0, -2) . ')';
		$sql .= " ENGINE=InnoDB CHARACTER SET utf8";
		$this->query($sql);

		foreach ($rows as $value) {
			$sql = "INSERT INTO " . $tableName . " (";
			foreach ($columns as $name => $val) {
				$sql .= $name . ', ';
			}
			$sql = substr($sql, 0, -2) . ") VALUES (";
			foreach ($value as $row) {
				$sql .= '"' . htmlentities($row) . '", ';
			}
			$sql = substr($sql, 0, -2) . ')';
			$this->query($sql);
		}
	}


	// Добавление индекса в таблицу. $table - имя таблицы, $index - название индекса, $column - колонка, для которой будет создан индекс
	// $unique - определяет, нужен ли уникальный индекс (для связки внешним ключом в будущем), по умолчанию false, чтобы не перегружать обращение из контроллера
	public function addTableIndex($table, $index, $column, $unique = false)
	{
		$sql = "ALTER TABLE " . $table . " ADD " . ($unique ? "UNIQUE " : "") . "INDEX " . $index . " (" . $column . ")";
		return $this->query($sql);
	}


	// Добавление внешнего ключа в таблицу. $table1 - название подчиненной таблицы, $col1 - название колонки подчиненной таблицы
	// $table2 - название главной таблицы, $col2 - название колонки главной таблицы
	public function addForeignKey($table1, $col1, $table2, $col2)
	{
		$sql = "ALTER TABLE " . $table1 . " ADD FOREIGN KEY (" . $col1 . ") REFERENCES " . $table2 . "(" . $col2 . ") ON DELETE SET NULL";
		$this->query($sql);
	}


	// Получение всех данных о статье блога из БД по URL
	public function getBlogData($href)
	{
		$sql = "SELECT * FROM blog WHERE href = '" . $href . "'";
		$result = $this->query($sql);
		if ($result->num_rows == 0) {
			return false;
		} else {
			return $result->row;
		}
	}


	// Получение всех данных о блогах для превью на главной странице и для AJAX
	public function getAllBlogs($limit = 10, $order = 'time_create', $type = 'DESC', $href = false)
	{
		$sql = "SELECT *, blog.href AS href FROM blog";
		if ($href) {
			$sql .= " JOIN products ON (products.name = blog.product AND products.href = '" . $href . "')";
		}
		$sql .= " ORDER BY $order $type LIMIT $limit";
		$result = $this->query($sql);
		if ($result->num_rows == 0) {
			return false;
		} else {
			return $result->rows;
		}
	}


	// Получение всех данных о блогах по названию продукта для главной страницы
	public function getProductBlogs($products)
	{
		$data = [];
		foreach ($products as $product) {
			$sql = "SELECT * FROM blog WHERE product = '" . $product['name'] . "'";
			$result = $this->query($sql);
			if ($result->num_rows > 0) {
				$data = $result->rows;
			}
		}
		return $data;
	}


	// Обновление кол-ва просмотров блога
	public function updateBlogViews($href)
	{
		$sql = "UPDATE blog SET views = views + 1 WHERE href = '" . $href . "'";
		$this->query($sql);
	}


	// Получение всех данных о продуктах/продукте
	public function getProductsData($href = false)
	{
		$sql = "SELECT * FROM products";
		if ($href) {
			$sql .= " WHERE href = '" . $href . "'";
		}
		$sql .= " ORDER BY name";
		$result = $this->query($sql);
		if ($result->num_rows == 0) {
			return false;
		} else {
			return $result->rows;
		}
	}


	// Запись изменений блога в БД, будем делать отбор по URL, так как для блогов он уникальный в задании
	public function editBlog($data)
	{
		$sql = 'UPDATE blog SET';
		$sql .= ' href = "' . $data['href'] . '",';
		$sql .= ' title = "' . htmlentities($data['title']) . '",';
		$sql .= ' body = "' . htmlentities($data['body']) . '",';
		$sql .= ' description = "' . htmlentities($data['description']) . '",';
		$sql .= ' product = "' . htmlentities($data['product']) . '",';
		$sql .= ' views = ' . $data['views'] . ',';
		$sql .= ' time_create = ' . $data['time_create'];
		$sql .= ' WHERE href = "' . $data['href'] . '"';
		$this->query($sql); // можно добавить в начале return, чтобы реагировать на результат в админке
	}
}
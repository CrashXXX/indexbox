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


	// Проверка существования таблицы в БД. Если она уже есть, то перезаписывать ее из файла не надо
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


	// Добавление таблицы и ее заполнение. $tableName - имя таблицы, $columns - создаваемые колонки, $rows - строки для их записи в БД
	public function addTable($tableName, $columns, $rows)
	{
		$sql = "CREATE TABLE " . $tableName . " ( ";
		foreach ($columns as $name => $value) {
			$sql .= $name . ' ' . $value . ', ';
		}
		$sql = substr($sql, 0, -2) . ')';
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
	// $unique - определяет, нужен ли уникальный индекс (для связки внешним ключом в будущем), по умолчанию false, чтобы не перегружать обращение к функции
	public function addTableIndex($table, $index, $column, $unique = false)
	{
		$sql = "ALTER TABLE " . $table . " ADD " . ($unique ? "UNIQUE " : "") . "INDEX " . $index . " (" . $column . ")";
		return $this->query($sql);
	}


	// Поиск и удаление отсутствующих записей в 2-х связанных таблицах. Их быть не должно, иначе не получится их связать внешним ключом
	// $table1, $table2 - имена таблиц, $col1, $col2 - названия колонок, по которым будет вестись сравнение значений записей
	public function delNotEqualRows($table1, $table2, $col1, $col2)
	{
		$sql = "SELECT {$col1} FROM {$table1} WHERE {$col1} NOT IN (SELECT {$col2} FROM {$table2})";
		$rows = $this->query($sql);
		if ($rows) {
			foreach ($rows->row as $unused) {
				$sql = "DELETE FROM {$table1} WHERE {$col1} = '" . $unused . "'";
				$this->query($sql);
			}
		}

		$sql = "SELECT {$col2} FROM {$table2} WHERE {$col2} NOT IN (SELECT {$col1} FROM {$table1})";
		$rows = $this->query($sql);
		if ($rows) {
			foreach ($rows->row as $unused) {
				$sql = "DELETE FROM {$table2} WHERE {$col2} = '" . $unused . "'";
				$this->query($sql);
			}
		}
	}


	// Добавление внешнего ключа в таблицу. $table1 - название подчиненной таблицы, $col1 - название колонки подчиненной таблицы
	// $table1 - название главной таблицы, $col1 - название колонки главной таблицы
	public function addForeignKey($table1, $col1, $table2, $col2)
	{
		$sql = "ALTER TABLE " . $table1 . " ADD FOREIGN KEY (" . $col1 . ") REFERENCES " . $table2 . "(" . $col2 . ") ON DELETE SET NULL";
		$this->query($sql);
	}


	// Проверка существования URL в БД по двум таблицами, так как везде есть поле href, в случае успеха возвращает название продукта
	public function getPath($href)
	{
		$sql = "SELECT name FROM products WHERE href = '" . $href . "'";
		$result = $this->query($sql);
		if ($result->num_rows < 1) {
			$sql = "SELECT product FROM blog WHERE href = '" . $href . "'";
			$result = $this->query($sql);
			if ($result->num_rows < 1) {
				return false;
			}
			$name = $result->row['product'];
		} else {
			$name = $result->row['name'];
		}
		return $name;
	}


	// Получение всех данных о статье блога из БД по названию
	public function getBlogData($name)
	{
		$sql = "SELECT * FROM blog WHERE product = '" . $name . "'";
		$result = $this->query($sql);
		if ($result->num_rows < 1) {
			return false;
		} else {
			return $result->rows;
		}
	}


	// Получение всех данных о продуктах
	public function getProductsData()
	{
		$sql = "SELECT * FROM products";
		$result = $this->query($sql);
		if ($result->num_rows < 1) {
			return false;
		} else {
			return $result->rows;
		}
	}
}
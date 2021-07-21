 <?php
class database
{
	private function connect($user)
	{
		switch ($user)
		{
			case "writer":
				require("db_config_writer.php");
				break;
			
			case "reader":
				require("db_config_reader.php");
				break;
				
			case "root":
				require("db_config.php");
				break;
			default:
				return false;
		}
        return new PDO("mysql:host=$db_host;dbname=$db_database", $db_user, $db_pass);

    }
	
	public function create_table($name, $columns, $columns_amount)
	{	
		try {
			$fields_string = "`ID` INT NOT NULL AUTO_INCREMENT";
		
			for($i = 0; $i <= $columns_amount; $i++)
			{
				$col_name = $columns[$i]['name'];
				$fields_string .= ", `$col_name` TEXT(50)";
			}
			
			$dbcon = $this->connect("writer");
			//запрос на создание таблицы
			$sql = "CREATE TABLE `$name` ($fields_string, PRIMARY KEY (`ID`)) ";
			$dbcon->exec($sql);
			echo "Таблица $name готова к использованию.";
		} catch(PDOException $e) {
			echo 'Ошибка: ' . $e->getMessage();
		}
	}
	
	public function write($operation, $table, $fields, $params)
	{
		try {
		    $fields_string = "";
            $values_string = "";

            var_dump($fields);

            var_dump($params);

            foreach ($fields as $val)
            {
                $fields_string .= "`".$val."`, ";
				$values_string .= ":".mb_substr($val, 3).", ";
            }

            $fields_string = mb_substr($fields_string, 0, -2);
            $values_string = mb_substr($values_string, 0, -2);


            var_dump($fields_string);

            var_dump($values_string);

			//соединение с БД
			$dbcon = $this->connect("writer");
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$data = $dbcon->prepare("$operation INTO `$table` ($fields_string) VALUES($values_string)");

			for($i = 0; $i < count($params); $i++)
			{
				foreach($params[$i] as $column => $value)
				{
					echo $column."-".$value."<br>";
					$data->bindValue(":".mb_substr($column, 3), $value);
				}
				$data->execute();
			}
		} catch(PDOException $e) {
			echo 'Ошибка: ' . $e->getMessage();
		}
	}

    public function update($table, $column, $value, $id){
        $dbcon = $this->connect("writer");
        $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = $dbcon->prepare("UPDATE `$table` SET `$column`=:column WHERE `id` =:id");
        $data->bindValue(":column", $value);
        $data->bindValue(":id", $id);

        $data->execute();
    }

    public function delete($id, $table)
    {
        $dbcon = $this->connect("writer");
        $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = $dbcon->prepare("DELETE FROM `$table` WHERE `id` =:id");
        $data->bindValue(":id", $id);

        $data->execute();
    }

	public function read($table, $fields=[], $values=[]) 
	{
	    $return = [];
	    $index = 0;
	    if(empty($values) && empty($fields))
        {
            $dbcon = $this->connect("reader");
            //запрос на создание таблицы
            $sql = "SELECT * FROM $table";
            $data = $dbcon->query($sql);
            while($rows = $data->fetch( PDO::FETCH_ASSOC )) {
                $return[$index] = $rows;
                $index++;
            }
            return $return;
        }
	    else return false;
	}
	
	public function show_tables()
    {
        $dbcon = $this->connect("reader");
        //запрос на создание таблицы
        $sql = "SHOW TABLES";
        return $dbcon->query($sql);
    }

    public function get_titles($table)
    {
        $dbcon = $this->connect("reader");
        //запрос на создание таблицы
        $sql = "DESCRIBE $table";
        return $dbcon->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }

    public function rows_amount($table)
    {
        $dbcon = $this->connect("reader");
        //запрос на создание таблицы
        $sql = "DESCRIBE $table";
        return $dbcon->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }
}
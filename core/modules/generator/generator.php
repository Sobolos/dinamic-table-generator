<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require('../database/database.php');

class db_generator
{
	private $title;
	private $columns_amount;
	private $structure;
	
	public function __construct()
	{
		$this->generate_title();
		$this->generate_columns();
		$this->generate_values();
		$this->compile();
	}
	
	private function words_generator($min, $max)
	{
		$code= array_merge(range('a', 'z'));
		$string = '';
		for ($i = 0; $i < rand($min, $max); $i++)
			$string .= $code[array_rand($code)];
		return $string;
	}
	
	private function generate_title()
	{
		$this->title = $this->words_generator(2,9);
	}
	
	private function generate_columns()
	{
		$this->columns_amount = rand(1,9);
		$this->structure["columns"][0] = "1. Color";


		for($k = 1; $k <= $this->columns_amount; $k++)
		{
			$name = $this->words_generator(2,9);
			$this->structure["columns"][$k] = ($k+1).". $name";
		}
    }
	
	private function generate_values()
	{
		for ($s = 0; $s <=  $this->columns_amount; $s++)
		{
			$this->structure['fields'][$s][0]["value"] = "#FFFFFF";
			
			for($i = 1; $i <= $this->columns_amount; $i++)
			{
				$value = $this->words_generator(1,50);
				$this->structure['fields'][$s][$i]["value"] = $value;
			}
		}
	}
	
	private function compile()
	{
		$this->create_table();
		$this->fill_table();
	}
	
	private function create_table()
	{
		$columns = [];
		for($i = 0; $i <= $this->columns_amount; $i++)
		{
			$columns[$i]['name'] = $this->structure["columns"][$i];
		}

		$database = new database();
		$database->create_table($this->title, $columns, $this->columns_amount);
	}
	
	private function fill_table()
	{		
		$params = array();
		
		foreach($this->structure['fields'] as $field => $value)
		{
			for($i = 0; $i < count($value); $i++)
			{
				$params[$field][$this->structure["columns"][$i]] = $value[$i]['value'];
			}
		}
		
        $database = new database();
		$database->write("INSERT", $this->title, $this->structure["columns"], $params);
	}

	public function addRow($rows, $rowsAmount)
    {

    }
}

$table1 = new db_generator();
$table2 = new db_generator();
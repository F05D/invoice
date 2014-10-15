<?php

class Db {
	public $oDb;
	
	// Create a database connection for use by all functions in this class
	public function __construct()
{
		
		//require_once ( dirname(__FILE__) . "/db.config.php");
		
		$host = '127.0.0.1';
		$user = 'root';
		$pass = '';
		$db_name = 'invoice';
		$port = "";
		$socket = "";
		
		$this->oDb = mysqli_connect ( $host, $user, $pass, $db_name );
		
		if ( !$this->oDb ) {
			exit ( 'Unable to connect to DB' );
		}
		
		// Set every possible option to utf-8
		mysqli_query ( $this->oDb, 'SET NAMES "utf8"' );
		mysqli_query ( $this->oDb, 'SET CHARACTER SET "utf8"' );
		mysqli_query ( $this->oDb, 'SET character_set_results = "utf8",' . 
		'character_set_client = "utf8", character_set_connection = "utf8",' . 
		'character_set_database = "utf8", character_set_server = "utf8"' );
	}
	
	// Create a standard data format for insertion of PHP dates into MySQL
	protected function date($php_date) {
		return date( 'Y-m-d H:i:s', strtotime ( $php_date ) );
	}
	
	// All text added to the DB should be cleaned with mysqli_real_escape_string
	// to block attempted SQL insertion exploits
	protected function escape($str) {
		return mysqli_real_escape_string ( $this->oDb, $str );
	}
	
	// Test to see if a specific field value is already in the DB
	// Return false if no, true if yes
	protected function in_table($table, $where) {
		$query = 'SELECT * FROM ' . $table . ' WHERE ' . $where;
		$result = mysqli_query ( $this->oDb, $query );
		return mysqli_num_rows ( $result ) > 0;
	}
	
	// Perform a generic select and return a pointer to the result
	protected function select($query) {
		$result = mysqli_query ( $this->oDb, $query );
		return $result;		
	}

	// Add a row to any table
	protected function insertValues($table, $values) {
		$query = 'INSERT INTO ' . $table . ' VALUES (' . $values . ')';
	
		if (!mysqli_query ( $this->oDb, $query ) ) {
			$error_exception = "ERROR:" . mysqli_error($this->oDb);			
			return false;
		}
	
		return true;
	}
	
	// Add a row to any table
	protected function insert($table, $field_values) {
		$query = 'INSERT INTO ' . $table . ' SET ' . $field_values;

		if (!mysqli_query ( $this->oDb, $query ) ) {
			$error_exception = "ERROR:" . mysqli_error($this->oDb);			
			return false;
		}
		
		return true;
	}
	
	// Update any row that matches a WHERE clause
	protected function update($table, $field_values, $where) {
		$query = 'UPDATE ' . $table . ' SET ' . $field_values . ' WHERE ' . $where;
		
		if (!mysqli_query ( $this->oDb, $query ) ) {
			$error_exception = "ERROR:" . mysqli_error($this->oDb);			
			return false;
		}
		
		return true;
	}
	
	// Get last id from table
	protected function lastId($table,$field) {
		$query = "SELECT $field FROM $table ORDER BY $field DESC LIMIT 1;";
	
		if (!mysqli_query ( $this->oDb, $query ) ) {
			$error_exception = "ERROR:" . mysqli_error($this->oDb);			
			return false;
		}
	
		return true;
	}
	
	// Update any row that matches a WHERE clause
	protected function delete($table, $where) {
		$query = 'DELETE FROM ' . $table . ' WHERE ' . $where;
	
		if (!mysqli_query ( $this->oDb, $query ) ) {
			$error_exception = "ERROR:" . mysqli_error($this->oDb);			
			return false;
		}
	
		return true;
	}
	
}


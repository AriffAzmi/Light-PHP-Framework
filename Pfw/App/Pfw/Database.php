<?php
	
	#---------------------------------------------------
	# PFW Framework
	# Programmer : Ariff Azmi
	# Email      : ariff.azmi16@gmail.com
	# Blog    	 : ariffazmi.me
	# @package		PFW (PHP Framework Wizard)
	# @copyright		Copyright (C) 2016 All rights reserved.
	#---------------------------------------------------
	// use \PDO;
	namespace App\Pfw;
	include_once __DIR__.'/Server.php';

	use \PDO;

	class Database
	{

		public function select($column=null,$tablename=null,$conditions=null,$bind=null)
		{

			$q = DB::db_connect()->prepare("SELECT $column FROM $tablename ".($conditions != null ? "WHERE $conditions":""));

			$q->execute($bind);
			$handle = $q->fetchAll(PDO::FETCH_OBJ);
			$count_handle = $q->rowCount();

			return array('data_count'=>$count_handle,'data'=>$handle);
		}

		public function delete($tablename=null,$conditions=null,$bind=null)
		{
			$q = DB::db_connect()->prepare("DELETE FROM $tablename WHERE $conditions");
			$q->execute($bind);
			return $q;
		}

		public function update($tablename=null,$statement=null,$conditions=null,$bind=null)
		{
			$q = DB::db_connect()->prepare("UPDATE $tablename SET $statement WHERE $conditions");
			$q->execute($bind);
			return $q;
		}

		public function insert($tablename=null,$statement=null,$bind=null)
		{
			$q = DB::db_connect()->prepare("INSERT INTO $tablename $statement");
			$q->execute($bind);
			return $q;
		}

		public function rows($result_query=null)
		{
			
			$rows = $result_query->rowCount();
			return $rows;
		}

		public function all($tablename=null)
		{

			
			$q = DB::db_connect()->prepare("SELECT * FROM '".$tablename."'");
			$q->execute();
			$count = $q->rowCount();
			$dataHandle=$q->fetchAll(PDO::FETCH_OBJ);
			
			return $dataHandle;
		}


		public function save()
		{
			global $table;

			return $table;
			# code...
		}

		public function db_connect()
		{

			try {
			    $db = new PDO('mysql:host='.SERVERLOCATION.';dbname='.SERVERDATABASE.'', SERVERUSERNAME, SERVERPASSWORD);
				$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		    } catch (PDOException $e) {
			    print_r($e->getMessage());
		    }
			
			return $db;
		}
	}
?>

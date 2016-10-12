<?php 
	namespace App\Pfw;
	session_start();
	/**
	* 
	*/
	// include_once __DIR__.'/Database.php';

	use ArrayAccess;

	use \App\Pfw\Database as DB;
	
	class Model
	{	

		/**
		*
		* @return function to save data
		*/
		public function save()
		{
			$data = get_object_vars($this);

			$value1 = implode(",", array_values(array_slice($data,1)));
			
			/**
			*
			* @return return affected column
			* @var string/integer
			*/
			$keyTable = implode(",", array_keys(array_slice($data,1)));
			$keyBind = implode(",:", array_keys(array_slice($data,1)));
			$keyBind = ":".$keyBind;


			$bindField = "VALUES (".$keyBind.")";
			$tableField = "(".$keyTable.")";


			/**
			*
			* @return array for bind paramater
			*/
			$groups_array = array_map('trim',explode(',', $keyBind));
			$functions_array = array_map('trim',explode(',', $value1));

			$final = array();
			for ($i = 0; $i <= count($groups_array); $i++) {
			    $final[$groups_array[$i]] = $functions_array[$i];
			}

			$bindParam = array_combine($groups_array, $functions_array);


			/**
			*
			* Statement to insert or update
			*/
			$statementInsertOrUpdate="";
			foreach ($data as $key => $value) {
				
				if ($key!='table') {
					$statementInsertOrUpdate .= "$key=:$key,";
				}
			}

			$statementInsertOrUpdate = rtrim($statementInsertOrUpdate, ",");
			
			$statement = "
			INSERT INTO $this->table $tableField $bindField
  			ON DUPLICATE KEY UPDATE $statementInsertOrUpdate";

  			/**
			*
			* @return true/false
			*/
			return Model::exec($statement,$bindParam);

		}

		/**
		*
		* @return execute function for the query
		*/
		static function exec($statement=null,$bindParam=null){

			$prepare = DB::db_connect()->prepare($statement);

			return $prepare->execute($bindParam);
		}

		/**
		*
		* @return function to destroy data
		*/
		public function destroy()
		{
			$data = get_object_vars($this);

			/**
			*
			* @return affected column
			* @var string/integer
			*/
			$column = array_keys(array_slice($data,1));
			$column = $column[0];

			/**
			*
			* @return bind column name
			*/
			$columnBind = ":$column";

			/**
			*
			* @return data belongs to the column
			*/
			$columnData = array_values(array_slice($data,1));
			$columnData = $columnData[0];

			/**
			*
			* Statement to delete
			* @var string
			*/
			$statement = "DELETE FROM $this->table WHERE ".$column."=".$columnBind."";

			/**
			*
			* @return array bind parameter
			*/
			$arrayBind = [
				$columnBind => $columnData
			];

			/**
			*
			* @return true/false
			*/
			return Model::exec($statement,$arrayBind);
			
		}


		public function connection()
		{
			return DB::db_connect();
		}

	}
?>
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
		
		public function __call($name, $arguments)
	    {	
	    	if (preg_match("/FindBy/", $name)) {
			    
			    $obj = explode('FindBy', $name);

			   	if (preg_match("/And/", $obj[1])) {
			   		
			   		$obj = explode("And", $obj[1]);

			   		$objCondition = implode("=:".implode(" AND ", array_values($obj))."=:", array_values($obj));
					
					$keyBind = implode(",:", array_values($obj));
					$keyBind = ":".$keyBind;
					$value1 = implode(",", $arguments);

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
					$limit = "";
			   	}
			   	elseif (preg_match("/Or/", $obj[1])) {
			   		
			   		$obj = explode("Or", $obj[1]);

			   		$objCondition = implode("=:".implode(" OR ", array_values($obj))."=:", array_values($obj));
					
					$keyBind = implode(",:", array_values($obj));
					$keyBind = ":".$keyBind;
					$value1 = implode(",", $arguments);

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
					$limit = "";
			   	}
			   	elseif (preg_match("/Between/", $obj[1])) {
			   		
			   		$obj = explode("Between", $obj[1]);

			   		$arguments = (count($arguments) <= 1 ? die('Between statement only accept 2 parameter to be check') : $arguments);
			   		
			   		$objCondition = $obj[0]." BETWEEN :".implode(" AND :", $arguments);

					/**
					*
					* @return array for bind paramater
					*/
					$key = ":".implode(" AND :", $arguments);
					$groups_array = array_map('trim',explode("AND", $key));
					$functions_array = array_map('trim',$arguments);

					$final = array();
					for ($i = 0; $i <= count($groups_array); $i++) {
					    $final[$groups_array[$i]] = $functions_array[$i];
					}

					$bindParam = array_combine($groups_array, $functions_array);
					$limit = "";
			   	}
			   	elseif (preg_match("/LessThan/", $obj[1])) {
			   	
			   		$obj = explode("LessThan", $obj[1]);
			   		$arguments = (count($arguments) > 1 ? die('LessThan statement only accept 1 parameter to be check') : $arguments[0]);

			   		$objCondition = $obj[0]." < :".$obj[0];
			   	
					$bindParam = [
						':'.$obj[0].'' => $arguments
					];
					$limit = "";
			   	}
			   	elseif (preg_match("/GreaterThan/", $obj[1])) {
			   	
			   		$obj = explode("GreaterThan", $obj[1]);
			   		$arguments = (count($arguments) > 1 ? die('GreaterThan statement only accept 1 parameter to be check') : $arguments[0]);

			   		$objCondition = $obj[0]." > :".$obj[0];
			   	
					$bindParam = [
						':'.$obj[0].'' => $arguments
					];
					$limit = "";
			   	}
			   	elseif (preg_match("/IsNull/", $obj[1])) {
			   	
			   		$obj = explode("IsNull", $obj[1]);

			   		$objCondition = $obj[0]." =:".$obj[0];
			   	
					$bindParam = [
						':'.$obj[0].'' => null
					];
					$limit = "LIMIT 1";
			   	}
			   	elseif (preg_match("/Like/", $obj[1])) {
			   	
			   		$obj = explode("Like", $obj[1]);
			   		$arguments = (count($arguments) > 1 ? die('Like statement only accept 1 parameter to be check') : $arguments[0]);

			   		$objCondition = $obj[0]." LIKE :".$obj[0];
			   	
					$bindParam = [
						':'.$obj[0].'' => $arguments
					];
					$limit = "LIMIT 1";
			   	}
			   	elseif (preg_match("/Containing/", $obj[1])) {
			   	
			   		$obj = explode("Containing", $obj[1]);
			   		$arguments = (count($arguments) > 1 ? die('Containing statement only accept 1 parameter to be check') : $arguments[0]);

			   		$objCondition = $obj[0]." LIKE :".$obj[0];
			   	
					$bindParam = [
						':'.$obj[0].'' => '%'.$arguments.'%'
					];
					$limit = "";
			   	}
			   	else{

				    $objCondition = $obj[1]."=:".$obj[1];
				    $bindParam = [
				    	':'.$obj[1].'' => $arguments[0]
				    ];

				    $limit = "LIMIT 1";
			   	}

			   	// $prepareString = "SELECT * FROM $this->table WHERE $objCondition $limit";
			    // return [$prepareString,$objCondition,$bindParam];

			    $prepare = DB::db_connect()->prepare("SELECT * FROM $this->table WHERE $objCondition $limit");
			    $prepare->execute($bindParam);

			    $prepareData = $prepare->fetchAll(PDO::FETCH_OBJ);

			    return $prepareData;

			} else {
			    
			    die("The called function was not provided by this ORM. <br>Please write your own query instead by using this function query().<br>Refer to docs for further info.");
			}
	        
	    }
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

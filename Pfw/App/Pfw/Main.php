<?php

	namespace App\Pfw;

	use \App\Http\Request as Request;

	class Main
	{
		
		protected $_debug='false';
		/**
		* @var array $_listUri List of URI's to match against
		*/
		private $_listUri = array();
		
		/**
		* @var array $_listCall List of closures to call 
		*/
		private $_listCall = array();

		/**
		* @var array $_listMethod List of method for each uri 
		*/
		private $_listMethod = array();
		
		/**
		* @var string $_trim Class-wide items to clean
		*/
		private $_trim = '/\^$';

		private $_notFound;

		private $req;
		function __construct($arrOptions='')
		{
			
			if (is_array($arrOptions)) {
				
				$this->_debug=$arrOptions['debug'];

				if ($this->_debug == true) {
					
					$_SESSION['debug'] = true;
				}
				else{
					$_SESSION['debug'] = false;
				}
				
			}

			$this->req = new Request();
		}

		/**
		* function to render php/html file
		*/
		public function render($filename=null,$arr=null)
		{

			$ex_filename = explode('/', $filename);

			if (count($ex_filename) > 1) {
				
				$folder = $ex_filename[0];
				$filename = $ex_filename[1].".php";

				$filename_path = realpath(dirname(__FILE__).'/../').'/Views/'.$folder.'/'.$filename;
			}
			else{
				
				$filename = $ex_filename[0].".php";
				$filename_path = realpath(dirname(__FILE__).'/../').'/Views/'.$filename;
			}

			
			if (file_exists($filename_path)) {
			    
			    try{
				    extract($arr);
				    return(include $filename_path);
				    exit();
			    }
			    catch(Exception $e){

			    	echo "<pre>";
			    	print_r(debug_backtrace());
			    }
			} 
			else {
			    
			    echo '<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>';
			    echo '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>';
			    echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">';
			    echo '<link href="'.SERVER_URL.'Pfw/Views/assets/css/main.css" rel="stylesheet" type="text/css">';

			    $debug = debug_backtrace();

			    $fileError = $debug[0]['args'][0];
			    if ($fileError != null) {
			    	
			    	$fileError = "File ".$fileError." not found";
			    }
			    else{
			    	$fileError = "Empty file to render";
			    }
			 	$file = $debug[0]['file'];
			 	$line_error = $debug[0]['line'];
			 	$function_error = $debug[0]['function'];

			 	if ($_SESSION['debug'] == true) {
				 	
				 	$i=0;
				 	$errMsg='<div class="col-md-8 col-md-offset-2" style="margin-top: 5%;">
				 	<center>
				 		<h1>PFW Application Error</h1>
				 		<h2>'.$fileError.'</h2>
				 	</center>
				 	<hr>				 	
				 	<div class="panel-group">';
					$errMsgTitle='';
					$errMsgBody='';

				 	foreach ($debug as $key => $value) {
				 		
				 		$i++;

				 		$errMsgBody.='<div class="panel panel-danger">
				      		<div class="panel-heading">
					        <h4 class="panel-title">
					          <a data-toggle="collapse" href="#collapse'.$key.'">('.$i.')'.$debug[$key]['file'].'</a>
					        </h4>
				      	</div>
				      	<div id="collapse'.$key.'" class="panel-collapse collapse">
					        <div class="panel-body" style="background-color: #ABB7B7;">
					        Line: '.$debug[$key]['line'].'
					        <br>
					        Function: '.$debug[$key]['class']."->".$debug[$key]['function'].'()
					        </div>
					      </div>
					    </div>';
				 	}
				 	$errMsg.=$errMsgBody.'</div></div>';				 	
				 	echo $errMsg;
			 	}
			 	else{
			 		
			 		return self::error_page();
			 	}
			}
		}

		/**
		* controller function for PFW
		*/
		public function controller($controller=null,$arrData=null)
		{
			
			$controller_ex = explode("@", $controller);

			$controller = $controller_ex[0];
			$use_function = $controller_ex[1];
			
			try{

			
				$stringController="\\App\\Controllers\\$controller";
				$controller = new $stringController;

				return $controller->$use_function($arrData);
				
			}
			catch (Exception $e) {
			    
		   		echo '<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>';
			    echo '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>';
			    echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">';
			    echo '<link href="'.SERVER_URL.'Pfw/Views/assets/css/main.css" rel="stylesheet" type="text/css">';

			    $debug = debug_backtrace();

			 	$file = $debug[0]['file'];
			 	$line_error = $debug[0]['line'];
			 	$function_error = $debug[0]['function'];
			 	
			 	if ($_SESSION['debug'] == true) {
				 	
				 	echo"
				 		<div class='container'>
				 			<div class='row'>
								<div class='col-md-6 col-md-offset-3' style='margin-top: 5%;'>
									<h1>MyFramework Application Error</h1>
							 		<div class='alert alert-danger' role='alert'>
							 		Opps! Something error was found in ".$file."<br>
							 		Line: ".$line_error."<br>
									At function: ".$function_error."()
									</div>
								</div>
							</div>
						</div>
				 	";
			 	}
			 	else{

			 		return self::error_page();
			 	}
			}
		}

		/**
		* function to create map
		*/
		public function map_url($meth='GET',$uri, $function)
		{
			// {$meth};
			$uri = trim($uri, $this->_trim);
			$this->_listUri[$meth] = $uri;
			$this->_listCall[] = $function;
			$this->_listMethod[$uri] = $meth;
		}

		public function __call($name, $arguments)
	    {
	        
	        $this->_listUri[] = trim($arguments[0], $this->_trim);
	        $this->_listCall[] = $arguments[1];
	        $this->_listMethod[] = $name;  
	    }
		
		/**
		* submit - Looks for a match for the URI and runs the related function
		*/
		public function submit()
		{	
			$uri = isset($_REQUEST['uri']) ? $_REQUEST['uri'] : '/';
			$uri = trim($uri, $this->_trim);

			$replacementValues = array();

			/**
			* List through the stored URI's
			*/
			$matched = false;

			foreach ($this->_listUri as $listKey => $listUri)
			{

			 	/**
			 	* See if there is a match
			 	*/				
				if (preg_match("#^$listUri$#", $uri))
				{

					switch (strtoupper($this->_listMethod[$listKey])) {
						
						case 'POST':
							
							if ($_SERVER['REQUEST_METHOD'] == 'POST') {

								/**
						 		* Replace the values
								*/
								$realUri = explode('/', $uri);
								$fakeUri = explode('/', $listUri);

								/**
								* Gather the .+ values with the real values in the URI
								*/
								foreach ($fakeUri as $key => $value) 
								{
									if ($value == '.+') 
									{
										$replacementValues[] = htmlentities($realUri[$key]);
									}
								}
								
								/**
								* Pass an array for arguments
								*/
								call_user_func_array($this->_listCall[$listKey], $replacementValues);
								$matched=true;
							}
							else{
								die("ONLY POST METHOD ALLOWED");
							}
							break;
							
						case 'GET':

							if ($_SERVER['REQUEST_METHOD'] == 'GET') {

								/**
						 		* Replace the values
								*/
								$realUri = explode('/', $uri);
								$fakeUri = explode('/', $listUri);

								/**
								* Gather the .+ values with the real values in the URI
								*/
								foreach ($fakeUri as $key => $value) 
								{
									if ($value == '.+') 
									{
										$replacementValues[] = htmlentities($realUri[$key]);
									}
								}
								
								/**
								* Pass an array for arguments
								*/
								call_user_func_array($this->_listCall[$listKey], $replacementValues);
								$matched=true;
							}
							else{
								die("ONLY GET METHOD ALLOWED");
							}
							break;

						case 'DELETE':
							# code...
							break;

						case 'PUT':
							# code...
							break;
						
						default:
							# code...
							break;
					}
				}
			}
			if(!$matched) {

				if ($_SESSION['debug']) {
					
					$debug = debug_backtrace();

					echo '<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>';
			    	echo '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>';
			    	echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">';

			    	$errMsg='<div class="col-md-8 col-md-offset-2" style="margin-top: 5%;">
				 	<center>
				 		<h1>PFW Application Error</h1>
				 		<h2>Requested URI ('.$this->req->uri.') not registered in index.php file</h2>
				 	</center>
				 	<hr>				 	
				 	<div class="panel-group">';
					$errMsgTitle='';
					$errMsgBody='';
					$i=0;
				 	foreach ($debug as $key => $value) {
				 		
				 		$i++;

				 		$errMsgBody.='<div class="panel panel-danger">
				      		<div class="panel-heading">
					        <h4 class="panel-title">
					          <a data-toggle="collapse" href="#collapse'.$key.'">('.$i.')'.$debug[$key]['file'].'</a>
					        </h4>
				      	</div>
				      	<div id="collapse'.$key.'" class="panel-collapse collapse">
					        <div class="panel-body" style="background-color: #ABB7B7;">
					        Line: '.$debug[$key]['line'].'
					        <br>
					        Function: '.$debug[$key]['class']."->".$debug[$key]['function'].'()
					        </div>
					      </div>
					    </div>';
				 	}
				 	$errMsg.=$errMsgBody.'</div></div>';				 	
				 	echo $errMsg;
				}
				else{

			        if(isset($this->_notFound)) {
		                call_user_func($this->_notFound);
			        }
				}
		    }
		}

		/**
		* trigger 404
		*/
		public function _error($callback=null) {
		    $this->_notFound = $callback;
		}

		/**
		* function to render 404 page
		*/
		public function error_page($template=null)
		{

			if ($template !=null) {
				
				return self::render($template);
				die();
			}
			else{

				return self::render('Error/Error');
				die();
			}
		}
	}
?>

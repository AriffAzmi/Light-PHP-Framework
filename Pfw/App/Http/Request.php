<?php 
	
	namespace App\Http;

	class Request
	{
	   	
		/**  Location for overloaded data.  */
	    private $data = array();
	    
	    /**  Overloading not used on declared properties.  */
	    public $declared = 1;

	    /**  Overloading only used on this when accessed outside the class.  */
	    private $hidden = 2;

	    public function __get($name)
	    {
	    	$requestValue = htmlspecialchars($_REQUEST[$name], ENT_QUOTES, 'UTF-8');
	    	return $requestValue;
	   	}
	}
?>
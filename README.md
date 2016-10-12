# PFW Framework


## Install the Application

Run `composer install` on your terminal.

Open file .htaccess in root directory. Then change this value with the folder directory name

    RewriteEngine On
	RewriteBase /replace-your-directory-folder-name
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d

	RewriteRule ^(.+)$ index.php?uri=$1 [QSA,L]
	

## Server Environment

Go to root folder directory for this project, then run this command on your terminal.

php pfw setServerEnv yourserverlocation serverusername serverpassword serverdatabase

Or you can edit this file at Pfw/App/Pfw/Server.php
    	
    define('SERVERLOCATION', '');
    define('SERVERUSERNAME', '');
    define('SERVERPASSWORD', '');
    define('SERVERDATABASE', '');
	
  
  
## Routing
    

    require 'vendor/autoload.php';

    use App\Pfw\Main as App;

    $app = new App([
        'debug' => true
    ]);


    //POST
    $app->post('/saveUser', function() use($app){
    
      echo "User Saved! ";
    });
  
    //GET
    $app->get('/', function() use($app){
    
      echo "Hello, im your home page.";
    });
  
    //GET WITH PARAMETER
    $app->get('/hello/.+', function($name) use($app){
    
      echo "Hello, $name.";
    });

## Routing With Controller
  
    //POST
    $app = new \App\Main\Pfw([
      'debug' => true
    ]);
  
    $app->post('/saveUser', function() use($app){
    
      $app->controller("UserController@save");
    });
  
    //GET
    $app->get('/', function() use($app){
    
      $app->controller("HomeController@index");
    });
  
    //GET WITH PARAMETER
    $app->get('/hello/.+', function($name) use($app){
    
      $app->controller("HomeController@greetings",$name);
    });

## Model
    
    =======================
    Example model for User:
    =======================

    namespace App\Models;
    
    /**
    * @return User data
    */

    use \App\Pfw\Model;
    
    Class User extends Model{
    
    	protected $table = 'users';
    }

    ============================
    Meanwhile in UserController:
    ============================

    namespace App\Controllers;

    use \App\Pfw\Main as App;
    use \App\Http\Request as Request;

    use \App\Models\User as User;
    
    //For store data
    public function store()
    {
	$user = new User();
	
	$user->fname = "John Doe";
	$user->email = "johndoe@gmail.com";
	$user->mobile_no = "60123456789";

	$save = $user->save();
    }
    
    //For update data
    public function update()
    {
	$user = new User();

	$user->id = 15;
	$user->fname = "John Doe 1";
	$user->email = "johndoe1@gmail.com";
	$user->mobile_no = "60123456789";

	$save = $user->save();
    }
    
    //For delete data
    public function delete()
    {
	$user = new User();

	$user->id = 15;

	$delete = $user->delete();
    }

    //For request form data
    public function reqFormData(){

        $req = new Request();
    
        echo $req->username;
    }
    
    "username" as the example of <input type="text" name="username">
  
## Generate Controller

The controller folder locate at Pfw/App/Controllers/.

Go to root folder directory for this project, then run this command on your terminal.

`php pfw makeController UserController`

## Generate Model

The model folder locate at Pfw/App/Models/.

Go to root folder directory for this project, then run this command on your terminal.

`php pfw makeModel User`

That's it! Now go build something cool.

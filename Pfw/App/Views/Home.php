<!DOCTYPE html>
<html>
<head>
	<title><?=$title?></title>
</head>
<body style="background-color:#95A5A6">
	
	<center>
		<div class="col-md-6 col-md-offset-3" style="margin-top:4%;background-color:#FFFFFF; padding: 15px 28px; margin-bottom: 20px;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;border: 1px solid #ccc;">
			<!-- <h1>Your homepage | <?=$date?></h1> -->

			<form action="data" method="POST">
				<label>Name</label>
				<input type="text" name="name">
				<br>
				<input type="submit" name="submit" value="Submit">
			</form>
		</div>
		
	</center>
</body>
</html>
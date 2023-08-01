<!-- 
	Main/login page for museum app
	Access link: https://www.students.cs.ubc.ca/~tineand/museum_app/pages/user_login.php
	Users can login by selecting their user type
-->
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>User Login page</title>
</head>
<body>
<h1> Museum App </h1>
		<p>Please select your user type to login:</p>
		<form id="user-login-form" method="POST" action="user_redirect.php">
			<label for="user-type">User type:</label>
				<select name="user-type" id="user-type">
					<option value="visitor">Visitor</option>
					<option value="owner">Owner</option>
					<option value="employee">Employee</option>
				</select>
				<br></br>
				<input type="submit" name="login-button" id="login-button" value="Login"/> 
		</form>
</body>
</html>

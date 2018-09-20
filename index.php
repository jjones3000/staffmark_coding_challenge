<?php
/**
 * Login page. This provides authentication against mysqli database 
 * using sha1 encrypted password, then redirects to the fileUpload.php page
 *
 */
session_start();
include ('includes/fxns.inc.php');
include ('includes/db.inc.php');
$error = $user = $pass ='';

	if (isset($_POST['user']))
	{
		$user=sanitizeString($_POST['user']);
		$pass = sanitizeString($_POST['pass']);
		$pass=sha1($pass);

		if ($user == "" || $pass == "")
		{
			$error = "Not all fields were entered<br />";
		}
		else
		{
			$query = "SELECT * FROM login WHERE username='".$user."' AND password='".$pass."'";
			$result=$conn->query($query);
			$numMatches=$result->num_rows;
				if ($numMatches == 0)
				{
					$error = "Username/Password invalid<br />";
				}
				else
				{
					$_SESSION['user'] = $user;
					$_SESSION['pass'] = $pass;
					die(header('location:./fileUpload.php'));
				}
			
		}
	}

echo <<<_END
<html>
<head><title></title></head>
<body>
	<form method='POST' action='index.php'>$error
	Username <input type='text' maxlength='16' name='user' value='$user' /><br />
	Password <input type='password' maxlength='16' name='pass' /><br />
	<input type='submit' value='Login' />
	</form>
</body>
</html>
_END
?>
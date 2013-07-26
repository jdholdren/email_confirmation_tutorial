<?php
 
// Check if the form has been submitted
if (!empty($_POST['submit']))
{
    require('./db.php');
    require('./User.php');
 
    $errors = array();
 
    // Check if the user has already signed up
    if (User::getUser($_POST['email']))
    {
        $errors[] = 'That email has already been subscribed';
    }
 
    if (empty($errors))
    {
        if (User::createUser($_POST['email']))
        {
            $errors[] = 'Thanks! Confirm using email.';
        }
        else
        {
            $errors[] = 'Error signing up.';
        }
    }
}
 
?>
<!DOCTYPE html>
<html>

<head>
	<title>Simple Email Confirmation</title>
	<link rel='stylesheet' href='./style.css' />
</head>

<body>
	<form action='./' method='post'>
		<h1>Sign Up For Updates</h1>
		<p>
			<input type='email' id='email' name='email' placeholder='Email' class='textInput' required='true'/>
		</p>
		<?php
			if (!empty($errors))
			{
	    		foreach($errors as $error)
	    		{
		    	    echo "<span class='error'>" . $error . '</span>';
	    		}
			}
		?>
		<p>
			<input type='submit' name='submit' id='submitButton' value='Sign Up' />
		</p>
	</form>
</body>

</html>
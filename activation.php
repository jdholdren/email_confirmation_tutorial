<?php

require('db.php');
require('User.php');

// Attempt to activate the account
$activated = (!empty($_GET['email']) && !empty($_GET['hash'])) ? User::activate($_GET['email'], $_GET['hash']) : false;

?>
<!DOCTYPE html>
<html>

<head>
	<title>Activate</title>
	<link rel='stylesheet' href='./style.css' />
</head>

<body>

<div id='messageContainer'>
	<?php
		if ($activated) { 
	?>
		Thanks! Your account has been activated.
	<?php
		}

		else { ?>
			There was an error in activating your account.
	<?php } ?>
</div>

</body>

</html>
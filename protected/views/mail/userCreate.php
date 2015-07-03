<p>Welcome to <?php echo app()->name; ?>.</p>

<p>Thank you for registering an account with us! Here are your login credentials.</p>

<p><strong>Username:</strong> <?php echo $username; ?></p>

<p><strong>Password:</strong> <?php echo $password; ?></p>

<p>Please <a href="<?php echo absUrl('activation').'?activate='.$activate; ?>">click here</a> to activate your account.</p>
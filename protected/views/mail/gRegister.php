<p>Welcome to <?php echo app()->name; ?>.</p>

<p>Please <a href="<?php echo absUrl('activation').'?activate='.$activate; ?>">click here</a> to activate your account.</p>

<p>We noticed that you registered at our site using Google. You may log in any time with Google, but for your records, here are your login credentials. You can use these to log in without Google.</p>

<p><strong>Username:</strong> <?php echo $username; ?></p>

<p><strong>Password:</strong> <?php echo $password; ?></p>
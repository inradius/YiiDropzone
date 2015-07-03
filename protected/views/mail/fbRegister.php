<p>Welcome to <?php echo app()->name; ?>.</p>

<p>Please <a href="<?php echo absUrl('activation').'?activate='.$activate; ?>">click here</a> to activate your account.</p>

<p>We noticed that you registered at our site using Facebook. You may log in any time with Facebook, but for your records, here are your login credentials. If you ever decide to deactivate your Facebook account, you can still access our site with this username and password.</p>

<p><strong>Username:</strong> <?php echo $username; ?></p>

<p><strong>Password:</strong> <?php echo $password; ?></p>
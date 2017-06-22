<h1>Login</h1>
<form id="loginform" action="/Login/Authenticate" method="POST">
	<label>Username:</label>
	<input type="text" name="username" value="<?= (isset($username)) ? $username : "" ?>"/><br>
	<label>Password:</label>
	<input type="password" name="password" /><br>	
	<?= $html->renderCSRF() ?>
	<input value="Login" type="submit" />
	<a href="/Registration/RequestPasswordReset">Passwort vergessen?</a>	
	<a href="/Registration">Kein Account?</a>	
</form>

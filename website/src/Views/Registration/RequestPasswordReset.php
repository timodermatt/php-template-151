<h1>Passwort Vergessen</h1>
<form action="/Registration/RequestPasswordResetEmail" method="POST">
	<?= $html->renderCSRF() ?>
	<label>Username:</label>
	<input type="text" name="username"/><br>
	<input value="Login" type="submit" />
</form>
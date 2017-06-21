<h1>Passwort Vergessen</h1>
<form action="/Registration/RequestPasswordResetEmail" method="POST">
	<?= $html->renderCSRF() ?>
	<label>Benutzername:</label>
	<input type="text" name="username"/><br>
	<input value="Login" type="submit" />
</form>
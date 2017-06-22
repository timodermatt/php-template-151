<h1>Passwort Zurücksetzen</h1>
<form action="/Registration/ResetPassword" method="POST">
	<?= $html->renderCSRF() ?>
	<label>Passwort:</label>
	<input type="password" name="password"/><br>
	<label>Passwort bestatigen:</label>
	<input type="password" name="passwordConfirm"/><br>
	<input type="hidden" name="userId" value="<?= $userId?>"/>
	<input type="hidden" name="confirmation" value="<?= $confirmation?>"/>	
	<input value="Bestätigen" type="submit" />
</form>

<form method="post" action="/Post/Create" enctype="multipart/form-data">
	<?= $html->renderCSRF() ?>
	<label>Titel:</label>
	<input type="text" name="title" value="<?= isset($title) ? $title : ""?>"/>
	<lable>Text:</lable>
	<input type="text" name="text" value="<?= isset($text) ? $text : ""?>"/>
	<lable>Bild:</lable>
	<input type="file" name="picture" value="<?= isset($picture) ? $picture : ""?>"/>
	<input type="submit" value="Erstellen" />
</form>
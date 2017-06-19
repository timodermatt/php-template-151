<h1><?= $post["title"]?></h1>
<img width="300" height="auto" src="/Post/GetPostImage?postId=<?= $post["postId"] ?>" />
<p><?= $post["text"] ?></p>
<p><?= $post["date"]?></p>
<?php 
foreach ($comments as $comment){
?>
	<p><?= $comment["username"]?> <?= $comment["date"]?></p>
	<p><?= $comment["message"]?></p>
<?php 
}
?>
<?php 
if(isset($_SESSION["userId"])){
	?>
	<form action="/Post/Comment" method="post">
	<?= $html->renderCSRF()?>
	<label>Text:</label>
	<input type="text" name="text" />
	<input type="hidden" name="postId" value="<?= $post["postId"]?>" />
	<input type="submit" value="Kommentieren"/>
	</form>
	<?php
}
?>

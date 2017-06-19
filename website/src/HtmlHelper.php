<?php

namespace LucStr;

class HtmlHelper{
	public function renderCSRF(){
		echo '<input type="hidden" name="csrf" value="' . $_SESSION["CSRF"] . '" />';
	}
}
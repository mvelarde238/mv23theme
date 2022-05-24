<?php
$search = SEARCH_PLACEHOLDER;
$current_lang = (function_exists('pll_current_language')) ? pll_current_language() : 'es';
?>
<form role="search" method="get" id="searchform" class="searchform" action="<?php echo home_url( '/' ); ?>">
	<input type="search" class="searchform__input" id="s" name="s" value="" placeholder="<?=$search[$current_lang]?>" />
	<button class="searchform__submit" type="submit" id="searchsubmit" ><i class="fa fa-search"></i></button>
</form>

<?php
global $user_ID, $current_user; 
wp_get_current_user(); 
$activeClass = ($user_ID == '') ? '' : 'active';
?>
<button class="user-btn <?=$activeClass?>" data-activates="user-sidenav">
	<span><i class="material-icons">person</i></span>
	<?php if ($user_ID == '') : ?>
		INICIAR SESIÓN
	<?php else : ?>
		<?php echo 'Bienvenido '.$current_user->display_name; ?>
	<?php endif; ?>
</button>
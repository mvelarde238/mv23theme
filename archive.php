<?php 
use Core\Posttype\Archive_Template;

get_header(); 
?>

<div id="content">
    <?php Archive_Template::getInstance()->display(); ?>
</div>

<?php get_footer(); ?>
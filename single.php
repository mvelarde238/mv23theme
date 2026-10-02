<?php 
use Core\Posttype\Single_Template;

get_header(); 
?>

<div id="content">
    <?php Single_Template::getInstance()->display(); ?>
</div>

<?php get_footer(); ?>
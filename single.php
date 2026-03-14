<?php 
use Core\Posttype\Single_Template;

get_header(); 
?>

<div id="content">
    <div class="container">
        <?php Single_Template::getInstance()->display(); ?>
    </div>
</div>

<?php get_footer(); ?>
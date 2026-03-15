<?php 
use Core\Posttype\Archive_Template;

get_header(); 
?>

<div id="content">
    <div class="container">
        <?php Archive_Template::getInstance()->display(); ?>
    </div>
</div>

<?php get_footer(); ?>
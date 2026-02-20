<?php
use Core\Builder\Component\Footer_Preview;

if(!is_singular('footer')) {
    echo Footer_Preview::display(array());
}
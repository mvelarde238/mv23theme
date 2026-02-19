<?php
use Core\Builder\Component\Header_Preview;

if(!is_singular('header')) {
    echo Header_Preview::display(array());
}
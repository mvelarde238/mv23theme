<?php
require_once( 'classes/Core.php' );

Offcanvas_Elements\Core::getInstance();

/*
**************************************************
TODO LIST:
**************************************************
- ON OPEN CALLBACK -> validar y filtrar el código introducido para mitigar riesgos de seguridad
- ADD tapTarget settings fields (position, icon)
- add contact form 7 events in TRIGGER EVENT

- ADD in master:
     add sidenav-close X clases
     add width 98% and max-width 300px and @extend .scroll to sidenav
- ADD A COMPONENT OR SHORTCODE TO SHOW A MENU, for example in sidenav or tapTarget
- modal footer is not working
- include tapTarget.js and css pulse
- ADD MV23_GLOBALS.scrollAnimations in scroll-animationns.js
- delete .open-minicart classes and js
- add modal data-status loading css
- check iframes widths and heights inside modal and sidenav an bottomsheet and all
- IN ORDER THE ASYNC METHOD GET THE CONTENT OF A PAGE SELECED WITH FIELD::OBJECT, 
THE_CONTENT NEED TO HAVE THE page_content post meta, this is a HUG refactorization in master
- ADD OFFCANVAS ELEMENTS MENU ITEM TO THEME OPTIONS WITHOUT BREAKING ORDER -> Core.php:52 -> add a filter for this order in master
*/
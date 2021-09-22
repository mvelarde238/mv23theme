<div class="uf-menu-selector">
    <?php
    $navs = get_terms('nav_menu', array( 'hide_empty' => false ) );
		
    $nav_menus = array();
    foreach( $navs as $nav ) {
        $nav_menus[ $nav->term_id ] = $nav->name;
    } 


    echo '<select>';
    echo '<option value=""> - Select - </option>';

    foreach( $nav_menus as $nav_menu_id => $nav_menu_name ) {
        // $selected = selected( $field['value'], $nav_menu_id );
        $selected = '';
        echo sprintf( '<option value="%1$d" %3$s>%2$s</option>', $nav_menu_id, $nav_menu_name, $selected );
    }

    echo '</select>';
    ?>
</div>
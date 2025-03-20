<div class="uf-map-error">
    <p>
        <span class="dashicons dashicons-admin-site"></span>
        <?php 
        printf(
            __( 'The Google Maps script could not be loaded. Please enter an API key at the following URL: <a href="%s" target="_blank">Theme Options</a>', 'ultimate-fields' ),
            esc_url( admin_url( 'admin.php?page=theme-options#maps_options' ) )
        );
        ?>
    </p>
</div>
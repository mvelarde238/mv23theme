<?php 
$home_url = esc_url( home_url() ); 

// Get primary color from new theme_colors structure
$primary_color = '';
$theme_colors = get_option( 'theme_colors', array() );
if ( is_array( $theme_colors ) && ! empty( $theme_colors ) ) {
    foreach ( $theme_colors as $color_item ) {
        if ( isset( $color_item['__type'] ) && $color_item['__type'] === 'color' 
            && isset( $color_item['css_property'] ) && $color_item['css_property'] === '--primary-color' 
            && ! empty( $color_item['color'] ) ) {
            $primary_color = $color_item['color'];
            break;
        }
    }
}

$accent_color = ( $primary_color ) ? $primary_color : CF7_EMAIL_MAIN_COLOR;
?>
            </td>
		</tr>
		<tr>
			<td style="padding: 30px 40px; color:#fff; background-color: <?php echo $accent_color; ?>" class="center">
				<p><b><a style="color:#fff" href="<?=$home_url?>">[_site_title]</a></b></p>
			</td>
		</tr>
	</table>
</body>
</html>
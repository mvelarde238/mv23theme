<?php 
$home_url = esc_url( home_url() ); 
$primary_color = get_option( 'primary_color' );
$accent_color = ($primary_color) ? $primary_color : CF7_EMAIL_MAIN_COLOR;
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
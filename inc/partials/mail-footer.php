<?php $home_url = esc_url( home_url() ); ?>
            </td>
		</tr>
		<tr>
			<td style="padding: 30px 40px; color:#fff; background-color: <?php echo CF7_EMAIL_MAIN_COLOR; ?>" class="center">
				<p><a style="color:#fff" href="<?=$home_url?>"><?php echo preg_replace("(^https?://)", "", $home_url ); ?></a></p>
			</td>
		</tr>
	</table>
</body>
</html>
<?php
if( CF7_EMAIL_LOGO ){
    $logo_url = CF7_EMAIL_LOGO;
} else {
    $logo_id = get_option( 'main_logo' );
    $logo_url = ($logo_id) ? wp_get_attachment_image_url( $logo_id, 'full') : array('');
}
$blogname = get_option( 'blogname' );
?>
<!doctype html>
<head>
	<meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<style>body{background-color: #f1f1f1;}.center{text-align: center;}.boton{display: inline-block;border-radius: 4px;color:#fff !important;text-decoration: none; background-color:#FF8C00;padding: 10px 20px;margin-bottom: 15px;}.columnas2{display: flex;justify-content: space-between;}.columnas2>div{width: 49%;}table.paleBlueRows {border: 1px solid #FFFFFF;width: 100%;max-width: 500px;border-collapse: collapse;margin: auto;}table.paleBlueRows td, table.paleBlueRows th {border: 1px solid #FFFFFF;padding: 3px 2px;}table.paleBlueRows tbody td {padding: 5px 15px;font-size: 15px;}table.paleBlueRows tr:nth-child(even) {background: #D0E4F5;}table.paleBlueRows thead {background: #0B6FA4;border-bottom: 5px solid #FFFFFF;}table.paleBlueRows thead th {font-size: 17px;font-weight: bold;color: #FFFFFF;text-align: center;border-left: 2px solid #FFFFFF;}table.paleBlueRows thead th:first-child {border-left: none;}table.paleBlueRows tfoot {font-size: 14px;font-weight: bold;color: #333333;background: #D0E4F5;border-top: 3px solid #444444;}table.paleBlueRows tfoot td {font-size: 14px;}img{max-width: 100%;height: auto;}@media only screen and (max-width: 768px){.columnas2{display: block;}.columnas2>div{width: 100%;}br{display: none;}}</style>
</head>
<body>
	<table style="width: 100%; max-width: 600px; margin: auto; background-color:#fff; font-family: Helvetica,Arial" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="center">
				<p>
					<img src="<?=$logo_url?>" style="height: 80px" alt="<?=$blogname?>">
				</p>
			</td>
		</tr>
		<tr>
			<td style="padding: 30px 40px;">
<?php
$id = get_the_ID();

$in_cart = false;
foreach( WC()->cart->get_cart() as $cart_item ) {
	$product_in_cart = $cart_item['product_id'];
	if ( $product_in_cart === $id ) $in_cart = true;
}
$btn_status = ($in_cart) ? 'added' : 'initial';
?>

<button class="btn add-to-quote" data-id="<?=$id?>" data-status="<?=$btn_status?>">
	<span>Agregar a Cotización</span>
	<span>Procesando</span>
	<span>Producto Agregado</span>
</button>
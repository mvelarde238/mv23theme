<?php
$tipo = $componente['__type'];
$grid_items = $componente['grid_items'];
$nth_items = count($grid_items);
if ($nth_items < 1) return; 
$components_margin = (!empty($componente['components_margin'])) ? $componente['components_margin'] : null;
$components_margin_attrs = ( $components_margin && $components_margin != 20) ? 'data-setmargin='.$components_margin : ''; 
$items_in_desktop = (!empty($componente['items_in_desktop'])) ? $componente['items_in_desktop'] : $nth_items;
$items_in_tablet = (!empty($componente['items_in_tablet'])) ? $componente['items_in_tablet'] : $nth_items;
$items_in_mobile = (!empty($componente['items_in_mobile'])) ? $componente['items_in_mobile'] : $nth_items;

$classes_array = format_classes(array(
	'componente',
	'items-grid',
	get_color_scheme($componente),
	$componente['class']
));

$attributes = generate_attributes($componente, $classes_array);
?>
<div <?=$attributes?> <?=$components_margin_attrs?>>
	<div class="items-grid__list l<?=$items_in_desktop?> m<?=$items_in_tablet?> s<?=$items_in_mobile?>">
		<?php foreach ($grid_items as $item): 
			$classes_array = format_classes(array(
				'items-grid__list-item',
				get_color_scheme($item),
				$item['class']
			));

			$item['__type'] = 'grid__item'; 
        	$item_attributes = generate_attributes($item, $classes_array);
        	?>
        	<div <?=$item_attributes?>>
				<?php foreach ($item['componentes'] as $component_inside) {
					set_query_var( 'componente', $component_inside );
					get_template_part( 'inc/ultimate-fields/componentes/views/'.$component_inside['__type'] );
    	    	}?>
    		</div>
		<?php endforeach ?>
	</div>
</div>
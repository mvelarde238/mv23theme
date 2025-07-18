<?php
namespace Core\Builder\Component;

use Core\Builder\Component\Row;

class Inner_Row extends Row{
    
    public function __construct() {
		parent::__construct(
			'inner_row',
			__( 'Inn-Row', 'mv23theme' )
		);
	}
}

new Inner_Row();
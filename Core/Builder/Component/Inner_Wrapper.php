<?php
namespace Core\Builder\Component;

use Core\Builder\Component\Components_Wrapper;

class Inner_Wrapper extends Components_Wrapper{
    
    public function __construct() {
		parent::__construct(
			'inner_wrapper',
			__( 'Inn-Wrapper', 'mv23theme' )
		);
	}
}

new Inner_Wrapper();
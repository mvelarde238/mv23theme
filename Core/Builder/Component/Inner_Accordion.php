<?php
namespace Core\Builder\Component;

use Core\Builder\Component\Accordion;

class Inner_Accordion extends Accordion {

    public function __construct() {
		parent::__construct(
			'inner_accordion',
			__( 'Inn-Accordion', 'mv23theme' )
		);
	}
}

new Inner_Accordion();
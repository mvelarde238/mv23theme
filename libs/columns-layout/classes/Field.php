<?php
namespace Ultimate_Fields\Columns_Layout;

use Ultimate_Fields\Field\Repeater;
use Ultimate_Fields\Template;
use Ultimate_Fields\Container\Layout_Group;
use Ultimate_Fields\Datastore\Group as Group_Datastore;

/**
 * Extends the repeater field by introducing the concept of columns layout.
 *
 * @since 3.0
 */
class Field extends Repeater {
	/**
	 * Holds the amount of elements, which should be used for the field.
	 *
	 * @since 3.0
	 * @var int
	 */
	protected $columns = 12;

	/**
	 * Adds a group to the repeater.
	 *
	 * @since 3.0
	 *
	 * @param string|Layout_Group $group Either the ID of a group or a generated one.
	 * @param array               $args  Arguments for the group.
	 *                                   @see Ultimate_Fields\Container\Layout_Group::__construct().
	 * @return Ultimate_Fields\Field\Layout          The instance of the field.
	 */
	public function add_group( $group, $args = array() ) {
		# If the group has already been created, just use it.
		if( is_a( $group, Layout_Group::class ) ) {
			$this->groups[ $group->get_id() ] = $group;
		} else {
			$group = new Layout_Group( $group, $args );
			$this->groups[ $group->get_id() ] = $group;
		}

		return $this;
	}

	/**
	 * The layout fiel requires proper groups, so the quick syntax is disabled.
	 *
	 * @since 3.0
	 *
	 * @param Ultimate_Fields\Field[] The fields to add to the group.
	 * @return Ultimate_Fields\Field\Repeater.
	 */
	public function add_fields( $fields ) {
		wp_die( 'Ultimate_Fields\\Field\\Columns_Layout does not support the short add_fields() syntax!' );
	}

	/**
	 * Enqueues the scripts for the field.
	 *
	 * @since 3.0
	 */
	public function enqueue_scripts() {
		parent::enqueue_scripts();

		wp_enqueue_script( 'uf-columns-layout' );
		wp_enqueue_script( 'uf-field-columns-layout' );
		wp_enqueue_style( 'uf-field-columns-layout' );

		# Add the necessary templates
		Template::add( 'columns-layout', 'base' );
		Template::add( 'columns-layout-placeholder', 'placeholder' );
		Template::add( 'columns-layout-element-prototype', 'element-prototype' );
		Template::add( 'columns-layout-column', 'column' );
		Template::add( 'columns-layout-group', 'group' );
	}

	/**
	 * Exports the settings of the field.
	 *
	 * @since 3.0
	 *
	 * @return mixed[]
	 */
	public function export_field() {
		$settings = parent::export_field();

        $settings[ 'type' ]           = 'Columns_Layout';
		$settings[ 'columns' ]      = $this->columns;
		$settings[ 'chooser_type' ] = 'widgets';

		return $settings;
	}

	/**
	 * Exports the data of the field.
	 *
	 * @since 3.0
	 *
	 * @return mixed[]
	 */
	public function export_data() {
		$raw   = $this->get_value( $this->name );
		$value = array( 
			'content' => array()
		);

		# Use the default value if needed
		if( null === $raw && is_array( $this->default_value ) ) {
			$raw = $this->default_value;
		}

		# If there are groups, go through each of them.
		if( isset($raw['content']) ) foreach( $raw['content'] as $raw_column ) {
			$column = array();

			foreach( $raw_column as $raw_group ) {
				$datastore = new Group_Datastore( $raw_group );

				# Groups without type are ignored
				if( ! isset( $raw_group[ '__type' ] ) ) {
					continue;
				}

				# If the type of group is no longer available, skip
				if( ! isset( $this->groups[ $raw_group[ '__type' ] ] ) )
					continue;

				# Get the datastore and export data
				$group = $this->groups[ $raw_group[ '__type' ] ];
				$group->set_datastore( $datastore );

				$column[] = $group->export_data();
			}

			if( ! empty( $column ) ) {
				$value['content'][] = $column;
			}
		}

		// export row settings
		$value['row_settings'] = $raw['row_settings'] ?? [];

		// export columns settings
		$value['columns_settings'] = $raw['columns_settings'] ?? [];

		return array(
			$this->name => $value
		);
	}

	/**
	 * Allows the amount of columns within the field to be changed.
	 *
	 * @since 3.0
	 *
	 * @param int $columns The new amount of columns.
	 * @return Ultimate_Fields\Field\Layout The instance of the field.
	 */
	public function set_columns( $columns ) {
		$this->columns = intval( $columns );

		return $this;
	}

	/**
	 * Returns the amount of columns, used by the field.
	 *
	 * @since 3.0
	 *
	 * @return int
	 */
	public function get_columns() {
		return $this->columns;
	}

	/**
	 * Retrieves the value of the field from a source and saves it in the current datastore.
	 *
	 * This method should not perform any validation - if something is wrong with
	 * the value of the field, simply don't save it. Validation will be performed
	 * later and will return an error anyway, if the internal value is empty.
	 *
	 * @since 3.0
	 *
	 * @param mixed[] $source The source which the value of the field should be available in.
	 */
	public function save( $source ) {
		$value = array();

		if( isset( $source[ $this->name ]['content'] ) && is_array( $source[ $this->name ]['content'] ) ) {
			$value['content'] = array();
			$raw = $source[ $this->name ]['content'];

			foreach( $raw as $raw_column ) {
				$column = array();

				foreach( $raw_column as $raw_column ) {
					if( ! isset( $raw_column[ '__type' ] ) || ! isset( $this->groups[ $raw_column[ '__type' ] ] ) ) {
						continue;
					}

					$group = $this->groups[ $raw_column[ '__type' ] ];
					$group->save( $raw_column );
					$column[] = $group->get_datastore()->get_values();
				}

				$value['content'][] = $column;
			}

			// save row settings
			$value['row_settings'] = $source[ $this->name ]['row_settings'];

			// save columns settings
			$value['columns_settings'] = $source[ $this->name ]['columns_settings'];
		}

		// $this->datastore->set( 'columns_settings', $source['columns_settings'] );
		$this->datastore->set( $this->name, $value );
	}
}

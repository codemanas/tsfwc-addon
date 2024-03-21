<?php
/**
 * Class TSFWCCustomAttributes
 *
 * This class is responsible for adding new fields to the Products schema
 * as well as displaying it on the fronted.
 */
class TSFWCCustomAttributes {
	private static $_instance = null;

	public static function get_instance() {
		return ( self::$_instance == null ) ? self::$_instance = new self() : self::$_instance;
	}

	private function __construct() {
		add_filter( 'cm_tsfwc_product_fields', [ $this, 'modify_schema' ] );
		add_filter( 'cm_tsfwc_data_before_entry', [ $this, 'format_data' ], 10, 3 );
		add_action( 'cm_tsfwc_custom_attributes', [ $this, 'your_attribute' ] );
	}

	public function modify_schema( $fields ) {
		$fields[] = [ 'name' => 'my_custom_facet', 'type' => 'string[]', 'facet' => true, 'optional' => true ];

		return $fields;
	}

	public function format_data( $formatted_data, $product, $product_id ) {
		//according to the schema value must be a array of strings
		//you can use the $product_id to get post meta fields taxonomoy fields etc.
		$data = get_post_meta( $product_id, 'my_custom_facet', true );
		//e.g of accepted data = ['banana', 'apple', 'orange']
		$formatted_data['my_custom_facet'] = [ 'banana', 'apple', 'orange' ]; //use $data instead

		return $formatted_data;
	}

	public function your_attribute() { ?>
        <div
                data-facet_name="my_custom_facet"
                data-title="<?php echo __( "Filter by My Custom Facet", 'storefront' ); ?>"
                data-attr_label="<?php echo __( "Custom Facet", 'storefront' ); ?>"
                class="cm-tsfwc-shortcode-tags-attribute-filters"
                data-filter_type="refinementList"
                data-settings="<?php echo _wp_specialchars( json_encode( [ "searchable" => false ] ), ENT_QUOTES, "UTF-8", true ) ?>"
        ></div><?php //leaving a space here auto renders a p ( damn you autop )
	}
}

TSFWCCustomAttributes::get_instance();




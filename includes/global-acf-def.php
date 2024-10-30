<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// add module layout to flexible content 

function load_global_blocks( $slug ) {
	$choices = array('' => 'None');
	$rows = get_option('options_'.$slug.'_blocks');
	global $wpdb;
	if( is_array( $rows ) ){
		foreach( $rows as $key => $row ){	
			$array = $wpdb -> get_results(
				$wpdb->prepare( 
					"
						SELECT * 
						FROM wp_options
						WHERE option_name LIKE %s
					",
					'options_'.$slug.'_blocks_'.$key.'_'.$row.'_name'
				)
			);
			if(is_array($array) && count($array)>0){
				$obj = $array[0];
				$choices[] = $obj->option_value;
			}
		}
	}
	return $choices;    
}

function build_global_layout( $args ){
	$container_name = $args['container_name'];
	// globals are stored in wp_options as 'options_content_blocks_{$global_key}_images_0_image'; 'global_block_predefined_block' saves the $global_key
	$global_key = get_sub_field('global_block_predefined_block_' . $args['container_type']);
	$i = 0;
	if( have_rows( $container_name , 'option' ) ):
		while ( have_rows($container_name , 'option') ): 
			the_row();
			if( $i == $global_key ):
				$test_block_type = preg_replace( '/_block/','',get_row_layout());
				$args = call_user_func('build_' . $test_block_type . '_layout');
				$args['global_type'] = preg_replace('/-/','_',$test_block_type);
			endif;
			$i++;
		endwhile;
	endif;
	return $args;
}

?>
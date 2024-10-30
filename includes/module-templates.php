<?php
if ( ! defined( 'ABSPATH' ) ) exit;
	function get_all_options_acf($option = 'page-one'){
		global $wpdb;
		$search_term = $wpdb->esc_like($option);
        $search_term = ' \'%' . $option . '%\'';
		$result = $wpdb->get_results('select * from wp_options where option_name LIKE '.$search_term);
		return $result;
	}
	function create_new_page_from_template(){
		$title = sanitize_text_field($_POST['pilot-template-option-title']);
		$template =  sanitize_text_field($_POST['template-page']);
		$post_def = [
			'post_type' => 'page',
			'post_title' => $title
		];
		$post_id = wp_insert_post($post_def);
		$block = 'content';
		// this needs to be dynamic!!!!!!!!!!!!!!!!
		$this_block = $template;
		$options = array_reverse(get_all_options_acf($this_block));
		$iterations = [];
		$blocks_arr = [];
		foreach($options as $option){
			$name = $option->option_name;
			if( preg_match('/_block_title/', $name) && preg_match('/_options/', $name)){
				preg_match_all('!\d+!', $name, $iterations);
				$iteration = $iterations[0][0];	
				$block_generic_name = preg_replace('!\d+!', '',$name);
				$block_generic_name = preg_replace('/'.$this_block.'_blocks_/', '', $block_generic_name);
				$block_generic_name = preg_replace('/_block_title/', '', $block_generic_name);
				$block_generic_name = preg_replace('/_options__/', '', $block_generic_name);
				$block_generic_name = preg_replace('/options__/', '', $block_generic_name);
				$blocks_arr[$iteration] = $block_generic_name."_block";
			}
			$meta_key = preg_replace('/options_'.$this_block.'/', $block, $option->option_name);
			$meta_value = $option->option_value;
			add_post_meta($post_id, $meta_key, $meta_value, true);
		}
		sort($blocks_arr);
		update_post_meta( $post_id, $meta_key, $meta_value );
		update_post_meta( $post_id, $block.'_blocks', serialize($blocks_arr) );
		$url = "/wp-admin/post.php?post=".$post_id."&action=edit";
		header("Location: " . $url);
		die();
	}
	// template page creation function is in /modules/admin-modules.js - form is created and submitted from acf message field
	if(array_key_exists('pilot-template-option-name', $_POST)){
		add_action('wp_loaded','create_new_page_from_template');
	}
$options_orig = get_all_options_acf('content');
//print_r($options_orig);

?>
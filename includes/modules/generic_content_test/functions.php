<?php
if ( ! defined( 'ABSPATH' ) ) exit;
	function build_generic_content_test_layout(){
		$args = array(
			'title' => get_sub_field('generic_content_block_title'),
			'content' => mason_get_sub_field('generic_content_block_content'),
		);
		return $args;
	}
?>
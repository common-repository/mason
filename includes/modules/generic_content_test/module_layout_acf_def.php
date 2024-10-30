<?php
if ( ! defined( 'ABSPATH' ) ) exit;
	global $pilot;
	// add module layout to flexible content 
	$module_layout = array (
		'key' => '569d9ff728231',
		'name' => 'generic_content_block',
		'label' => 'Generic Content',
		'display' => 'block',
		'sub_fields' => array (
			array (
				'key' => 'field_569da00928232',
				'label' => 'Content',
				'name' => 'generic_content_block_content',
				'type' => 'wysiwyg',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => 1,
			),
		),
		'min' => '',
		'max' => '',
	);
?>
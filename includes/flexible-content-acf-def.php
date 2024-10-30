<?php
if ( ! defined( 'ABSPATH' ) ) exit;
	if( function_exists('acf_add_local_field_group') ):
		acf_add_local_field_group(array (
			'key' => 'group_57af2997e0601',
			'title' => 'Module Containers',
			'fields' => array (
				array (
					'key' => 'field_57af29a6129a2',
					'label' => 'Containers',
					'name' => 'mason_module_containers',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'collapsed' => '',
					'min' => '',
					'max' => '',
					'layout' => 'table',
					'button_label' => 'Add Row',
					'sub_fields' => array (
						array (
							'key' => 'field_57af29e8129a3',
							'label' => 'Container Name',
							'name' => 'container_name',
							'type' => 'text',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
							'readonly' => 0,
							'disabled' => 0,
						),
						array (
							'key' => 'field_57af2b198c063',
							'label' => 'Container Slug',
							'name' => 'container_slug',
							'type' => 'text',
							'instructions' => '',
							'required' => '',
							'conditional_logic' => '',
							'wrapper' => array (
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
							'readonly' => 0,
							'disabled' => 0,
						),
						array (
						'key' => create_key('included_modules'),
						'label' => 'Included Modules',
						'name' => 'included_modules',
						'type' => 'select',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => array (
						),
						'default_value' => array (
						),
						'allow_null' => 0,
						'multiple' => 1,
						'ui' => 1,
						'ajax' => 1,
						'placeholder' => '',
						'disabled' => 0,
						'readonly' => 0,
					),
					array (
						'key' => create_key('_locations'),
						'label' => 'Locations',
						'name' => '_locations',
						'type' => 'select',
						'instructions' => 'Set where this container will show up in the admin section.',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => array (
							'page' => 'Show on All Pages',
							'post' => 'Show on All Posts',
							'menus' => 'Show on Menus Option Page',
							'header' => 'Show on Header Option Page',
							'footer' => 'Show on Footer Option Page'
						),
						'default_value' => array (
						),
						'allow_null' => 0,
						'multiple' => 1,
						'ui' => 1,
						'ajax' => 1,
						'placeholder' => '',
						'disabled' => 0,
						'readonly' => 0,
					),

					),
				),
			),
			'location' => array (
				array (
					array (
						'param' => 'options_page',
						'operator' => '==',
						'value' => 'mason-settings',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => 1,
			'description' => '',
		));
		$containers = $this->get_containers();
		$container_arr = [];
		if( is_array($containers)){
			foreach($containers as $container){
				$slug = $container['container_slug'];
				$name = $container['container_name'];
				$container_arr[$slug] = $name;
			}
			acf_add_local_field_group(array(
				'key' => 'group_5afb7cb517efc',
				'title' => 'Template Pages',
				'fields' => array(
					array(
						'key' => 'field_5afb7cd864da4',
						'label' => 'Pages',
						'name' => 'pages',
						'type' => 'repeater',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'collapsed' => '',
						'min' => 0,
						'max' => 0,
						'layout' => 'table',
						'button_label' => '',
						'sub_fields' => array(
							array(
								'key' => 'field_5afb7d0b061ba',
								'label' => 'Page Name',
								'name' => 'page_name',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'placeholder' => '',
								'prepend' => '',
								'append' => '',
								'maxlength' => '',
							),
							array(
								'key' => 'field_5afb7d17061bb',
								'label' => 'Page Slug',
								'name' => 'page_slug',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'placeholder' => '',
								'prepend' => '',
								'append' => '',
								'maxlength' => '',
							),
							array(
								'key' => 'field_5b04d354d6ff0',
								'label' => 'Include Modules from Container',
								'name' => 'page_container',
								'type' => 'select',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'choices' => $container_arr,
								'default_value' => array(
								),
								'allow_null' => 0,
								'multiple' => 0,
								'ui' => 0,
								'ajax' => 0,
								'return_format' => 'value',
								'placeholder' => '',
							),
						),
					),
				),
				'location' => array(
					array(
						array(
							'param' => 'options_page',
							'operator' => '==',
							'value' => 'template-settings',
						),
					),
				),
				'menu_order' => 0,
				'position' => 'normal',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
				'active' => 1,
				'description' => '',
			));
		}
		else{
			echo "No Containers - provide instructions!";
		}

	endif;

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5b2e8257b1bb4',
	'title' => 'Settings',
	'fields' => array(
		array(
			'key' => 'field_5b2e82730edb2',
			'label' => 'Include Multiple Languages?',
			'name' => 'include_multiple_languages',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '25',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5b2e8786dd201',
			'label' => 'Included Languages',
			'name' => 'included_languages',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5b2e82730edb2',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '75',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'eng' => 'English',
				'fra' => 'Francais',
				'esp' => 'Espanol',
			),
			'default_value' => array(
			),
			'allow_null' => 1,
			'multiple' => 1,
			'ui' => 1,
			'ajax' => 0,
			'return_format' => 'value',
			'placeholder' => '',
		),
		array(
			'key' => 'field_5b31a65f2d55b',
			'label' => '',
			'name' => '',
			'type' => 'message',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '100',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'new_lines' => 'wpautop',
			'esc_html' => 0,
		),
		array(
			'key' => 'field_5b2e87e9d13b4',
			'label' => 'Remove Default Content Editor',
			'name' => 'remove_default_content_editor',
			'type' => 'true_false',
			'instructions' => 'If you are adding your output function in the file (in page.php, single.php, etc) and not as a shortcode, we recommend hiding the default editor to present a cleaner more intuitive admin area.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '25',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5b2efab92290c',
			'label' => 'Page and Posts to Remove Editor',
			'name' => 'page_and_posts_to_remove_editor',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5b2e87e9d13b4',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '75',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'pages' => 'Pages',
				'posts' => 'Posts',
			),
			'default_value' => array(
			),
			'allow_null' => 1,
			'multiple' => 1,
			'ui' => 1,
			'ajax' => 0,
			'return_format' => 'value',
			'placeholder' => '',
		),
		array(
			'key' => 'field_5b31a6f0239f5',
			'label' => '',
			'name' => '',
			'type' => 'message',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '100',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'new_lines' => 'wpautop',
			'esc_html' => 0,
		),
		array(
			'key' => 'field_5b3115b22a2f6',
			'label' => 'Advanced Settings',
			'name' => 'advanced_settings',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => 'Hide',
			'ui_off_text' => 'Show',
		),
		array(
			'key' => create_key('settings','show_styling'),
			'label' => 'Add Custom Styling Options to All Modules ',
			'name' => 'styling_option',
			'type' => 'true_false',
			'instructions' => 'When set to "Add Styling" and a Styling ACF file is added to the theme\'s mason-modules, those ACF fields will be added to ALL modules.',
			'required' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 1,
			'ui_on_text' => 'Add Styling',
			'ui_off_text' => 'Hide Styling',
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5b3115b22a2f6',
						'operator' => '==',
						'value' => '1',
					),
				),
			),

		),		
		array(
			'key' => 'field_5b310b217f73c',
			'label' => 'Custom Post Type/ACF Field Location Data',
			'name' => 'acf_field_locations',
			'type' => 'repeater',
			'instructions' => 'When exporting the php code for a field group, the group is "attached" to certain edit screens by the "Location" parameter. In the event Mason cannot identify all possible Locations, add yours here to include in select lists.',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_5b3115b22a2f6',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 0,
			'max' => 0,
			'layout' => 'table',
			'button_label' => '',
			'sub_fields' => array(
				array(
					'key' => 'field_5b310c421fc41',
					'label' => 'Label',
					'name' => 'label',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '25',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_5b310bd37f73d',
					'label' => 'Param',
					'name' => 'param',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '25',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_5b310bdf7f73e',
					'label' => 'Operator',
					'name' => 'operator',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '25',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_5b310be77f73f',
					'label' => 'Value',
					'name' => 'value',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '25',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'mason-settings',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;
	

?>
<?php
defined('ABSPATH') or die("Cannot access pages directly.");



/*
 * Define meta boxes
 */

$theme_meta[] = array(

	'name' => 'Page Options',
	'post_type' => array('page'),
	'id' => 'overview_page_options',
	'priority' => 'high',
	'location' => 'normal',
	'metabox_text' => 'Description of what this meta box is for.',

	'fields' => array(


		array(

			'type' => 'text',
			'name' => 'text_example',
			'id' => 'text_example',
			'label' => 'Text Example',
			'desc' => 'This is some description text.'


		),
		array(

			'type' => 'email',
			'name' => 'email_example',
			'id' => 'email_example',
			'label' => 'Email Example',
			'desc' => 'This is some description text.'


		),
		array(

			'type' => 'select',
			'name' => 'select_test',
			'id' => 'select_test',
			'label' => 'Select Example',
			'desc' => 'Example of select.',
			'options' => array(

				'red' => 'Red',
				'blue' => 'Blue'

			)



		),

		array(

			'type' => 'wysiwyg',
			'name' => 'wysiwyg_test',
			'id' => 'wysiwyg_test',
			'label' => 'Editor Test',
			'desc' => 'Full WP Editor'



		),
		array(

			'type' => 'upload',
			'name' => 'upload_example',
			'id' => 'upload_example',
			'label' => 'Upload Example',
			'desc' => 'This is some description text.'


		),







	)


);
<?php
defined('ABSPATH') or die("Cannot access pages directly.");


/*
 * Build our sections and fields for our theme options
 */


$theme_fields[] = array(

	'name' => 'Branding',
	'id' => 'branding',
	'overview_text' => 'Manage your branding like logos and icons',
	'icon' => 'dashicons-format-gallery',

	'fields' => array(


		array(

			'type' => 'upload',
			'name' => 'logo',
			'class' => 'input-text',
			'id' => 'logo',
			'placeholder' => 'Upload your logo',
			'label' => 'Main Logo',
			'desc' => 'The main logo for your website'


		),



		array(

			'type' => 'upload',
			'name' => 'logo_mobile',
			'class' => 'input-text',
			'id' => 'logo_mobile',
			'placeholder' => 'Upload your logo',
			'label' => 'Mobile Logo',
			'desc' => 'The mobile logo for your website'


		),

		array(

			'type' => 'upload',
			'name' => 'favicon',
			'class' => 'input-text',
			'id' => 'favicon',
			'placeholder' => 'Test',
			'label' => 'Favicon',
			'desc' => 'The favicon for your website png or ico'


		),


	)


);



$theme_fields[] = array(

	'name' => 'Archive Style',
	'id' => 'archives',
	'overview_text' => 'Manage your archive look',
	'icon' => 'dashicons-layout',

	'fields' => array(
		array(

			'type' => 'upload_bg',
			'name' => 'video_tax_bg',
			'class' => 'input-text',
			'id' => 'video_tax_bg',
			'placeholder' => '',
			'label' => 'Background Image',
			'desc' => 'Header image for video page',
			'css' => array(

				'selector' => 'body.post-type-archive-video .featured-image',
				'property' => 'background'

			)


		),
		array(

			'type' => 'upload_bg',
			'name' => 'testimonial_tax_bg',
			'class' => 'input-text',
			'id' => 'testimonial-tax_bg',
			'placeholder' => '',
			'label' => 'Background Image',
			'desc' => 'Header image for testimonial page',
			'css' => array(

				'selector' => 'body.post-type-archive-testimonial .featured-image',
				'property' => 'background'

			)



		),
		array(

			'type' => 'upload_bg',
			'name' => 'blog_tax_bg',
			'class' => 'input-text',
			'id' => 'blog_tax_bg',
			'placeholder' => '',
			'label' => 'Background Image',
			'desc' => 'Header image for blog page',

			'css' => array(


				'selector' => 'body.blog .featured-image, body.single-post .featured-image',
				'property' => 'background'

			),



		),

	)


);



$theme_fields[] = array(

	'name' => 'Footer Style',
	'id' => 'footer',
	'overview_text' => 'Manage your footer layout',
	'icon' => 'dashicons-layout',

	'fields' => array(


		array(

			'type' => 'number',
			'name' => 'footer_height',
			'class' => 'input-text',
			'id' => 'Min Footer_height',
			'placeholder' => '',
			'label' => 'Footer height',
			'desc' => 'Enter your minimum footer height in px, it will stretch if contents are higher.',



		),


		array(

			'type' => 'select',
			'name' => 'footer_layout',
			'class' => 'input-text',
			'id' => 'footer_layout',
			'placeholder' => '',
			'label' => 'Footer Layout',
			'desc' => 'Choose a layout for your footer',
			'options' => array(

				'boxed' => 'Boxed',
				'wide' => 'Wide',




			)


		),


		array(

			'type' => 'upload_bg',
			'name' => 'footer_bg_image',
			'class' => 'input-text',
			'id' => 'footer_bg_image',
			'placeholder' => '',
			'label' => 'Footer Background',
			'desc' => 'Upload a footer background image',
			'css' => array(

				'selector' => 'footer.site-footer',
				'property' => 'background'

			)



		),

		array(

			'type' => 'color',
			'name' => 'footer_bg_color',
			'class' => 'input-text',
			'id' => 'footer_bg_color',
			'placeholder' => '#FFFFFF',
			'label' => 'Footer Background Color',
			'desc' => '',
			'css' => array(

				'selector' => 'footer.site-footer',
				'property' => 'background-color'

			)


		),








	)


);



$theme_fields[] = array(

	'name' => 'Content Style',
	'id' => 'content',
	'overview_text' => 'Manage your themes colour and styles',
	'icon' => 'dashicons-admin-appearance',

	'fields' => array(


		array(

			'type' => 'color',
			'name' => 'body_bg',
			'class' => 'input-text',
			'id' => 'body_bg',
			'placeholder' => '#FFFFFF',
			'label' => 'Background Colour',
			'desc' => 'The main body background color',
			'css' => array(

				'selector' => 'body',
				'property' => 'background-color'

			)


		),
		array(

			'type' => 'upload_bg',
			'name' => 'body_bg_image',
			'class' => 'input-text',
			'id' => 'body_bg_image',
			'placeholder' => '',
			'label' => 'Body Background',
			'desc' => 'Upload a main background image',
			'css' => array(

				'selector' => 'body',
				'property' => 'background-image'

			)



		),


	)


);


$theme_fields[] = array(

	'name' => 'Fonts',
	'id' => 'fonts',
	'overview_text' => 'Manage your themes typography',
	'icon' => 'dashicons-editor-textcolor',


	'fields' => array(


		array(

			'type' => 'font',
			'name' => 'body_font',
			'id' => 'body_font',
			'label' => 'Body Font',
			'options' => array(

				'font_size' => true,
				'color' => true,
				'enqueue' => true,

			),

			'css' => array(

				'selector' => 'body',
				'property' => 'font'

			)

		),

		array(

			'type' => 'font',
			'name' => 'h1_font',
			'id' => 'h1_font',
			'label' => 'Heading 1 Font',
			'enqueue' => true,

			'options' => array(

				'font_size' => true,
				'color' => true,
				'enqueue' => true

			),
			'css' => array(

				'selector' => 'h1',
				'property' => 'font'

			)

		),


		array(

			'type' => 'font',
			'name' => 'h2_font',
			'id' => 'h2_font',
			'label' => 'Heading 2 Font',
			'options' => array(

				'font_size' => true,
				'color' => true,
				'enqueue' => true,


			),
			'css' => array(

				'selector' => 'h2',
				'property' => 'font'

			)

		),

		array(

			'type' => 'font',
			'name' => 'h3_font',
			'id' => 'h3_font',
			'label' => 'Heading 3 Font',
			'options' => array(

				'font_size' => true,
				'color' => true,
				'enqueue' => true,


			),
			'css' => array(

				'selector' => 'h3',
				'property' => 'font'

			)

		),

		array(

			'type' => 'font',
			'name' => 'h4_font',
			'id' => 'h4_font',
			'label' => 'Heading 4 Font',
			'options' => array(
				'font_size' => true,
				'color' => true,
				'enqueue' => true,
			),
			'css' => array(

				'selector' => 'h4',
				'property' => 'font'

			)

		),

		array(

			'type' => 'font',
			'name' => 'h5_font',
			'id' => 'h5_font',
			'label' => 'Heading 5 Font',
			'options' => array(

				'font_size' => true,
				'color' => true,
				'enqueue' => true,

			),
			'css' => array(

				'selector' => 'h5',
				'property' => 'font'

			)

		),




	),


);



$theme_fields[] = array(

	'name' => 'Custom JS &amp; CSS',
	'id' => 'custom',
	'overview_text' => 'Manage advanced settings like custom Javascript, CSS &amp; Google Maps API',
	'icon' => 'dashicons-editor-code',

	'fields' => array(


		array(

			'type' => 'textarea',
			'name' => 'custom_css',
			'class' => 'input-text',
			'id' => 'custom_css',
			'placeholder' => 'Paste your css here',
			'label' => 'Custom CSS',
			'desc' => 'Custom CSS styles, no need for opening or closing tags',




		),

		array(

			'type' => 'textarea',
			'name' => 'custom_js',
			'class' => 'input-text',
			'id' => 'custom_js',
			'placeholder' => 'Paste you javascript or tracking code here',
			'label' => 'Custom JS',
			'desc' => 'Custom JS styles, no need for opening or closing tags'


		),


		array(

			'type' => 'text',
			'name' => 'google_maps_api',
			'class' => 'input-text',
			'id' => 'google_maps_api',
			'placeholder' => '',
			'label' => 'Google Maps API',
			'desc' => 'Get your API for Google Maps <a href="https://console.developers.google.com" target="_blank">here</a>'


		)


	)


);
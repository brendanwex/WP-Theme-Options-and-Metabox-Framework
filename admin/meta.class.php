<?php

/**
 * @User Brendan
 * @File meta.class.php
 * @Date 12-Dec-16  12:10 PM
 * @Version
 */
class adminMeta extends adminOptions
{


	function __construct() {

		add_action('add_meta_boxes', array($this,'loadMetaBoxes'));
		add_action( 'save_post', array($this, 'metaBoxSave' ), 1);

	}


	/**
	 * Loops though metaboxes.php and loads metaboxes using wp action add_meta_box
	 */
	function loadMetaBoxes(){


		global $theme_meta;

		foreach($theme_meta as $meta) {
			add_meta_box($meta['id'], $meta['name'], array($this, 'metaBoxHTML'), $meta['post_type'], $meta['location'], $meta['priority'], array('id' => $meta['id']));

		}

	}


	/**
	 * Loops though metaboxes.php and generates the fields
	 */
	function metaBoxHTML($post, $callback_args){
		global $theme_meta, $post;
		$meta_args = array_map( 'esc_html', $callback_args['args'] );
		wp_nonce_field( '_'.$meta_args['id'].'_nonce', $meta_args['id'].'_nonce' );


		echo "<div class='theme-options-metabox-wrapper'>";


		foreach($theme_meta as $meta) {

			if($meta['id'] == $meta_args['id']) {

				if (!empty($meta['metabox_text'])) {
					echo "<p class='meta-overview-text'>{$meta['metabox_text']}</p>";
				}


				foreach ($meta['fields'] as $fields) {


					if (!empty($fields['options'])) {
						$options = $fields['options'];
					} else {
						$options = "";
					}

					if (!isset($fields['type'])) {
						$fields['type'] = "";
					}
					if (!isset($fields['name'])) {
						$fields['name'] = "";
					}
					if (!isset($fields['id'])) {
						$fields['id'] = "";
					}
					if (!isset($fields['class'])) {
						$fields['class'] = "";
					}
					if (!isset($fields['placeholder'])) {
						$fields['placeholder'] = "";
					}
					if (!isset($fields['label'])) {
						$fields['label'] = "";
					}
					if (!isset($fields['desc'])) {
						$fields['desc'] = "";
					}

					$this->theme_admin_build_fields($fields['type'], $fields['id'], $fields['name'], $fields['class'], $fields['placeholder'], $fields['label'], $fields['desc'], $options, $meta['id']);


				}

				echo "<input type='hidden' name='admin_options_metabox_id[]' value=\"$meta[id]\" />";

			}

		}



		echo "</div>";






	}



	/**
	 * Save metaboxe
	 */
	function metaBoxSave($post_id){

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( ! current_user_can( 'edit_post', $post_id ) ) return;



		isset($_POST['admin_options_metabox_id']) ? $metaboxes = $_POST['admin_options_metabox_id'] : $metaboxes = '';



		if(!empty($metaboxes)) {

			foreach ($metaboxes as $metabox) {

				update_post_meta($post_id, $metabox, $_POST[$metabox]);


			}

		}












	}


}
$adminMeta = new adminMeta();
<?php
defined('ABSPATH') or die("Cannot access pages directly.");
/**
 * Class adminOptions
 */


class adminOptions
{


	/**
	 * adminOptions constructor.
	 */
	function __construct() {

		add_action( 'admin_enqueue_scripts', array($this, 'theme_admin_assets'));
		if(!ADMIN_OPTIONS_DISABLE) {
			add_action('admin_menu', array($this, 'theme_admin_init_menu'));
			add_action('admin_bar_menu', array($this, 'theme_admin_init_menu_toolbar'), 999);
		}
		add_action('wp_ajax_do_theme_restore', array($this, 'theme_admin_options_restore'));
		add_action('wp_ajax_do_theme_backup', array($this, 'theme_admin_options_backup_download'));
		add_action( 'wp_head', array($this, 'theme_admin_options_generate_css' ));
	    add_action('wp_footer', array($this, 'theme_admin_options_auto_enqueue_gfonts'));


	}


	/**
	 * Load our js and css assets
	 */
	function theme_admin_assets(){

			wp_enqueue_style('jquery-ui-css', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
			wp_enqueue_style('theme-admin-css-combined', get_stylesheet_directory_uri() . '/admin/assets/css/combined.css', false, '1.0.0');
			wp_enqueue_style('theme-admin-css', get_stylesheet_directory_uri() . '/admin/assets/css/admin.css', false, '1.0.0');
		    wp_enqueue_script('theme-js-combined', get_stylesheet_directory_uri() . '/admin/assets/js/combined.js', array('jquery'), false, '1.0.0');
			wp_enqueue_script('theme-admin-js', get_stylesheet_directory_uri() . '/admin/assets/js/admin.js', array('jquery'), false, '1.0.0');
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script( 'jquery-ui-datepicker', array( 'jquery' ) );



	}


	/**
	 * Initiate our admin menu under "Appearance" menu
	 */
	function theme_admin_init_menu(){

		add_submenu_page('themes.php', ADMIN_OPTIONS_TITLE_LABEL, ADMIN_OPTIONS_MENU_LABEL, 'manage_options', ADMIN_OPTIONS_PAGE_SLUG, array($this, 'theme_admin_options_page'));
		add_action('admin_init', array($this, 'theme_admin_settings_init'));

	}


	/**
	 * Load our "Theme Options" link in WP admin bar
	 * @param $wp_admin_bar
	 */
	function theme_admin_init_menu_toolbar($wp_admin_bar){

		$args = array(
			'id' => 'theme-admin-options',
			'title' => ADMIN_OPTIONS_MENU_LABEL,
			'href' => admin_url('themes.php?page='.ADMIN_OPTIONS_PAGE_SLUG),
			'meta' => array(
				'title' => ADMIN_OPTIONS_MENU_LABEL
			)
		);
		$wp_admin_bar->add_node($args);



	}


	/**
	 * Initiate our theme options db field name
	 */
	function theme_admin_settings_init(){

		register_setting('admin-options-theme-setting', ADMIN_OPTIONS_OPTION_NAME);



	}


	/**
	 * Build our nav using fields from fields.php
	 */
	function theme_admin_options_ui_tabs(){


		global $theme_fields;

		$output = "";


		foreach($theme_fields as $section){

			$output .= "<li data-tab='{$section['id']}' title='{$section['overview_text']}'>{$section['name']}</li>";



		}


		echo $output;


	}


	/**
	 * Build our content sections using fields from fields.php
	 */
	function theme_admin_options_ui_content(){


		global $theme_fields;



		foreach($theme_fields as $section){

			echo "<div class='tab-content'>";


			foreach($section['fields'] as $fields){

				if(!empty($fields['options'])){
					$options = $fields['options'];
				}else{
					$options = "";
				}

				if(!isset($fields['type'])) { $fields['type'] = "";}
				if(!isset($fields['name'])) { $fields['name'] = "";}
				if(!isset($fields['id'])) { $fields['id'] = "";}
				if(!isset($fields['class'])) { $fields['class'] = "";}
				if(!isset($fields['placeholder'])) { $fields['placeholder'] = "";}
				if(!isset($fields['label'])) { $fields['label'] = "";}
				if(!isset($fields['desc'])) { $fields['desc'] = "";}


				$this->theme_admin_build_fields($fields['type'], $fields['id'], $fields['name'], $fields['class'], $fields['placeholder'], $fields['label'], $fields['desc'], $options);

			}


			echo  "</div>";



		}




	}

	/**
	 * Build our overview tab using fields from fields.php
	 */
	function theme_admin_options_overview(){

		global $theme_fields;

		$output = "<div class=\"section group\">";


		foreach($theme_fields as $section){





				$output .= "<div class='overview-panel col span_1_of_3'><a href='#{$section['id']}'><h1><span class='dashicons {$section['icon']}'></span></h1><h2>{$section['overview_text']}</h2></a></div>";







		}

		$output .= "<div class='overview-panel col span_1_of_3'><a href='#export'><h1><span class='dashicons dashicons-shield'></span></h1><h2>Backup &amp; Restore your settings</h2></a></div>";


		$output .= "</div>";

		echo $output;

	}


	/**
	 * Our import / export content area
	 */
	function theme_admin_options_backup(){



		$output = "<div class='form-row'><label><span>Export Settings</span><button type='button' class='do-theme-backup btn-submit'>Generate Back Up</button><button type='button' class='do-theme-backup-action' style='display:none;'  data-ajax='".admin_url('admin-ajax.php')."?action=do_theme_backup'>Download Back Up</button></label></div>";

		$output .= "<div class='form-row'><label><span>Import Settings</span><textarea name='import_settings' id='import_settings'></textarea><br /><button type='button' class='do-theme-restore' data-ajax='".admin_url('admin-ajax.php')."?action=do_theme_restore'>Restore Settings</button></label></div>";

		echo $output;







	}


	/**
	 * Our ajax action for restoring our settings
	 */
	function theme_admin_options_restore(){

		global $wpdb;

		isset($_POST['import_settings']) ? $import_settings = trim($_POST['import_settings']) : $import_settings = '';

		if(!empty($import_settings)){

			$import = base64_decode($import_settings);





			$option_name = ADMIN_OPTIONS_OPTION_NAME;

			if ( get_option( $option_name ) !== false ) {

				$table = $wpdb->prefix."options";

				$wpdb->update($table, array('option_value' => $import), array('option_name' => $option_name));

				echo "success";


			} else {

				echo "error";


			}







		}else{


			echo "error";
		}


		wp_die();




	}

	/**
	 * Our ajax action for downloading a backup
	 */
	function theme_admin_options_backup_download(){


		if(get_option(ADMIN_OPTIONS_OPTION_NAME) !== false) {


			$export_settings = serialize(get_option(ADMIN_OPTIONS_OPTION_NAME));

			$handle = fopen("theme_backup.txt", "w");
			fwrite($handle, base64_encode($export_settings));
			fclose($handle);

			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename('theme_backup.txt'));
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize('theme_backup.txt'));
			readfile('theme_backup.txt');

		}else{

			echo "Sorry no options saved yet";

		}

		wp_die();




	}



	/**
	 *
	 * Build our input types and output the,
	 *
	 * @param string $type - the input type
	 * @param string $id - the id attribute of the input
	 * @param string $name - the name attribute of the input
	 * @param string $class - the class attribute of the input
	 * @param string $placeholder - the placeholder attribute
	 * @param string $label - The label for the input
	 * @param string $desc - The help text or html for the html type input
	 * @param array  $options - List of options for the select input type and gfonts input type
	 * @param string  $is_meta - passes the metabox id if it is a  metabox, otherwise is empty
	 * @return string
	 */
	function theme_admin_build_fields($type="", $id="", $name="", $class="", $placeholder="", $label="", $desc="", $options, $is_meta=""){




		if(!empty($is_meta)){
			$field_name = $is_meta;
			$args = array('field_name' => $is_meta);
		}else{
			$field_name = ADMIN_OPTIONS_OPTION_NAME;
			$args = "";
		}

		switch($type){

		case "text":


		    if($is_meta){
				echo "<p><label>$label</label></p><input type='text' name='".$field_name."[{$name}]' id='{$id}' class='{$class}' placeholder='{$placeholder}' value='{$this->theme_opt($name, $args)}' /><p class='field-desc'>$desc</p>";

			}else{
				echo "<div class='form-row'><label><span>$label</span><input type='text' name='".$field_name."[{$name}]' id='{$id}' class='{$class}' placeholder='{$placeholder}' value='{$this->theme_opt($name, $args)}' /></label><div class='field-desc'>$desc</div></div>";

			}


		break;

		case "number":


		    if($is_meta){
				echo "<p><label>$label</label></p><input type='number' name='".$field_name."[{$name}]' id='{$id}' class='{$class}' placeholder='{$placeholder}' value='{$this->theme_opt($name, $args)}' /><p class='field-desc'>$desc</p>";

			}else{
				echo "<div class='form-row'><label><span>$label</span><input type='number' name='".$field_name."[{$name}]' id='{$id}' class='{$class}' placeholder='{$placeholder}' value='{$this->theme_opt($name, $args)}' /></label><div class='field-desc'>$desc</div></div>";

			}


		break;

		case "date":

		    if($is_meta){
				echo "<p class='form-row'><label>$label</label></p><input type='date' name='".$field_name."[{$name}]' id='{$id}' class='{$class}' placeholder='{$placeholder}' value='{$this->theme_opt($name, $args)}' /><p class='field-desc'>$desc</p>";

			}else{
				echo "<div class='form-row'><label><span>$label</span><input type='date' name='".$field_name."[{$name}]' id='{$id}' class='{$class}' placeholder='{$placeholder}' value='{$this->theme_opt($name, $args)}' /></label><div class='field-desc'>$desc</div></div>";

			}



		$date_option = "";
		if(!empty($options)){
			$date_option = "{";
			foreach($options as $option => $value){

				$date_option .=  "$option : '$value',";

			}
			$date_option .= "}";
		}
		echo "  <script>
				jQuery( document ).ready(function() {
				jQuery( \"#$id\" ).datepicker($date_option);
				});
				</script>";
		break;

		case "email":


		    if($is_meta){
				echo "<p<label>$label</p><input type='email' name='".$field_name."[{$name}]' id='{$id}' class='{$class}' placeholder='{$placeholder}' value='{$this->theme_opt($name, $args)}' /><p class='field-desc'>$desc</p>";

			}else{
				echo "<div class='form-row'><label><span>$label</span><input type='email' name='".$field_name."[{$name}]' id='{$id}' class='{$class}' placeholder='{$placeholder}' value='{$this->theme_opt($name, $args)}' /></label><div class='field-desc'>$desc</div></div>";

			}

		break;


		case "textarea":

		    if($is_meta){
				echo "<p><label>$label</label></p><textarea name='".$field_name."[{$name}]' id='{$id}' class='{$class}' placeholder='{$placeholder}'>".$this->theme_opt($name, $args)."</textarea><p class='field-desc'>$desc</p>";

			}else{
				echo "<div class='form-row'><label><span>$label</span><textarea name='".$field_name."[{$name}]' id='{$id}' class='{$class}' placeholder='{$placeholder}'>".$this->theme_opt($name, $args)."</textarea></label><div class='field-desc'>$desc</div></div>";

			}


		break;


		case "wysiwyg":

		    if($is_meta){
				echo "<p><label>$label</label></p>";
				$editor_name = $field_name."[".$name."]";
				wp_editor( $this->theme_opt($name, $args), $id, array('textarea_name' => $editor_name) );
				echo "<p class='field-desc'>$desc</p>";
            }else{

				echo "<div class='form-row'><label><span>$label</span>";
				$editor_name = $field_name."[".$name."]";
				wp_editor( $this->theme_opt($name, $args), $id, array('textarea_name' => $editor_name) );
				echo "</label><div class='field-desc'>$desc</div></div>";

            }



		break;

		case "color":

		    if($is_meta){
				echo "<p><label>$label</label></p><input type='text' name='".$field_name."[{$name}]' id='{$id}' class='theme-admin-color-picker {$class}' placeholder='{$placeholder}' value='{$this->theme_opt($name, $args)}' /><p class='field-desc'>$desc</p>";

			}else{
				echo "<div class='form-row'><label><span>$label</span><input type='text' name='".$field_name."[{$name}]' id='{$id}' class='theme-admin-color-picker {$class}' placeholder='{$placeholder}' value='{$this->theme_opt($name, $args)}' /></label><div class='field-desc'>$desc</div></div>";

			}


		break;

		case "upload":


		    if($is_meta){
				echo "<p><label>$label</label></p><input type='text' name='".$field_name."[{$name}]' id='{$id}' class='{$class}' placeholder='{$placeholder}' value='{$this->theme_opt($name, $args)}' /> <button type='button' class='button theme-options-uploader' data-return='{$id}'>Upload</button>";

			}else{
				echo "<div class='form-row'><label><span>$label</span><input type='text' name='".$field_name."[{$name}]' id='{$id}' class='{$class}' placeholder='{$placeholder}' value='{$this->theme_opt($name, $args)}' /> <button type='button' class='btn-submit theme-options-uploader' data-return='{$id}'>Upload</button>";

			}
			echo  "<div class='img-preview {$id}-preview'>";
			if(!empty($this->theme_opt($name, $args))){
				echo "<img src='{$this->theme_opt($name, $args)}' alt='preview' />";
			}
			echo "</div>";

			if($is_meta){
				echo "<p class='field-desc'>$desc</p>";

			}else{
				echo "</label><div class='field-desc'>$desc</div></div>";

			}

		break;

		case "upload_repeater":
			echo "<div class='form-row'>";
			if(empty($this->theme_opt($name, $args))) {

				echo "<label><span>$label</span>";
				echo "<input type='text' name='" . $field_name . "[{$name}][]' id='{$id}' class='{$class}' placeholder='{$placeholder}' value='' /> <button type='button' class='btn-submit theme-options-uploader' data-return='{$id}'>Upload</button>";
				echo "<div class='img-preview {$id}-preview'>";
				if (!empty($this->theme_opt($name, $args))) {
					echo "<img src='{$this->theme_opt($name, $args)}' alt='preview' />";
				}
				echo "</div>";
				echo "<div id='upload-repeater'>";
				echo "</div>";

				echo "</label>";



			}else{
				$images = (array) $this->theme_opt($name, $args);
				$i = 0;
				$count = count($images);
				foreach($images as $img => $val){
					$i++;
					if($i == 1) {
						//if first field show label
						echo "<label><span>$label</span>";

					}else{
						if($i == 2){
							//If second filed add this div so we can drag images
							echo "<div id='upload-repeater'>";

						}
						//If not the first add empty label
						echo "<div class='repeater-row'><label><span></span>";

					}
					//Field
					echo"<input type='text' name='" . $field_name . "[{$name}][]' class='{$class}' placeholder='{$placeholder}' value='$val' /> <button type='button' class='btn-submit theme-options-uploader' data-return='{$id}'>Upload</button>";

					//if not the first field, show remove button
					if($i > 1){ echo " <button class='theme-options-repeater-remove' type='button'>Remove</button>";}

					//Preview div
					echo "<div class='img-preview {$id}-preview'>";
					if (!empty(array_filter($this->theme_opt($name, $args)))) {
						echo "<img src='$val' alt='preview' />";
					}

					echo "</div>";

					if($i == 1) {
						//If first field close label
						echo "</label>";

					}else{
						//If not first field close .repeater-row div and label
						echo "</div></label>";

					}
					if($i == $count) {
						//if its the last field close #upload-repeater
						echo "</div>";
					}

				}
			}

			echo "<div class='add-field'><button type='button' class='add-field-btn btn-submit' data-name='".$field_name."[{$name}][]' data-id='{$id}' data-class='{$class}' data-placeholder='{$placeholder}'>Add Another</button></div>";
			echo "<div class='field-desc'>$desc</div>";


			echo "</div>";


			break;

		case "upload_bg":

			echo "<div class='form-row'><label><span>$label</span><input type='text' name='".$field_name."[{$name}]' id='{$id}' class='{$class}' placeholder='{$placeholder}' value='{$this->theme_opt($name, $args)}' />";
			echo " <button type='button' class='btn-submit theme-options-uploader' data-return='{$id}'>Upload</button>";
			if(!empty($this->theme_opt($name, $args))){
			$style = "display:inline;";}else{$style = "display:none";
			}
			echo "<div class='theme-options-bg-layout' style='$style'>";
			echo " <select name='".$field_name."[{$name}_position]' class='admin-options-select'><option value='".$this->theme_opt($name."_position_selected", $args)."' selected>".$this->theme_opt($name."_position_selected", $args)."</option><option value='left top'>left top</option><option value='left center'>left center</option><option value='left bottom'>left bottom</option><option value='right top'>right top</option><option value='right center'>right center</option><option value='right-bottom'>right bottom</option><option value='center top'>center top</option><option value='center center'>center center</option></select><input type='hidden' name='".$field_name."[{$name}_postion_selected]' class='hidden-select' value='".$this->theme_opt($name."_position_selected", $args)."' />";
			echo " <select name='".$field_name."[{$name}_size]' class='admin-options-select'><option value='".$this->theme_opt($name."_size_selected", $args)."' selected>".$this->theme_opt($name."_size_selected", $args)."</option><option value='none'>none</option><option value='cover'>cover</option><option value='contain'>contain</option></select><input type='hidden' name='".$field_name."[{$name}_size_selected]' class='hidden-select' value='".$this->theme_opt($name."_size_selected", $args)."' />";
			echo "</div>";
			echo "<div class='img-preview {$id}-preview'>";
			if(!empty($this->theme_opt($name, $args))){
				echo "<img src='{$this->theme_opt($name, $args)}' alt='preview' />";
			}
			echo "</div>";
			echo "<div class='field-desc'>$desc</div></div></label>";

		break;


		case "select":



		    if($is_meta){
				echo "<p><label>$label</label></p><select name='".$field_name."[{$name}]' id='{$id}' class='admin-options-select {$class}'>";

			}else{
				echo "<div class='form-row'><label><span>$label</span><select name='".$field_name."[{$name}]' id='{$id}' class='admin-options-select {$class}'>";

			}
			echo "<option value='{$this->theme_opt($name, $args)}' selected>".$this->theme_opt($name.'_selected', $args)."</option>";

				foreach($options as $option => $value){

					echo "<option value='$option'>$value</option>";

				}
			echo "</select><input type='hidden' name='".$field_name."[{$name}_selected]' class='hidden-select' value='".$this->theme_opt($name."_selected", $args)."' />";

                if($is_meta){
					echo "<p class='field-desc'>$desc</p>";

				}else{
					echo "</label><div class='field-desc'>$desc</div></div>";

				}

				break;


		/*	case "checkbox":


				echo "<div class='form-row'><label><span>$label</span>";

					foreach ($options as $option => $value) {

						echo "<label class='checkbox-label'><input type='checkbox' name='" . $field_name . "[{$name}][]' value='$option' class='admin-options-select {$class}'> $value </label>";

					}

				echo "</select></label><div class='field-desc'>$desc</div></div>";

				break;

		*/


		case "font":


		    if($is_meta){
		        echo "Font field is not supported in metaboxes yet.";
            }else {

				if (file_exists(get_template_directory() . "/admin/assets/libs/google-fonts.json")) {


					$gfonts_list = file_get_contents(get_template_directory() . "/admin/assets/libs/google-fonts.json");
					$websafe_fonts = array('Georgia, serif', '"Palatino Linotype", "Book Antiqua", Palatino, serif', '"Times New Roman", Times, serif', 'Arial, Helvetica, sans-serif', '"Arial Black", Gadget, sans-serif', '"Lucida Sans Unicode", "Lucida Grande", sans-serif', 'Tahoma, Geneva, sans-serif', '"Trebuchet MS", Helvetica, sans-serif', 'Verdana, Geneva, sans-serif', '"Courier New", Courier, monospace', '"Lucida Console", Monaco, monospace');

					sort($websafe_fonts);


					echo "<div class='form-row'><label><span>$label</span><select name='" . $field_name . "[{$name}]' id='{$id}' class='gfont-select {$class}' placeholder='{$placeholder}'>";

					echo "<option value='{$this->theme_opt($name,$args)}' selected>" . $this->theme_opt($name . '_selected', $args) . "</option>";

					$jsonIterator = json_decode($gfonts_list);

					echo "<optgroup label='Web Safe Fonts'>";

					foreach ($websafe_fonts as $wfont) {

						echo "<option value='$wfont'>$wfont</option>";

					}

					echo "</optgroup>";

					echo "<optgroup label='Google Fonts'>";


					foreach ($jsonIterator as $fnt) {

						echo "<option value='$fnt->family' data-font='web'>$fnt->family</option>";


					}

					echo "</select>";

					echo "</optgroup>";

					echo "<input type='hidden' name='" . $field_name . "[{$name}_selected]' class='hidden-select' value='" . $this->theme_opt($name . "_selected", $args) . "' />";

					foreach ($options as $option => $value) {

						if ($option == 'enqueue' && $value == true) {

							echo "<input type='hidden' name='" . $field_name . "[font_enqueue][]' value='{$this->theme_opt($name."_selected",$args)}' id='hidden-enqueue-{$id}' />";

						}

						if ($option == 'font_size' && $value == true) {

							echo "  <input type='number' size='2' name='" . $field_name . "[{$name}_size]' value='{$this->theme_opt($name."_size",$args)}' /><span class='input-addon'>px</span>";

						}
						if ($option == 'color' && $value == true) {

							echo "  <input type='text' name='" . $field_name . "[{$name}_color]' value='{$this->theme_opt($name."_color",$args)}' class='theme-admin-color-picker' />";

						}

					}

					echo "</label><div class='field-desc'>$desc</div></div>";

				} else {

					echo "Google fonts list is not readable or does not exist. Please check for google-fonts.json file in admin/assets/libs folder.";

				}

			}
				break;


			case "html":

				echo"<div class='form-row'>$desc</div>";

				break;



			default:

				echo "<div class='form-row'><label><span>$label</span><span class='field-error'>Sorry this field type is not supported or you have an error in your code.</span></label></div>";





		}




	}




	/**
	 * Return the value from DB setting
	 * @param $opt - The option name
	 * @return string
	 */
	function theme_opt($opt, $args=""){

		global $post;

		if(empty($args)) {

			$option = get_option(ADMIN_OPTIONS_OPTION_NAME);

			if (isset($option[$opt])) {
				$option_value = $option[$opt];
			} else {
				$option_value = "";
			}

		}else{


			$field_args = (object)$args;


			$field_value = get_post_meta( $post->ID, $field_args->field_name, true );


			if(isset($field_value[$opt])){

				$option_value = $field_value[$opt];

			}else{
				$option_value = "";

			}

		}



	if(is_string($option_value)){
		return stripslashes($option_value);

	}else{
		return $option_value;
	}




	}

	/**
	 * The main options page
	 */
	function theme_admin_options_page(){

		global $adminHelpers;
		?>
		<div class="theme-options-overlay"></div>
		<div class="theme-options-loading"><img src="<?php echo get_stylesheet_directory_uri();?>/admin/assets/img/loading.svg" alt="Loading..." /></div>
		<div class="wrap">



			<div id="theme-admin-messages">

			</div>
			<form method="post"  id="theme-admin-options">
				<?php settings_fields('admin-options-theme-setting'); ?>
				<?php do_settings_sections('admin-options-theme-setting'); ?>
				<div class="theme-options-wrapper">
					<div id="theme-admin-nav-tabs">
						<ul>
							<li data-tab="overview" class="first current">Overview</li>
							<?php $this->theme_admin_options_ui_tabs();?>
							<li data-tab="export">Export / Import</li>

						</ul>
						<div class="tab-content" style="display: block">
							<?php $this->theme_admin_options_overview();?>
						</div>
						<?php $this->theme_admin_options_ui_content();?>

						<div class="tab-content">

							<?php $this->theme_admin_options_backup();?>
							</div>
					</div>

					<input type="hidden" name="settings-changed" id="settings-changed" value="" />
					<button type="submit" class="btn-submit pull-right">Save Changes</button>

				</div>

			</form>

		</div>
	<?php wp_enqueue_media();?>
		<script>

		</script>
	<?php }


	/**
	 * Returns debug info for theme in JSON format - can be called from fields.php for use in a help tab
	 * @return string
	 */
	static function theme_admin_options_debug(){


		$debug_info = wp_get_theme();
		$debug_theme = $debug_info->get('Name');
		$debug_theme_version = $debug_info->get('Name');

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$debug_plugins = get_plugins();

		$debug_array = json_encode(array('theme' => $debug_theme, 'version' => $debug_theme_version, 'plugins' => $debug_plugins), true);

		return $debug_array;
	}


	/**
	 * Auto enqueues are gfont selection using webfont.js to reduce load times.
	 */
	function theme_admin_options_auto_enqueue_gfonts(){


		$gfont = $this->theme_opt('font_enqueue');

		$unique_fonts = array_unique($gfont);

		$pipe_fonts = json_encode($unique_fonts);

		if(!empty($pipe_fonts)){

			?>
			<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.5.18/webfont.js"></script>
			<script>
			WebFont.load({
			google: {
			families: <?php echo $pipe_fonts;?>
			}
			});
			</script>

			<?php



		}


	}


	/**
	 * Auto generates CSS based on fields if using the auto css option on fields
	 */
	function theme_admin_options_generate_css(){


		global $theme_fields;

		$output = "";

		foreach($theme_fields as $section) {

			foreach ($section['fields'] as $fields) {


				$css = "";


				if(!empty($fields['css']) && !empty($this->theme_opt($fields['name']))){

					$field_value = $this->theme_opt($fields['name']);



					switch($fields['css']['property']){

						case "background":

							$background_position = $this->theme_opt($fields['name']."_position");
							$background_size = $this->theme_opt($fields['name']."_size");

							$css .= "background-image: url($field_value);";
							$css .= "background-position: $background_position;";
							$css .= "background-size: $background_size;";


							break;

						case "font":

							$font_size = $this->theme_opt($fields['name']."_size");
							$font_family = $this->theme_opt($fields['name']."_selected");
							$font_color = $this->theme_opt($fields['name']."_color");

							$css .= "font-family: {$font_family};";
							if(!empty($font_size)) {
								$css .= "font-size: {$font_size}px;";
							}
							if(!empty($font_color)) {
								$css .= "color: $font_color;";
							}


							break;

						case "height":

							$css .= $fields['css']['property'].":".$field_value."px;";


							break;

						default:


							$css .= $fields['css']['property'].":".$field_value.";";


					}




						$output .= $fields['css']['selector']."{".$css."}";


				}


			}

		}


		if(!empty($output)){
			echo "\n<style>$output</style>\n";
		}



	}

}
$adminOptions = new adminOptions();
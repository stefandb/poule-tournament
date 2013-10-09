<?php

/**
 * Class to set the settings for the plugin
 * 
 * @version 1
 * @author Stefan de bruin
 */
class poulesettings {

	/**
	 * Home function for the settings contains all the settings
	 * 
	 * @since version 1
	 * @version 1
	 * @access public
	 * @author Stefan de Bruin
	 */
	public function home() {
		if (!$settings = get_option('poule_phase_settings')) {
			$settings = '';
		}

		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$settings = (isset($_POST['tournament'])) ? "WK" : "EK";
			update_option('poule_phase_settings', $settings);
		}

		$set_checked = ($settings == "WK") ? 'checked="checked"' : '';

		require_once POULE_PATH . 'poule-admin/templates/poulesettings/home.php';
	}

}

?>

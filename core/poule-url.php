<?php

/**
 * Description of poule-url
 *
 * @version 1
 * @author Stefan
 */
class poule_url {

	/**
	 * Function that laod the correct class and function
	 * 
	 * @since version 1
	 * @version 1
	 * @access public
	 * @author Stefan de Bruin
	 * @global type $wpdb
	 * @return nothing class the correct class
	 */
	public function Load() {
		$class = $_GET['page'];
		if (file_exists(POULE_PATH . 'poule-admin/classes/' . $class . '.php')) {
			$funtion = (isset($_GET['function'])) ? $_GET['function'] : "home";
			require_once POULE_PATH . 'poule-admin/classes/' . $class . '.php';
			$exist = method_exists($class, $funtion);
			if ($exist) {
				$class_system = new $class;
				$class_system->$funtion();
			} else {
				$this->ShowExitError("no function");
			}
		} else {
			$this->ShowExitError("no page");
		}
	}

	/**
	 * Function to show a error
	 * 
	 * @since version 1
	 * @version 1
	 * @access public
	 * @author Stefan de Bruin
	 * @global type $wpdb
	 * @return nothing show the template file
	 */
	protected function ShowExitError($text) {
		echo '<div class="wrap">
				<div id="message" class="error">' . __($text, 'poule-system') . '</div>
			</div>';
	}

}

?>

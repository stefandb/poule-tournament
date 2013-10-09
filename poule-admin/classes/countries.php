<?php

	/**
	 * Description of countries
	 *
	 * @version 1
	 * @author Stefan
	 */
	class countries {

		/**
		 * Function that show all the countries in a table
		 * 
		 * @since version 1
		 * @version 1
		 * @access public
		 * @author Stefan de Bruin
		 * @global type $wpdb
		 * @return nothing show the template file
		 */
		public function home() {
			global $wpdb;

			$countries = array();
			$table_name = $wpdb->prefix . 'poule_countries';

			foreach ($wpdb->get_results("SELECT * FROM $table_name", ARRAY_A) as $key => $value) {
				$countries[] = array('id' => $value['id'], 'name' => $value['name']);
			}

			require_once POULE_PATH . 'poule-admin/templates/countries/home.php';
		}

		/**
		 * Function that add a country
		 * 
		 * @since version 1
		 * @version 1
		 * @access public
		 * @author Stefan de Bruin
		 * @global type $wpdb
		 * @return nothing show the template file
		 */
		public function add() {
			global $wpdb;

			if ($_SERVER['REQUEST_METHOD'] == "POST") {
				$check = TRUE;
				$error = 'form-succes';
				if (isset($_POST['country'])) {
					$country = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_countries WHERE name = '" . $_POST['country'] . "'", ARRAY_A);
					if ($country == NULL && strlen($_POST['country']) <= 2) {
						$check = FALSE;
						$error = 'form-error';
						$_POST['country'] = "";
					}
				} else {
					$_POST['country'] = "";
					$error = 'form-error';
					$check = FALSE;
				}

				if ($check) {
					$wpdb->insert($wpdb->prefix . 'poule_countries', array('name' => $_POST['country']), array('%s'));
				}
			}
			require_once POULE_PATH . 'poule-admin/templates/countries/add.php';
		}

		/**
		 * Function that edit a country
		 * 
		 * @since version 1
		 * @version 1
		 * @access public
		 * @author Stefan de Bruin
		 * @global type $wpdb
		 * @return nothing show the template file
		 */
		public function edit() {
			global $wpdb;

			$country = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_countries WHERE id = '" . $_GET['country'] . "'", ARRAY_A);
			if ($country != NULL) {
				if ($_SERVER['REQUEST_METHOD'] == "POST") {
					if (isset($_POST['save'])) {
						$check = TRUE;
						$error = 'form-succes';
						if (isset($_POST['country'])) {
							$country_check = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_countries WHERE name = '" . $_POST['country'] . "'", ARRAY_A);
							if ($country_check == NULL && strlen($_POST['country']) <= 2) {
								$check = FALSE;
								$error = 'form-error';
							} else {
								$country['name'] = $_POST['country'];
							}
						} else {
							$error = 'form-error';
							$check = FALSE;
						}

						if ($check) {
							$wpdb->update($wpdb->prefix . 'poule_countries', array('name' => $_POST['country']), array('id' => $_GET['country']));
						}
					} else if (isset($_POST['delete'])) {
						$error = 'form-succes';
						$wpdb->delete($wpdb->prefix . 'poule_countries', array('id' => $_GET['country']));
					}
				}
			}
			require_once POULE_PATH . 'poule-admin/templates/countries/edit.php';
		}

	}

?>
<?php

	/**
	 * Class for the dashboard widget
	 *
	 * @vesion 1
	 * @author Stefan
	 */
	class dashboard {

		/**
		 * Function that show the reset matches on the dashboard
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

			if (!$settings = get_option('poule_widget_settings')) {
				$settings = array(
					'hours' => 12,
					'no_score' => 1,
				);
			}

			$endtime = time();
			$hours = (60 * 60) * $settings['hours'];
			$starttime = time() - $hours;

			$groups = array();

			$countries = array();
			foreach ($wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "poule_countries", ARRAY_A) as $key => $value) {
				$countries[$value['id']] = $value['name'];
			}

			foreach ($wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "poule_matches WHERE start_time BETWEEN " . $starttime . " and " . $endtime, ARRAY_A) as $match) {
				$score = unserialize($match['score']);
				if ($score['score_1'] != "" && $score['score_1'] != "" && $ssettings['score'] == 1) {
					continue;
				}

				if (!array_key_exists($match['group_id'], $groups)) {

					$group_info = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "poule_matches_groups WHERE id='" . $match['group_id'] . "'", ARRAY_A);
					$groups[$match['group_id']] = array(
						'group_name' => $group_info['group_name'],
						'matches' => array()
					);
				}
				$score = unserialize($match['score']);

				$add = array(
					'country1' => $countries[$match['country_1']],
					'country2' => $countries[$match['country_2']],
					'score_1' => $score['score_1'],
					'score_2' => $score['score_2'],
					'penalty_1' => $score['penalty_1'],
					'penalty_2' => $score['penalty_2'],
					'hidden' => 'hidden="hidden"'
				);

				$groups[$match['group_id']]['matches'][] = $add;
				$group_info = null;
			}

			require_once POULE_PATH . 'poule-admin/templates/dashboard/home.php';
		}

		/**
		 * Function to set the config fot the dashboard
		 * 
		 * @since version 1
		 * @version 1
		 * @access public
		 * @author Stefan de Bruin
		 * @global type $wpdb
		 * @return nothing show the template file
		 */
		public function config() {
			if (!$settings = get_option('poule_widget_settings')) {
				$settings = array(
					'hours' => 12,
					'no_score' => 1,
				);

				update_option('poule_widget_settings', $settings);
			}

			if ('POST' == $_SERVER['REQUEST_METHOD']) {
				$data = array(
					'hours' => $_POST['hours'],
					'no_score' => $_POST['score'],
				);

				update_option('poule_widget_settings', $data);
			}

			$hours = $settings['hours'];
			if ($settings['no_score'] == 1) {
				$score = 'checked="checked"';
			} else {
				$score = "";
			}

			require_once POULE_PATH . 'poule-admin/templates/dashboard/config.php';
		}

	}

?>
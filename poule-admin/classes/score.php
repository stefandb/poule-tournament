<?php

/**
 * This class contaisn the function for the admin score
 *
 * @version 1
 * @author Stefan de bruin
 */
class score {

	/**
	 * Function that show the form to set the official score
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

		$groups = array();

		$phase = (isset($_GET['phase'])) ? $_GET['phase'] : "group";

		$phase_id = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_phases WHERE name = '" . $phase . "'", ARRAY_A);

		if (get_option('poule_phase_settings') == "EK" && $phase == "18_final" || $phase_id == NULL) {
			echo '<div id="message" class="error"><p>' . __('Incorrect Tournament Phase', 'poule-system') . '</p></div>';
		} else {
			$pagination = array();
			foreach ($wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "poule_phases", ARRAY_A) as $value) {
				if (get_option('poule_phase_settings') == "EK" && $value['name'] == "18_final") {
					continue;
				}
				$name = str_replace("_", " ", $value['name']);
				$active = ($phase == $value['name'] ) ? "nav-tab-active" : "";
				$pagination[] = array('link' => $value['name'], 'name' => __($name, 'poule-system'), 'active' => $active);
			}

			$countries = array();
			foreach ($wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "poule_countries", ARRAY_A) as $key => $value) {
				$countries[$value['id']] = $value['name'];
			}

			$groups = functions::Get_score($phase, TRUE);
			$errors = array();
			if ($_SERVER['REQUEST_METHOD'] == "POST") {

				$update = TRUE;

				$input_score = $_POST['score'];

				foreach ($input_score as $match_id => $score) {

					$match_info = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_matches WHERE id = '" . $match_id . "'", ARRAY_A);
					$matches = $groups[$match_info['group_id']]['matches'];

					$row = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_matches WHERE id = '$match_id'", ARRAY_A);
					if ($row != NULL && count($score) == 4) {
						if ($matches[$match_id]['readonly'] == "") {

							if (preg_match('/^\d+$/', $score['score_1'])) {
								$groups[$match_info['group_id']]['matches'][$match_id]['score_1'] = $score['score_1'];
								$errors[$match_id]['score_1'] = "form-success";
							} else {
								$update = FALSE . 1;
								$errors[$match_id]['score_1'] = "form-error";
							}

							if (preg_match('/^\d+$/', $score['score_2'])) {
								$groups[$match_info['group_id']]['matches'][$match_id]['score_2'] = $score['score_2'];
								$errors[$match_id]['score_2'] = "form-success";
							} else {
								$update = FALSE . 2;
								$errors[$match_id]['score_2'] = "form-error";
							}
							if ($phase != "group" && $score['score_2'] == $score['score_1']) {
								if (preg_match('/^\d+$/', $score['penalty_1']) && $score['penalty_1'] <= 5) {
									$groups[$match_info['group_id']]['matches'][$match_id]['penalty_1'] = $score['penalty_1'];
									$errors[$match_id]['penalty_1'] = "form-success";
								} else {
									$update = FALSE . 3;
									$errors[$match_id]['penalty_1'] = "form-error";
								}

								if (preg_match('/^\d+$/', $score['penalty_1']) && $score['penalty_2'] <= 5) {
									$groups[$match_info['group_id']]['matches'][$match_id]['penalty_2'] = $score['penalty_2'];
									$errors[$match_id]['penalty_2'] = "form-success";
								} else {
									$update = FALSE . 4;
									$errors[$match_id]['penalty_2'] = "form-error";
								}
							}
						} else {
							$input_score[$match_id]['score_1'] = $matches[$match_id]['score_1'];
							$input_score[$match_id]['score_2'] = $matches[$match_id]['score_2'];
							$input_score[$match_id]['penalty_1'] = $matches[$match_id]['penalty_1'];
							$input_score[$match_id]['penalty_2'] = $matches[$match_id]['penalty_2'];

							$errors[$match_id]['score_1'] = "form-warning";
							$errors[$match_id]['score_2'] = "form-warning";
							$errors[$match_id]['penalty_1'] = "form-warning";
							$errors[$match_id]['penalty_2'] = "form-warning";
						}
					} else {
						$update = FALSE . 5;
						$errors[$match_id]['score_1'] = "form-error";
						$errors[$match_id]['score_2'] = "form-error";
						$errors[$match_id]['penalty_1'] = "form-error";
						$errors[$match_id]['penalty_2'] = "form-error";
					}
				}
				
				if ($update) {
					foreach ($input_score as $match_id => $score) {
						$update = array('score' => serialize($score));
						$wpdb->update($wpdb->prefix . 'poule_matches', $update, array('id' => $match_id));
					}
				}
			}

			require_once POULE_PATH . 'poule-admin/templates/score/home.php';
		}
	}

}

?>

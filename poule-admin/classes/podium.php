<?php

/**
 * This class contains the podium functions
 *
 * @version 1
 * @author Stefan
 */
class podium {

	/**
	 * The home function show the podium
	 * 
	 * @since version 1
	 * @version 1
	 * @access public
	 * @author Stefan de Bruin
	 * @global type $wpdb
	 */
	public function Home() {
		global $wpdb;

		$current_user = wp_get_current_user();

		$podium = array();

		$users = array();

		foreach ($wpdb->get_results("SELECT user_id FROM " . $wpdb->prefix . "poule_score", ARRAY_A) as $user)
			if (!in_array($user, $users))
				array_push($users, $user);

		$scores = array();

		$phases = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "poule_phases", ARRAY_A);

		foreach ($users as $id => $user) {

			$scores[$user['user_id']]['points_total'] = 0;

			foreach ($phases as $phase) {
				$sql = $wpdb->get_row("SELECT score FROM " . $wpdb->prefix . "poule_score WHERE user_id='" . $user['user_id'] . "' AND phase='" . $phase['id'] . "'", ARRAY_A);
				if (is_array($sql)) {
					$scores[$user['user_id']][$phase['id']] = unserialize($sql['score']);
				}
			}
		}

		foreach ($phases as $phase) {
			foreach ($wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "poule_matches_groups WHERE phase = '" . $phase['id'] . "'", ARRAY_A) as $group) {
				foreach ($wpdb->get_results("SELECT id, score,country_1,country_2 FROM " . $wpdb->prefix . "poule_matches WHERE group_id = '" . $group['id'] . "'", ARRAY_A) as $match) {
					$score = unserialize($match['score']);
					$match_id = $match['id'];
					$index = 1;
					foreach ($users as $id => $user_content) {
						$points = 0;
						if (isset($scores[$user_content['user_id']][$phase['id']][$match_id])) {
							if ($score['score_1'] == "" && $score['score_2'] == "") {
								continue;
							}

							$user_score = $scores[$user_content['user_id']][$phase['id']][$match_id];

							if ($user_score['score_1'] == $score['score_1'] && $user_score['score_2'] == $score['score_2']) {
								$points += 2;
							}

							if ($score['score_1'] > $score['score_2']) {
								if ($user_score['score_1'] > $user_score['score_2'])
									$points++;
							}else if ($score['score_1'] < $score['score_2']) {
								if ($user_score['score_1'] < $user_score['score_2'])
									$points++;
							}else if ($score['score_1'] == $score['score_2']) {
								if ($user_score['score_1'] == $user_score['score_2'])
									$points++;
							}

							if ($phase['id'] != 1) {
								if ($score['score_1'] == $score['score_2'] && $score['penalty_1'] != "" && $score['penalty_2'] != "") {
									if ($user_score['score_1'] == $user_score['score_2']) {
										if ($score['penalty_1'] > $score['penalty_2']) {
											if ($match['country_1'] == $user_score['penalty']) {
												$points++;
											}
										} else if ($score['penalty_1'] < $score['penalty_2']) {
											if ($match['country_2'] == $user_score['penalty']) {
												$points++;
											}
										}
									}
								}
							}
						}

						$scores[$user_content['user_id']]['points_total'] += $points;
						$index++;
					}
				}
			}
		}

		$podium = array();
		foreach ($users as $id => $user_content) {
			$podium[$user_content['user_id']]['points_total'] = $scores[$user_content['user_id']]['points_total'];
			$podium[$user_content['user_id']]['user_id'] = $user_content['user_id'];
		}
		arsort($podium);
		$place = 1;
		foreach ($podium as $key => $value) {
			$podium[$value['user_id']]['place'] = $place;

			$podium[$value['user_id']]['fullname'] = $current_user->display_name;

			$podium[$value['user_id']]['score'] = $value['points_total'];

			$place++;
		}

		require_once POULE_PATH . 'poule-admin/templates/podium/home.php';
	}

	/**
	 * Thios function show the score of a single user
	 * 
	 * @since version 1
	 * @version 1
	 * @access public
	 * @author Stefan de Bruin
	 * @global type $wpdb
	 */
	public function userscore() {
		global $wpdb;

		$groups = array();

		$phase = (isset($_GET['phase'])) ? $_GET['phase'] : "group";

		$phase_id = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_phases WHERE name = '" . $phase . "'", ARRAY_A);

		if (get_option('poule_phase_settings') == "EK" && $phase == "18_final" || $phase_id == NULL) {
			echo '<div id="message" class="error"><p>' . __('Incorrect Tournament Phase', 'poule-system') . '</p></div>';
		} else {

			$user_info = get_userdata($_GET['user']);
			if ($user_info === FALSE) {
				echo '<div id="message" class="error"><p>' . __('incorrect user', 'poule-system') . '</p></div>';
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

				$groups = functions::Get_score($phase, FALSE, TRUE, $_GET['user']);
				require_once POULE_PATH . 'poule-admin/templates/podium/userscore.php';
			}
		}
	}

}

?>

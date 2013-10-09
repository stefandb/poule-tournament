<?php

	/**
	 * shortcut to show the podium for the users
	 * 
	 * @since version 1
	 * @version 1
	 * @access public
	 * @author Stefan de Bruin
	 * @global type $wpdb
	 * @return nothing show the template file
	 */
	function podium() {
		global $wpdb;
		$current_user = wp_get_current_user();
		$podium = array();

		$arguments = array();

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

		require_once POULE_PATH . 'shortcuts/poule_podium.php';
	}

	add_shortcode('poule_podium', 'podium');

	/**
	 * shortcut to show the officla score
	 * 
	 * @since version 1
	 * @version 1
	 * @access public
	 * @author Stefan de Bruin
	 * @global type $wpdb
	 * @return nothing show the template file
	 */
	function officialscore() {
		global $wpdb;

		$groups = array();

		$phase = (isset($_GET['phase'])) ? $_GET['phase'] : "group";

		$phase_id = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_phases WHERE name = '" . $phase . "'", ARRAY_A);
		if ($phase_id == null) {
			echo '<div class="alert alert-danger">no matches</div>';
			die();
		}
		$countries = array();
		foreach ($wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "poule_countries", ARRAY_A) as $key => $value) {
			$countries[$value['id']] = $value['name'];
		}

		$i = 0;
		$scores_set = array();

		$groups = functions::Get_score($phase, TRUE);
		
		$pagination = functions::pagination();

		require_once POULE_PATH . 'shortcuts/poule_official_score.php';
	}

	add_shortcode('poule_official_score', 'officialscore');

	/**
	 * shortcut to view a form to set the match results
	 * 
	 * @since version 1
	 * @version 1
	 * @access public
	 * @author Stefan de Bruin
	 * @global type $wpdb
	 * @return nothing show the template file
	 */
	function set_score() {
		global $wpdb;

		if (!is_user_logged_in()) {
			echo '<div class="alert alert-danger">' . _e('Login required', 'poule-system') . ' link naar login form</div>';
		} else {

			$current_user = wp_get_current_user();

			$phase = (isset($_GET['phase'])) ? $_GET['phase'] : "group";

			$phase_id = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_phases WHERE name = '" . $phase . "'", ARRAY_A);

			$groups = functions::Get_score($phase, FALSE, FALSE);
			$error = array();
			$next = TRUE;

			if ($_SERVER['REQUEST_METHOD'] == "POST") {
				$matches = $groups['matches'];
				if (isset($_POST['score'])) {

					$input_score = $_POST['score'];

					foreach ($_POST['score'] as $match_id => $score) {
						$match_info = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_matches WHERE id = '" . $match_id . "'", ARRAY_A);
						$error[$match_id] = array();
						if ($matches[$match_id]['readonly'] == "") {
							if (preg_match('/^\d+$/', $score['score_1'])) {
								$groups[$match_info['group_id']]['matches'][$match_id]['score_1'] = $score['score_1'];
								$error[$match_id]['score_1'] = "has-success";
							} else {
								$next = FALSE;
								$error[$match_id]['score_1'] = "has-error";
							}

							if (preg_match('/^\d+$/', $score['score_2'])) {
								$groups[$match_info['group_id']]['matches'][$match_id]['score_2'] = $score['score_2'];
								$error[$match_id]['score_2'] = "has-success";
							} else {
								$next = FALSE;
								$error[$match_id]['score_2'] = "has-error";
							}
							if ($phase != "group") {
								if (preg_match('/^\d+$/', $score['penalty'])) {
									$error[$match_id]['penalty'] = "has-success";
								} else {
									$next = FALSE;
									$error[$match_id]['penalty'] = "has-error";
								}
							}
						} else {
							$input_score[$match_id]['score_1'] = $matches[$match_id]['score_1'];
							$input_score[$match_id]['score_2'] = $matches[$match_id]['score_2'];
							$input_score[$match_id]['penalty'] = $matches[$match_id]['penalty'];

							$error[$match_id]['score_1'] = "has-warning";
							$error[$match_id]['score_2'] = "has-warning";
							$error[$match_id]['penalty'] = "has-warning";
						}
						$i++;
					}
				} else {
					$next = FALSE;
				}

				if ($next) {
					$check = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_score WHERE user_id = '" . $current_user->ID . "'phase = '" . $phase_id['id'] . "'", ARRAY_A);
					if (is_array($check)) {
						$update = array(
							'score' => serialize($input_score)
						);
						$where = array(
							'user_id' => $current_user->ID,
							'phase' => $phase_id['id']
						);

						$wpdb->update($wpdb->prefix . "poule_score", $update, $where);
					} else {
						$insert = array();
						$insert['user_id'] = $current_user->ID;
						$insert['phase'] = $phase_id['id'];
						$insert['score'] = serialize($input_score);

						$wpdb->insert($wpdb->prefix . "poule_score", $insert);
					}
				}
			}

			$pagination = functions::pagination();

			require_once POULE_PATH . 'shortcuts/setscore.php';
		}
	}

	add_shortcode('poule_setscore', 'set_score');

	/**
	 * shortcut to view the user score
	 * 
	 * @since version 1
	 * @version 1
	 * @access public
	 * @author Stefan de Bruin
	 * @global type $wpdb
	 * @return nothing show the template file
	 */
	function ownscore() {

		global $wpdb;

		if (!is_user_logged_in()) {
			echo '<div class="alert alert-danger">' . _e('Login required', 'poule-system') . ' link naar login form</div>';
		} else {

			$current_user = wp_get_current_user();

			$groups = array();

			$phase = (isset($_GET['phase'])) ? $_GET['phase'] : "group";

			$phase_id = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_phases WHERE name = '" . $phase . "'", ARRAY_A);
			if ($phase_id == null) {
				echo '<div class="alert alert-danger">no matches</div>';
			}

			$groups = functions::Get_score($phase, FALSE, TRUE);
			$pagination = functions::pagination();

			require_once POULE_PATH . 'shortcuts/own_score.php';
		}
	}

	add_shortcode('poule_own_score', 'ownscore');
?>
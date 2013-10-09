<?php

/**
 * This class contains the matches functions
 *
 * @version 1
 * @author Stefan
 */
class matches {

	/**
	 * Function that show the matches sort in the groups
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

		$phase = (isset($_GET['phase'])) ? $_GET['phase'] : "group";

		$phase_id = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_phases WHERE name = '$phase'", ARRAY_A);

		if (get_option('poule_phase_settings') == "EK" && $phase == "18_final" || $phase_id == NULL) {
			echo '<div id="message" class="error"><p>' . __('Incorrect tournament phase', 'poule-system') . '</p></div>';
		} else {
			$groups = array();

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

			$i = 0;
			foreach ($wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "poule_matches_groups WHERE phase = " . $phase_id['id'] . " ORDER BY group_name ASC", ARRAY_A) as $key => $value) {
				$group = array();
				$group['group_name'] = $value['group_name'];
				$group['group_id'] = $value['id'];
				$group['phase'] = $phase;
				$group['matches'] = array();
				foreach ($wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "poule_matches WHERE group_id = " . $value['id'], ARRAY_A) as $match) {
					$match['starttime'] = date("d-m-Y G:i:s", $match['start_time']);
					$match['country1'] = $countries[$match['country_1']];
					$match['country2'] = $countries[$match['country_2']];
					$group['matches'][] = $match;
				}
				$groups[$i] = $group;
				$i++;
			}
			require_once POULE_PATH . 'poule-admin/templates/matches/home.php';
		}
	}

	/**
	 * Function  add matches and the match group
	 * 
	 * @since version 1
	 * @version 1
	 * @access public
	 * @author Stefan de Bruin
	 * @global type $wpdb
	 * @return nothing show the template file
	 */
	function add() {
		global $wpdb;

		$errors = array();
		$phase = (isset($_GET['phase'])) ? $_GET['phase'] : "group";

		$phase_id = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_phases WHERE name = '$phase'", ARRAY_A);

		$countries = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "poule_countries", ARRAY_A);

		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$i = 0;
			$user_input = $_POST;
			$selected = array();
			$insert = TRUE;

			if ($_POST['group_name'] == "" || !isset($_POST['group_name'])) {
				$errors['group_name'] = "form-error";
				$user_input['group_name'] = "";
				$insert = FALSE;
			} else {
				$errors['group_name'] = "form-succes";
			}

			foreach ($_POST['match'] as $key => $value) {
				$selected[$i] = array();
				if ($value['date'] != "") {
					if (date('d-m-Y H:i:s', strtotime($value['date'])) != $value['date']) {
						$insert = FALSE;
						$errors[$i]['date'] = "form-error";
						$user_input['match'][$i]['date'] = "";
					} else {
						$errors[$i]['date'] = "form-succes";
					}
				} else {
					$errors[$i]['date'] = "form-error";
				}

				$countries_check = array();
				foreach ($wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "poule_countries", ARRAY_A) as $key => $country) {
					$countries_check[$country['id']] = array('id' => $country['id'], 'key' => $key);
				}

				if (!array_key_exists($value['country1'], $countries_check)) {
					$insert = FALSE;
					$errors[$i]['country1'] = "form-error";
					$user_input['match'][$i]['country1'] = "";
					$selected[$i]['country1'][$value['country1']] = '';
				} else {
					$errors[$i]['country1'] = "form-succes";

					$selected[$i]['country1'][$value['country1']] = 'selected="selected"';
				}

				if (!array_key_exists($value['country2'], $countries_check)) {
					$insert = FALSE;
					$errors[$i]['country2'] = "form-error";
					$user_input['match'][$i]['country2'] = "";
					$key_info = $countries_check[$value['country2']];
					$countries[$key_info['key']]['selected2'] = '';

					$selected[$i]['country2'][$value['country2']] = '';
				} else {
					$errors[$i]['country2'] = "form-succes";
					$key_info = $countries_check[$value['country2']];
					$selected[$i]['country2'][$value['country2']] = 'selected="selected"';
				}
				$i++;
			}

			if ($insert) {
				$wpdb->insert($wpdb->prefix . 'poule_matches_groups', array('phase' => $phase_id['id'], 'group_name' => $_POST['group_name']));
				$group_id = $wpdb->insert_id;
				foreach ($_POST['match'] as $key => $value) {
					$insert = array();
					$insert['group_id'] = $group_id;
					$insert['country_1'] = $value['country1'];
					$insert['country_2'] = $value['country2'];
					$insert['start_time'] = strtotime($value['date']);
					$wpdb->insert($wpdb->prefix . 'poule_matches', $insert);
				}
			}
		}

		require_once POULE_PATH . 'poule-admin/templates/matches/add.php';
	}

	/**
	 * Function to edit a match group
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

		$phase = (isset($_GET['phase'])) ? $_GET['phase'] : "group";
		$group_id = (isset($_GET['group'])) ? $_GET['group'] : "0";

		$phase_id = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_phases WHERE name = '$phase'", ARRAY_A);

		$group = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_matches_groups WHERE id = '$group_id' AND phase = '" . $phase_id['id'] . "'", ARRAY_A);

		$matches = array();
		foreach ($wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "poule_matches WHERE group_id = " . $group_id, ARRAY_A) as $match) {
			$add = array();
			$add['date'] = date("d-m-Y H:i:s", $match['start_time']);
			$add['match_id'] = $match['id'];
			$countries = array();
			foreach ($wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "poule_countries", ARRAY_A) as $country) {
				$countries[$country['id']] = array('id' => $country['id'], 'name' => $country['name']);
			}
			$country1 = $countries;
			$country1[$match['country_1']]['selected'] = "selected";
			$add['country_1'] = $country1;

			$country2 = $countries;
			$country2[$match['country_2']]['selected'] = "selected";
			$add['country_2'] = $country2;

			$matches[] = $add;
		}

		if ($_SERVER['REQUEST_METHOD'] == "POST") {

			if (isset($_POST['save'])) {
				$i = 0;

				$update = TRUE;

				if ($_POST['group_name'] == "" || !isset($_POST['group_name'])) {
					$errors['group_name'] = "form-error";
					$update = FALSE;
				} else {
					$errors['group_name'] = "form-succes";
				}

				if (isset($_POST['match'])) {
					foreach ($_POST['match'] as $key => $value) {

						$row = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_matches WHERE id = '$key'", ARRAY_A);
						if ($row != NULL) {
							if ($value['date'] != "") {

								if (!$this->validateDate($value['date'], "d-m-Y H:i:s")) {
									$update = FALSE;
									$errors[$i]['date'] = "form-error";
								} else {
									$errors[$i]['date'] = "form-succes";
								}
							} else {
								$update = FALSE;
								$errors[$i]['date'] = "form-error";
							}

							$countries_check = array();
							foreach ($wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "poule_countries", ARRAY_A) as $country) {
								$countries_check[$country['id']] = $country['id'];
							}

							if (!in_array($value['country1'], $countries_check)) {
								$update = FALSE;
								$errors[$i]['country1'] = "form-error";
							} else {
								$errors[$i]['country1'] = "form-succes";
							}

							if (!in_array($value['country2'], $countries_check)) {
								$update = FALSE;
								$errors[$i]['country2'] = "form-error";
							} else {
								$errors[$i]['country2'] = "form-succes";
							}
						} else {
							$update = FALSE;
							$errors[$i]['country2'] = "form-error";
							$errors[$i]['date'] = "form-error";
							$errors[$i]['country1'] = "form-error";
						}
						$i++;
					}
				}

				if ($update) {
					echo "UPDATE";
					$wpdb->update($wpdb->prefix . 'poule_matches_groups', array('group_name' => $_POST['group_name']), array('id' => $group_id));

					foreach ($_POST['match'] as $key => $value) {
						$update = array();
						$update['start_time'] = strtotime($value['date']);
						$update['country_1'] = $value['country1'];
						$update['country_2'] = $value['country2'];
						$wpdb->update($wpdb->prefix . 'poule_matches', $update, array('id' => $key));
					}
				}
			} else if (isset($_POST['delete'])) {
				$wpdb->delete($wpdb->prefix . 'poule_matches_groups', array('id' => $group_id));

				$wpdb->delete($wpdb->prefix . 'poule_matches', array('group_id' => $group_id));
			}
		}

		require_once POULE_PATH . 'poule-admin/templates/matches/edit.php';
	}

	/**
	 * Function to create the matches automatic
	 * 
	 * @since version 1
	 * @version 1
	 * @access public
	 * @author Stefan de Bruin
	 * @global type $wpdb
	 * @return nothing show the template file
	 */
	public function auto() {
		global $wpdb;

		$phase_error = FALSE;
		$phase_name = (isset($_GET['phase'])) ? $_GET['phase'] : "group";
		
		$arguments = array();
		$wk_ek = get_option('poule_phase_settings');
		$phase_id = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_phases WHERE name = '$phase_name'", ARRAY_A);
		if ($phase_id['id'] == 1) {
			$phase_error = TRUE;
		} else {
			if ($wk_ek == 'wk') {
				$phase = $phase_id['id'] - 1;
			} else {
				if ($phase_id['id'] == 3) {
					$phase = $phase_id['id'] - 2;
				} else {
					$phase = $phase_id['id'] - 1;
				}
			}
		}

		$groups = array();
		$arguments['while_groups'] = array();
		$match_count = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_phases WHERE name = '$phase_name'", ARRAY_A);
		$groups_data = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . "poule_matches_groups WHERE phase = '$phase'", ARRAY_A);
		
		if(count($groups_data) != 0){
			for ($i = 1; $i <= $match_count['count']; $i++) {
				$groups[$i] = array('countries_options' => $groups_data, 'row' => $i);
				$arguments['while_groups'][$i] = array('countries_options' => $groups_data, 'row' => $i);
			}

			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$place = array();
				$groups = array();

				foreach($wpdb->get_results('SELECT id FROM ' . $wpdb->prefix . "poule_matches_groups WHERE phase = '$phase'", ARRAY_A) as $group_number => $group){
					$groups[$group['id']] = array();
					$place[$group['id']] = array();
					foreach($wpdb->get_results('SELECT id, country_1, country_2, score FROM ' . $wpdb->prefix . "poule_matches WHERE group_id = '".$group['id']."'", ARRAY_A) as $value){
						$match_score = unserialize($value['score']);
						$countries = array('country_1' => $value['country_1'], 'country_2' => $value['country_2']);
						$groups[$group['id']][$value['id']] = array_merge($countries, $match_score);
					}
				}

				foreach ($groups as $group_id => $value) {
					foreach ($value as $match) {
						if ($phase == "1") {
							$won = 0;
							$equal = 0;
							$lost = 0;
							$positive = 0;
							$negative = 0;

							if ($match['score_1'] > $match['score_2']) {
								$won++;
							} else if ($match['score_1'] == $match['score_2']) {
								$equal++;
							} else {
								$lost++;
							}
							$points = $won * 3 + $equal;
							if (array_key_exists($match['country_1'], $place[$group_id])) {
								$place[$group_id][$match['country_1']]['played']++;
								$place[$group_id][$match['country_1']]['won'] += $won;
								$place[$group_id][$match['country_1']]['equal'] += $equal;
								$place[$group_id][$match['country_1']]['lost'] += $lost;
								$place[$group_id][$match['country_1']]['points'] += $points;

								$place[$group_id][$match['country_1']]['positive'] += $match['score_1'];
								$place[$group_id][$match['country_1']]['negative'] += $match['score_2'];

								$goal_difference = $place[$group_id][$match['country_1']]['positive'] - $place[$group_id][$match['country_1']]['negative'];
								$place[$group_id][$match['country_1']]['goal_difference'] = $goal_difference;

								$points2 = $place[$group_id][$match['country_1']]['points'] + $goal_difference / 100 + $place[$group_id][$match['country_1']]['positive'] / 1000;
								$place[$group_id][$match['country_1']]['points2'] = $points2;
							} else {
								$place[$group_id][$match['country_1']] = array('played' => 1, 'won' => $won, 'equal' => $equal, 'lost' => $lost, 'points' => $points, 'positive' => $match['score_1'], 'negative' => $match['score_2']);
							}

							$won = 0;
							$equal = 0;
							$lost = 0;
							$positive = 0;
							$negative = 0;

							if ($match['score_2'] > $match['score_1']) {
								$won++;
							} else if ($match['score_1'] == $match['score_2']) {
								$equal++;
							} else {
								$lost++;
							}
							$points = $won * 3 + $equal;
							if (array_key_exists($match['country_2'], $place[$group_id])) {
								$place[$group_id][$match['country_2']]['played']++;
								$place[$group_id][$match['country_2']]['won'] += $won;
								$place[$group_id][$match['country_2']]['equal'] += $equal;
								$place[$group_id][$match['country_2']]['lost'] += $lost;
								$place[$group_id][$match['country_2']]['points'] += $points;

								$place[$group_id][$match['country_2']]['positive'] += $match['score_2'];
								$place[$group_id][$match['country_2']]['negative'] += $match['score_1'];

								$goal_difference = $place[$group_id][$match['country_2']]['positive'] - $place[$group_id][$match['country_2']]['negative'];
								$place[$group_id][$match['country_2']]['goal_difference'] = $goal_difference;

								$points2 = $place[$group_id][$match['country_2']]['points'] + $goal_difference / 100 + $place[$group_id][$match['country_2']]['positive'] / 1000;
								$place[$group_id][$match['country_2']]['points2'] = $points2;
							} else {
								$place[$group_id][$match['country_2']] = array('played' => 1, 'won' => $won, 'equal' => $equal, 'lost' => $lost, 'points' => $points, 'positive' => $match['score_2'], 'negative' => $match['score_1']);
							}
						} else {
							if ($match['score_1'] > $match['score_2']) {
								$place[$group_id][$match['country_1']]['points2'] = 20;
								$place[$group_id][$match['country_2']]['points2'] = 10;
							} else if ($match['score_2'] > $match['score_1']) {
								$place[$group_id][$match['country_1']]['points2'] = 10;
								$place[$group_id][$match['country_2']]['points2'] = 20;
							} else if ($match['score_2'] == $match['score_1']) {
								if ($match['penalty_1'] == $match['penalty_2']) {
									$place[$group_id][$match['country_1']]['points2'] = 20;
									$place[$group_id][$match['country_2']]['points2'] = 20;
								} else if ($match['penalty_1'] > $match['penalty_2']) {
									$place[$group_id][$match['country_1']]['points2'] = 20;
									$place[$group_id][$match['country_2']]['points2'] = 10;
								} else if ($match['penalty_1'] < $match['penalty_2']) {
									$place[$group_id][$match['country_1']]['points2'] = 10;
									$place[$group_id][$match['country_2']]['points2'] = 20;
								}
							}
						}
					}
				}

				foreach ($_POST['group'] as $group) {
					$insert_group = array();
					$insert_group['group_name'] = $group['name'];
					$insert_group['phase'] = $phase_id['id'];

					$group_id = $wpdb->insert($wpdb->prefix . 'poule_matches_groups', $insert_group);

					$insert_matches = array();
					$insert_matches['group_id'] = $group_id;
					$insert_matches['start_time'] = strtotime($group['date']);

					$old_group = explode('_', $group['country_1']);

					foreach ($place[$old_group[0]] as $key => $value) {
						$place[$old_group[0]][$key]['country'] = $key;
					}

					$podium = $this->array_sort_by_column($place[$old_group[0]], 'points2', SORT_DESC);

					$insert_matches['country_1'] = $podium[$old_group[1]]['country'];

					$old_group = explode('_', $group['country_2']);

					foreach ($place[$old_group[0]] as $key => $value) {
						$place[$old_group[0]][$key]['country'] = $key;
					}
					$podium = $this->array_sort_by_column($place[$old_group[0]], 'points2', SORT_DESC);

					$insert_matches['country_2'] = $podium[$old_group[1]]['country'];

					$wpdb->insert($wpdb->prefix . 'poule_matches', $insert_matches);
				}
			}
		}else{
			$phase_error = TRUE;
		}
		
		require_once POULE_PATH . 'poule-admin/templates/matches/auto.php';
	}

	/**
	 * Function to validate a text is a date
	 * 
	 * @author php.net
	 * @param type $date
	 * @param type $format
	 * @return type
	 */
	private function validateDate($date, $format = 'Y-m-d H:i:s') {
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}

	/**
	 * Function to sort a multi array
	 * 
	 * @author php.net
	 * @param type $multiArray
	 * @param type $col
	 * @param type $dir
	 * @return type
	 */
	private function array_sort_by_column($multiArray, $col, $dir = SORT_ASC)
	{
		$keys = array();
		$sort = array();
		foreach ($multiArray as $key => $row) {
			$keys[$key]  = $key;
			$sort[$key] = $row[$col];
		}

		array_multisort($sort, $dir, $keys, SORT_ASC, $multiArray);
		return $multiArray;
	}

}

?>

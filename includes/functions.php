<?php
/**
 * Class with some functions
 * 
 * @version 1
 * @author Stefan de Bruin
 */
class functions {

	/**
	 * Function to return the matches in the correct array
	 * 
	 * @since version 1
	 * @version 1
	 * @access public static
	 * @author Stefan de Bruin
	 * @global string $wpdb
	 * @param boolean $phase
	 * @param boolean $official
	 * @param boolean $look
	 * @param boolean/int $user_id
	 * @return array
	 */
	public static function Get_score($phase, $official = FALSE, $look = FALSE, $user_id = FALSE) {
		global $wpdb;

		$current_user = wp_get_current_user();

		$phase_id = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_phases WHERE name = '" . $phase . "'", ARRAY_A);

		$countries = array();
		foreach ($wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "poule_countries", ARRAY_A) as $key => $value) {
			$countries[$value['id']] = $value['name'];
		}

		$groups = array();
		$i = 0;

		if (!$official) {
			if ($user_id === FALSE) {
				$user_id = $current_user->ID;
			}
			$sql = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . "poule_score WHERE user_id = '" . $user_id . "' AND phase = '" . $phase_id['id'] . "'", ARRAY_A);
			$scores_set = (is_array($sql)) ? unserialize($sql['score']) : array();
		}
		else
			$scores_set = array();


		foreach ($wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "poule_matches_groups WHERE phase = '" . $phase_id['id'] . "' ORDER BY group_name ASC", ARRAY_A) as $value) {
			$group = array();
			$group['group_name'] = $value['group_name'];
			$group['matches'] = array();
			$row_id = 1;
			foreach ($wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "poule_matches WHERE group_id = " . $value['id'], ARRAY_A) as $match) {
				if ($official === FALSE) {
					if (array_key_exists($match['id'], $scores_set)) {
						$score = $scores_set[$match['id']];
					} else {
						$score = array();
					}
				} else {
					$score = unserialize($match['score']);
				}

				if (array_key_exists('score_1', $score)) {
					$match['score_1'] = $score['score_1'];
				} else {
					$match['score_1'] = '';
				}

				if (array_key_exists('score_2', $score)) {
					$match['score_2'] = $score['score_2'];
				} else {
					$match['score_2'] = '';
				}
				if (array_key_exists('penalty_1', $score)) {
					$match['penalty_1'] = $score['penalty_1'];
				}
				if (array_key_exists('penalty_2', $score)) {
					$match['penalty_2'] = $score['penalty_2'];
				}

				$match['match_id'] = $match['id'];

				if ($phase != "group") {
					if ($official === TRUE) {
						if ($match['score_1'] == $match['score_2'] && $match['score_1'] != ""){
							$match['hidden'] = "";
							if ($match['penalty_1'] > $match['penalty_2']) {
								$match['penalty_country'] = $countries[$match['country_1']];
							} else {
								$match['penalty_country'] = $countries[$match['country_2']];
							}
						}else $match['hidden'] = 'hidden="hidden"';
					}else if ($official === FALSE) {
						if ($look) {
							if ($match['score_1'] == $match['score_2'] && $match['score_1'] != "") {
								$match['hidden'] = "";
								if ($match['penalty_1'] > $match['penalty_2']) {
									$match['penalty_country'] = $countries[$match['country_1']];
								} else {
									$match['penalty_country'] = $countries[$match['country_2']];
								}
							}
							else
								$match['hidden'] = 'hidden="hidden"';
						}else {
							if ($match['score_1'] == $match['score_2'] && $match['score_1'] == "") {
								$match['hidden'] = 'hidden="hidden"';
							} else if ($match['score_1'] == $match['score_2'] && $match['score_1'] != "") {
								$match['hidden'] = "";
							}

							$item1 = array('country_id' => $match['country_1'], 'country' => $countries[$match['country_1']]);
							$item2 = array('country_id' => $match['country_2'], 'country' => $countries[$match['country_2']]);
							if (array_key_exists($match['id'], $scores_set)) {
								if (array_key_exists('penalty', $scores_set[$match['id']])) {
									if ($look === FALSE) {
										
										if ($scores_set[$match['id']]['penalty'] == $match['country_1']) {
											$item1['selected'] = "selected";
											$item2['selected'] = "";
										} else if ($scores_set[$match['id']]['penalty'] == $match['country_2']) {
											$item1['selected'] = "";
											$item2['selected'] = "selected";
										} else {
											$item1['selected'] = "";
											$item2['selected'] = "";
										}
									} else {
										if ($scores_set[$match['id']]['penalty'] == $match['country_1']) {
											$penalty = $countries[$match['country_1']];
										} else if ($scores_set[$match['id']]['penalty'] == $match['country_2']) {
											$penalty = $countries[$match['country_2']];
										}
									}
								} else {
									$item1['selected'] = "";
									$item2['selected'] = "";
								}
							} else {
								$item1['selected'] = "";
								$item2['selected'] = "";
							}

							$match['countries'][] = $item1;
							$match['countries'][] = $item2;
						}
					}
				}
				else $match['hidden'] = 'hidden="hidden"';


				$match['start_time'] = date("d-m-Y G:i:s", $match['start_time']);
				if ($official) {
					$length = (45 + 45 + 15) * 60;
					$readonly_time = strtotime($match['start_time']) + $length;
					if (time() < $readonly_time) {
						$match['readonly'] = 'readonly="readonly"';
					} else {
						$next_phase = $phase_id['id'] + 1;

						$next = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "poule_matches_groups WHERE phase = '" . $next_phase . "'", ARRAY_A);
						$match['readonly'] = (count($next) != 0) ? 'readonly="readonly"' : '';
					}
				} else {
					$length = (60) * 60;
					$readonly_time = strtotime($match['start_time']) - $length;
					if (time() > $readonly_time) {
						$match['readonly'] = 'readonly="readonly"';
					} else {
						$match['readonly'] = "";
					}
				}

				$match['country1'] = $countries[$match['country_1']];
				$match['country2'] = $countries[$match['country_2']];
				$match['country_id1'] = $match['country_1'];
				$match['country_id2'] = $match['country_2'];
				$match['row_id'] = $row_id;
				$group['matches'][$match['match_id']] = $match;
				$row_id++;
			}
			$groups[$value['id']] = $group;
			$i++;
		}

		return $groups;
	}

	/**
	 * Function to return the pagination menu as array
	 * 
	 * @since version 1
	 * @version 1
	 * @access public static
	 * @author Stefan de Bruin
	 * @global string $wpdb
	 * @return array
	 */
	public static function pagination() {
		global $wpdb;

		$phases = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "poule_phases", ARRAY_A);

		$nav = array();
		foreach ($phases as $phase) {
			$nav[] = array('link' => $phase['name'], 'name' => $phase['name']);
		}
		return $nav;
	}

}

?>
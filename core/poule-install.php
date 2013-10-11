<?php 

/**
 * install script
 * 
 * @since version 1
 * @version 1
 * @access public
 * @author Stefan de Bruin
 * @global type $wpdb
 * @return nothing
 */
function poule_install()
{
	global $wpdb;
	
	add_option('poule_phase_settings', "WK");
	
	$pages = array(
		'poule-podium' => array(
			'name' => 'poule-podium',
			'title' => __( 'poule podium', 'poule-system'),
			'tag' => '[poule_podium]',
			'option' => 'poule_podium_url'
		),
		'score' => array(
			'name' => 'score',
			'title' => __( 'poule score', 'poule-system'),
			'tag' => '[poule_official_score]',
			'option' => 'poule_official_score_url'
		),
		'own-score' => array(
			'name' => 'own-score',
			'title' => __( 'own score', 'poule-system'),
			'tag' => '[poule_own_score]',
			'option' => 'poule_own_score_url'
		),
		'set-score' => array(
			'name' => 'set-score',
			'title' => __('set score', 'poule-system'),
			'tag' => '[poule_setscore]',
			'option' => 'poule_set_score_url'
		),
	);
	
	if(!get_page_by_title($pages['poule-podium']['title'])){
		$page_id = wp_insert_post( array(
			'post_title' 	=>	$pages['poule-podium']['title'],
			'post_type' 	=>	'page',
			'post_name'		=>	$pages['poule-podium']['name'],
			'comment_status'=>	'closed',
			'ping_status' 	=>	'closed',
			'post_content' 	=>	$pages['poule-podium']['tag'],
			'post_status' 	=>	'publish',
			'post_author' 	=>	1,
			'menu_order'	=>	0
		));
	}
	
	unset($pages['poule-podium']);
	
	foreach($pages as $page){
		if(!get_page_by_title($page['title'])){
			$my_post = array(
				'post_title' 	=>	$page['title'],
				'post_type' 	=>	'page',
				'post_name'		=>	$page['name'],
				'comment_status'=>	'closed',
				'ping_status' 	=>	'closed',
				'post_content' 	=>	$page['tag'],
				'post_status' 	=>	'publish',
				'post_author' 	=>	1,
				'menu_order'	=>	0,
				'post_parent'	=>	$page_id
			);

			wp_insert_post( $my_post );
		}
	}
}

?>
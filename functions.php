<?php

function freelanceengine_child_scripts() {
    // Dequeue (remove) parent theme script
	wp_dequeue_script( 'profile' );
	wp_deregister_script( 'profile' );

	wp_register_script( 'profile', get_stylesheet_directory_uri() . '/assets/js/profile.js', array( 'jquery',
		'underscore',
		'backbone',
		'appengine',
		'front' ), ET_VERSION, true );

	// enqueue replacement child theme script
	wp_enqueue_script( 'profile' );

	// javascript to show/hide bulletin post content on lawandaction.com homepage and /bulletins
	wp_register_script( 'bulletins', get_stylesheet_directory_uri() . '/assets/js/bulletins.js', array( 'jquery',
		'underscore',
		'backbone',
		'appengine',
		'front' ), ET_VERSION, true );

	wp_enqueue_script( 'bulletins' );
}
add_action( 'wp_enqueue_scripts', 'freelanceengine_child_scripts', 100 );

function freelanceengine_child_styles() {
	// dequeue parent styles
	wp_dequeue_style( 'main-style' );
	wp_deregister_style( 'main-style' );

	wp_register_style( 'main-style', get_stylesheet_directory_uri() .'/style.css', array());
	// enqueue child styles
	wp_enqueue_style('main-style' );	
}
add_action( 'wp_enqueue_styles', 'freelanceengine_child_styles', 100 );

// get environment language of the system
function get_env_language() {
	return strval(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
}

// function get page id by name
function get_page_id($page_name) {
	global $wpdb;
	$page_id = $wpdb->get_var("SELECT ID FROM " . $wpdb->posts . " WHERE post_name = '" . $page_name . "'");
	return $page_id;
}

// insert taxonomy post language
function insert_post_language_taxonomy() {
	$taxs = array (
		'0' => array(
			'name'	=> 'English',
			'slug'	=> 'en',
		),
		'1' => array(
			'name'	=> 'Korean',
			'slug'	=> 'ko',
		),
		'2' => array(
			'name'	=> 'Chinese',
			'slug'	=> 'zh-CN',
		),
	);

	foreach ( $taxs as $tax_key => $tax ) {
		wp_insert_term(
			$tax['name'],
			'post_language',
			array(
				'description'	=> '',
				'slug'			=> $tax['slug'],
			)
		);
		unset( $tax );
	}
}

/**
 * register taxonomy post language
 */
function create_post_language_taxonomy() {
	register_taxonomy( 
		'post_language', 
		'languages', 
		array(
			'label' => __( 'Post Language' ),
			'rewrite' => array( 'slug' => 'product_style' ),
			'hierarchical' => true,
		) 
	);

	if( !term_exists( 'post_language', '' )) {
		insert_post_language_taxonomy();
	}
}
add_action( 'init', 'create_post_language_taxonomy', 0 );

/***********************************************************************************************************
 * Registers a new post type profile
 * @uses $wp_post_types Inserts new post type object into the list
 *
 * @param string  Post type key, must not exceed 20 characters
 * @param array|string See optional args description above.
 *
 * @return object|WP_Error the registered post type object, or an error object
 ***********************************************************************************************************/
function fre_register_profile_child() {
	$labels = array(
		'name'               => __( 'Profiles', ET_DOMAIN ),
		'singular_name'      => __( 'Profile', ET_DOMAIN ),
		'add_new'            => _x( 'Add New profile', ET_DOMAIN, ET_DOMAIN ),
		'add_new_item'       => __( 'Add New profile', ET_DOMAIN ),
		'edit_item'          => __( 'Edit profile', ET_DOMAIN ),
		'new_item'           => __( 'New profile', ET_DOMAIN ),
		'view_item'          => __( 'View profile', ET_DOMAIN ),
		'search_items'       => __( 'Search Profiles', ET_DOMAIN ),
		'not_found'          => __( 'No Profiles found', ET_DOMAIN ),
		'not_found_in_trash' => __( 'No Profiles found in Trash', ET_DOMAIN ),
		'parent_item_colon'  => __( 'Parent profile:', ET_DOMAIN ),
		'menu_name'          => __( 'Profiles', ET_DOMAIN ),
	);
	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_in_menu'      => true,
		'show_in_admin_bar' => true,
		'menu_position'     => 6,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => ae_get_option( 'fre_profile_archive', 'profiles' ),
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => true, //array('slug' => ae_get_option('fre_profile_slug', '')),
		'capability_type'     => 'post',
		'supports'            => array(
			'title',
			'editor',
			'author',
			'thumbnail',
			'excerpt',
			'custom-fields',
			'trackbacks',
			'comments',
			'revisions',
			'page-attributes',
			'post-formats'
		)
	);
	register_post_type( PROFILE, $args );
	$labels = array(
		'name'                  => _x( 'Countries', 'Taxonomy plural name', ET_DOMAIN ),
		'singular_name'         => _x( 'Country', 'Taxonomy singular name', ET_DOMAIN ),
		'search_items'          => __( 'Search countries', ET_DOMAIN ),
		'popular_items'         => __( 'Popular countries', ET_DOMAIN ),
		'all_items'             => __( 'All countries', ET_DOMAIN ),
		'parent_item'           => __( 'Parent country', ET_DOMAIN ),
		'parent_item_colon'     => __( 'Parent country', ET_DOMAIN ),
		'edit_item'             => __( 'Edit country', ET_DOMAIN ),
		'update_item'           => __( 'Update country ', ET_DOMAIN ),
		'add_new_item'          => __( 'Add New country ', ET_DOMAIN ),
		'new_item_name'         => __( 'New country Name', ET_DOMAIN ),
		'add_or_remove_items'   => __( 'Add or remove country', ET_DOMAIN ),
		'choose_from_most_used' => __( 'Choose from most used enginetheme', ET_DOMAIN ),
		'menu_name'             => __( 'Countries', ET_DOMAIN ),
	);
	$args = array(
		'labels'            => $labels,
		'public'            => true,
		'show_in_nav_menus' => true,
		'show_admin_column' => true,
		'hierarchical'      => true,
		'show_tagcloud'     => true,
		'show_ui'           => true,
		'query_var'         => true,
		'rewrite'           => array(
			'slug' => ae_get_option( 'country_slug', 'country' )
		),
		'query_var'         => true,
		'capabilities'      => array(
			'manage_terms',
			'edit_terms',
			'delete_terms',
			'assign_terms'
		)
	);
	register_taxonomy( 'country', array( PROFILE, PROJECT ), $args );
	global $ae_post_factory;
	$ae_post_factory->set( PROFILE, new AE_Posts( PROFILE, array( 'project_category', 'skill', 'country' ), array(
		'et_professional_title',
		'rating_score',
		'hour_rate',
		'et_experience',
		'et_receive_mail',
		'currency',
		'languages' // yiping added - for retrieving this parameter
	) ) );
}

add_action( 'init', 'fre_register_profile_child', 25);

/**********************************************************************************************************
 * function to save all profile data to the databases including 'bulletin posts'
 **********************************************************************************************************/
function child_theme_class() {
	class Modified_Fre_ProfileAction extends AE_PostAction {
		function __construct( $post_type = 'fre_profile' ) {
			$this->post_type = PROFILE;
			// add action fetch profile
			$this->add_ajax( 'ae-fetch-profiles', 'fetch_post' );
			/**
			 * sync profile
			 * # update , insert ...
			 *
			 * @param Array $request
			 *
			 * @since v1.0
			 */
			$this->add_ajax( 'ae-profile-sync', 'sync_post' );
			$this->add_action( 'pre_get_posts', 'pre_get_profile' );
			/**
			 * hook convert a profile to add custom meta data
			 *
			 * @param Object $result profile object
			 *
			 * @since v1.0
			 */
			$this->add_filter( 'ae_convert_fre_profile', 'ae_convert_profile' );
			// hook to groupy by, group profile by author
			$this->add_filter( 'posts_groupby', 'posts_groupby', 10, 2 );
			// filter post where to check user professional title
			$this->add_filter( 'posts_search', 'fre_posts_search', 10, 2 );
			// add filter posts join to join post meta and get et professional title
			$this->add_filter( 'posts_join', 'fre_join_post', 10, 2 );
			// add fiter groupby
			$this->add_filter( 'posts_groupby', 'fre_posts_group_by', 10, 2 );
			// Delete profile after admin delete user
			$this->add_action( 'remove_user_from_blog', 'fre_delete_profile_after_delete_user' );
			// delete education, certification, experience
			$this->add_ajax( 'ae-profile-delete-meta', 'deleteMetaProfile' );
		}
		/**
		 * convert  profile
		 * @package FreelanceEngine
		 */
		function ae_convert_profile( $result ) {
			$result->et_avatar   = get_avatar( $result->post_author, 70 );
			$result->author_link = get_author_posts_url( $result->post_author );
			$et_experience = (int)  $result->et_experience;
			if ( $et_experience == 1 ) {
				$result->experience = sprintf( __( "%d year experience", ET_DOMAIN ), $et_experience );
			} else {
				$result->experience = sprintf( __( "%d years experience", ET_DOMAIN ), $et_experience );
			}
			// override profile ling
			$result->permalink         = $result->author_link;
			$result->author_name       = get_the_author_meta( 'display_name', $result->post_author );
	
			$result->hourly_rate_price = '';
			if ( (int) $result->hour_rate > 0 )
				$result->hourly_rate_price = sprintf( __( "<b>%s</b>/hr", ET_DOMAIN ), fre_price_format( $result->hour_rate ) );
	
			$rating               = Fre_Review::freelancer_rating_score( $result->post_author );
			$result->rating_score = $rating['rating_score'];
			ob_start();
			$i = 1;
			if ( $result->tax_input['skill'] ) {
				$total_skill   = count( $result->tax_input['skill'] );
				$string_length = 0;
				foreach ( $result->tax_input['skill'] as $tax ) {
					$string_length += strlen( $tax->name );
					?>
					<li><span class="skill-name-profile"><?php echo $tax->name; ?></span></li>
					<?php
					if ( $string_length > 20 ) {
						break;
					}
					if ( $i >= 4 ) {
						break;
					}
					$i ++;
				}
				if ( $i < $total_skill ) {
					echo '<li><span class="skill-name-profile">+' . ( $total_skill - $i ) . '</span></li>';
				}
			}
			$skill_list = ob_get_clean();
			// skill dont need id array
			unset( $result->skill );
			// generate skill list
			$result->skill_list     = $skill_list;
			$result->user_available = get_user_meta( $result->post_author, 'user_available', true );
			$project_worked = (int ) get_post_meta( $result->ID, 'total_projects_worked', true );
			$result->project_worked = sprintf( __( '%d projects worked', ET_DOMAIN ), $project_worked );
			if ( $project_worked == 1 ) {
				$result->project_worked = sprintf( __( '%d project worked', ET_DOMAIN ), $project_worked );
			}
			$email_skill         = get_post_meta( $result->ID, 'email_skill', true );
			$result->email_skill = ! empty( $email_skill ) ? $email_skill : 0;
			$earned         = fre_count_total_user_earned( $result->post_author );
			$result->earned = price_about_format( $earned ) . ' ' . __( 'earned', ET_DOMAIN );
			$result->excerpt = fre_trim_words( $result->post_content, 80 ); // 1.8.3.1
			return $result;
		}
		/**
		 * group profile by user id if user can not edit other profils
		 *
		 * @param string $groupby
		 * @param object $groupby Wp_Query object
		 *
		 * @since 1.0
		 * @author Dakachi
		 */
		function posts_groupby( $groupby, $query ) {
			global $wpdb;
			$query_vars = ( isset( $query->query_vars['post_type'] ) ) ? $query->query_vars : '';
			if ( isset( $query_vars['post_type'] ) && $query_vars['post_type'] == $this->post_type ) {
				$groupby = "{$wpdb->posts}.post_author";
			}
			return $groupby;
		}
		/**
		 * add post where when user search, check professional title
		 *
		 * @param String $where SQL where string
		 *
		 * @since 1.4
		 * @author Dakachi
		 */
		function fre_posts_search( $post_search, $query ) {
			global $wpdb;
			if ( isset( $_REQUEST['query']['s'] ) && $_REQUEST['query']['s'] != '' && $query->query_vars['post_type'] == PROFILE ) {
				$post_search = substr( $post_search, 0, - 2 );
				$search = $_REQUEST['query']['s'];
				$q      = array();
				$q['s'] = $search;
				// there are no line breaks in <input /> fields
				$search                  = str_replace( array( "\r", "\n" ), '', esc_sql( $search ) );
				$q['search_terms_count'] = 1;
				if ( ! empty( $q['sentence'] ) ) {
					$q['search_terms'] = array( $q['s'] );
				} else {
					if ( preg_match_all( '/".*?("|$)|((?<=[\t ",+])|^)[^\t ",+]+/', $q['s'], $matches ) ) {
						$q['search_terms_count'] = count( $matches[0] );
						$q['search_terms']       = $matches[0];
						// if the search string has only short terms or stopwords, or is 10+ terms long, match it as sentence
						if ( empty( $q['search_terms'] ) || count( $q['search_terms'] ) > 9 ) {
							$q['search_terms'] = array( $q['s'] );
						}
					} else {
						$q['search_terms'] = array( $q['s'] );
					}
				}
				foreach ( $q['search_terms'] as $term ) {
					$post_search .= " OR prof_title.meta_value LIKE '%" . $term . "%'";
				}
				$post_search .= ") ";
				// $where .= " OR prof_title.meta_value LIKE '%".$_REQUEST['query']['s']."%'";
			}
			// wp_send_json( $post_search );
			return $post_search;
		}
		/**
		 * join postmeta table to get et_professional_title
		 *
		 * @param String $join SQL join string
		 *
		 * @since 1.4
		 * @author Dakachi
		 */
		function fre_join_post( $join, $query ) {
			global $wpdb;
			if ( isset( $_REQUEST['query']['s'] ) && $_REQUEST['query']['s'] != '' && $query->query_vars['post_type'] == PROFILE ) {
				$join .= " INNER JOIN $wpdb->postmeta as prof_title ON ID = prof_title.post_id AND prof_title.meta_key='et_professional_title' ";
			}
			if ( isset( $_REQUEST['query']['earning'] ) && ( $_REQUEST['query']['earning'] ) ) {
				$join .= " LEFT JOIN $wpdb->posts as prof_post_bid ON prof_post_bid.post_author =  $wpdb->posts.post_author AND prof_post_bid.post_type='bid'
				 AND prof_post_bid.post_status='complete'";
				$join .= " LEFT JOIN $wpdb->postmeta as prof_post_bid_meta ON prof_post_bid.ID =  prof_post_bid_meta.post_id
				AND prof_post_bid_meta.meta_key = 'bid_budget'";
			}
			return $join;
		}
		function fre_posts_group_by( $group_by ) {
			if ( isset( $_REQUEST['query']['earning'] ) && ( $_REQUEST['query']['earning'] ) ) {
				global $wpdb;
				$group_by = $wpdb->posts . ".post_author ";
				$earning  = $_REQUEST['query']['earning'];
				switch ( $earning ) {
					case '100-1000':
						$group_by .= " HAVING ( SUM(prof_post_bid_meta.meta_value) BETWEEN '100' AND '1000') ";
						break;
					case '1000-10000':
						$group_by .= " HAVING ( SUM(prof_post_bid_meta.meta_value) BETWEEN '1000' AND '10000') ";
						break;
					case '10000':
						$group_by .= " HAVING ( SUM(prof_post_bid_meta.meta_value) > 10000 ) ";
						break;
					default:
						$group_by .= " HAVING ( SUM(prof_post_bid_meta.meta_value) BETWEEN '0' AND '100' OR SUM(prof_post_bid_meta.meta_value) IS NULL ) ";
				}
			}
			return $group_by;
		}
		/**
		 * filter query args before query
		 * @package FreelanceEngine
		 */
		public function filter_query_args( $query_args ) {
			if ( isset( $_REQUEST['query'] ) ) {
				$query      = $_REQUEST['query'];
				$query_args = wp_parse_args( $query_args, $query );
				// query profile base on skill
				if ( isset( $query['skill'] ) && $query['skill'] != '' ) {
					//$query_args['skill_slug__and'] = $query['skill'];
					$query_args['tax_query'] = array(
						'skill' => array(
							'taxonomy' => 'skill',
							'terms'    => $query['skill'],
							'field'    => 'slug'
						)
					);
					unset( $query_args['skill'] );
				}
				// list featured profile
				if ( isset( $query['meta_key'] ) ) {
					$query_args['meta_key'] = $query['meta_key'];
					if ( isset( $query['meta_value'] ) ) {
						$query_args['meta_value'] = $query['meta_value'];
					}
				}
				// add hour rate filter to query
				if ( isset( $query['hour_rate'] ) && ! empty( $query['hour_rate'] ) ) {
					$hour_rate = $query['hour_rate'];
					$hour_rate = explode( ",", $hour_rate );
					if ( (int) $hour_rate[0] == (int) $hour_rate[1] ) {
						$query_args['meta_query'] = array(
							array(
								'key'   => 'hour_rate',
								'value' => (int) $hour_rate[0],
								'type'  => 'numeric',
								// 'compare' => 'BETWEEN'
							)
						);
					} else {
						$query_args['meta_query'] = array(
							array(
								'key'     => 'hour_rate',
								'value'   => array( (int) $hour_rate[0], (int) $hour_rate[1] ),
								'type'    => 'numeric',
								'compare' => 'BETWEEN'
							)
						);
					}
				} else {
					$query_args['meta_query'] = array(
						array(
							'key'     => 'hour_rate',
							'value'   => array( 0, (int) ae_get_option( 'fre_slide_max_budget_freelancer', 2000 ) ),
							'type'    => 'numeric',
							'compare' => 'BETWEEN'
						)
					);
				}
				if ( ! current_user_can( 'manage_options' ) ) {
					$query_args['meta_query'][] = array(
						'key'     => 'user_available',
						'value'   => 'on',
						'compare' => '='
					);
				}
				if ( isset( $query['country'] ) && $query['country'] != '' ) {
					$query_args['country'] = $query['country'];
				}
				if ( isset( $query['project_category'] ) && $query['project_category'] != '' ) {
					$query_args['project_category'] = $query['project_category'];
				}
				// Order
				if ( isset( $query['orderby'] ) ) {
					$orderby = $query['orderby'];
					switch ( $orderby ) {
						case 'date':
							$query_args['orderby'] = 'date';
							break;
						case 'hour_rate':
							$query_args['meta_key'] = 'hour_rate';
							$query_args['orderby']  = 'meta_value_num date';
							$query_args['order']    = 'DESC';
							break;
						case 'projects_worked':
							$query_args['meta_key'] = 'total_projects_worked';
							$query_args['orderby']  = 'meta_value_num date';
							$query_args['order']    = 'DESC';
							break;
						case 'rating':
							$query_args['meta_key']     = 'rating_score';
							$query_args['orderby']      = 'meta_value_num date';
							$query_args['meta_query'][] = array(
								'relation' => 'AND',
								array(
									'key'     => 'rating_score',
									'compare' => 'BETWEEN',
									'value'   => array( 0, 5 )
								)
							);
							break;
					}
				}
				//check query projects worked
				if ( isset( $query['total_projects_worked'] ) && $query['total_projects_worked'] ) {
					$total_projects_worked = $query['total_projects_worked'];
					switch ( $total_projects_worked ) {
						case '10':
							$query_args['meta_query'][] = array(
								'key'     => 'total_projects_worked',
								'value'   => '10',
								'type'    => 'numeric',
								'compare' => '<=',
							);
							break;
						case '20':
							$query_args['meta_query'][] = array(
								'key'     => 'total_projects_worked',
								'value'   => '11',
								'type'    => 'numeric',
								'compare' => '>=',
							);
							$query_args['meta_query'][] = array(
								'key'     => 'total_projects_worked',
								'value'   => '20',
								'type'    => 'numeric',
								'compare' => '<=',
							);
							break;
						case '30':
							$query_args['meta_query'][] = array(
								'key'     => 'total_projects_worked',
								'value'   => '21',
								'type'    => 'numeric',
								'compare' => '>=',
							);
							$query_args['meta_query'][] = array(
								'key'     => 'total_projects_worked',
								'value'   => '30',
								'type'    => 'numeric',
								'compare' => '<=',
							);
							break;
						case '40':
							$query_args['meta_query'][] = array(
								'key'     => 'total_projects_worked',
								'value'   => '30',
								'type'    => 'numeric',
								'compare' => '>',
							);
							break;
					}
				}
			}
			return apply_filters( 'fre_profile_query_args', $query_args, $query );
		}
		/**
		 * filter pre get profile
		 *
		 * @param $query
		 *
		 * @package FreelanceEngine
		 * @return
		 */
		function pre_get_profile( $query ) {
	
			if ( ! wp_doing_ajax() && is_admin() ){
				return $query;
			}
	
			if ( is_post_type_archive( 'fre_profile' ) ) {
				$query_profile = $query->query;
				$post_type     = isset( $query_profile['post_type'] ) ? $query_profile['post_type'] : '';
				if ( $post_type == PROFILE ) {
	
					// if ( is_admin() ){
					//  return $query;
					// }
					$query->query_vars['meta_query'] = '';
					if ( isset( $_REQUEST['query']['hour_rate'] ) && ! empty( $_REQUEST['query']['hour_rate'] ) ) {
						$hour_rate                       = $_REQUEST['query']['hour_rate'];
						$hour_rate                       = explode( ",", $hour_rate );
						$query->query_vars['meta_query'] = array(
							array(
								'key'     => 'hour_rate',
								'value'   => array( (int) $hour_rate[0], (int) $hour_rate[1] ),
								'type'    => 'numeric',
								'compare' => 'BETWEEN'
							)
						);
					} else {
						// Query Hour_rate default
						$query->query_vars['meta_query'] = array(
							array(
								'key'     => 'hour_rate',
								'value'   => array( 0, (int) ae_get_option( 'fre_slide_max_budget_freelancer', 2000 ) ),
								'type'    => 'numeric',
								'compare' => 'BETWEEN'
							)
						);
					}
					// always check hour rate because employer have profile
					$query->query_vars['meta_query'][] = array(
						'key'     => 'hour_rate',
						'value'   => '',
						'compare' => '!='
					);
					if ( ! current_user_can( 'manage_options' ) ) {
	
	
						/*
						 * fre/emp/visitor only see profile is available for hire.
						 */
						$query->query_vars['meta_query'] = array(
							array(
								'key'     => 'user_available',
								'value'   => 'on',
								'compare' => '='
							)
						);
					}
				}
			}
			// Search default
			if ( $query->is_search() && is_search() && ! is_admin() ) {
				$query->set( 'post_type', array( 'post', 'page' ) );
			} // end if
			return $query;
		}
		/**
		 * hanlde profile action
		 * @package FreelanceEngine
		 */
		function sync_post() {
			global $ae_post_factory, $user_ID, $current_user;
			$request   = $_REQUEST;
			$ae_users  = new AE_Users();
			$user_data = $ae_users->convert( $current_user );
			$profile   = $ae_post_factory->get( $this->post_type );
			if ( ! AE_Users::is_activate( $user_ID ) ) {
				wp_send_json( array(
						'success' => false,
						'msg'     => __( "Your account is pending. You have to activate your account to create profile.", ET_DOMAIN )
					)
				);
			};
			// set status for profile
			if ( ! isset( $request['post_status'] ) ) {
				$request['post_status'] = 'publish';
			}
			// version 1.8.2 set display name when update profile
			if ( isset( $request['display_name'] ) and ! empty( $request['display_name'] ) ) {
				wp_update_user( array( 'ID' => $user_ID, 'display_name' => $request['display_name'] ) );
			}
			// yiping - added
			if ( isset( $request['bulletin'] ) && ! empty( $request['bulletin'] ) && is_array( $request['bulletin'] ) ) {
				$profile_id = get_user_meta( $user_ID, 'user_profile_id', true );
				$bulletin = $request['bulletin'];
				if ( ! empty( $bulletin['title'] ) && ! empty( $bulletin['content'] ) ) {
					if ( ! empty( $bulletin['id'] ) ) {
						$meta_id = $bulletin['id'];
						unset( $bulletin['id'] );
						global $wpdb;
						$update = $wpdb->update( $wpdb->postmeta, array( 'meta_value' => $bulletin ), array( 'meta_id' => $meta_id ) );
					} else {
						$update = add_post_meta( $profile_id, 'bulletin', $bulletin );
					}
					if ( $update === false ) {
						wp_send_json( array(
							'success' => false,
							'msg'     => __( "Update Bulletin fail.", ET_DOMAIN )
						) );
					}
				}
			}
			if ( isset( $request['work_experience'] ) && ! empty( $request['work_experience'] ) && is_array( $request['work_experience'] ) ) {
				$profile_id = get_user_meta( $user_ID, 'user_profile_id', true );
				$experience = $request['work_experience'];
				if ( ! empty( $experience['title'] ) && ! empty( $experience['subtitle'] ) ) {
					if ( ! empty( $experience['id'] ) ) {
						$meta_id = $experience['id'];
						unset( $experience['id'] );
						global $wpdb;
						$update = $wpdb->update( $wpdb->postmeta, array( 'meta_value' => serialize( $experience ) ), array( 'meta_id' => $meta_id ) );
					} else {
						$update = add_post_meta( $profile_id, 'work_experience', serialize( $experience ) );
					}
					if ( $update === false ) {
						wp_send_json( array(
							'success' => false,
							'msg'     => __( "Update Experience fail.", ET_DOMAIN )
						) );
					}
				}
			}
			if ( isset( $request['certification'] ) && ! empty( $request['certification'] ) && is_array( $request['certification'] ) ) {
				$profile_id    = get_user_meta( $user_ID, 'user_profile_id', true );
				$certification = $request['certification'];
				if ( ! empty( $certification['title'] ) && ! empty( $certification['subtitle'] ) ) {
					if ( ! empty( $certification['id'] ) ) {
						$meta_id = $certification['id'];
						unset( $certification['id'] );
						global $wpdb;
						$update = $wpdb->update( $wpdb->postmeta, array( 'meta_value' => serialize( $certification ) ), array( 'meta_id' => $meta_id ) );
					} else {
						$update = add_post_meta( $profile_id, 'certification', serialize( $certification ) );
					}
					if ( $update === false ) {
						wp_send_json( array(
							'success' => false,
							'msg'     => __( "Update Fertification fail.", ET_DOMAIN )
						) );
					}
				}
			}
			if ( isset( $request['education'] ) && ! empty( $request['education'] ) && is_array( $request['education'] ) ) {
				$profile_id = get_user_meta( $user_ID, 'user_profile_id', true );
				$education  = $request['education'];
				if ( ! empty( $education['title'] ) && ! empty( $education['subtitle'] ) ) {
					if ( ! empty( $education['id'] ) ) {
						$meta_id = $education['id'];
						unset( $education['id'] );
						global $wpdb;
						$update = $wpdb->update( $wpdb->postmeta, array( 'meta_value' => serialize( $education ) ), array( 'meta_id' => $meta_id ) );
					} else {
						$update = add_post_meta( $profile_id, 'education', serialize( $education ) );
					}
					if ( $update === false ) {
						wp_send_json( array(
							'success' => false,
							'msg'     => __( "Edit Education Fail.", ET_DOMAIN )
						) );
					}
				}
			}
			// set profile title
			$request['post_title'] = ! empty( $request['display_name'] ) ? $request['display_name'] : $user_data->display_name;
			if ( $request['method'] == 'create' ) {
				$profile_id = get_user_meta( $user_ID, 'user_profile_id', true );
				if ( $profile_id ) {
					$profile_post = get_post( $profile_id );
					if ( $profile_post && $profile_post->post_status != 'draft' ) {
						wp_send_json( array(
								'success' => false,
								'msg'     => __( "You only can have one profile.", ET_DOMAIN )
							)
						);
					}
				}
			}
			$email_skill = 0;
			if ( isset( $request['email_skill'] ) ) {
	
				if ( ! empty( $request['email_skill'] ) ) {
	
					if ( is_array( $request['email_skill'] ) ) {
						$email_skill = ! empty( $request['email_skill'][0] ) ? $request['email_skill'][0] : 0;
					} else {
						$email_skill = $request['email_skill'];
					}
				} else {
					$email_skill = 0;
				}
				$profile_id = get_user_meta( $user_ID, 'user_profile_id', true );
				update_post_meta( $profile_id, 'email_skill', $email_skill );
			}
			do_action('before_sync_profile', $request);
			// sync profile
			$result = $profile->sync( $request );
			if ( ! is_wp_error( $result ) ) {
				$result->redirect_url = $result->permalink;
				$rating_score         = get_post_meta( $result->ID, 'rating_score', true );
				if ( ! $rating_score ) {
					update_post_meta( $result->ID, 'rating_score', 0 );
				}
				$user_available = get_user_meta( $user_ID, 'user_available', true );
				update_post_meta( $result->ID, 'user_available', $user_available );
				// get profile languages - yiping
				if ( isset( $request['languages']) && ! empty( $request['languages']) && is_array( $request['languages'])) {
					$languages = $request['languages'];
					update_post_meta( $result->ID, 'languages', $languages );
				}
				// action create profile
				if ( $request['method'] == 'create' ) {
					//update_post_meta( $result->ID,'hour_rate', 0);//@author: danng  fix query meta in page profiles search in version 1.8.4
					update_post_meta( $result->ID, 'total_projects_worked', 0 );
	
					$profile_id = get_user_meta( $user_ID, 'user_profile_id', true ); // 1.8.6.1
					update_post_meta( $profile_id, 'email_skill', $email_skill );  // 1.8.6.1
					// store profile id to user meta
					$response = array(
						'success' => true,
						'data'    => $result,
						'msg'     => __( "Your profile has been created successfully.", ET_DOMAIN )
					);
					wp_send_json( $response );
					//action update profile
				} else if ( $request['method'] == 'update' ) {
					$response = array(
						'success' => true,
						'data'    => $result,
						'msg'     => __( "Your profile has been updated successfully.", ET_DOMAIN )
					);
					wp_send_json( $response );
				}
			} else {
				wp_send_json( array(
					'success' => false,
					'data'    => $result,
					'msg'     => $result->get_error_message()
				) );
			}
		}
		/**
		 * Delete profile after delete user
		 *
		 * @param integer $user_id the id of user to delete
		 *
		 * @return void
		 * @since 1.7
		 * @package freelanceengine
		 * @category PROFILE
		 * @author Tambh
		 */
		function fre_delete_profile_after_delete_user( $user_id ) {
			if ( current_user_can( 'manage_options' ) ) {
				$profile_ids = $this->fre_get_profile_id( array( 'author' => $user_id ) );
				foreach ( $profile_ids as $key => $value ) {
					wp_trash_post( $value );
				}
			}
		}
		/**
		 * Get profile id
		 *
		 * @param array $args parameter of profile
		 *
		 * @return array $id of profile
		 * @since 1.7
		 * @package freelanceengine
		 * @category
		 * @author Tambh
		 */
		public function fre_get_profile_id( $args = array() ) {
			global $user_ID;
			$default  = array(
				'post_type'      => PROFILE,
				'posts_per_page' => - 1,
				'post_status'    => array( 'publish', 'pending' )
			);
			$args     = wp_parse_args( $args, $default );
			$result   = get_posts( $args );
			$post_ids = array();
			foreach ( $result as $key => $value ) {
				array_push( $post_ids, $value->ID );
			}
			return $post_ids;
		}
		public function deleteMetaProfile() {
			global $wpdb;
			$request  = $_REQUEST;
			$response = array(
				'success' => false,
				'msg'     => __( "An error, please try again.", ET_DOMAIN )
			);
			$profile_id = get_user_meta( get_current_user_id(), 'user_profile_id', true );
			if ( ! empty( $request['ID'] ) ) {
				$meta_id = $request['ID'];
				$meta    = get_post_meta_by_id( $meta_id );
				if ( $profile_id == $meta->post_id ) {
					$delete = $wpdb->delete( $wpdb->postmeta, array( 'meta_id' => $meta_id ) );
					if ( $delete ) {
						$response = array(
							'success' => true,
							'msg'     => __( "Deleted successfully.", ET_DOMAIN )
						);
					}
				} else {
					$response = array(
						'success' => false,
						'msg'     => __( "You do not have permission to delete post.", ET_DOMAIN )
					);
				}
			}
			wp_send_json( $response );
		}
	}

	new Modified_Fre_ProfileAction();
}
add_action( 'after_setup_theme', 'child_theme_class' );

/*************************************
 * function to display bulletin posts 
 * (used by home-list-bulletins.php and page-list-bulletins.php)
 */
function list_bulletins( $type ) {
	global $wpdb;
	$bulletins = $wpdb->get_results( "SELECT * FROM " . $wpdb->postmeta . " WHERE meta_key = 'bulletin' ORDER BY meta_id DESC LIMIT 4" );
	$env_language = get_env_language();
	if ( !empty( $bulletins ) ) {
		$i = 1;
		foreach ($bulletins as $k => $bulletin) {
			if ( !empty( $bulletin->meta_value ) && is_serialized($bulletin->meta_value)) {
				$e_value = unserialize( $bulletin->meta_value );
			}
			if ( !empty( $bulletin->post_id)) {
				$name_author = $wpdb->get_var( "SELECT post_title FROM " . $wpdb->posts . " AS posts WHERE posts.ID = " . $bulletin->post_id );
				$id_post_author = $wpdb->get_var( "SELECT post_author FROM " . $wpdb->posts . " AS posts WHERE posts.ID = " . $bulletin->post_id );
				$languages = $wpdb->get_var( "SELECT meta_value FROM " . $wpdb->postmeta . " WHERE meta_key = 'languages' AND post_id = " . $bulletin->post_id );
			}
			// unserialize languages 
			if ( isset($languages) && is_serialized($languages)) {
				$languages = unserialize($languages);
			}
			// judging if should display this lawyer's bulletin post
			$isSameLang = false;
			foreach ($languages as $language) {
				if ( $env_language === strval(substr($language, 0, 2)) || 
						$env_language === "zh" && $language === "chinese" ) {
					$isSameLang = true;
				}
			}
			if ( !empty( $e_value ) && !empty( $name_author ) && $isSameLang ) { 
				if ( "home" === $type ) { ?>
				<div class="col-lg-6 col-md-12">
				<?php } ?>
					<div class="fre-freelancer-wrap">
						<div id=<?php echo "bulletin-list-wrap-" . $i ?> class="bulletin-title-wrap">
							<h2><?php echo $e_value['title'] ?></h2>
						</div> 
						<p>By <a href="<?php echo get_author_posts_url( $id_post_author ); ?>"><?php echo $name_author; ?></a></p>
						<div id=<?php echo "bulletin-content-wrap-" . $i ?> class="bulletin-content-wrap">
							<h3><?php echo apply_filters( 'the_content', $e_value['content'] ); ?></h3>
						</div>
					</div>
				<?php if ( "home" === $type ) { ?>
				</div>
				<?php 
				}
			}
			$i++;
		}
	}
}
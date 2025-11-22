<?php
/**
 * Dog Profiles Custom Post Type
 *
 * @package Caniincasa_My_Dog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Caniincasa_My_Dog_Post_Type {

	/**
	 * Register custom post type
	 */
	public static function register() {
		$labels = array(
			'name'                  => __( 'I Miei Cani', 'caniincasa-my-dog' ),
			'singular_name'         => __( 'Cane', 'caniincasa-my-dog' ),
			'menu_name'             => __( 'I Miei Cani', 'caniincasa-my-dog' ),
			'add_new'               => __( 'Aggiungi Cane', 'caniincasa-my-dog' ),
			'add_new_item'          => __( 'Aggiungi Nuovo Cane', 'caniincasa-my-dog' ),
			'edit_item'             => __( 'Modifica Cane', 'caniincasa-my-dog' ),
			'new_item'              => __( 'Nuovo Cane', 'caniincasa-my-dog' ),
			'view_item'             => __( 'Visualizza Cane', 'caniincasa-my-dog' ),
			'search_items'          => __( 'Cerca Cane', 'caniincasa-my-dog' ),
			'not_found'             => __( 'Nessun cane trovato', 'caniincasa-my-dog' ),
			'not_found_in_trash'    => __( 'Nessun cane nel cestino', 'caniincasa-my-dog' ),
			'all_items'             => __( 'Tutti i Cani', 'caniincasa-my-dog' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => false,  // Non pubblico
			'publicly_queryable'  => true,   // Ma query-able per owner
			'show_ui'             => false,  // Non mostrare in admin (usiamo frontend)
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'show_in_rest'        => false,
			'capability_type'     => array( 'dog_profile', 'dog_profiles' ),
			'map_meta_cap'        => true,
			'has_archive'         => false,
			'hierarchical'        => false,
			'menu_position'       => null,
			'menu_icon'           => 'dashicons-pets',
			'supports'            => array( 'title', 'thumbnail', 'author' ),
			'rewrite'             => array(
				'slug'       => 'my-dogs',
				'with_front' => false,
			),
		);

		register_post_type( 'dog_profile', $args );

		// Add custom capabilities to roles
		add_action( 'admin_init', array( __CLASS__, 'add_capabilities' ) );
	}

	/**
	 * Add custom capabilities
	 */
	public static function add_capabilities() {
		// Get subscriber role (base user role)
		$role = get_role( 'subscriber' );

		if ( $role ) {
			// Allow subscribers to manage their own dog profiles
			$role->add_cap( 'read_dog_profile' );
			$role->add_cap( 'read_private_dog_profiles' );
			$role->add_cap( 'edit_dog_profiles' );
			$role->add_cap( 'edit_published_dog_profiles' );
			$role->add_cap( 'publish_dog_profiles' );
			$role->add_cap( 'delete_dog_profiles' );
			$role->add_cap( 'delete_published_dog_profiles' );
		}

		// Admin can do everything
		$admin = get_role( 'administrator' );
		if ( $admin ) {
			$admin->add_cap( 'read_dog_profile' );
			$admin->add_cap( 'read_private_dog_profiles' );
			$admin->add_cap( 'edit_dog_profiles' );
			$admin->add_cap( 'edit_others_dog_profiles' );
			$admin->add_cap( 'edit_published_dog_profiles' );
			$admin->add_cap( 'publish_dog_profiles' );
			$admin->add_cap( 'delete_dog_profiles' );
			$admin->add_cap( 'delete_others_dog_profiles' );
			$admin->add_cap( 'delete_published_dog_profiles' );
		}
	}

	/**
	 * Check if user can view dog profile
	 *
	 * @param int $post_id Dog profile ID
	 * @param int $user_id User ID (default: current user)
	 * @return bool
	 */
	public static function user_can_view( $post_id, $user_id = null ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		if ( ! $user_id ) {
			return false;
		}

		$post = get_post( $post_id );

		if ( ! $post || $post->post_type !== 'dog_profile' ) {
			return false;
		}

		// Owner can view
		if ( $post->post_author == $user_id ) {
			return true;
		}

		// Admin can view
		if ( current_user_can( 'edit_others_dog_profiles' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get user's dogs
	 *
	 * @param int $user_id User ID (default: current user)
	 * @return array
	 */
	public static function get_user_dogs( $user_id = null ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		if ( ! $user_id ) {
			return array();
		}

		$args = array(
			'post_type'      => 'dog_profile',
			'post_status'    => 'publish',
			'author'         => $user_id,
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		);

		$query = new WP_Query( $args );
		return $query->posts;
	}

	/**
	 * Get dog age in years and months
	 *
	 * @param string $birth_date Birth date (Y-m-d format)
	 * @return array
	 */
	public static function calculate_age( $birth_date ) {
		if ( empty( $birth_date ) ) {
			return array(
				'years'  => 0,
				'months' => 0,
				'total_months' => 0,
			);
		}

		$birth = new DateTime( $birth_date );
		$today = new DateTime();
		$diff  = $today->diff( $birth );

		return array(
			'years'        => $diff->y,
			'months'       => $diff->m,
			'total_months' => ( $diff->y * 12 ) + $diff->m,
		);
	}

	/**
	 * Calculate dog age in human years
	 *
	 * @param string $birth_date Birth date
	 * @param string $size Dog size (small, medium, large)
	 * @return int
	 */
	public static function calculate_human_age( $birth_date, $size = 'medium' ) {
		$age = self::calculate_age( $birth_date );
		$months = $age['total_months'];

		if ( $months <= 0 ) {
			return 0;
		}

		// First 2 years calculation (common for all sizes)
		if ( $months <= 24 ) {
			// First year = 15 human years
			// Second year = 9 human years
			if ( $months <= 12 ) {
				return round( ( $months / 12 ) * 15 );
			} else {
				return 15 + round( ( ( $months - 12 ) / 12 ) * 9 );
			}
		}

		// After 2 years, rate varies by size
		$base_age = 24; // 2 years
		$years_after_two = $months - 24;

		$multipliers = array(
			'small'  => 4,  // Small dogs: +4 years per year
			'medium' => 5,  // Medium dogs: +5 years per year
			'large'  => 6,  // Large dogs: +6 years per year
			'giant'  => 7,  // Giant dogs: +7 years per year
		);

		$multiplier = isset( $multipliers[ $size ] ) ? $multipliers[ $size ] : 5;

		return 24 + round( ( $years_after_two / 12 ) * $multiplier );
	}
}

<?php
/**
 * Frontend Dashboard for Dog Profiles
 *
 * @package Caniincasa_My_Dog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Caniincasa_My_Dog_Dashboard {

	/**
	 * Initialize
	 */
	public static function init() {
		// Register shortcodes
		add_shortcode( 'my_dogs_dashboard', array( __CLASS__, 'dashboard_shortcode' ) );
		add_shortcode( 'my_dog_single', array( __CLASS__, 'single_dog_shortcode' ) );

		// Rewrite rules for dashboard
		add_action( 'init', array( __CLASS__, 'add_rewrite_rules' ) );
		add_filter( 'query_vars', array( __CLASS__, 'add_query_vars' ) );
		add_action( 'template_redirect', array( __CLASS__, 'dashboard_template_redirect' ) );
	}

	/**
	 * Add rewrite rules
	 */
	public static function add_rewrite_rules() {
		add_rewrite_rule( '^i-miei-cani/?$', 'index.php?my_dogs_dashboard=1', 'top' );
		add_rewrite_rule( '^i-miei-cani/aggiungi/?$', 'index.php?my_dogs_dashboard=add', 'top' );
		add_rewrite_rule( '^i-miei-cani/([0-9]+)/?$', 'index.php?my_dogs_dashboard=view&dog_id=$matches[1]', 'top' );
		add_rewrite_rule( '^i-miei-cani/([0-9]+)/modifica/?$', 'index.php?my_dogs_dashboard=edit&dog_id=$matches[1]', 'top' );
	}

	/**
	 * Add query vars
	 */
	public static function add_query_vars( $vars ) {
		$vars[] = 'my_dogs_dashboard';
		$vars[] = 'dog_id';
		return $vars;
	}

	/**
	 * Template redirect
	 */
	public static function dashboard_template_redirect() {
		$dashboard = get_query_var( 'my_dogs_dashboard' );

		if ( ! $dashboard ) {
			return;
		}

		// User must be logged in
		if ( ! is_user_logged_in() ) {
			wp_redirect( wp_login_url( home_url( '/i-miei-cani/' ) ) );
			exit;
		}

		// Load template
		include CANIINCASA_MY_DOG_PATH . 'templates/dashboard.php';
		exit;
	}

	/**
	 * Dashboard shortcode
	 */
	public static function dashboard_shortcode( $atts ) {
		if ( ! is_user_logged_in() ) {
			return '<p>' . __( 'Devi effettuare il login per visualizzare questa pagina.', 'caniincasa-my-dog' ) . '</p>';
		}

		$dogs = Caniincasa_My_Dog_Post_Type::get_user_dogs();

		ob_start();
		include CANIINCASA_MY_DOG_PATH . 'templates/dashboard-list.php';
		return ob_get_clean();
	}

	/**
	 * Single dog shortcode
	 */
	public static function single_dog_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'id' => 0,
		), $atts );

		$dog_id = intval( $atts['id'] );

		if ( ! $dog_id || ! Caniincasa_My_Dog_Post_Type::user_can_view( $dog_id ) ) {
			return '<p>' . __( 'Cane non trovato.', 'caniincasa-my-dog' ) . '</p>';
		}

		ob_start();
		include CANIINCASA_MY_DOG_PATH . 'templates/single-dog.php';
		return ob_get_clean();
	}

	/**
	 * Render dog card
	 */
	public static function render_dog_card( $dog ) {
		$dog_id = $dog->ID;
		$photo  = get_the_post_thumbnail_url( $dog_id, 'medium' );

		// Get ACF fields
		$razza      = get_field( 'dog_razza', $dog_id );
		$razza_custom = get_field( 'dog_razza_custom', $dog_id );
		$birth_date = get_field( 'dog_birth_date', $dog_id );
		$gender     = get_field( 'dog_gender', $dog_id );
		$weight     = get_field( 'dog_weight', $dog_id );
		$size       = get_field( 'dog_size', $dog_id );

		// Calculate age
		$age = Caniincasa_My_Dog_Post_Type::calculate_age( $birth_date );
		$human_age = Caniincasa_My_Dog_Post_Type::calculate_human_age( $birth_date, $size );

		// Get razza name
		$razza_name = '';
		if ( $razza ) {
			$razza_name = get_the_title( $razza );
		} elseif ( $razza_custom ) {
			$razza_name = $razza_custom;
		}

		?>
		<div class="dog-card" data-dog-id="<?php echo esc_attr( $dog_id ); ?>">
			<div class="dog-card-photo">
				<?php if ( $photo ) : ?>
					<img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $dog->post_title ); ?>">
				<?php else : ?>
					<div class="dog-placeholder">
						<svg width="80" height="80" viewBox="0 0 24 24" fill="none">
							<path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z" fill="#ccc"/>
						</svg>
					</div>
				<?php endif; ?>
			</div>

			<div class="dog-card-content">
				<h3 class="dog-name"><?php echo esc_html( $dog->post_title ); ?></h3>

				<?php if ( $razza_name ) : ?>
					<p class="dog-breed"><?php echo esc_html( $razza_name ); ?></p>
				<?php endif; ?>

				<div class="dog-info">
					<?php if ( $birth_date ) : ?>
						<div class="dog-info-item">
							<span class="label">📅 Età:</span>
							<span class="value">
								<?php
								if ( $age['years'] > 0 ) {
									printf( __( '%d anni', 'caniincasa-my-dog' ), $age['years'] );
									if ( $age['months'] > 0 ) {
										printf( __( ' e %d mesi', 'caniincasa-my-dog' ), $age['months'] );
									}
								} else {
									printf( __( '%d mesi', 'caniincasa-my-dog' ), $age['months'] );
								}
								?>
								<span class="human-age">(<?php echo esc_html( $human_age ); ?> anni umani)</span>
							</span>
						</div>
					<?php endif; ?>

					<?php if ( $gender ) : ?>
						<div class="dog-info-item">
							<span class="label"><?php echo $gender === 'male' ? '♂️' : '♀️'; ?> Sesso:</span>
							<span class="value"><?php echo $gender === 'male' ? __( 'Maschio', 'caniincasa-my-dog' ) : __( 'Femmina', 'caniincasa-my-dog' ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( $weight ) : ?>
						<div class="dog-info-item">
							<span class="label">⚖️ Peso:</span>
							<span class="value"><?php echo esc_html( $weight ); ?> kg</span>
						</div>
					<?php endif; ?>
				</div>

				<div class="dog-card-actions">
					<a href="<?php echo home_url( '/i-miei-cani/' . $dog_id . '/' ); ?>" class="btn btn-primary">
						<?php _e( 'Visualizza', 'caniincasa-my-dog' ); ?>
					</a>
					<a href="<?php echo home_url( '/i-miei-cani/' . $dog_id . '/modifica/' ); ?>" class="btn btn-secondary">
						<?php _e( 'Modifica', 'caniincasa-my-dog' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render add/edit dog form
	 */
	public static function render_dog_form( $dog_id = 0 ) {
		$is_edit = $dog_id > 0;

		if ( $is_edit && ! Caniincasa_My_Dog_Post_Type::user_can_view( $dog_id ) ) {
			echo '<p>' . __( 'Non hai i permessi per modificare questo cane.', 'caniincasa-my-dog' ) . '</p>';
			return;
		}

		$dog_name = $is_edit ? get_the_title( $dog_id ) : '';

		?>
		<div class="dog-form-wrapper">
			<h2><?php echo $is_edit ? __( 'Modifica Cane', 'caniincasa-my-dog' ) : __( 'Aggiungi Nuovo Cane', 'caniincasa-my-dog' ); ?></h2>

			<form id="dog-form" class="dog-form" method="post" enctype="multipart/form-data">
				<?php wp_nonce_field( 'save_dog_profile', 'dog_profile_nonce' ); ?>
				<input type="hidden" name="action" value="save_dog_profile">
				<input type="hidden" name="dog_id" value="<?php echo esc_attr( $dog_id ); ?>">

				<div class="form-group">
					<label for="dog_name"><?php _e( 'Nome del Cane *', 'caniincasa-my-dog' ); ?></label>
					<input type="text" id="dog_name" name="dog_name" value="<?php echo esc_attr( $dog_name ); ?>" required>
				</div>

				<div class="form-group">
					<label for="dog_photo"><?php _e( 'Foto', 'caniincasa-my-dog' ); ?></label>
					<input type="file" id="dog_photo" name="dog_photo" accept="image/*">
					<?php if ( $is_edit && has_post_thumbnail( $dog_id ) ) : ?>
						<div class="current-photo">
							<?php echo get_the_post_thumbnail( $dog_id, 'thumbnail' ); ?>
						</div>
					<?php endif; ?>
				</div>

				<?php
				// Render ACF form
				if ( function_exists( 'acf_form' ) ) {
					acf_form( array(
						'post_id'      => $dog_id ? $dog_id : 'new_post',
						'post_title'   => false,
						'post_content' => false,
						'submit_value' => $is_edit ? __( 'Aggiorna Cane', 'caniincasa-my-dog' ) : __( 'Salva Cane', 'caniincasa-my-dog' ),
						'updated_message' => false,
					) );
				}
				?>
			</form>
		</div>
		<?php
	}
}

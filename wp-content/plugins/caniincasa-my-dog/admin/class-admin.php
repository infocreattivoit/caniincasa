<?php
/**
 * Admin Panel
 *
 * @package Caniincasa_My_Dog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Caniincasa_My_Dog_Admin {

	/**
	 * Initialize
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_admin_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
	}

	/**
	 * Add admin menu
	 */
	public static function add_admin_menu() {
		add_menu_page(
			__( 'Caniincasa My Dog', 'caniincasa-my-dog' ),
			__( 'My Dog', 'caniincasa-my-dog' ),
			'manage_options',
			'caniincasa-my-dog',
			array( __CLASS__, 'render_admin_page' ),
			'dashicons-pets',
			25
		);

		add_submenu_page(
			'caniincasa-my-dog',
			__( 'Impostazioni', 'caniincasa-my-dog' ),
			__( 'Impostazioni', 'caniincasa-my-dog' ),
			'manage_options',
			'caniincasa-my-dog',
			array( __CLASS__, 'render_admin_page' )
		);

		add_submenu_page(
			'caniincasa-my-dog',
			__( 'Iscritti Newsletter', 'caniincasa-my-dog' ),
			__( 'Newsletter', 'caniincasa-my-dog' ),
			'manage_options',
			'caniincasa-my-dog-newsletter',
			array( __CLASS__, 'render_newsletter_page' )
		);

		add_submenu_page(
			'caniincasa-my-dog',
			__( 'Statistiche', 'caniincasa-my-dog' ),
			__( 'Statistiche', 'caniincasa-my-dog' ),
			'manage_options',
			'caniincasa-my-dog-stats',
			array( __CLASS__, 'render_stats_page' )
		);
	}

	/**
	 * Register settings
	 */
	public static function register_settings() {
		register_setting( 'caniincasa_my_dog_settings', 'caniincasa_my_dog_newsletter_enabled' );
	}

	/**
	 * Render admin page
	 */
	public static function render_admin_page() {
		?>
		<div class="wrap">
			<h1><?php _e( 'Caniincasa My Dog - Impostazioni', 'caniincasa-my-dog' ); ?></h1>

			<div class="my-dog-admin-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-top: 30px;">
				<div class="main-content">
					<form method="post" action="options.php">
						<?php
						settings_fields( 'caniincasa_my_dog_settings' );
						do_settings_sections( 'caniincasa_my_dog_settings' );
						?>

						<table class="form-table">
							<tr>
								<th scope="row">
									<label><?php _e( 'Newsletter Block', 'caniincasa-my-dog' ); ?></label>
								</th>
								<td>
									<label>
										<input
											type="checkbox"
											name="caniincasa_my_dog_newsletter_enabled"
											value="1"
											<?php checked( get_option( 'caniincasa_my_dog_newsletter_enabled', '1' ), '1' ); ?>
										>
										<?php _e( 'Mostra blocco newsletter pre-footer', 'caniincasa-my-dog' ); ?>
									</label>
									<p class="description">
										<?php _e( 'Attiva o disattiva il blocco iscrizione newsletter visualizzato prima del footer su tutte le pagine.', 'caniincasa-my-dog' ); ?>
									</p>
								</td>
							</tr>
						</table>

						<?php submit_button(); ?>
					</form>

					<hr>

					<h2><?php _e( 'Shortcode Disponibili', 'caniincasa-my-dog' ); ?></h2>
					<div class="shortcodes-list">
						<div class="shortcode-item">
							<code>[my_dogs_dashboard]</code>
							<p><?php _e( 'Dashboard completa con lista dei cani dell\'utente', 'caniincasa-my-dog' ); ?></p>
						</div>

						<div class="shortcode-item">
							<code>[my_dog_single id="123"]</code>
							<p><?php _e( 'Visualizza un singolo cane (sostituire 123 con ID)', 'caniincasa-my-dog' ); ?></p>
						</div>

						<div class="shortcode-item">
							<code>[dog_vaccination_calendar dog_id="123"]</code>
							<p><?php _e( 'Calendario vaccinazioni per un cane specifico', 'caniincasa-my-dog' ); ?></p>
						</div>

						<div class="shortcode-item">
							<code>[dog_age_calculator]</code>
							<p><?php _e( 'Calcolatore età umana del cane', 'caniincasa-my-dog' ); ?></p>
						</div>

						<div class="shortcode-item">
							<code>[dog_weight_tracker dog_id="123"]</code>
							<p><?php _e( 'Tracker peso con grafico', 'caniincasa-my-dog' ); ?></p>
						</div>

						<div class="shortcode-item">
							<code>[dog_food_calculator]</code>
							<p><?php _e( 'Calcolatore quantità cibo giornaliero', 'caniincasa-my-dog' ); ?></p>
						</div>

						<div class="shortcode-item">
							<code>[newsletter_signup]</code>
							<p><?php _e( 'Form iscrizione newsletter (standalone)', 'caniincasa-my-dog' ); ?></p>
						</div>
					</div>

					<hr>

					<h2><?php _e( 'URL Dashboard', 'caniincasa-my-dog' ); ?></h2>
					<p><?php _e( 'URL principale per la gestione dei cani:', 'caniincasa-my-dog' ); ?></p>
					<p><a href="<?php echo home_url( '/i-miei-cani/' ); ?>" target="_blank">
						<code><?php echo home_url( '/i-miei-cani/' ); ?></code>
					</a></p>
					<p class="description">
						<?php _e( 'Puoi anche creare una pagina WordPress e inserire lo shortcode [my_dogs_dashboard]', 'caniincasa-my-dog' ); ?>
					</p>
				</div>

				<div class="sidebar">
					<div class="postbox">
						<h2 class="hndle"><span><?php _e( 'Informazioni Plugin', 'caniincasa-my-dog' ); ?></span></h2>
						<div class="inside">
							<p><strong><?php _e( 'Versione:', 'caniincasa-my-dog' ); ?></strong> <?php echo CANIINCASA_MY_DOG_VERSION; ?></p>
							<p><strong><?php _e( 'Autore:', 'caniincasa-my-dog' ); ?></strong> Caniincasa</p>
							<hr>
							<?php
							global $wpdb;
							$total_dogs = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'dog_profile' AND post_status = 'publish'" );
							$total_vaccinations = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}dog_vaccinations" );
							$total_weights = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}dog_weight_tracker" );
							$total_notes = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}dog_notes" );
							$subscribers = count( get_option( 'caniincasa_newsletter_subscribers', array() ) );
							?>
							<p><strong>📊 <?php _e( 'Statistiche:', 'caniincasa-my-dog' ); ?></strong></p>
							<ul>
								<li><?php printf( __( 'Cani registrati: %d', 'caniincasa-my-dog' ), $total_dogs ); ?></li>
								<li><?php printf( __( 'Vaccinazioni: %d', 'caniincasa-my-dog' ), $total_vaccinations ); ?></li>
								<li><?php printf( __( 'Pesate: %d', 'caniincasa-my-dog' ), $total_weights ); ?></li>
								<li><?php printf( __( 'Note: %d', 'caniincasa-my-dog' ), $total_notes ); ?></li>
								<li><?php printf( __( 'Iscritti newsletter: %d', 'caniincasa-my-dog' ), $subscribers ); ?></li>
							</ul>
						</div>
					</div>

					<div class="postbox">
						<h2 class="hndle"><span><?php _e( 'Funzionalità', 'caniincasa-my-dog' ); ?></span></h2>
						<div class="inside">
							<ul style="line-height: 2;">
								<li>✅ Profili cani privati per utente</li>
								<li>✅ Calendario vaccinazioni con reminder</li>
								<li>✅ Esportazione PDF per veterinario</li>
								<li>✅ Tracker peso con grafico</li>
								<li>✅ Diario note giornaliere</li>
								<li>✅ Calcolatore età umana</li>
								<li>✅ Calcolatore cibo</li>
								<li>✅ Blocco newsletter</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>

		<style>
			.shortcode-item {
				margin: 15px 0;
				padding: 15px;
				background: #f9f9f9;
				border-left: 3px solid #FF6B35;
			}
			.shortcode-item code {
				background: #fff;
				padding: 5px 10px;
				border: 1px solid #ddd;
				border-radius: 3px;
				font-size: 14px;
			}
			.shortcode-item p {
				margin: 10px 0 0;
				color: #666;
			}
		</style>
		<?php
	}

	/**
	 * Render newsletter page
	 */
	public static function render_newsletter_page() {
		$subscribers = get_option( 'caniincasa_newsletter_subscribers', array() );

		if ( isset( $_POST['export_csv'] ) ) {
			self::export_subscribers_csv( $subscribers );
		}

		?>
		<div class="wrap">
			<h1><?php _e( 'Iscritti Newsletter', 'caniincasa-my-dog' ); ?></h1>

			<p><?php printf( __( 'Totale iscritti: %d', 'caniincasa-my-dog' ), count( $subscribers ) ); ?></p>

			<form method="post">
				<button type="submit" name="export_csv" class="button">
					<?php _e( 'Esporta CSV', 'caniincasa-my-dog' ); ?>
				</button>
			</form>

			<table class="wp-list-table widefat fixed striped" style="margin-top: 20px;">
				<thead>
					<tr>
						<th><?php _e( '#', 'caniincasa-my-dog' ); ?></th>
						<th><?php _e( 'Email', 'caniincasa-my-dog' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( ! empty( $subscribers ) ) : ?>
						<?php $i = 1; foreach ( $subscribers as $email ) : ?>
							<tr>
								<td><?php echo $i++; ?></td>
								<td><?php echo esc_html( $email ); ?></td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr>
							<td colspan="2"><?php _e( 'Nessun iscritto.', 'caniincasa-my-dog' ); ?></td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Export subscribers CSV
	 */
	private static function export_subscribers_csv( $subscribers ) {
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=newsletter-subscribers-' . date( 'Y-m-d' ) . '.csv' );

		$output = fopen( 'php://output', 'w' );
		fputcsv( $output, array( 'Email', 'Data Iscrizione' ) );

		foreach ( $subscribers as $email ) {
			fputcsv( $output, array( $email, date( 'Y-m-d H:i:s' ) ) );
		}

		fclose( $output );
		exit;
	}

	/**
	 * Render stats page
	 */
	public static function render_stats_page() {
		global $wpdb;

		// Get stats
		$stats = array(
			'total_dogs' => $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'dog_profile' AND post_status = 'publish'" ),
			'total_users' => $wpdb->get_var( "SELECT COUNT(DISTINCT post_author) FROM {$wpdb->posts} WHERE post_type = 'dog_profile' AND post_status = 'publish'" ),
			'total_vaccinations' => $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}dog_vaccinations" ),
			'upcoming_vaccinations' => $wpdb->get_var( $wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->prefix}dog_vaccinations WHERE next_date BETWEEN %s AND %s",
				date( 'Y-m-d' ),
				date( 'Y-m-d', strtotime( '+30 days' ) )
			) ),
			'total_weights' => $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}dog_weight_tracker" ),
			'total_notes' => $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}dog_notes" ),
		);

		// Top razze
		$top_razze = $wpdb->get_results( "
			SELECT pm.meta_value as razza_id, COUNT(*) as count
			FROM {$wpdb->postmeta} pm
			WHERE pm.meta_key = 'dog_razza'
			AND pm.meta_value != ''
			GROUP BY pm.meta_value
			ORDER BY count DESC
			LIMIT 10
		" );

		?>
		<div class="wrap">
			<h1><?php _e( 'Statistiche', 'caniincasa-my-dog' ); ?></h1>

			<div class="stats-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin: 30px 0;">
				<div class="stat-box" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
					<h3 style="margin: 0 0 10px; color: #666; font-size: 14px;">🐕 Cani Registrati</h3>
					<p style="font-size: 36px; font-weight: bold; margin: 0; color: #FF6B35;"><?php echo number_format( $stats['total_dogs'] ); ?></p>
				</div>

				<div class="stat-box" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
					<h3 style="margin: 0 0 10px; color: #666; font-size: 14px;">👤 Utenti Attivi</h3>
					<p style="font-size: 36px; font-weight: bold; margin: 0; color: #667eea;"><?php echo number_format( $stats['total_users'] ); ?></p>
				</div>

				<div class="stat-box" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
					<h3 style="margin: 0 0 10px; color: #666; font-size: 14px;">💉 Vaccinazioni Totali</h3>
					<p style="font-size: 36px; font-weight: bold; margin: 0; color: #4CAF50;"><?php echo number_format( $stats['total_vaccinations'] ); ?></p>
				</div>

				<div class="stat-box" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
					<h3 style="margin: 0 0 10px; color: #666; font-size: 14px;">⚠️ Vaccinazioni in Scadenza</h3>
					<p style="font-size: 36px; font-weight: bold; margin: 0; color: #FFC107;"><?php echo number_format( $stats['upcoming_vaccinations'] ); ?></p>
					<p style="font-size: 12px; color: #666; margin: 5px 0 0;"><?php _e( 'prossimi 30 giorni', 'caniincasa-my-dog' ); ?></p>
				</div>

				<div class="stat-box" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
					<h3 style="margin: 0 0 10px; color: #666; font-size: 14px;">⚖️ Pesate Registrate</h3>
					<p style="font-size: 36px; font-weight: bold; margin: 0; color: #9C27B0;"><?php echo number_format( $stats['total_weights'] ); ?></p>
				</div>

				<div class="stat-box" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
					<h3 style="margin: 0 0 10px; color: #666; font-size: 14px;">📝 Note / Diario</h3>
					<p style="font-size: 36px; font-weight: bold; margin: 0; color: #FF5722;"><?php echo number_format( $stats['total_notes'] ); ?></p>
				</div>
			</div>

			<?php if ( ! empty( $top_razze ) ) : ?>
				<h2><?php _e( 'Top 10 Razze Più Popolari', 'caniincasa-my-dog' ); ?></h2>
				<table class="wp-list-table widefat fixed striped">
					<thead>
						<tr>
							<th><?php _e( 'Posizione', 'caniincasa-my-dog' ); ?></th>
							<th><?php _e( 'Razza', 'caniincasa-my-dog' ); ?></th>
							<th><?php _e( 'Numero Cani', 'caniincasa-my-dog' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $pos = 1; foreach ( $top_razze as $razza ) : ?>
							<tr>
								<td><?php echo $pos++; ?></td>
								<td><?php echo esc_html( get_the_title( $razza->razza_id ) ); ?></td>
								<td><?php echo number_format( $razza->count ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
		<?php
	}
}

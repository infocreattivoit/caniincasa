<?php
/**
 * Vaccination Calendar & Reminders
 *
 * @package Caniincasa_My_Dog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Caniincasa_My_Dog_Calendar {

	/**
	 * Initialize
	 */
	public static function init() {
		// Cron job for reminders
		add_action( 'caniincasa_vaccination_reminders', array( __CLASS__, 'send_reminders' ) );

		// Schedule cron if not scheduled
		if ( ! wp_next_scheduled( 'caniincasa_vaccination_reminders' ) ) {
			wp_schedule_event( time(), 'daily', 'caniincasa_vaccination_reminders' );
		}

		// Shortcode
		add_shortcode( 'dog_vaccination_calendar', array( __CLASS__, 'calendar_shortcode' ) );
	}

	/**
	 * Get dog vaccinations
	 */
	public static function get_vaccinations( $dog_id, $limit = null ) {
		global $wpdb;

		$query = $wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}dog_vaccinations
			WHERE dog_id = %d
			ORDER BY vaccine_date DESC",
			$dog_id
		);

		if ( $limit ) {
			$query .= $wpdb->prepare( " LIMIT %d", $limit );
		}

		return $wpdb->get_results( $query );
	}

	/**
	 * Get upcoming vaccinations
	 */
	public static function get_upcoming( $dog_id = null, $days_ahead = 30 ) {
		global $wpdb;

		$today = date( 'Y-m-d' );
		$future_date = date( 'Y-m-d', strtotime( "+{$days_ahead} days" ) );

		if ( $dog_id ) {
			$query = $wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}dog_vaccinations
				WHERE dog_id = %d
				AND next_date IS NOT NULL
				AND next_date BETWEEN %s AND %s
				ORDER BY next_date ASC",
				$dog_id,
				$today,
				$future_date
			);
		} else {
			// All user's dogs
			$user_id = get_current_user_id();
			$dogs = Caniincasa_My_Dog_Post_Type::get_user_dogs( $user_id );
			$dog_ids = wp_list_pluck( $dogs, 'ID' );

			if ( empty( $dog_ids ) ) {
				return array();
			}

			$placeholders = implode( ',', array_fill( 0, count( $dog_ids ), '%d' ) );

			$query = $wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}dog_vaccinations
				WHERE dog_id IN ($placeholders)
				AND next_date IS NOT NULL
				AND next_date BETWEEN %s AND %s
				ORDER BY next_date ASC",
				array_merge( $dog_ids, array( $today, $future_date ) )
			);
		}

		return $wpdb->get_results( $query );
	}

	/**
	 * Send reminders (cron job)
	 */
	public static function send_reminders() {
		global $wpdb;

		// Get vaccinations due in next 7 days that haven't been reminded
		$remind_date = date( 'Y-m-d', strtotime( '+7 days' ) );
		$today = date( 'Y-m-d' );

		$vaccinations = $wpdb->get_results( $wpdb->prepare(
			"SELECT v.*, p.post_author
			FROM {$wpdb->prefix}dog_vaccinations v
			INNER JOIN {$wpdb->posts} p ON v.dog_id = p.ID
			WHERE v.next_date BETWEEN %s AND %s
			AND v.reminder_sent = 0
			AND p.post_type = 'dog_profile'
			AND p.post_status = 'publish'",
			$today,
			$remind_date
		) );

		foreach ( $vaccinations as $vacc ) {
			$user = get_userdata( $vacc->post_author );

			if ( ! $user || ! $user->user_email ) {
				continue;
			}

			$dog_name = get_the_title( $vacc->dog_id );

			// Send email
			$subject = sprintf(
				__( 'Promemoria: Vaccinazione di %s in scadenza', 'caniincasa-my-dog' ),
				$dog_name
			);

			$message = sprintf(
				__( "Ciao,\n\nti ricordiamo che la vaccinazione \"%s\" per il tuo cane %s è in scadenza il %s.\n\nDettagli:\n- Vaccino: %s\n- Data prossima somministrazione: %s\n- Veterinario: %s\n\nNote: %s\n\nVisita il tuo profilo: %s\n\nCaniincasa.it", 'caniincasa-my-dog' ),
				$vacc->vaccine_name,
				$dog_name,
				date_i18n( 'd/m/Y', strtotime( $vacc->next_date ) ),
				$vacc->vaccine_name,
				date_i18n( 'd/m/Y', strtotime( $vacc->next_date ) ),
				$vacc->veterinarian ? $vacc->veterinarian : __( 'Non specificato', 'caniincasa-my-dog' ),
				$vacc->notes ? $vacc->notes : __( 'Nessuna', 'caniincasa-my-dog' ),
				home_url( '/i-miei-cani/' . $vacc->dog_id . '/' )
			);

			$sent = wp_mail( $user->user_email, $subject, $message );

			if ( $sent ) {
				// Mark as reminded
				$wpdb->update(
					$wpdb->prefix . 'dog_vaccinations',
					array( 'reminder_sent' => 1 ),
					array( 'id' => $vacc->id ),
					array( '%d' ),
					array( '%d' )
				);
			}
		}
	}

	/**
	 * Calendar shortcode
	 */
	public static function calendar_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'dog_id' => 0,
		), $atts );

		$dog_id = intval( $atts['dog_id'] );

		if ( ! is_user_logged_in() ) {
			return '<p>' . __( 'Devi effettuare il login.', 'caniincasa-my-dog' ) . '</p>';
		}

		if ( $dog_id && ! Caniincasa_My_Dog_Post_Type::user_can_view( $dog_id ) ) {
			return '<p>' . __( 'Cane non trovato.', 'caniincasa-my-dog' ) . '</p>';
		}

		$vaccinations = self::get_vaccinations( $dog_id );
		$upcoming = self::get_upcoming( $dog_id );

		ob_start();
		include CANIINCASA_MY_DOG_PATH . 'templates/vaccination-calendar.php';
		return ob_get_clean();
	}

	/**
	 * Render vaccination calendar
	 */
	public static function render_calendar( $dog_id ) {
		$vaccinations = self::get_vaccinations( $dog_id );
		$upcoming = self::get_upcoming( $dog_id );

		?>
		<div class="vaccination-calendar">
			<h3><?php _e( 'Calendario Vaccinazioni', 'caniincasa-my-dog' ); ?></h3>

			<?php if ( ! empty( $upcoming ) ) : ?>
				<div class="upcoming-vaccinations alert alert-warning">
					<h4>⚠️ <?php _e( 'In scadenza nei prossimi 30 giorni', 'caniincasa-my-dog' ); ?></h4>
					<ul>
						<?php foreach ( $upcoming as $vacc ) : ?>
							<li>
								<strong><?php echo esc_html( $vacc->vaccine_name ); ?></strong> -
								<?php echo date_i18n( 'd/m/Y', strtotime( $vacc->next_date ) ); ?>
								<?php if ( $vacc->veterinarian ) : ?>
									(<?php echo esc_html( $vacc->veterinarian ); ?>)
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<!-- Add vaccination form -->
			<div class="add-vaccination-form">
				<h4><?php _e( 'Aggiungi Vaccinazione', 'caniincasa-my-dog' ); ?></h4>
				<form id="add-vaccination-form">
					<input type="hidden" name="dog_id" value="<?php echo esc_attr( $dog_id ); ?>">

					<div class="form-row">
						<div class="form-group">
							<label><?php _e( 'Nome Vaccino *', 'caniincasa-my-dog' ); ?></label>
							<input type="text" name="vaccine_name" required>
						</div>

						<div class="form-group">
							<label><?php _e( 'Data Somministrazione *', 'caniincasa-my-dog' ); ?></label>
							<input type="date" name="vaccine_date" required>
						</div>
					</div>

					<div class="form-row">
						<div class="form-group">
							<label><?php _e( 'Prossima Data (Richiamo)', 'caniincasa-my-dog' ); ?></label>
							<input type="date" name="next_date">
						</div>

						<div class="form-group">
							<label><?php _e( 'Veterinario', 'caniincasa-my-dog' ); ?></label>
							<input type="text" name="veterinarian">
						</div>
					</div>

					<div class="form-group">
						<label><?php _e( 'Note', 'caniincasa-my-dog' ); ?></label>
						<textarea name="notes" rows="2"></textarea>
					</div>

					<button type="submit" class="btn btn-primary"><?php _e( 'Aggiungi', 'caniincasa-my-dog' ); ?></button>
				</form>
			</div>

			<!-- Vaccination history -->
			<div class="vaccination-history">
				<h4><?php _e( 'Storico Vaccinazioni', 'caniincasa-my-dog' ); ?></h4>

				<?php if ( ! empty( $vaccinations ) ) : ?>
					<table class="vaccinations-table">
						<thead>
							<tr>
								<th><?php _e( 'Vaccino', 'caniincasa-my-dog' ); ?></th>
								<th><?php _e( 'Data', 'caniincasa-my-dog' ); ?></th>
								<th><?php _e( 'Prossima', 'caniincasa-my-dog' ); ?></th>
								<th><?php _e( 'Veterinario', 'caniincasa-my-dog' ); ?></th>
								<th><?php _e( 'Note', 'caniincasa-my-dog' ); ?></th>
								<th><?php _e( 'Azioni', 'caniincasa-my-dog' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $vaccinations as $vacc ) : ?>
								<tr>
									<td><?php echo esc_html( $vacc->vaccine_name ); ?></td>
									<td><?php echo date_i18n( 'd/m/Y', strtotime( $vacc->vaccine_date ) ); ?></td>
									<td>
										<?php
										if ( $vacc->next_date ) {
											echo date_i18n( 'd/m/Y', strtotime( $vacc->next_date ) );
											$days_until = floor( ( strtotime( $vacc->next_date ) - time() ) / ( 60 * 60 * 24 ) );
											if ( $days_until <= 30 && $days_until >= 0 ) {
												echo ' <span class="badge badge-warning">' . sprintf( __( 'tra %d giorni', 'caniincasa-my-dog' ), $days_until ) . '</span>';
											} elseif ( $days_until < 0 ) {
												echo ' <span class="badge badge-danger">' . __( 'scaduta', 'caniincasa-my-dog' ) . '</span>';
											}
										} else {
											echo '-';
										}
										?>
									</td>
									<td><?php echo esc_html( $vacc->veterinarian ? $vacc->veterinarian : '-' ); ?></td>
									<td><?php echo esc_html( $vacc->notes ? wp_trim_words( $vacc->notes, 10 ) : '-' ); ?></td>
									<td>
										<button class="btn btn-sm btn-danger delete-vaccination" data-vacc-id="<?php echo esc_attr( $vacc->id ); ?>">
											<?php _e( 'Elimina', 'caniincasa-my-dog' ); ?>
										</button>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php else : ?>
					<p class="no-data"><?php _e( 'Nessuna vaccinazione registrata.', 'caniincasa-my-dog' ); ?></p>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}

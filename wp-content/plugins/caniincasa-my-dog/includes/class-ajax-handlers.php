<?php
/**
 * AJAX Handlers
 *
 * @package Caniincasa_My_Dog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Caniincasa_My_Dog_AJAX {

	/**
	 * Initialize
	 */
	public static function init() {
		// Save dog profile
		add_action( 'wp_ajax_save_dog_profile', array( __CLASS__, 'save_dog_profile' ) );

		// Delete dog profile
		add_action( 'wp_ajax_delete_dog_profile', array( __CLASS__, 'delete_dog_profile' ) );

		// Add vaccination
		add_action( 'wp_ajax_add_vaccination', array( __CLASS__, 'add_vaccination' ) );

		// Delete vaccination
		add_action( 'wp_ajax_delete_vaccination', array( __CLASS__, 'delete_vaccination' ) );

		// Add weight entry
		add_action( 'wp_ajax_add_weight_entry', array( __CLASS__, 'add_weight_entry' ) );

		// Add note
		add_action( 'wp_ajax_add_dog_note', array( __CLASS__, 'add_dog_note' ) );

		// Newsletter signup
		add_action( 'wp_ajax_newsletter_signup', array( __CLASS__, 'newsletter_signup' ) );
		add_action( 'wp_ajax_nopriv_newsletter_signup', array( __CLASS__, 'newsletter_signup' ) );
	}

	/**
	 * Save dog profile
	 */
	public static function save_dog_profile() {
		check_ajax_referer( 'save_dog_profile', 'dog_profile_nonce' );

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => __( 'Devi effettuare il login.', 'caniincasa-my-dog' ) ) );
		}

		$dog_id   = isset( $_POST['dog_id'] ) ? intval( $_POST['dog_id'] ) : 0;
		$dog_name = isset( $_POST['dog_nome'] ) ? sanitize_text_field( $_POST['dog_nome'] ) : '';

		if ( empty( $dog_name ) ) {
			wp_send_json_error( array( 'message' => __( 'Il nome è obbligatorio.', 'caniincasa-my-dog' ) ) );
		}

		$user_id = get_current_user_id();

		// Check permissions for edit
		if ( $dog_id && ! Caniincasa_My_Dog_Post_Type::user_can_view( $dog_id, $user_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Non hai i permessi.', 'caniincasa-my-dog' ) ) );
		}

		// Create or update post
		$post_data = array(
			'post_title'  => $dog_name,
			'post_type'   => 'dog_profile',
			'post_status' => 'publish',
			'post_author' => $user_id,
		);

		if ( $dog_id ) {
			$post_data['ID'] = $dog_id;
			$result = wp_update_post( $post_data );
		} else {
			$result = wp_insert_post( $post_data );
			$dog_id = $result;
		}

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => __( 'Errore nel salvataggio.', 'caniincasa-my-dog' ) ) );
		}

		// Save ACF fields - ALL OF THEM
		update_field( 'nome', $dog_name, $dog_id );

		// Basic info
		if ( isset( $_POST['dog_razza_custom'] ) ) {
			update_field( 'dog_razza_custom', sanitize_text_field( $_POST['dog_razza_custom'] ), $dog_id );
		}
		if ( isset( $_POST['dog_gender'] ) ) {
			update_field( 'dog_gender', sanitize_text_field( $_POST['dog_gender'] ), $dog_id );
		}
		if ( isset( $_POST['dog_birth_date'] ) ) {
			update_field( 'dog_birth_date', sanitize_text_field( $_POST['dog_birth_date'] ), $dog_id );
		}
		if ( isset( $_POST['dog_size'] ) ) {
			update_field( 'dog_size', sanitize_text_field( $_POST['dog_size'] ), $dog_id );
		}
		if ( isset( $_POST['dog_weight'] ) ) {
			update_field( 'dog_weight', floatval( $_POST['dog_weight'] ), $dog_id );
		}
		if ( isset( $_POST['dog_color'] ) ) {
			update_field( 'dog_color', sanitize_text_field( $_POST['dog_color'] ), $dog_id );
		}
		update_field( 'dog_neutered', isset( $_POST['dog_neutered'] ) ? 1 : 0, $dog_id );

		// Identification
		if ( isset( $_POST['dog_microchip'] ) ) {
			update_field( 'dog_microchip', sanitize_text_field( $_POST['dog_microchip'] ), $dog_id );
		}
		if ( isset( $_POST['dog_microchip_date'] ) ) {
			update_field( 'dog_microchip_date', sanitize_text_field( $_POST['dog_microchip_date'] ), $dog_id );
		}
		if ( isset( $_POST['dog_pedigree'] ) ) {
			update_field( 'dog_pedigree', sanitize_text_field( $_POST['dog_pedigree'] ), $dog_id );
		}
		if ( isset( $_POST['dog_passport'] ) ) {
			update_field( 'dog_passport', sanitize_text_field( $_POST['dog_passport'] ), $dog_id );
		}

		// Health
		if ( isset( $_POST['dog_veterinarian'] ) ) {
			update_field( 'dog_veterinarian', sanitize_text_field( $_POST['dog_veterinarian'] ), $dog_id );
		}
		if ( isset( $_POST['dog_veterinarian_phone'] ) ) {
			update_field( 'dog_veterinarian_phone', sanitize_text_field( $_POST['dog_veterinarian_phone'] ), $dog_id );
		}
		if ( isset( $_POST['dog_veterinarian_address'] ) ) {
			update_field( 'dog_veterinarian_address', sanitize_textarea_field( $_POST['dog_veterinarian_address'] ), $dog_id );
		}
		if ( isset( $_POST['dog_allergies'] ) ) {
			update_field( 'dog_allergies', sanitize_textarea_field( $_POST['dog_allergies'] ), $dog_id );
		}
		if ( isset( $_POST['dog_medical_conditions'] ) ) {
			update_field( 'dog_medical_conditions', sanitize_textarea_field( $_POST['dog_medical_conditions'] ), $dog_id );
		}
		if ( isset( $_POST['dog_medications'] ) ) {
			update_field( 'dog_medications', sanitize_textarea_field( $_POST['dog_medications'] ), $dog_id );
		}
		if ( isset( $_POST['dog_insurance'] ) ) {
			update_field( 'dog_insurance', sanitize_text_field( $_POST['dog_insurance'] ), $dog_id );
		}
		if ( isset( $_POST['dog_insurance_number'] ) ) {
			update_field( 'dog_insurance_number', sanitize_text_field( $_POST['dog_insurance_number'] ), $dog_id );
		}
		if ( isset( $_POST['dog_emergency_contact'] ) ) {
			update_field( 'dog_emergency_contact', sanitize_text_field( $_POST['dog_emergency_contact'] ), $dog_id );
		}
		if ( isset( $_POST['dog_emergency_phone'] ) ) {
			update_field( 'dog_emergency_phone', sanitize_text_field( $_POST['dog_emergency_phone'] ), $dog_id );
		}

		// Diet
		if ( isset( $_POST['dog_food_type'] ) ) {
			update_field( 'dog_food_type', sanitize_text_field( $_POST['dog_food_type'] ), $dog_id );
		}
		if ( isset( $_POST['dog_food_brand'] ) ) {
			update_field( 'dog_food_brand', sanitize_text_field( $_POST['dog_food_brand'] ), $dog_id );
		}
		if ( isset( $_POST['dog_food_amount'] ) ) {
			update_field( 'dog_food_amount', intval( $_POST['dog_food_amount'] ), $dog_id );
		}
		if ( isset( $_POST['dog_meals_per_day'] ) ) {
			update_field( 'dog_meals_per_day', intval( $_POST['dog_meals_per_day'] ), $dog_id );
		}
		if ( isset( $_POST['dog_diet_notes'] ) ) {
			update_field( 'dog_diet_notes', sanitize_textarea_field( $_POST['dog_diet_notes'] ), $dog_id );
		}

		// Behavior
		if ( isset( $_POST['dog_temperament'] ) && is_array( $_POST['dog_temperament'] ) ) {
			$temperament = array_map( 'sanitize_text_field', $_POST['dog_temperament'] );
			update_field( 'dog_temperament', $temperament, $dog_id );
		} else {
			update_field( 'dog_temperament', array(), $dog_id );
		}
		if ( isset( $_POST['dog_training_level'] ) ) {
			update_field( 'dog_training_level', sanitize_text_field( $_POST['dog_training_level'] ), $dog_id );
		}
		if ( isset( $_POST['dog_behavior_notes'] ) ) {
			update_field( 'dog_behavior_notes', sanitize_textarea_field( $_POST['dog_behavior_notes'] ), $dog_id );
		}
		if ( isset( $_POST['dog_notes'] ) ) {
			update_field( 'dog_notes', sanitize_textarea_field( $_POST['dog_notes'] ), $dog_id );
		}

		// Handle photo upload
		if ( ! empty( $_FILES['dog_foto']['name'] ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';

			$attachment_id = media_handle_upload( 'dog_foto', $dog_id );

			if ( ! is_wp_error( $attachment_id ) ) {
				update_field( 'foto', $attachment_id, $dog_id );
			}
		}

		wp_send_json_success( array(
			'message' => __( 'Cane salvato con successo!', 'caniincasa-my-dog' ),
			'dog_id'  => $dog_id,
			'redirect' => home_url( '/i-miei-cani/' . $dog_id . '/' ),
		) );
	}

	/**
	 * Delete dog profile
	 */
	public static function delete_dog_profile() {
		check_ajax_referer( 'my_dog_nonce', 'nonce' );

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => __( 'Devi effettuare il login.', 'caniincasa-my-dog' ) ) );
		}

		$dog_id = isset( $_POST['dog_id'] ) ? intval( $_POST['dog_id'] ) : 0;

		if ( ! $dog_id || ! Caniincasa_My_Dog_Post_Type::user_can_view( $dog_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Cane non trovato.', 'caniincasa-my-dog' ) ) );
		}

		$result = wp_delete_post( $dog_id, true );

		if ( ! $result ) {
			wp_send_json_error( array( 'message' => __( 'Errore nell\'eliminazione.', 'caniincasa-my-dog' ) ) );
		}

		// Delete associated data
		global $wpdb;
		$wpdb->delete( $wpdb->prefix . 'dog_vaccinations', array( 'dog_id' => $dog_id ) );
		$wpdb->delete( $wpdb->prefix . 'dog_weight_tracker', array( 'dog_id' => $dog_id ) );
		$wpdb->delete( $wpdb->prefix . 'dog_notes', array( 'dog_id' => $dog_id ) );

		wp_send_json_success( array(
			'message' => __( 'Cane eliminato.', 'caniincasa-my-dog' ),
			'redirect' => home_url( '/i-miei-cani/' ),
		) );
	}

	/**
	 * Add vaccination
	 */
	public static function add_vaccination() {
		check_ajax_referer( 'my_dog_nonce', 'nonce' );

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => __( 'Devi effettuare il login.', 'caniincasa-my-dog' ) ) );
		}

		$dog_id        = isset( $_POST['dog_id'] ) ? intval( $_POST['dog_id'] ) : 0;
		$vaccine_name  = isset( $_POST['vaccine_name'] ) ? sanitize_text_field( $_POST['vaccine_name'] ) : '';
		$vaccine_date  = isset( $_POST['vaccine_date'] ) ? sanitize_text_field( $_POST['vaccine_date'] ) : '';
		$next_date     = isset( $_POST['next_date'] ) ? sanitize_text_field( $_POST['next_date'] ) : null;
		$veterinarian  = isset( $_POST['veterinarian'] ) ? sanitize_text_field( $_POST['veterinarian'] ) : '';
		$notes         = isset( $_POST['notes'] ) ? sanitize_textarea_field( $_POST['notes'] ) : '';

		if ( ! $dog_id || ! Caniincasa_My_Dog_Post_Type::user_can_view( $dog_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Cane non trovato.', 'caniincasa-my-dog' ) ) );
		}

		if ( empty( $vaccine_name ) || empty( $vaccine_date ) ) {
			wp_send_json_error( array( 'message' => __( 'Compila i campi obbligatori.', 'caniincasa-my-dog' ) ) );
		}

		global $wpdb;
		$result = $wpdb->insert(
			$wpdb->prefix . 'dog_vaccinations',
			array(
				'dog_id'        => $dog_id,
				'vaccine_name'  => $vaccine_name,
				'vaccine_date'  => $vaccine_date,
				'next_date'     => $next_date,
				'veterinarian'  => $veterinarian,
				'notes'         => $notes,
			),
			array( '%d', '%s', '%s', '%s', '%s', '%s' )
		);

		if ( ! $result ) {
			wp_send_json_error( array( 'message' => __( 'Errore nel salvataggio.', 'caniincasa-my-dog' ) ) );
		}

		wp_send_json_success( array(
			'message' => __( 'Vaccinazione aggiunta!', 'caniincasa-my-dog' ),
			'id'      => $wpdb->insert_id,
		) );
	}

	/**
	 * Delete vaccination
	 */
	public static function delete_vaccination() {
		check_ajax_referer( 'my_dog_nonce', 'nonce' );

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => __( 'Devi effettuare il login.', 'caniincasa-my-dog' ) ) );
		}

		$vacc_id = isset( $_POST['vacc_id'] ) ? intval( $_POST['vacc_id'] ) : 0;

		if ( ! $vacc_id ) {
			wp_send_json_error( array( 'message' => __( 'ID non valido.', 'caniincasa-my-dog' ) ) );
		}

		// Check ownership
		global $wpdb;
		$vaccination = $wpdb->get_row( $wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}dog_vaccinations WHERE id = %d",
			$vacc_id
		) );

		if ( ! $vaccination || ! Caniincasa_My_Dog_Post_Type::user_can_view( $vaccination->dog_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Vaccinazione non trovata.', 'caniincasa-my-dog' ) ) );
		}

		$wpdb->delete( $wpdb->prefix . 'dog_vaccinations', array( 'id' => $vacc_id ) );

		wp_send_json_success( array( 'message' => __( 'Vaccinazione eliminata.', 'caniincasa-my-dog' ) ) );
	}

	/**
	 * Add weight entry
	 */
	public static function add_weight_entry() {
		check_ajax_referer( 'my_dog_nonce', 'nonce' );

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => __( 'Devi effettuare il login.', 'caniincasa-my-dog' ) ) );
		}

		$dog_id    = isset( $_POST['dog_id'] ) ? intval( $_POST['dog_id'] ) : 0;
		$weight    = isset( $_POST['weight'] ) ? floatval( $_POST['weight'] ) : 0;
		$date      = isset( $_POST['measurement_date'] ) ? sanitize_text_field( $_POST['measurement_date'] ) : date( 'Y-m-d' );
		$notes     = isset( $_POST['notes'] ) ? sanitize_textarea_field( $_POST['notes'] ) : '';

		if ( ! $dog_id || ! Caniincasa_My_Dog_Post_Type::user_can_view( $dog_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Cane non trovato.', 'caniincasa-my-dog' ) ) );
		}

		if ( $weight <= 0 ) {
			wp_send_json_error( array( 'message' => __( 'Peso non valido.', 'caniincasa-my-dog' ) ) );
		}

		global $wpdb;
		$result = $wpdb->insert(
			$wpdb->prefix . 'dog_weight_tracker',
			array(
				'dog_id'           => $dog_id,
				'weight'           => $weight,
				'measurement_date' => $date,
				'notes'            => $notes,
			),
			array( '%d', '%f', '%s', '%s' )
		);

		if ( ! $result ) {
			wp_send_json_error( array( 'message' => __( 'Errore nel salvataggio.', 'caniincasa-my-dog' ) ) );
		}

		// Update ACF field with latest weight
		update_field( 'dog_weight', $weight, $dog_id );

		wp_send_json_success( array(
			'message' => __( 'Peso registrato!', 'caniincasa-my-dog' ),
			'id'      => $wpdb->insert_id,
		) );
	}

	/**
	 * Add dog note
	 */
	public static function add_dog_note() {
		check_ajax_referer( 'my_dog_nonce', 'nonce' );

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => __( 'Devi effettuare il login.', 'caniincasa-my-dog' ) ) );
		}

		$dog_id  = isset( $_POST['dog_id'] ) ? intval( $_POST['dog_id'] ) : 0;
		$date    = isset( $_POST['note_date'] ) ? sanitize_text_field( $_POST['note_date'] ) : date( 'Y-m-d' );
		$type    = isset( $_POST['note_type'] ) ? sanitize_text_field( $_POST['note_type'] ) : 'general';
		$content = isset( $_POST['note_content'] ) ? sanitize_textarea_field( $_POST['note_content'] ) : '';

		if ( ! $dog_id || ! Caniincasa_My_Dog_Post_Type::user_can_view( $dog_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Cane non trovato.', 'caniincasa-my-dog' ) ) );
		}

		if ( empty( $content ) ) {
			wp_send_json_error( array( 'message' => __( 'La nota non può essere vuota.', 'caniincasa-my-dog' ) ) );
		}

		global $wpdb;
		$result = $wpdb->insert(
			$wpdb->prefix . 'dog_notes',
			array(
				'dog_id'       => $dog_id,
				'note_date'    => $date,
				'note_type'    => $type,
				'note_content' => $content,
			),
			array( '%d', '%s', '%s', '%s' )
		);

		if ( ! $result ) {
			wp_send_json_error( array( 'message' => __( 'Errore nel salvataggio.', 'caniincasa-my-dog' ) ) );
		}

		wp_send_json_success( array(
			'message' => __( 'Nota aggiunta!', 'caniincasa-my-dog' ),
			'id'      => $wpdb->insert_id,
		) );
	}

	/**
	 * Newsletter signup
	 */
	public static function newsletter_signup() {
		check_ajax_referer( 'newsletter_nonce', 'nonce' );

		$email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';

		if ( ! is_email( $email ) ) {
			wp_send_json_error( array( 'message' => __( 'Email non valida.', 'caniincasa-my-dog' ) ) );
		}

		// Save to custom table or use third-party service
		// For now, save to WP options as simple array
		$subscribers = get_option( 'caniincasa_newsletter_subscribers', array() );

		if ( in_array( $email, $subscribers ) ) {
			wp_send_json_error( array( 'message' => __( 'Sei già iscritto!', 'caniincasa-my-dog' ) ) );
		}

		$subscribers[] = $email;
		update_option( 'caniincasa_newsletter_subscribers', $subscribers );

		// TODO: Integrate with MailChimp/Sendinblue API here

		wp_send_json_success( array(
			'message' => __( 'Iscrizione completata! Grazie!', 'caniincasa-my-dog' ),
		) );
	}
}

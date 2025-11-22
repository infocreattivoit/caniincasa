<?php
/**
 * ACF Fields for Dog Profiles
 *
 * @package Caniincasa_My_Dog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Caniincasa_My_Dog_ACF_Fields {

	/**
	 * Initialize
	 */
	public static function init() {
		add_action( 'acf/init', array( __CLASS__, 'register_fields' ) );
	}

	/**
	 * Register ACF fields
	 */
	public static function register_fields() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		acf_add_local_field_group( array(
			'key'      => 'group_dog_profile',
			'title'    => __( 'Dati del Cane', 'caniincasa-my-dog' ),
			'fields'   => array(

				// === INFORMAZIONI BASE ===
				array(
					'key'   => 'field_dog_basic_info_tab',
					'label' => __( 'Informazioni Base', 'caniincasa-my-dog' ),
					'type'  => 'tab',
				),

				array(
					'key'          => 'field_dog_razza',
					'label'        => __( 'Razza', 'caniincasa-my-dog' ),
					'name'         => 'dog_razza',
					'type'         => 'post_object',
					'post_type'    => array( 'razze_di_cani' ),
					'allow_null'   => 1,
					'multiple'     => 0,
					'return_format' => 'id',
				),

				array(
					'key'   => 'field_dog_razza_custom',
					'label' => __( 'Razza (se non in elenco)', 'caniincasa-my-dog' ),
					'name'  => 'dog_razza_custom',
					'type'  => 'text',
				),

				array(
					'key'           => 'field_dog_birth_date',
					'label'         => __( 'Data di Nascita', 'caniincasa-my-dog' ),
					'name'          => 'dog_birth_date',
					'type'          => 'date_picker',
					'display_format' => 'd/m/Y',
					'return_format'  => 'Y-m-d',
					'first_day'      => 1,
				),

				array(
					'key'     => 'field_dog_gender',
					'label'   => __( 'Sesso', 'caniincasa-my-dog' ),
					'name'    => 'dog_gender',
					'type'    => 'select',
					'choices' => array(
						'male'   => __( 'Maschio', 'caniincasa-my-dog' ),
						'female' => __( 'Femmina', 'caniincasa-my-dog' ),
					),
				),

				array(
					'key'     => 'field_dog_neutered',
					'label'   => __( 'Sterilizzato/Castrato', 'caniincasa-my-dog' ),
					'name'    => 'dog_neutered',
					'type'    => 'true_false',
					'ui'      => 1,
				),

				array(
					'key'     => 'field_dog_size',
					'label'   => __( 'Taglia', 'caniincasa-my-dog' ),
					'name'    => 'dog_size',
					'type'    => 'select',
					'choices' => array(
						'toy'    => __( 'Toy (< 5kg)', 'caniincasa-my-dog' ),
						'small'  => __( 'Piccola (5-10kg)', 'caniincasa-my-dog' ),
						'medium' => __( 'Media (10-25kg)', 'caniincasa-my-dog' ),
						'large'  => __( 'Grande (25-45kg)', 'caniincasa-my-dog' ),
						'giant'  => __( 'Gigante (> 45kg)', 'caniincasa-my-dog' ),
					),
				),

				array(
					'key'     => 'field_dog_weight',
					'label'   => __( 'Peso Attuale (kg)', 'caniincasa-my-dog' ),
					'name'    => 'dog_weight',
					'type'    => 'number',
					'step'    => 0.1,
					'min'     => 0,
				),

				array(
					'key'     => 'field_dog_color',
					'label'   => __( 'Colore/Mantello', 'caniincasa-my-dog' ),
					'name'    => 'dog_color',
					'type'    => 'text',
				),

				// === IDENTIFICAZIONE ===
				array(
					'key'   => 'field_dog_identification_tab',
					'label' => __( 'Identificazione', 'caniincasa-my-dog' ),
					'type'  => 'tab',
				),

				array(
					'key'     => 'field_dog_microchip',
					'label'   => __( 'Numero Microchip', 'caniincasa-my-dog' ),
					'name'    => 'dog_microchip',
					'type'    => 'text',
				),

				array(
					'key'           => 'field_dog_microchip_date',
					'label'         => __( 'Data Impianto Microchip', 'caniincasa-my-dog' ),
					'name'          => 'dog_microchip_date',
					'type'          => 'date_picker',
					'display_format' => 'd/m/Y',
					'return_format'  => 'Y-m-d',
				),

				array(
					'key'     => 'field_dog_pedigree',
					'label'   => __( 'Numero Pedigree', 'caniincasa-my-dog' ),
					'name'    => 'dog_pedigree',
					'type'    => 'text',
				),

				array(
					'key'     => 'field_dog_passport',
					'label'   => __( 'Passaporto Europeo', 'caniincasa-my-dog' ),
					'name'    => 'dog_passport',
					'type'    => 'text',
				),

				// === SALUTE ===
				array(
					'key'   => 'field_dog_health_tab',
					'label' => __( 'Salute', 'caniincasa-my-dog' ),
					'type'  => 'tab',
				),

				array(
					'key'     => 'field_dog_veterinarian',
					'label'   => __( 'Veterinario di Riferimento', 'caniincasa-my-dog' ),
					'name'    => 'dog_veterinarian',
					'type'    => 'text',
				),

				array(
					'key'     => 'field_dog_veterinarian_phone',
					'label'   => __( 'Telefono Veterinario', 'caniincasa-my-dog' ),
					'name'    => 'dog_veterinarian_phone',
					'type'    => 'text',
				),

				array(
					'key'     => 'field_dog_veterinarian_address',
					'label'   => __( 'Indirizzo Veterinario', 'caniincasa-my-dog' ),
					'name'    => 'dog_veterinarian_address',
					'type'    => 'textarea',
					'rows'    => 2,
				),

				array(
					'key'     => 'field_dog_allergies',
					'label'   => __( 'Allergie', 'caniincasa-my-dog' ),
					'name'    => 'dog_allergies',
					'type'    => 'textarea',
					'rows'    => 3,
				),

				array(
					'key'     => 'field_dog_medical_conditions',
					'label'   => __( 'Condizioni Mediche / Patologie', 'caniincasa-my-dog' ),
					'name'    => 'dog_medical_conditions',
					'type'    => 'textarea',
					'rows'    => 4,
				),

				array(
					'key'     => 'field_dog_medications',
					'label'   => __( 'Farmaci Assunti', 'caniincasa-my-dog' ),
					'name'    => 'dog_medications',
					'type'    => 'textarea',
					'rows'    => 3,
				),

				array(
					'key'     => 'field_dog_insurance',
					'label'   => __( 'Assicurazione', 'caniincasa-my-dog' ),
					'name'    => 'dog_insurance',
					'type'    => 'text',
				),

				array(
					'key'     => 'field_dog_insurance_number',
					'label'   => __( 'Numero Polizza', 'caniincasa-my-dog' ),
					'name'    => 'dog_insurance_number',
					'type'    => 'text',
				),

				// === ALIMENTAZIONE ===
				array(
					'key'   => 'field_dog_diet_tab',
					'label' => __( 'Alimentazione', 'caniincasa-my-dog' ),
					'type'  => 'tab',
				),

				array(
					'key'     => 'field_dog_food_type',
					'label'   => __( 'Tipo di Alimentazione', 'caniincasa-my-dog' ),
					'name'    => 'dog_food_type',
					'type'    => 'select',
					'choices' => array(
						'dry'      => __( 'Crocchette', 'caniincasa-my-dog' ),
						'wet'      => __( 'Umido', 'caniincasa-my-dog' ),
						'mixed'    => __( 'Misto (crocchette + umido)', 'caniincasa-my-dog' ),
						'barf'     => __( 'BARF (cibo crudo)', 'caniincasa-my-dog' ),
						'homemade' => __( 'Casalinga', 'caniincasa-my-dog' ),
						'other'    => __( 'Altro', 'caniincasa-my-dog' ),
					),
					'allow_null' => 1,
				),

				array(
					'key'     => 'field_dog_food_brand',
					'label'   => __( 'Marca Cibo', 'caniincasa-my-dog' ),
					'name'    => 'dog_food_brand',
					'type'    => 'text',
				),

				array(
					'key'     => 'field_dog_food_amount',
					'label'   => __( 'Quantità Giornaliera (grammi)', 'caniincasa-my-dog' ),
					'name'    => 'dog_food_amount',
					'type'    => 'number',
				),

				array(
					'key'     => 'field_dog_meals_per_day',
					'label'   => __( 'Pasti al Giorno', 'caniincasa-my-dog' ),
					'name'    => 'dog_meals_per_day',
					'type'    => 'number',
					'min'     => 1,
					'max'     => 5,
				),

				array(
					'key'     => 'field_dog_diet_notes',
					'label'   => __( 'Note Alimentazione', 'caniincasa-my-dog' ),
					'name'    => 'dog_diet_notes',
					'type'    => 'textarea',
					'rows'    => 3,
				),

				// === COMPORTAMENTO ===
				array(
					'key'   => 'field_dog_behavior_tab',
					'label' => __( 'Comportamento', 'caniincasa-my-dog' ),
					'type'  => 'tab',
				),

				array(
					'key'     => 'field_dog_temperament',
					'label'   => __( 'Temperamento', 'caniincasa-my-dog' ),
					'name'    => 'dog_temperament',
					'type'    => 'checkbox',
					'choices' => array(
						'friendly'   => __( 'Socievole', 'caniincasa-my-dog' ),
						'playful'    => __( 'Giocoso', 'caniincasa-my-dog' ),
						'calm'       => __( 'Calmo', 'caniincasa-my-dog' ),
						'energetic'  => __( 'Energico', 'caniincasa-my-dog' ),
						'protective' => __( 'Protettivo', 'caniincasa-my-dog' ),
						'shy'        => __( 'Timido', 'caniincasa-my-dog' ),
						'aggressive' => __( 'Aggressivo', 'caniincasa-my-dog' ),
					),
				),

				array(
					'key'     => 'field_dog_training_level',
					'label'   => __( 'Livello di Addestramento', 'caniincasa-my-dog' ),
					'name'    => 'dog_training_level',
					'type'    => 'select',
					'choices' => array(
						'none'         => __( 'Nessun addestramento', 'caniincasa-my-dog' ),
						'basic'        => __( 'Comandi base', 'caniincasa-my-dog' ),
						'intermediate' => __( 'Intermedio', 'caniincasa-my-dog' ),
						'advanced'     => __( 'Avanzato', 'caniincasa-my-dog' ),
					),
					'allow_null' => 1,
				),

				array(
					'key'     => 'field_dog_behavior_notes',
					'label'   => __( 'Note Comportamento', 'caniincasa-my-dog' ),
					'name'    => 'dog_behavior_notes',
					'type'    => 'textarea',
					'rows'    => 4,
				),

				// === NOTE AGGIUNTIVE ===
				array(
					'key'   => 'field_dog_notes_tab',
					'label' => __( 'Note', 'caniincasa-my-dog' ),
					'type'  => 'tab',
				),

				array(
					'key'     => 'field_dog_notes',
					'label'   => __( 'Note Generali', 'caniincasa-my-dog' ),
					'name'    => 'dog_notes',
					'type'    => 'textarea',
					'rows'    => 6,
				),

				array(
					'key'     => 'field_dog_emergency_contact',
					'label'   => __( 'Contatto di Emergenza', 'caniincasa-my-dog' ),
					'name'    => 'dog_emergency_contact',
					'type'    => 'text',
				),

				array(
					'key'     => 'field_dog_emergency_phone',
					'label'   => __( 'Telefono Emergenza', 'caniincasa-my-dog' ),
					'name'    => 'dog_emergency_phone',
					'type'    => 'text',
				),

			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'dog_profile',
					),
				),
			),
			'style'    => 'default',
		) );
	}
}

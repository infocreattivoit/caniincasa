<?php
/**
 * ACF Field Groups Registration for Strutture CPTs
 *
 * Registra programmaticamente i field groups ACF per Toelettature e Aree Cani.
 * Questo file viene caricato automaticamente dal plugin core.
 *
 * @package Caniincasa_Core
 * @since 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register ACF fields for Toelettature CPT
 */
function caniincasa_register_acf_toelettature() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'      => 'group_toelettature',
		'title'    => 'Informazioni Toelettatura',
		'fields'   => array(
			array(
				'key'          => 'field_toelettatura_persona',
				'label'        => 'Responsabile/Titolare',
				'name'         => 'persona',
				'type'         => 'text',
				'instructions' => 'Nome del responsabile o titolare della toelettatura',
				'required'     => 0,
			),
			array(
				'key'          => 'field_toelettatura_indirizzo',
				'label'        => 'Indirizzo',
				'name'         => 'indirizzo',
				'type'         => 'text',
				'instructions' => 'Via e numero civico',
				'required'     => 0,
			),
			array(
				'key'          => 'field_toelettatura_localita',
				'label'        => 'Località',
				'name'         => 'localita',
				'type'         => 'text',
				'instructions' => 'Città o comune',
				'required'     => 0,
			),
			array(
				'key'          => 'field_toelettatura_provincia',
				'label'        => 'Provincia',
				'name'         => 'provincia',
				'type'         => 'text',
				'instructions' => 'Sigla provincia (es. MI, RM)',
				'required'     => 0,
				'maxlength'    => 2,
			),
			array(
				'key'          => 'field_toelettatura_cap',
				'label'        => 'CAP',
				'name'         => 'cap',
				'type'         => 'text',
				'instructions' => 'Codice postale',
				'required'     => 0,
				'maxlength'    => 5,
			),
			array(
				'key'          => 'field_toelettatura_telefono',
				'label'        => 'Telefono',
				'name'         => 'telefono',
				'type'         => 'text',
				'instructions' => 'Numero di telefono',
				'required'     => 0,
			),
			array(
				'key'          => 'field_toelettatura_email',
				'label'        => 'Email',
				'name'         => 'email',
				'type'         => 'email',
				'instructions' => 'Indirizzo email di contatto',
				'required'     => 0,
			),
			array(
				'key'          => 'field_toelettatura_sito_web',
				'label'        => 'Sito Web',
				'name'         => 'sito_web',
				'type'         => 'url',
				'instructions' => 'URL del sito web (con https://)',
				'required'     => 0,
			),
			array(
				'key'          => 'field_toelettatura_servizi_offerti',
				'label'        => 'Servizi Offerti',
				'name'         => 'servizi_offerti',
				'type'         => 'checkbox',
				'instructions' => 'Seleziona i servizi disponibili',
				'required'     => 0,
				'choices'      => array(
					'Bagno e asciugatura'                => 'Bagno e asciugatura',
					'Toelettatura completa'              => 'Toelettatura completa',
					'Taglio pelo'                        => 'Taglio pelo',
					'Tosatura'                           => 'Tosatura',
					'Stripping'                          => 'Stripping',
					'Taglio unghie'                      => 'Taglio unghie',
					'Pulizia orecchie'                   => 'Pulizia orecchie',
					'Pulizia denti'                      => 'Pulizia denti',
					'Trattamenti antiparassitari'        => 'Trattamenti antiparassitari',
					'Servizio taxi per animali'          => 'Servizio taxi per animali',
					'Toelettatura a domicilio'           => 'Toelettatura a domicilio',
					'Servizio di lavaggio self-service'  => 'Servizio di lavaggio self-service',
				),
				'layout'       => 'vertical',
			),
			array(
				'key'          => 'field_toelettatura_orari_apertura',
				'label'        => 'Orari di Apertura',
				'name'         => 'orari_apertura',
				'type'         => 'textarea',
				'instructions' => 'Orari di apertura settimanali',
				'required'     => 0,
				'rows'         => 4,
			),
			array(
				'key'          => 'field_toelettatura_prezzi_indicativi',
				'label'        => 'Prezzi Indicativi',
				'name'         => 'prezzi_indicativi',
				'type'         => 'textarea',
				'instructions' => 'Prezzi indicativi dei servizi principali',
				'required'     => 0,
				'rows'         => 6,
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'toelettature',
				),
			),
		),
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => true,
		'description'           => '',
	) );
}
add_action( 'acf/init', 'caniincasa_register_acf_toelettature' );

/**
 * Register ACF fields for Aree Cani CPT
 */
function caniincasa_register_acf_aree_cani() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'      => 'group_aree_cani',
		'title'    => 'Informazioni Area Cani',
		'fields'   => array(
			array(
				'key'          => 'field_area_cani_indirizzo',
				'label'        => 'Indirizzo',
				'name'         => 'indirizzo',
				'type'         => 'text',
				'instructions' => 'Via, nome del parco o indirizzo generico',
				'required'     => 0,
			),
			array(
				'key'          => 'field_area_cani_localita',
				'label'        => 'Località',
				'name'         => 'localita',
				'type'         => 'text',
				'instructions' => 'Città o comune',
				'required'     => 0,
			),
			array(
				'key'          => 'field_area_cani_provincia',
				'label'        => 'Provincia',
				'name'         => 'provincia',
				'type'         => 'text',
				'instructions' => 'Sigla provincia (es. MI, RM)',
				'required'     => 0,
				'maxlength'    => 2,
			),
			array(
				'key'          => 'field_area_cani_cap',
				'label'        => 'CAP',
				'name'         => 'cap',
				'type'         => 'text',
				'instructions' => 'Codice postale',
				'required'     => 0,
				'maxlength'    => 5,
			),
			array(
				'key'          => 'field_area_cani_tipo_area',
				'label'        => 'Tipo Area',
				'name'         => 'tipo_area',
				'type'         => 'checkbox',
				'instructions' => 'Caratteristiche dell\'area cani',
				'required'     => 0,
				'choices'      => array(
					'Recintata'                => 'Recintata',
					'Libera (non recintata)'   => 'Libera (non recintata)',
					'Per cani di piccola taglia' => 'Per cani di piccola taglia',
					'Per cani di taglia grande' => 'Per cani di taglia grande',
					'Area mista'               => 'Area mista',
					'Doppia area (piccola/grande)' => 'Doppia area (piccola/grande)',
				),
				'layout'       => 'vertical',
			),
			array(
				'key'          => 'field_area_cani_superficie',
				'label'        => 'Superficie',
				'name'         => 'superficie',
				'type'         => 'number',
				'instructions' => 'Superficie approssimativa in metri quadri',
				'required'     => 0,
				'min'          => 0,
				'step'         => 1,
				'append'       => 'mq',
			),
			array(
				'key'          => 'field_area_cani_servizi_disponibili',
				'label'        => 'Servizi Disponibili',
				'name'         => 'servizi_disponibili',
				'type'         => 'checkbox',
				'instructions' => 'Servizi e attrezzature presenti nell\'area',
				'required'     => 0,
				'choices'      => array(
					'Fontanella acqua'      => 'Fontanella acqua',
					'Sacchetti igienici'    => 'Sacchetti igienici',
					'Cestini'               => 'Cestini',
					'Panchine'              => 'Panchine',
					'Illuminazione notturna' => 'Illuminazione notturna',
					'Giochi per cani'       => 'Giochi per cani',
					'Percorso agility'      => 'Percorso agility',
					'Ombreggiatura/alberi'  => 'Ombreggiatura/alberi',
					'Parcheggio vicino'     => 'Parcheggio vicino',
				),
				'layout'       => 'vertical',
			),
			array(
				'key'          => 'field_area_cani_orari_accesso',
				'label'        => 'Orari di Accesso',
				'name'         => 'orari_accesso',
				'type'         => 'textarea',
				'instructions' => 'Orari di accesso all\'area (es. "Libero accesso 24h" o "Segue orari parco")',
				'required'     => 0,
				'rows'         => 3,
			),
			array(
				'key'          => 'field_area_cani_regolamento',
				'label'        => 'Regolamento',
				'name'         => 'regolamento',
				'type'         => 'textarea',
				'instructions' => 'Regole di utilizzo dell\'area cani',
				'required'     => 0,
				'rows'         => 6,
			),
			array(
				'key'          => 'field_area_cani_accessibilita',
				'label'        => 'Accessibilità',
				'name'         => 'accessibilita',
				'type'         => 'textarea',
				'instructions' => 'Informazioni sull\'accessibilità per persone con disabilità',
				'required'     => 0,
				'rows'         => 3,
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'aree_cani',
				),
			),
		),
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => true,
		'description'           => '',
	) );
}
add_action( 'acf/init', 'caniincasa_register_acf_aree_cani' );

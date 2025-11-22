<?php
/**
 * Plugin Name: Caniincasa REST API Basic Auth
 * Plugin URI: https://www.caniincasa.it
 * Description: Abilita l'autenticazione Basic Auth per WordPress REST API. Necessario per Chrome Extension e altre app esterne.
 * Version: 1.0.0
 * Author: Caniincasa
 * License: GPL v2 or later
 *
 * @package Caniincasa_REST_Auth
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abilita Basic Authentication per REST API
 * Usa Application Passwords (WordPress 5.6+)
 */
add_filter( 'determine_current_user', 'caniincasa_rest_basic_auth_handler', 20 );
add_filter( 'rest_authentication_errors', 'caniincasa_rest_basic_auth_error' );

/**
 * Basic Authentication handler
 */
function caniincasa_rest_basic_auth_handler( $user_id ) {
	// Non fare nulla se l'utente è già autenticato
	if ( ! empty( $user_id ) ) {
		return $user_id;
	}

	// Solo per richieste REST API
	if ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) {
		return $user_id;
	}

	// Verifica se ci sono credenziali Basic Auth
	if ( ! isset( $_SERVER['PHP_AUTH_USER'] ) ) {
		return $user_id;
	}

	$username = $_SERVER['PHP_AUTH_USER'];
	$password = $_SERVER['PHP_AUTH_PW'];

	// Prova autenticazione con Application Password
	$user = wp_authenticate_application_password( null, $username, $password );

	if ( is_wp_error( $user ) ) {
		// Se fallisce, prova autenticazione normale (deprecato, solo per fallback)
		$user = wp_authenticate( $username, $password );
	}

	if ( is_wp_error( $user ) ) {
		return $user_id;
	}

	return $user->ID;
}

/**
 * Gestisci errori di autenticazione REST
 */
function caniincasa_rest_basic_auth_error( $error ) {
	// Se c'è già un errore, ritornalo
	if ( ! empty( $error ) ) {
		return $error;
	}

	// Se non c'è un utente autenticato e ci sono header Basic Auth
	if ( ! is_user_logged_in() && isset( $_SERVER['PHP_AUTH_USER'] ) ) {
		return new WP_Error(
			'rest_authentication_error',
			__( 'Credenziali non valide. Verifica username e Application Password.', 'caniincasa-rest-auth' ),
			array( 'status' => 401 )
		);
	}

	return $error;
}

/**
 * Aggiungi header CORS per permettere richieste da Chrome Extension
 */
add_action( 'rest_api_init', function() {
	remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
	add_filter( 'rest_pre_serve_request', function( $served ) {
		header( 'Access-Control-Allow-Origin: *' );
		header( 'Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS' );
		header( 'Access-Control-Allow-Credentials: true' );
		header( 'Access-Control-Allow-Headers: Authorization, Content-Type, X-WP-Nonce' );
		return $served;
	});
}, 15 );

/**
 * Log di debug per aiutare a diagnosticare problemi
 * Disabilitare in produzione
 */
if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
	add_action( 'rest_api_init', function() {
		error_log( 'REST API Init - Basic Auth Plugin attivo' );

		if ( isset( $_SERVER['PHP_AUTH_USER'] ) ) {
			error_log( 'Basic Auth rilevato per utente: ' . $_SERVER['PHP_AUTH_USER'] );
		}
	});
}

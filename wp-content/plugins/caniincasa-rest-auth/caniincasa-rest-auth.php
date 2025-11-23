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
		// Debug: nessun header Basic Auth trovato
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( 'REST Auth: Nessun header PHP_AUTH_USER trovato. Verifica che il server supporti Basic Auth.' );
		}
		return $user_id;
	}

	$username = $_SERVER['PHP_AUTH_USER'];
	$password = $_SERVER['PHP_AUTH_PW'];

	// Debug: tentativo autenticazione
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( sprintf( 'REST Auth: Tentativo autenticazione per utente: %s', $username ) );
	}

	// Prova autenticazione con Application Password
	$user = wp_authenticate_application_password( null, $username, $password );

	if ( is_wp_error( $user ) ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( sprintf( 'REST Auth: Application Password fallita: %s', $user->get_error_message() ) );
		}

		// Se fallisce, prova autenticazione normale (deprecato, solo per fallback)
		$user = wp_authenticate( $username, $password );

		if ( is_wp_error( $user ) ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( sprintf( 'REST Auth: Autenticazione normale fallita: %s', $user->get_error_message() ) );
			}
			return $user_id;
		}
	}

	// Debug: autenticazione riuscita
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( sprintf( 'REST Auth: Autenticazione riuscita per utente ID: %d', $user->ID ) );
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

/**
 * Endpoint di test autenticazione
 * GET /wp-json/caniincasa/v1/auth-test
 */
add_action( 'rest_api_init', function() {
	register_rest_route( 'caniincasa/v1', '/auth-test', array(
		'methods'  => 'GET',
		'callback' => 'caniincasa_rest_auth_test',
		'permission_callback' => '__return_true',
	) );
} );

function caniincasa_rest_auth_test() {
	$current_user = wp_get_current_user();

	if ( $current_user->ID ) {
		return array(
			'success' => true,
			'message' => 'Autenticazione riuscita!',
			'user'    => array(
				'id'       => $current_user->ID,
				'username' => $current_user->user_login,
				'email'    => $current_user->user_email,
				'roles'    => $current_user->roles,
			),
		);
	} else {
		return array(
			'success' => false,
			'message' => 'Autenticazione fallita. Nessun utente loggato.',
			'debug'   => array(
				'php_auth_user' => isset( $_SERVER['PHP_AUTH_USER'] ) ? $_SERVER['PHP_AUTH_USER'] : 'non trovato',
				'authorization_header' => isset( $_SERVER['HTTP_AUTHORIZATION'] ) ? 'presente' : 'assente',
			),
		);
	}
}

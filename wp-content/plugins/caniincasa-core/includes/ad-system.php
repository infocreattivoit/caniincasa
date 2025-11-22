<?php
/**
 * Sistema Gestione Banner Pubblicitari
 *
 * Sistema completo per gestire banner pubblicitari con:
 * - Admin panel per inserimento codice (HTML/iframe)
 * - Supporto responsive: desktop, tablet, mobile
 * - Posizioni predefinite per ogni template
 * - Auto-inject nei template esistenti
 *
 * @package Caniincasa_Core
 * @since 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Classe principale sistema banner
 */
class Caniincasa_Ad_System {

    /**
     * Posizioni banner disponibili
     */
    private static $positions = array(
        // Homepage
        'home_after_hero'       => 'Homepage - Dopo Hero Section',
        'home_between_sections' => 'Homepage - Tra le Sezioni (dopo annunci)',
        'home_before_footer'    => 'Homepage - Prima del Footer',

        // Archivi Razze
        'archive_razze_top'     => 'Archivio Razze - Top (dopo filtri)',
        'archive_razze_sidebar' => 'Archivio Razze - Sidebar',
        'archive_razze_middle'  => 'Archivio Razze - Middle (dopo 6 razze)',

        // Single Razza
        'single_razza_sidebar_top'    => 'Razza Singola - Sidebar Top',
        'single_razza_sidebar_bottom' => 'Razza Singola - Sidebar Bottom',
        'single_razza_after_desc'     => 'Razza Singola - Dopo Descrizione',
        'single_razza_before_related' => 'Razza Singola - Prima Razze Correlate',

        // Archivi Strutture (tutti)
        'archive_strutture_top'          => 'Archivio Strutture - Top',
        'archive_strutture_sidebar'      => 'Archivio Strutture - Sidebar',
        'archive_strutture_middle'       => 'Archivio Strutture - Middle',
        'archive_strutture_before_footer' => 'Archivio Strutture - Prima del Footer',

        // Single Struttura
        'single_struttura_sidebar_top'    => 'Struttura Singola - Sidebar Top',
        'single_struttura_sidebar_bottom' => 'Struttura Singola - Sidebar Bottom',
        'single_struttura_before_footer'  => 'Struttura Singola - Prima del Footer',

        // Archivi Annunci
        'archive_annunci_top'     => 'Archivio Annunci - Top',
        'archive_annunci_sidebar' => 'Archivio Annunci - Sidebar',
        'archive_annunci_middle'  => 'Archivio Annunci - Middle',

        // Single Annuncio
        'single_annuncio_sidebar_top'    => 'Annuncio Singolo - Sidebar Top',
        'single_annuncio_sidebar_bottom' => 'Annuncio Singolo - Sidebar Bottom',

        // Calcolatori
        'calculator_sidebar'       => 'Calcolatori - Sidebar',
        'calculator_after_results' => 'Calcolatori - Dopo Risultati',

        // Comparatore Razze
        'comparatore_sidebar'      => 'Comparatore Razze - Sidebar',
        'comparatore_after_table'  => 'Comparatore Razze - Dopo Tabella',

        // Blog/Articoli
        'archive_blog_top'     => 'Archivio Blog - Top',
        'archive_blog_sidebar' => 'Archivio Blog - Sidebar',
        'single_post_sidebar'  => 'Articolo Singolo - Sidebar',
        'single_post_content'  => 'Articolo Singolo - Middle Content',

        // Dashboard Utente
        'dashboard_sidebar' => 'Dashboard Utente - Sidebar',
        'dashboard_top'     => 'Dashboard Utente - Top',

        // Globali
        'global_header'  => 'Globale - Header (tutte le pagine)',
        'global_footer'  => 'Globale - Footer (tutte le pagine)',
        'global_sidebar' => 'Globale - Sidebar Generico',
    );

    /**
     * Dispositivi supportati
     */
    private static $devices = array(
        'desktop' => 'Desktop (> 1024px)',
        'tablet'  => 'Tablet (768px - 1024px)',
        'mobile'  => 'Mobile (< 768px)',
    );

    /**
     * Initialize
     */
    public static function init() {
        // Admin menu
        add_action( 'admin_menu', array( __CLASS__, 'add_admin_menu' ) );

        // Salva impostazioni
        add_action( 'admin_post_caniincasa_save_ads', array( __CLASS__, 'save_ads' ) );

        // Enqueue admin assets
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ) );

        // Enqueue frontend assets
        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'frontend_enqueue_scripts' ) );

        // Registra shortcode
        add_shortcode( 'caniincasa_ad', array( __CLASS__, 'shortcode' ) );

        // Auto-inject banner nei template
        self::register_template_hooks();
    }

    /**
     * Aggiungi menu admin
     */
    public static function add_admin_menu() {
        add_menu_page(
            'Gestione Banner',
            'Banner Pubblicitari',
            'manage_options',
            'caniincasa-ads',
            array( __CLASS__, 'render_admin_page' ),
            'dashicons-megaphone',
            30
        );
    }

    /**
     * Render admin page
     */
    public static function render_admin_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Non hai i permessi per accedere a questa pagina.' );
        }

        $ads = get_option( 'caniincasa_ads', array() );
        $positions = self::$positions;
        $devices = self::$devices;

        include CANIINCASA_CORE_PATH . 'admin/admin-ads.php';
    }

    /**
     * Salva banner
     */
    public static function save_ads() {
        check_admin_referer( 'caniincasa_save_ads' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Non hai i permessi per salvare i banner.' );
        }

        $ads = array();

        foreach ( self::$positions as $position => $label ) {
            foreach ( self::$devices as $device => $device_label ) {
                $field_name = 'ad_' . $position . '_' . $device;

                if ( isset( $_POST[ $field_name ] ) ) {
                    // Permettiamo HTML/iframe/script per i banner
                    $ads[ $position ][ $device ] = wp_kses_post( $_POST[ $field_name ] );
                }

                // Checkbox attivo/disattivo
                $active_field = 'ad_active_' . $position . '_' . $device;
                $ads[ $position ][ $device . '_active' ] = isset( $_POST[ $active_field ] ) ? 1 : 0;
            }
        }

        update_option( 'caniincasa_ads', $ads );

        wp_redirect( add_query_arg( 'message', 'saved', admin_url( 'admin.php?page=caniincasa-ads' ) ) );
        exit;
    }

    /**
     * Enqueue admin scripts
     */
    public static function admin_enqueue_scripts( $hook ) {
        if ( 'toplevel_page_caniincasa-ads' !== $hook ) {
            return;
        }

        wp_enqueue_code_editor( array( 'type' => 'text/html' ) );
        wp_enqueue_script(
            'caniincasa-ads-admin',
            CANIINCASA_CORE_URL . 'assets/js/ads-admin.js',
            array( 'jquery', 'wp-codemirror' ),
            CANIINCASA_CORE_VERSION,
            true
        );

        wp_enqueue_style(
            'caniincasa-ads-admin',
            CANIINCASA_CORE_URL . 'assets/css/ads-admin.css',
            array(),
            CANIINCASA_CORE_VERSION
        );
    }

    /**
     * Enqueue frontend scripts
     */
    public static function frontend_enqueue_scripts() {
        wp_enqueue_style(
            'caniincasa-ads',
            CANIINCASA_CORE_URL . 'assets/css/ads.css',
            array(),
            CANIINCASA_CORE_VERSION
        );
    }

    /**
     * Ottieni banner per posizione
     *
     * @param string $position Posizione del banner
     * @param array  $args Argomenti aggiuntivi
     * @return string HTML del banner
     */
    public static function get_ad( $position, $args = array() ) {
        $ads = get_option( 'caniincasa_ads', array() );

        if ( empty( $ads[ $position ] ) ) {
            return '';
        }

        $position_ads = $ads[ $position ];

        // Determina dispositivo corrente (server-side fallback, poi gestito con CSS)
        $desktop_code = ! empty( $position_ads['desktop'] ) && ! empty( $position_ads['desktop_active'] ) ? $position_ads['desktop'] : '';
        $tablet_code  = ! empty( $position_ads['tablet'] ) && ! empty( $position_ads['tablet_active'] ) ? $position_ads['tablet'] : '';
        $mobile_code  = ! empty( $position_ads['mobile'] ) && ! empty( $position_ads['mobile_active'] ) ? $position_ads['mobile'] : '';

        // Se nessun banner attivo, return
        if ( empty( $desktop_code ) && empty( $tablet_code ) && empty( $mobile_code ) ) {
            return '';
        }

        // Wrapper con classi responsive
        ob_start();
        ?>
        <div class="caniincasa-ad caniincasa-ad-<?php echo esc_attr( $position ); ?>" data-position="<?php echo esc_attr( $position ); ?>">
            <?php if ( ! empty( $desktop_code ) ) : ?>
                <div class="ad-desktop">
                    <?php echo $desktop_code; // Already sanitized with wp_kses_post ?>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $tablet_code ) ) : ?>
                <div class="ad-tablet">
                    <?php echo $tablet_code; ?>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $mobile_code ) ) : ?>
                <div class="ad-mobile">
                    <?php echo $mobile_code; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php

        return ob_get_clean();
    }

    /**
     * Output banner
     *
     * @param string $position
     * @param array  $args
     */
    public static function display_ad( $position, $args = array() ) {
        echo self::get_ad( $position, $args );
    }

    /**
     * Shortcode per inserire banner manualmente
     *
     * Uso: [caniincasa_ad position="single_razza_sidebar_top"]
     */
    public static function shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'position' => '',
        ), $atts );

        if ( empty( $atts['position'] ) ) {
            return '';
        }

        return self::get_ad( $atts['position'] );
    }

    /**
     * Registra hook automatici per i template
     */
    private static function register_template_hooks() {
        // Homepage
        add_action( 'caniincasa_home_after_hero', array( __CLASS__, 'hook_home_after_hero' ) );
        add_action( 'caniincasa_home_between_sections', array( __CLASS__, 'hook_home_between_sections' ) );
        add_action( 'caniincasa_home_before_footer', array( __CLASS__, 'hook_home_before_footer' ) );

        // Archive Razze
        add_action( 'caniincasa_archive_razze_top', array( __CLASS__, 'hook_archive_razze_top' ) );
        add_action( 'caniincasa_archive_razze_sidebar', array( __CLASS__, 'hook_archive_razze_sidebar' ) );
        add_action( 'caniincasa_archive_razze_middle', array( __CLASS__, 'hook_archive_razze_middle' ) );

        // Single Razza
        add_action( 'caniincasa_single_razza_sidebar_top', array( __CLASS__, 'hook_single_razza_sidebar_top' ) );
        add_action( 'caniincasa_single_razza_sidebar_bottom', array( __CLASS__, 'hook_single_razza_sidebar_bottom' ) );
        add_action( 'caniincasa_single_razza_after_desc', array( __CLASS__, 'hook_single_razza_after_desc' ) );
        add_action( 'caniincasa_single_razza_before_related', array( __CLASS__, 'hook_single_razza_before_related' ) );

        // Archive Strutture
        add_action( 'caniincasa_archive_strutture_top', array( __CLASS__, 'hook_archive_strutture_top' ) );
        add_action( 'caniincasa_archive_strutture_sidebar', array( __CLASS__, 'hook_archive_strutture_sidebar' ) );
        add_action( 'caniincasa_archive_strutture_middle', array( __CLASS__, 'hook_archive_strutture_middle' ) );
        add_action( 'caniincasa_archive_strutture_before_footer', array( __CLASS__, 'hook_archive_strutture_before_footer' ) );

        // Single Struttura
        add_action( 'caniincasa_single_struttura_sidebar_top', array( __CLASS__, 'hook_single_struttura_sidebar_top' ) );
        add_action( 'caniincasa_single_struttura_sidebar_bottom', array( __CLASS__, 'hook_single_struttura_sidebar_bottom' ) );
        add_action( 'caniincasa_single_struttura_before_footer', array( __CLASS__, 'hook_single_struttura_before_footer' ) );

        // Archive Annunci
        add_action( 'caniincasa_archive_annunci_top', array( __CLASS__, 'hook_archive_annunci_top' ) );
        add_action( 'caniincasa_archive_annunci_sidebar', array( __CLASS__, 'hook_archive_annunci_sidebar' ) );
        add_action( 'caniincasa_archive_annunci_middle', array( __CLASS__, 'hook_archive_annunci_middle' ) );

        // Single Annuncio
        add_action( 'caniincasa_single_annuncio_sidebar_top', array( __CLASS__, 'hook_single_annuncio_sidebar_top' ) );
        add_action( 'caniincasa_single_annuncio_sidebar_bottom', array( __CLASS__, 'hook_single_annuncio_sidebar_bottom' ) );

        // Calcolatori
        add_action( 'caniincasa_calculator_sidebar', array( __CLASS__, 'hook_calculator_sidebar' ) );
        add_action( 'caniincasa_calculator_after_results', array( __CLASS__, 'hook_calculator_after_results' ) );

        // Comparatore
        add_action( 'caniincasa_comparatore_sidebar', array( __CLASS__, 'hook_comparatore_sidebar' ) );
        add_action( 'caniincasa_comparatore_after_table', array( __CLASS__, 'hook_comparatore_after_table' ) );

        // Blog
        add_action( 'caniincasa_archive_blog_top', array( __CLASS__, 'hook_archive_blog_top' ) );
        add_action( 'caniincasa_archive_blog_sidebar', array( __CLASS__, 'hook_archive_blog_sidebar' ) );
        add_action( 'caniincasa_single_post_sidebar', array( __CLASS__, 'hook_single_post_sidebar' ) );
        add_action( 'caniincasa_single_post_content', array( __CLASS__, 'hook_single_post_content' ) );

        // Dashboard
        add_action( 'caniincasa_dashboard_sidebar', array( __CLASS__, 'hook_dashboard_sidebar' ) );
        add_action( 'caniincasa_dashboard_top', array( __CLASS__, 'hook_dashboard_top' ) );

        // Globali
        add_action( 'wp_body_open', array( __CLASS__, 'hook_global_header' ) );
        add_action( 'wp_footer', array( __CLASS__, 'hook_global_footer' ), 5 );
    }

    /**
     * Hook callbacks
     */
    public static function hook_home_after_hero() {
        self::display_ad( 'home_after_hero' );
    }

    public static function hook_home_between_sections() {
        self::display_ad( 'home_between_sections' );
    }

    public static function hook_home_before_footer() {
        self::display_ad( 'home_before_footer' );
    }

    public static function hook_archive_razze_top() {
        self::display_ad( 'archive_razze_top' );
    }

    public static function hook_archive_razze_sidebar() {
        self::display_ad( 'archive_razze_sidebar' );
    }

    public static function hook_archive_razze_middle() {
        self::display_ad( 'archive_razze_middle' );
    }

    public static function hook_single_razza_sidebar_top() {
        self::display_ad( 'single_razza_sidebar_top' );
    }

    public static function hook_single_razza_sidebar_bottom() {
        self::display_ad( 'single_razza_sidebar_bottom' );
    }

    public static function hook_single_razza_after_desc() {
        self::display_ad( 'single_razza_after_desc' );
    }

    public static function hook_single_razza_before_related() {
        self::display_ad( 'single_razza_before_related' );
    }

    public static function hook_archive_strutture_top() {
        self::display_ad( 'archive_strutture_top' );
    }

    public static function hook_archive_strutture_sidebar() {
        self::display_ad( 'archive_strutture_sidebar' );
    }

    public static function hook_archive_strutture_middle() {
        self::display_ad( 'archive_strutture_middle' );
    }

    public static function hook_archive_strutture_before_footer() {
        self::display_ad( 'archive_strutture_before_footer' );
    }

    public static function hook_single_struttura_sidebar_top() {
        self::display_ad( 'single_struttura_sidebar_top' );
    }

    public static function hook_single_struttura_sidebar_bottom() {
        self::display_ad( 'single_struttura_sidebar_bottom' );
    }

    public static function hook_single_struttura_before_footer() {
        self::display_ad( 'single_struttura_before_footer' );
    }

    public static function hook_archive_annunci_top() {
        self::display_ad( 'archive_annunci_top' );
    }

    public static function hook_archive_annunci_sidebar() {
        self::display_ad( 'archive_annunci_sidebar' );
    }

    public static function hook_archive_annunci_middle() {
        self::display_ad( 'archive_annunci_middle' );
    }

    public static function hook_single_annuncio_sidebar_top() {
        self::display_ad( 'single_annuncio_sidebar_top' );
    }

    public static function hook_single_annuncio_sidebar_bottom() {
        self::display_ad( 'single_annuncio_sidebar_bottom' );
    }

    public static function hook_calculator_sidebar() {
        self::display_ad( 'calculator_sidebar' );
    }

    public static function hook_calculator_after_results() {
        self::display_ad( 'calculator_after_results' );
    }

    public static function hook_comparatore_sidebar() {
        self::display_ad( 'comparatore_sidebar' );
    }

    public static function hook_comparatore_after_table() {
        self::display_ad( 'comparatore_after_table' );
    }

    public static function hook_archive_blog_top() {
        self::display_ad( 'archive_blog_top' );
    }

    public static function hook_archive_blog_sidebar() {
        self::display_ad( 'archive_blog_sidebar' );
    }

    public static function hook_single_post_sidebar() {
        self::display_ad( 'single_post_sidebar' );
    }

    public static function hook_single_post_content() {
        self::display_ad( 'single_post_content' );
    }

    public static function hook_dashboard_sidebar() {
        self::display_ad( 'dashboard_sidebar' );
    }

    public static function hook_dashboard_top() {
        self::display_ad( 'dashboard_top' );
    }

    public static function hook_global_header() {
        self::display_ad( 'global_header' );
    }

    public static function hook_global_footer() {
        self::display_ad( 'global_footer' );
    }

    /**
     * Get all positions
     */
    public static function get_positions() {
        return self::$positions;
    }

    /**
     * Get all devices
     */
    public static function get_devices() {
        return self::$devices;
    }
}

// Initialize
Caniincasa_Ad_System::init();

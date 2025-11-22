<?php
/**
 * Admin Page - Gestione Banner Pubblicitari
 *
 * @package Caniincasa_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="wrap caniincasa-ads-admin">
    <h1>Gestione Banner Pubblicitari</h1>

    <?php if ( isset( $_GET['message'] ) && $_GET['message'] === 'saved' ) : ?>
        <div class="notice notice-success is-dismissible">
            <p><strong>Banner salvati con successo!</strong></p>
        </div>
    <?php endif; ?>

    <div class="ads-intro">
        <p class="description">
            Gestisci i banner pubblicitari per tutte le posizioni del sito. Puoi inserire codice HTML/iframe diverso per ogni dispositivo (Desktop, Tablet, Mobile).
        </p>
        <p class="description">
            <strong>Breakpoint responsive:</strong> Desktop (> 1024px) | Tablet (768px - 1024px) | Mobile (< 768px)
        </p>
    </div>

    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="ads-form">
        <?php wp_nonce_field( 'caniincasa_save_ads' ); ?>
        <input type="hidden" name="action" value="caniincasa_save_ads">

        <!-- Tabs per categoria -->
        <div class="nav-tab-wrapper">
            <a href="#tab-home" class="nav-tab nav-tab-active">Homepage</a>
            <a href="#tab-razze" class="nav-tab">Razze</a>
            <a href="#tab-strutture" class="nav-tab">Strutture</a>
            <a href="#tab-annunci" class="nav-tab">Annunci</a>
            <a href="#tab-tools" class="nav-tab">Strumenti</a>
            <a href="#tab-blog" class="nav-tab">Blog</a>
            <a href="#tab-dashboard" class="nav-tab">Dashboard</a>
            <a href="#tab-global" class="nav-tab">Globali</a>
        </div>

        <!-- Tab Homepage -->
        <div id="tab-home" class="tab-content active">
            <h2>Banner Homepage</h2>
            <?php
            $home_positions = array(
                'home_after_hero',
                'home_between_sections',
                'home_before_footer',
            );
            self::render_positions( $home_positions, $positions, $devices, $ads );
            ?>
        </div>

        <!-- Tab Razze -->
        <div id="tab-razze" class="tab-content">
            <h2>Banner Razze</h2>
            <h3>Archivio Razze</h3>
            <?php
            $razze_archive = array( 'archive_razze_top', 'archive_razze_sidebar', 'archive_razze_middle' );
            self::render_positions( $razze_archive, $positions, $devices, $ads );
            ?>

            <h3>Singola Razza</h3>
            <?php
            $razze_single = array(
                'single_razza_sidebar_top',
                'single_razza_sidebar_bottom',
                'single_razza_after_desc',
                'single_razza_before_related',
            );
            self::render_positions( $razze_single, $positions, $devices, $ads );
            ?>
        </div>

        <!-- Tab Strutture -->
        <div id="tab-strutture" class="tab-content">
            <h2>Banner Strutture</h2>
            <h3>Archivio Strutture</h3>
            <?php
            $strutture_archive = array( 'archive_strutture_top', 'archive_strutture_sidebar', 'archive_strutture_middle' );
            self::render_positions( $strutture_archive, $positions, $devices, $ads );
            ?>

            <h3>Singola Struttura</h3>
            <?php
            $strutture_single = array( 'single_struttura_sidebar_top', 'single_struttura_sidebar_bottom' );
            self::render_positions( $strutture_single, $positions, $devices, $ads );
            ?>
        </div>

        <!-- Tab Annunci -->
        <div id="tab-annunci" class="tab-content">
            <h2>Banner Annunci</h2>
            <h3>Archivio Annunci</h3>
            <?php
            $annunci_archive = array( 'archive_annunci_top', 'archive_annunci_sidebar', 'archive_annunci_middle' );
            self::render_positions( $annunci_archive, $positions, $devices, $ads );
            ?>

            <h3>Singolo Annuncio</h3>
            <?php
            $annunci_single = array( 'single_annuncio_sidebar_top', 'single_annuncio_sidebar_bottom' );
            self::render_positions( $annunci_single, $positions, $devices, $ads );
            ?>
        </div>

        <!-- Tab Strumenti -->
        <div id="tab-tools" class="tab-content">
            <h2>Banner Strumenti</h2>
            <h3>Calcolatori</h3>
            <?php
            $calc_positions = array( 'calculator_sidebar', 'calculator_after_results' );
            self::render_positions( $calc_positions, $positions, $devices, $ads );
            ?>

            <h3>Comparatore Razze</h3>
            <?php
            $comp_positions = array( 'comparatore_sidebar', 'comparatore_after_table' );
            self::render_positions( $comp_positions, $positions, $devices, $ads );
            ?>
        </div>

        <!-- Tab Blog -->
        <div id="tab-blog" class="tab-content">
            <h2>Banner Blog</h2>
            <h3>Archivio Blog</h3>
            <?php
            $blog_archive = array( 'archive_blog_top', 'archive_blog_sidebar' );
            self::render_positions( $blog_archive, $positions, $devices, $ads );
            ?>

            <h3>Articolo Singolo</h3>
            <?php
            $blog_single = array( 'single_post_sidebar', 'single_post_content' );
            self::render_positions( $blog_single, $positions, $devices, $ads );
            ?>
        </div>

        <!-- Tab Dashboard -->
        <div id="tab-dashboard" class="tab-content">
            <h2>Banner Dashboard Utente</h2>
            <?php
            $dashboard_positions = array( 'dashboard_sidebar', 'dashboard_top' );
            self::render_positions( $dashboard_positions, $positions, $devices, $ads );
            ?>
        </div>

        <!-- Tab Globali -->
        <div id="tab-global" class="tab-content">
            <h2>Banner Globali</h2>
            <p class="description">I banner globali vengono visualizzati su tutte le pagine del sito.</p>
            <?php
            $global_positions = array( 'global_header', 'global_footer', 'global_sidebar' );
            self::render_positions( $global_positions, $positions, $devices, $ads );
            ?>
        </div>

        <p class="submit">
            <button type="submit" class="button button-primary button-large">Salva Tutti i Banner</button>
        </p>
    </form>
</div>

<?php
/**
 * Helper per rendere le posizioni
 */
function render_positions( $position_keys, $all_positions, $devices, $ads ) {
    foreach ( $position_keys as $position ) {
        if ( ! isset( $all_positions[ $position ] ) ) {
            continue;
        }

        $label = $all_positions[ $position ];
        ?>
        <div class="ad-position-block">
            <h4 class="ad-position-title">
                <span class="dashicons dashicons-megaphone"></span>
                <?php echo esc_html( $label ); ?>
                <button type="button" class="toggle-position button button-small">Espandi</button>
            </h4>

            <div class="ad-position-content" style="display: none;">
                <div class="device-tabs">
                    <?php foreach ( $devices as $device => $device_label ) : ?>
                        <?php
                        $field_name = 'ad_' . $position . '_' . $device;
                        $active_field = 'ad_active_' . $position . '_' . $device;
                        $value = isset( $ads[ $position ][ $device ] ) ? $ads[ $position ][ $device ] : '';
                        $is_active = isset( $ads[ $position ][ $device . '_active' ] ) ? $ads[ $position ][ $device . '_active' ] : 1;
                        ?>
                        <div class="device-tab">
                            <h5>
                                <span class="dashicons dashicons-<?php echo $device === 'desktop' ? 'desktop' : ( $device === 'tablet' ? 'tablet' : 'smartphone' ); ?>"></span>
                                <?php echo esc_html( $device_label ); ?>
                            </h5>

                            <p>
                                <label>
                                    <input type="checkbox"
                                           name="<?php echo esc_attr( $active_field ); ?>"
                                           value="1"
                                           <?php checked( $is_active, 1 ); ?>>
                                    Attivo
                                </label>
                            </p>

                            <p>
                                <textarea
                                    name="<?php echo esc_attr( $field_name ); ?>"
                                    class="code-editor"
                                    rows="10"
                                    placeholder="Inserisci HTML/iframe/script per il banner <?php echo esc_attr( $device ); ?>..."><?php echo esc_textarea( $value ); ?></textarea>
                            </p>

                            <?php if ( ! empty( $value ) ) : ?>
                                <p class="preview-container">
                                    <strong>Anteprima:</strong>
                                    <div class="ad-preview">
                                        <?php echo $value; ?>
                                    </div>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <p class="shortcode-info">
                    <strong>Shortcode per inserimento manuale:</strong>
                    <code>[caniincasa_ad position="<?php echo esc_attr( $position ); ?>"]</code>
                    <button type="button" class="copy-shortcode button button-small" data-shortcode='[caniincasa_ad position="<?php echo esc_attr( $position ); ?>"]'>Copia</button>
                </p>
            </div>
        </div>
        <?php
    }
}
?>

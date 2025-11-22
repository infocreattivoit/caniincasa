<?php
/**
 * Template for Single Area Cani
 *
 * @package Caniincasa
 */

get_header();

while ( have_posts() ) :
    the_post();

    // Get ACF fields
    $indirizzo             = get_field( 'indirizzo' );
    $localita              = get_field( 'localita' );
    $provincia             = get_field( 'provincia' );
    $cap                   = get_field( 'cap' );
    $tipo_area             = get_field( 'tipo_area' ); // Checkbox
    $superficie            = get_field( 'superficie' ); // Number (mq)
    $servizi_disponibili   = get_field( 'servizi_disponibili' ); // Checkbox
    $orari_accesso         = get_field( 'orari_accesso' );
    $regolamento           = get_field( 'regolamento' );
    $accessibilita         = get_field( 'accessibilita' );
    ?>

    <main id="main-content" class="site-main single-struttura single-area-cani">

        <!-- Hero Section -->
        <div class="single-hero">
            <div class="container">
                <h1 class="entry-title"><?php the_title(); ?></h1>
                <p class="entry-subtitle">Area Cani</p>
            </div>
        </div>

        <!-- Breadcrumbs -->
        <div class="container">
            <div class="breadcrumbs-wrapper">
                <?php caniincasa_breadcrumbs(); ?>
            </div>
        </div>

        <div class="container">
            <div class="struttura-content-wrapper">

                <!-- Main Content -->
                <div class="struttura-main-content">

                    <!-- Info Box -->
                    <div class="struttura-info-box">
                        <h2 class="box-title">Informazioni Area</h2>

                        <div class="info-grid">
                            <?php if ( $indirizzo || $localita || $provincia || $cap ) : ?>
                                <div class="info-item full-width">
                                    <span class="info-label">Indirizzo:</span>
                                    <span class="info-value">
                                        <?php if ( $indirizzo ) : ?>
                                            <?php echo esc_html( $indirizzo ); ?><br>
                                        <?php endif; ?>
                                        <?php
                                        $address_parts = array_filter( array(
                                            $cap,
                                            $localita,
                                            $provincia ? '(' . $provincia . ')' : '',
                                        ) );
                                        echo esc_html( implode( ' ', $address_parts ) );
                                        ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <?php if ( $tipo_area && is_array( $tipo_area ) && count( $tipo_area ) > 0 ) : ?>
                                <div class="info-item">
                                    <span class="info-label">Tipo Area:</span>
                                    <span class="info-value"><?php echo esc_html( implode( ', ', $tipo_area ) ); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ( $superficie ) : ?>
                                <div class="info-item">
                                    <span class="info-label">Superficie:</span>
                                    <span class="info-value"><?php echo esc_html( $superficie ); ?> mq</span>
                                </div>
                            <?php endif; ?>

                            <?php if ( $orari_accesso ) : ?>
                                <div class="info-item full-width">
                                    <span class="info-label">Orari Accesso:</span>
                                    <span class="info-value"><?php echo nl2br( esc_html( $orari_accesso ) ); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Descrizione -->
                    <?php if ( get_the_content() ) : ?>
                        <div class="struttura-description">
                            <h2>Descrizione</h2>
                            <div class="description-content">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Servizi Disponibili -->
                    <?php if ( $servizi_disponibili && is_array( $servizi_disponibili ) && count( $servizi_disponibili ) > 0 ) : ?>
                        <div class="servizi-section">
                            <h2 class="section-title">Servizi Disponibili</h2>
                            <div class="servizi-list">
                                <?php foreach ( $servizi_disponibili as $servizio ) : ?>
                                    <div class="servizio-item">
                                        <span class="icon">✓</span>
                                        <?php echo esc_html( $servizio ); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Regolamento -->
                    <?php if ( $regolamento ) : ?>
                        <div class="regolamento-section">
                            <h2 class="section-title">Regolamento</h2>
                            <div class="regolamento-content">
                                <?php echo wp_kses_post( wpautop( $regolamento ) ); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Accessibilità -->
                    <?php if ( $accessibilita ) : ?>
                        <div class="accessibilita-section">
                            <h2 class="section-title">Accessibilità</h2>
                            <div class="accessibilita-content">
                                <?php echo wp_kses_post( wpautop( $accessibilita ) ); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>

                <!-- Sidebar -->
                <aside class="struttura-sidebar">

                    <?php do_action( 'caniincasa_single_struttura_sidebar_top' ); ?>

                    <!-- Back to Search Box -->
                    <div class="sidebar-box back-search-box">
                        <h3 class="box-title">Cerca Altre Aree Cani</h3>
                        <p>Torna all'archivio per cercare altre aree cani.</p>
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'aree_cani' ) ); ?>" class="btn btn-secondary btn-block">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" style="margin-right: 5px;">
                                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z" fill="currentColor"/>
                            </svg>
                            Torna alla Ricerca
                        </a>
                    </div>

                    <!-- Preferiti Box -->
                    <?php if ( is_user_logged_in() ) : ?>
                    <div class="sidebar-box preferiti-action-box">
                        <h3 class="box-title">Ti piace questa area?</h3>
                        <?php echo caniincasa_get_preferiti_button( get_the_ID(), 'aree_cani' ); ?>
                    </div>
                    <?php endif; ?>

                    <!-- Segnala Area Box -->
                    <div class="sidebar-box proponi-box">
                        <h3 class="box-title">Conosci un'area cani?</h3>
                        <p>Segnalaci un'area cani da aggiungere al nostro database.</p>
                        <a href="<?php echo esc_url( home_url( '/contatti' ) ); ?>" class="btn btn-primary btn-block">
                            Segnala Area
                        </a>
                    </div>

                    <!-- Share Box -->
                    <div class="sidebar-box share-box">
                        <h3 class="box-title">Condividi</h3>
                        <?php caniincasa_social_share_buttons(); ?>
                    </div>

                    <?php do_action( 'caniincasa_single_struttura_sidebar_bottom' ); ?>

                </aside>

            </div>
        </div>

        <?php do_action( 'caniincasa_single_struttura_before_footer' ); ?>

    </main>

    <?php
endwhile;

get_footer();

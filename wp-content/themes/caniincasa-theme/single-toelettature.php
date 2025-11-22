<?php
/**
 * Template for Single Toelettatura
 *
 * @package Caniincasa
 */

get_header();

while ( have_posts() ) :
    the_post();

    // Get ACF fields
    $persona           = get_field( 'persona' );
    $indirizzo         = get_field( 'indirizzo' );
    $localita          = get_field( 'localita' );
    $provincia         = get_field( 'provincia' );
    $cap               = get_field( 'cap' );
    $telefono          = get_field( 'telefono' );
    $email             = get_field( 'email' );
    $sito_web          = get_field( 'sito_web' );
    $servizi_offerti   = get_field( 'servizi_offerti' ); // Checkbox
    $orari_apertura    = get_field( 'orari_apertura' );
    $prezzi_indicativi = get_field( 'prezzi_indicativi' );
    ?>

    <main id="main-content" class="site-main single-struttura single-toelettatura">

        <!-- Hero Section -->
        <div class="single-hero">
            <div class="container">
                <h1 class="entry-title"><?php the_title(); ?></h1>
                <p class="entry-subtitle">Toelettatura</p>
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

                    <!-- Info & Contact Unified Box -->
                    <div class="struttura-info-box">
                        <h2 class="box-title">Informazioni e Contatti</h2>

                        <div class="info-grid">
                            <?php if ( $persona ) : ?>
                                <div class="info-item">
                                    <span class="info-label">Referente:</span>
                                    <span class="info-value"><?php echo esc_html( $persona ); ?></span>
                                </div>
                            <?php endif; ?>

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

                            <?php if ( $telefono ) : ?>
                                <div class="info-item">
                                    <span class="info-label">Telefono:</span>
                                    <span class="info-value"><?php echo esc_html( $telefono ); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ( $email ) : ?>
                                <div class="info-item">
                                    <span class="info-label">Email:</span>
                                    <span class="info-value"><?php echo esc_html( $email ); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ( $sito_web ) : ?>
                                <div class="info-item full-width">
                                    <span class="info-label">Sito Web:</span>
                                    <span class="info-value">
                                        <a href="<?php echo esc_url( $sito_web ); ?>" target="_blank" rel="noopener">
                                            <?php echo esc_html( $sito_web ); ?>
                                        </a>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Content -->
                    <?php if ( get_the_content() ) : ?>
                        <div class="struttura-description">
                            <h2>Descrizione</h2>
                            <div class="description-content">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Servizi Offerti -->
                    <?php if ( $servizi_offerti && is_array( $servizi_offerti ) && count( $servizi_offerti ) > 0 ) : ?>
                        <div class="servizi-section">
                            <h2 class="section-title">Servizi Offerti</h2>
                            <div class="servizi-list">
                                <?php foreach ( $servizi_offerti as $servizio ) : ?>
                                    <div class="servizio-item">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="servizio-icon">
                                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" fill="#4CAF50"/>
                                        </svg>
                                        <span><?php echo esc_html( $servizio ); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Orari Apertura -->
                    <?php if ( $orari_apertura ) : ?>
                        <div class="orari-section">
                            <h2 class="section-title">Orari di Apertura</h2>
                            <div class="orari-content">
                                <?php echo wp_kses_post( wpautop( $orari_apertura ) ); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Prezzi Indicativi -->
                    <?php if ( $prezzi_indicativi ) : ?>
                        <div class="prezzi-section">
                            <h2 class="section-title">Prezzi Indicativi</h2>
                            <div class="prezzi-content">
                                <?php echo wp_kses_post( wpautop( $prezzi_indicativi ) ); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>

                <!-- Sidebar -->
                <aside class="struttura-sidebar">

                    <?php do_action( 'caniincasa_single_struttura_sidebar_top' ); ?>

                    <!-- Owner Box -->
                    <?php caniincasa_struttura_claim_buttons( get_the_ID(), 'toelettature' ); ?>

                    <!-- Back to Search Box -->
                    <div class="sidebar-box back-search-box">
                        <h3 class="box-title">Cerca Altre Toelettature</h3>
                        <p>Torna all'archivio per cercare altre toelettature.</p>
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'toelettature' ) ); ?>" class="btn btn-secondary btn-block">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" style="margin-right: 5px;">
                                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z" fill="currentColor"/>
                            </svg>
                            Torna alla Ricerca
                        </a>
                    </div>

                    <!-- Preferiti Box -->
                    <?php if ( is_user_logged_in() ) : ?>
                    <div class="sidebar-box preferiti-action-box">
                        <h3 class="box-title">Ti piace questa toelettatura?</h3>
                        <?php echo caniincasa_get_preferiti_button( get_the_ID(), 'toelettature' ); ?>
                    </div>
                    <?php endif; ?>

                    <!-- Proponi Struttura Box -->
                    <div class="sidebar-box proponi-box">
                        <h3 class="box-title">Hai una toelettatura?</h3>
                        <p>Proponi la tua struttura per essere inserito nel nostro database e raggiungere più clienti.</p>
                        <a href="<?php echo esc_url( home_url( '/contatti' ) ); ?>" class="btn btn-primary btn-block">
                            Proponi la tua struttura
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

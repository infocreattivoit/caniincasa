# 🔧 Guida Integrazione Banner nei Template Esistenti

Questa guida mostra **dove e come** aggiungere gli hook per i banner nei template esistenti di Caniincasa.

---

## 📍 Template da Modificare

### 1. **Homepage** - `front-page.php` o `page-home.php`

```php
<?php get_header(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <!-- ... hero content ... -->
</section>

<!-- ✅ BANNER: Dopo Hero -->
<?php do_action( 'caniincasa_home_after_hero' ); ?>

<!-- Sezione Annunci Recenti -->
<section class="annunci-recenti">
    <!-- ... annunci ... -->
</section>

<!-- ✅ BANNER: Tra le Sezioni -->
<?php do_action( 'caniincasa_home_between_sections' ); ?>

<!-- Sezione Razze Popolari -->
<section class="razze-popolari">
    <!-- ... razze ... -->
</section>

<!-- Altre sezioni -->

<!-- ✅ BANNER: Prima del Footer -->
<?php do_action( 'caniincasa_home_before_footer' ); ?>

<?php get_footer(); ?>
```

---

### 2. **Archive Razze** - `archive-razze_di_cani.php`

```php
<?php get_header(); ?>

<main id="main-content" class="site-main">

    <!-- Archive Header -->
    <div class="archive-header">
        <div class="container">
            <h1 class="archive-title">Razze di Cani</h1>
            <?php caniincasa_breadcrumbs(); ?>
        </div>
    </div>

    <!-- ✅ BANNER: Top (dopo filtri) -->
    <div class="container">
        <?php do_action( 'caniincasa_archive_razze_top' ); ?>
    </div>

    <!-- Grid Layout -->
    <div class="container">
        <div class="archive-layout">

            <!-- Sidebar -->
            <aside class="archive-sidebar">
                <!-- Filtri -->
                <?php get_sidebar( 'razze-filters' ); ?>

                <!-- ✅ BANNER: Sidebar -->
                <?php do_action( 'caniincasa_archive_razze_sidebar' ); ?>
            </aside>

            <!-- Main Grid -->
            <div class="razze-grid">
                <?php
                if ( have_posts() ) :
                    $count = 0;

                    while ( have_posts() ) : the_post();
                        // Card razza
                        get_template_part( 'template-parts/content', 'razza-card' );

                        $count++;

                        // ✅ BANNER: Middle (dopo 6 razze)
                        if ( $count === 6 ) {
                            echo '<div class="grid-banner">';
                            do_action( 'caniincasa_archive_razze_middle' );
                            echo '</div>';
                        }
                    endwhile;

                    // Pagination
                    the_posts_pagination();
                else :
                    echo '<p>Nessuna razza trovata.</p>';
                endif;
                ?>
            </div>

        </div>
    </div>

</main>

<?php get_footer(); ?>
```

**CSS per grid banner:**

```css
/* In archive-razze.css */
.razze-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
}

.grid-banner {
    grid-column: 1 / -1; /* Full width */
}
```

---

### 3. **Single Razza** - `single-razze_di_cani.php`

```php
<?php get_header(); ?>

<main id="main-content" class="site-main single-razza">

    <div class="container">
        <div class="razza-layout">

            <!-- Sidebar -->
            <aside class="razza-sidebar">

                <!-- ✅ BANNER: Sidebar Top -->
                <?php do_action( 'caniincasa_single_razza_sidebar_top' ); ?>

                <!-- Widget Info Razza -->
                <div class="widget-info-razza">
                    <!-- Taglia, Peso, Aspettativa vita, etc -->
                </div>

                <!-- Widget Razze Simili -->
                <div class="widget-razze-simili">
                    <!-- ... -->
                </div>

                <!-- ✅ BANNER: Sidebar Bottom -->
                <?php do_action( 'caniincasa_single_razza_sidebar_bottom' ); ?>

            </aside>

            <!-- Main Content -->
            <article class="razza-content">

                <!-- Header -->
                <header class="razza-header">
                    <h1><?php the_title(); ?></h1>
                    <?php caniincasa_breadcrumbs(); ?>
                </header>

                <!-- Featured Image -->
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="razza-featured-image">
                        <?php the_post_thumbnail( 'large' ); ?>
                    </div>
                <?php endif; ?>

                <!-- Descrizione Generale -->
                <div class="razza-description">
                    <?php
                    $desc = get_field( 'descrizione_generale' );
                    if ( $desc ) {
                        echo wp_kses_post( $desc );
                    }
                    ?>
                </div>

                <!-- ✅ BANNER: Dopo Descrizione -->
                <?php do_action( 'caniincasa_single_razza_after_desc' ); ?>

                <!-- Tab Navigation -->
                <div class="razza-tabs">
                    <!-- Origini, Aspetto, Carattere, Salute, etc -->
                </div>

                <!-- Caratteristiche con Grafici -->
                <div class="razza-caratteristiche">
                    <?php get_template_part( 'template-parts/razza', 'characteristics' ); ?>
                </div>

                <!-- ✅ BANNER: Prima Razze Correlate -->
                <?php do_action( 'caniincasa_single_razza_before_related' ); ?>

                <!-- Razze Correlate -->
                <div class="razze-correlate">
                    <h2>Razze Simili</h2>
                    <?php caniincasa_related_breeds(); ?>
                </div>

            </article>

        </div>
    </div>

</main>

<?php get_footer(); ?>
```

---

### 4. **Archive Strutture** - `archive-allevamenti.php` (e altri 4 CPT strutture)

```php
<?php get_header(); ?>

<main id="main-content" class="site-main">

    <!-- Archive Header -->
    <div class="archive-header">
        <h1><?php post_type_archive_title(); ?></h1>
    </div>

    <!-- ✅ BANNER: Top -->
    <div class="container">
        <?php do_action( 'caniincasa_archive_strutture_top' ); ?>
    </div>

    <div class="container">
        <div class="archive-layout">

            <!-- Sidebar -->
            <aside class="archive-sidebar">
                <!-- Filtri provincia, regione, etc -->
                <?php get_sidebar( 'strutture-filters' ); ?>

                <!-- ✅ BANNER: Sidebar -->
                <?php do_action( 'caniincasa_archive_strutture_sidebar' ); ?>
            </aside>

            <!-- Main List -->
            <div class="strutture-list">
                <?php
                if ( have_posts() ) :
                    $count = 0;

                    while ( have_posts() ) : the_post();
                        get_template_part( 'template-parts/content', 'struttura-card' );

                        $count++;

                        // ✅ BANNER: Middle (dopo 5 strutture)
                        if ( $count === 5 ) {
                            do_action( 'caniincasa_archive_strutture_middle' );
                        }
                    endwhile;

                    the_posts_pagination();
                endif;
                ?>
            </div>

        </div>
    </div>

</main>

<?php get_footer(); ?>
```

---

### 5. **Single Struttura** - `single-allevamenti.php` (e altri 4 CPT)

```php
<?php get_header(); ?>

<main id="main-content" class="site-main single-struttura">

    <div class="container">
        <div class="struttura-layout">

            <!-- Sidebar -->
            <aside class="struttura-sidebar">

                <!-- ✅ BANNER: Sidebar Top -->
                <?php do_action( 'caniincasa_single_struttura_sidebar_top' ); ?>

                <!-- Widget Contatti -->
                <div class="widget-contatti">
                    <!-- ... -->
                </div>

                <!-- Widget Mappa -->
                <div class="widget-mappa">
                    <!-- ... -->
                </div>

                <!-- ✅ BANNER: Sidebar Bottom -->
                <?php do_action( 'caniincasa_single_struttura_sidebar_bottom' ); ?>

            </aside>

            <!-- Main Content -->
            <article class="struttura-content">
                <!-- Nome, descrizione, servizi, etc -->
                <?php the_content(); ?>
            </article>

        </div>
    </div>

</main>

<?php get_footer(); ?>
```

---

### 6. **Archive Annunci** - `archive-annunci_4zampe.php` / `archive-annunci_dogsitter.php`

```php
<?php get_header(); ?>

<main id="main-content" class="site-main">

    <div class="archive-header">
        <h1><?php post_type_archive_title(); ?></h1>
    </div>

    <!-- ✅ BANNER: Top -->
    <div class="container">
        <?php do_action( 'caniincasa_archive_annunci_top' ); ?>
    </div>

    <div class="container">
        <div class="archive-layout">

            <aside class="archive-sidebar">
                <!-- Filtri annunci -->

                <!-- ✅ BANNER: Sidebar -->
                <?php do_action( 'caniincasa_archive_annunci_sidebar' ); ?>
            </aside>

            <div class="annunci-list">
                <?php
                if ( have_posts() ) :
                    $count = 0;

                    while ( have_posts() ) : the_post();
                        get_template_part( 'template-parts/content', 'annuncio-card' );

                        $count++;

                        // ✅ BANNER: Middle (dopo 6 annunci)
                        if ( $count === 6 ) {
                            do_action( 'caniincasa_archive_annunci_middle' );
                        }
                    endwhile;
                endif;
                ?>
            </div>

        </div>
    </div>

</main>

<?php get_footer(); ?>
```

---

### 7. **Single Annuncio** - `single-annunci_4zampe.php` / `single-annunci_dogsitter.php`

```php
<?php get_header(); ?>

<main id="main-content" class="site-main single-annuncio">

    <div class="container">
        <div class="annuncio-layout">

            <aside class="annuncio-sidebar">

                <!-- ✅ BANNER: Sidebar Top -->
                <?php do_action( 'caniincasa_single_annuncio_sidebar_top' ); ?>

                <!-- Widget Autore -->
                <div class="widget-autore">
                    <!-- ... -->
                </div>

                <!-- Widget Contatto -->
                <div class="widget-contatto">
                    <!-- ... -->
                </div>

                <!-- ✅ BANNER: Sidebar Bottom -->
                <?php do_action( 'caniincasa_single_annuncio_sidebar_bottom' ); ?>

            </aside>

            <article class="annuncio-content">
                <!-- Contenuto annuncio -->
            </article>

        </div>
    </div>

</main>

<?php get_footer(); ?>
```

---

### 8. **Calcolatori** - `page-calcolatore-eta.php` (e altri 3)

```php
<?php get_header(); ?>

<main id="main-content" class="site-main calcolatore-eta">

    <div class="container">
        <div class="calcolatore-layout">

            <!-- Sidebar -->
            <aside class="calcolatore-sidebar">

                <!-- ✅ BANNER: Sidebar -->
                <?php do_action( 'caniincasa_calculator_sidebar' ); ?>

                <!-- Widget Info -->
                <div class="widget-info-calculator">
                    <h3>Come funziona?</h3>
                    <!-- ... -->
                </div>

            </aside>

            <!-- Main Calculator -->
            <div class="calcolatore-main">

                <h1><?php the_title(); ?></h1>

                <!-- Form calcolatore -->
                <form id="calculator-form" class="calculator-form">
                    <!-- Input razza, età, etc -->
                </form>

                <!-- Results Container -->
                <div id="calculator-results" style="display: none;">
                    <!-- Risultati inseriti via JavaScript -->
                </div>

                <!-- ✅ BANNER: Dopo Risultati -->
                <div id="banner-after-results">
                    <?php do_action( 'caniincasa_calculator_after_results' ); ?>
                </div>

            </div>

        </div>
    </div>

</main>

<script>
// Mostra banner dopo visualizzazione risultati
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('calculator-form');
    const results = document.getElementById('calculator-results');
    const banner = document.getElementById('banner-after-results');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // ... calcolo ...

        // Mostra risultati
        results.style.display = 'block';

        // Mostra banner dopo risultati
        banner.style.display = 'block';
    });
});
</script>

<?php get_footer(); ?>
```

---

### 9. **Comparatore Razze** - `page-comparatore-razze.php`

```php
<?php get_header(); ?>

<main id="main-content" class="site-main comparatore-razze">

    <div class="container">
        <div class="comparatore-layout">

            <!-- Sidebar -->
            <aside class="comparatore-sidebar">

                <!-- ✅ BANNER: Sidebar -->
                <?php do_action( 'caniincasa_comparatore_sidebar' ); ?>

                <!-- Widget Razze Popolari -->
                <div class="widget-razze-popolari">
                    <!-- ... -->
                </div>

            </aside>

            <!-- Main Comparatore -->
            <div class="comparatore-main">

                <h1>Comparatore Razze</h1>

                <!-- Selezione razze -->
                <div class="razze-selector">
                    <!-- ... -->
                </div>

                <!-- Tabella Confronto -->
                <div id="comparison-table" style="display: none;">
                    <!-- Tabella inserita via AJAX -->
                </div>

                <!-- ✅ BANNER: Dopo Tabella -->
                <div id="banner-after-table" style="display: none;">
                    <?php do_action( 'caniincasa_comparatore_after_table' ); ?>
                </div>

            </div>

        </div>
    </div>

</main>

<script>
// Mostra banner dopo caricamento tabella
jQuery(document).on('comparison_loaded', function() {
    jQuery('#banner-after-table').fadeIn();
});
</script>

<?php get_footer(); ?>
```

---

### 10. **Blog Archive** - `archive.php` o `home.php`

```php
<?php get_header(); ?>

<main id="main-content" class="site-main blog-archive">

    <div class="archive-header">
        <h1><?php the_archive_title(); ?></h1>
    </div>

    <!-- ✅ BANNER: Top -->
    <div class="container">
        <?php do_action( 'caniincasa_archive_blog_top' ); ?>
    </div>

    <div class="container">
        <div class="blog-layout">

            <!-- Sidebar -->
            <aside class="blog-sidebar">
                <!-- Widget Categorie -->
                <?php dynamic_sidebar( 'sidebar-blog' ); ?>

                <!-- ✅ BANNER: Sidebar -->
                <?php do_action( 'caniincasa_archive_blog_sidebar' ); ?>
            </aside>

            <!-- Main Posts -->
            <div class="blog-posts">
                <?php
                if ( have_posts() ) :
                    while ( have_posts() ) : the_post();
                        get_template_part( 'template-parts/content', get_post_format() );
                    endwhile;
                endif;
                ?>
            </div>

        </div>
    </div>

</main>

<?php get_footer(); ?>
```

---

### 11. **Single Post Blog** - `single.php`

```php
<?php get_header(); ?>

<main id="main-content" class="site-main single-post">

    <div class="container">
        <div class="post-layout">

            <!-- Sidebar -->
            <aside class="post-sidebar">
                <!-- Widget Autore -->
                <!-- Widget Post Recenti -->

                <!-- ✅ BANNER: Sidebar -->
                <?php do_action( 'caniincasa_single_post_sidebar' ); ?>
            </aside>

            <!-- Main Article -->
            <article class="post-content">

                <header class="post-header">
                    <h1><?php the_title(); ?></h1>
                    <div class="post-meta">
                        <?php caniincasa_post_meta(); ?>
                    </div>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail( 'large' ); ?>
                    </div>
                <?php endif; ?>

                <div class="post-body">
                    <?php
                    // Dividi contenuto per inserire banner mid-content
                    $content = apply_filters( 'the_content', get_the_content() );
                    $paragraphs = explode( '</p>', $content );

                    $mid_point = floor( count( $paragraphs ) / 2 );

                    // Prima metà
                    for ( $i = 0; $i < $mid_point; $i++ ) {
                        echo $paragraphs[ $i ] . '</p>';
                    }

                    // ✅ BANNER: Middle Content
                    do_action( 'caniincasa_single_post_content' );

                    // Seconda metà
                    for ( $i = $mid_point; $i < count( $paragraphs ); $i++ ) {
                        echo $paragraphs[ $i ];
                    }
                    ?>
                </div>

                <!-- Post Footer (tags, share, etc) -->

            </article>

        </div>
    </div>

</main>

<?php get_footer(); ?>
```

---

### 12. **Dashboard Utente** - `template-dashboard.php`

```php
<?php get_header(); ?>

<main id="main-content" class="site-main user-dashboard">

    <div class="container">

        <!-- ✅ BANNER: Top -->
        <?php do_action( 'caniincasa_dashboard_top' ); ?>

        <div class="dashboard-layout">

            <!-- Sidebar -->
            <aside class="dashboard-sidebar">

                <!-- Menu navigazione -->
                <nav class="dashboard-nav">
                    <!-- ... -->
                </nav>

                <!-- ✅ BANNER: Sidebar -->
                <?php do_action( 'caniincasa_dashboard_sidebar' ); ?>

            </aside>

            <!-- Main Dashboard -->
            <div class="dashboard-main">

                <!-- Tab Content -->
                <div class="dashboard-tabs">
                    <!-- Profilo, Annunci, Messaggi, etc -->
                </div>

            </div>

        </div>
    </div>

</main>

<?php get_footer(); ?>
```

---

### 13. **Header Globale** - `header.php`

```php
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<!-- ✅ BANNER: Global Header (se configurato, appare dopo body open) -->

<div id="page" class="site">

    <header id="masthead" class="site-header">
        <!-- Logo, menu, etc -->
    </header>

    <!-- Il contenuto principale va qui -->
```

---

### 14. **Footer Globale** - `footer.php`

```php
    <!-- Fine contenuto principale -->

    <footer id="colophon" class="site-footer">
        <!-- Footer widgets, copyright, etc -->
    </footer>

</div><!-- #page -->

<!-- ✅ BANNER: Global Footer (prima di wp_footer) -->

<?php wp_footer(); ?>
</body>
</html>
```

---

## 🎯 Checklist Integrazione

Per ogni template, aggiungi:

- [ ] Hook banner top (se applicabile)
- [ ] Hook banner sidebar (se c'è sidebar)
- [ ] Hook banner middle/content (posizione centrale)
- [ ] Hook banner before related/footer (fine contenuto)

### Template Minimi (già completi)

Se hai creato i file, aggiungi semplicemente gli hook:

```php
<!-- Esempio minimo -->
<?php do_action( 'caniincasa_archive_razze_top' ); ?>
```

Gli hook sono **già registrati** nel sistema, basta chiamarli nei template.

---

## 📂 File Template da Modificare (Riepilogo)

```
wp-content/themes/caniincasa-theme/
├── front-page.php (o page-home.php)           ← 3 hook
├── archive-razze_di_cani.php                  ← 3 hook
├── single-razze_di_cani.php                   ← 4 hook
├── archive-allevamenti.php                    ← 3 hook
├── archive-veterinari.php                     ← 3 hook
├── archive-canili.php                         ← 3 hook
├── archive-pensioni_per_cani.php              ← 3 hook
├── archive-centri_cinofili.php                ← 3 hook
├── single-allevamenti.php (e altri 4)         ← 2 hook
├── archive-annunci_4zampe.php                 ← 3 hook
├── archive-annunci_dogsitter.php              ← 3 hook
├── single-annunci_4zampe.php (e dogsitter)    ← 2 hook
├── page-calcolatore-eta.php                   ← 2 hook
├── page-calcolatore-peso.php                  ← 2 hook
├── page-calcolatore-costi.php                 ← 2 hook
├── page-calcolatore-cibo.php                  ← 2 hook
├── page-comparatore-razze.php                 ← 2 hook
├── archive.php (blog)                         ← 2 hook
├── single.php (blog post)                     ← 2 hook
├── template-dashboard.php                     ← 2 hook
├── header.php                                 ← (auto-inject con wp_body_open)
└── footer.php                                 ← (auto-inject con wp_footer)
```

---

**TOTALE HOOK DA AGGIUNGERE: ~60 linee di codice** (1 linea per hook)

**TEMPO STIMATO: 30-45 minuti** per aggiungere tutti gli hook

---

## 🚀 Script Automatico (Opzionale)

Se vuoi automatizzare l'inserimento, puoi usare sed/awk:

```bash
# Esempio: Aggiungi hook in archive-razze_di_cani.php dopo div.archive-header
sed -i '/<div class="archive-header">/a <?php do_action( '"'"'caniincasa_archive_razze_top'"'"' ); ?>' archive-razze_di_cani.php
```

**⚠️ Consiglio:** Fai manualmente per controllo preciso delle posizioni.

---

**Guida Integrazione Completata!** 🎉

Ora hai tutti gli esempi per aggiungere i banner nei template esistenti. Procedi template per template e testa i banner dopo ogni integrazione.

# 📢 Guida Sistema Banner Pubblicitari

## 🎯 Panoramica

Sistema completo per gestire banner pubblicitari responsive su **tutte le pagine del sito**.

**Caratteristiche:**
- ✅ Admin panel visuale per gestione banner
- ✅ Supporto codice HTML/iframe/script
- ✅ **3 versioni responsive**: Desktop (>1024px), Tablet (768-1024px), Mobile (<768px)
- ✅ **40+ posizioni predefinite** per ogni tipo di template
- ✅ Attivazione/disattivazione singolo banner
- ✅ Auto-inject nei template esistenti
- ✅ Shortcode per inserimento manuale
- ✅ Preview in tempo reale

---

## 📍 Posizioni Disponibili

### Homepage (3 posizioni)
- `home_after_hero` - Dopo Hero Section
- `home_between_sections` - Tra le Sezioni (dopo annunci)
- `home_before_footer` - Prima del Footer

### Archivio Razze (3 posizioni)
- `archive_razze_top` - Top (dopo filtri)
- `archive_razze_sidebar` - Sidebar
- `archive_razze_middle` - Middle (dopo 6 razze)

### Singola Razza (4 posizioni)
- `single_razza_sidebar_top` - Sidebar Top
- `single_razza_sidebar_bottom` - Sidebar Bottom
- `single_razza_after_desc` - Dopo Descrizione
- `single_razza_before_related` - Prima Razze Correlate

### Archivio Strutture (3 posizioni)
- `archive_strutture_top` - Top
- `archive_strutture_sidebar` - Sidebar
- `archive_strutture_middle` - Middle

### Singola Struttura (2 posizioni)
- `single_struttura_sidebar_top` - Sidebar Top
- `single_struttura_sidebar_bottom` - Sidebar Bottom

### Archivio Annunci (3 posizioni)
- `archive_annunci_top` - Top
- `archive_annunci_sidebar` - Sidebar
- `archive_annunci_middle` - Middle

### Singolo Annuncio (2 posizioni)
- `single_annuncio_sidebar_top` - Sidebar Top
- `single_annuncio_sidebar_bottom` - Sidebar Bottom

### Calcolatori (2 posizioni)
- `calculator_sidebar` - Sidebar
- `calculator_after_results` - Dopo Risultati

### Comparatore Razze (2 posizioni)
- `comparatore_sidebar` - Sidebar
- `comparatore_after_table` - Dopo Tabella

### Blog (4 posizioni)
- `archive_blog_top` - Archivio Top
- `archive_blog_sidebar` - Archivio Sidebar
- `single_post_sidebar` - Articolo Sidebar
- `single_post_content` - Articolo Middle Content

### Dashboard Utente (2 posizioni)
- `dashboard_sidebar` - Sidebar
- `dashboard_top` - Top

### Globali (3 posizioni)
- `global_header` - Header (tutte le pagine)
- `global_footer` - Footer (tutte le pagine)
- `global_sidebar` - Sidebar Generico

**TOTALE: 40 posizioni**

---

## 🚀 Utilizzo Quick Start

### 1. Accedi all'Admin

```
WordPress Admin → Banner Pubblicitari
```

### 2. Seleziona Posizione

Naviga tra i tab (Homepage, Razze, Strutture, etc.) e clicca "Espandi" sulla posizione desiderata.

### 3. Inserisci Codice Banner

Per ogni dispositivo (Desktop, Tablet, Mobile):

```html
<!-- Esempio Google AdSense -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-XXXXXXXXXX"
     data-ad-slot="1234567890"
     data-ad-format="auto"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
```

```html
<!-- Esempio iframe generico -->
<iframe src="https://esempio.com/banner.html"
        width="728"
        height="90"
        frameborder="0"
        scrolling="no"></iframe>
```

```html
<!-- Esempio HTML custom -->
<div class="banner-custom">
    <a href="https://esempio.com" target="_blank">
        <img src="/wp-content/uploads/banner-desktop.jpg" alt="Banner">
    </a>
</div>
```

### 4. Attiva il Banner

- [x] **Attivo** (checkbox per ogni dispositivo)

### 5. Salva

Click "Salva Tutti i Banner" in fondo alla pagina.

---

## 📱 Come Funziona il Responsive

Il sistema mostra automaticamente il banner corretto in base al dispositivo:

### Breakpoint

| Dispositivo | Viewport | Banner Mostrato |
|-------------|----------|-----------------|
| Desktop | > 1024px | `desktop` |
| Tablet | 768px - 1024px | `tablet` (fallback a `desktop` se vuoto) |
| Mobile | < 768px | `mobile` (fallback a `tablet` → `desktop`) |

### Esempio

Se inserisci solo il banner **desktop**, verrà mostrato su tutti i dispositivi.

Se inserisci:
- Desktop: 728x90 leaderboard
- Tablet: (vuoto)
- Mobile: 320x50 banner

**Risultato:**
- Desktop → 728x90
- Tablet → 728x90 (fallback)
- Mobile → 320x50

---

## 🎨 Inserimento Manuale con Shortcode

Puoi inserire banner manualmente in qualsiasi contenuto usando lo shortcode:

```
[caniincasa_ad position="single_razza_sidebar_top"]
```

**Dove usarlo:**
- Contenuto post/pagine (editor)
- Widget (Widget Text)
- Template PHP (con `do_shortcode()`)

**Esempio in template:**

```php
<?php echo do_shortcode( '[caniincasa_ad position="archive_razze_top"]' ); ?>
```

---

## 🔧 Integrazione nei Template

### Metodo 1: Hook Automatici (RACCOMANDATO)

Il sistema usa hook action per auto-inject i banner. Basta aggiungere l'action nei template:

```php
<!-- Esempio: single-razze_di_cani.php -->

<div class="razza-content">
    <!-- Sidebar -->
    <aside class="sidebar">
        <?php
        // Banner sidebar top
        do_action( 'caniincasa_single_razza_sidebar_top' );
        ?>

        <!-- Widget, info razza, etc -->

        <?php
        // Banner sidebar bottom
        do_action( 'caniincasa_single_razza_sidebar_bottom' );
        ?>
    </aside>

    <!-- Main content -->
    <main>
        <div class="descrizione">
            <?php the_content(); ?>
        </div>

        <?php
        // Banner dopo descrizione
        do_action( 'caniincasa_single_razza_after_desc' );
        ?>

        <div class="caratteristiche">
            <!-- ... -->
        </div>

        <?php
        // Banner prima razze correlate
        do_action( 'caniincasa_single_razza_before_related' );
        ?>

        <div class="razze-correlate">
            <!-- ... -->
        </div>
    </main>
</div>
```

### Metodo 2: Funzione Diretta

```php
<?php
// Display banner direttamente
Caniincasa_Ad_System::display_ad( 'archive_razze_middle' );
?>
```

### Metodo 3: Ottieni HTML Banner

```php
<?php
// Ottieni HTML senza output
$banner_html = Caniincasa_Ad_System::get_ad( 'calculator_sidebar' );

if ( ! empty( $banner_html ) ) {
    echo '<div class="banner-wrapper">' . $banner_html . '</div>';
}
?>
```

---

## 📂 Esempi Integrazione per Template

### Homepage (`front-page.php`)

```php
<!-- Dopo hero -->
<?php do_action( 'caniincasa_home_after_hero' ); ?>

<!-- Tra sezioni -->
<section class="annunci-recenti">
    <!-- ... -->
</section>

<?php do_action( 'caniincasa_home_between_sections' ); ?>

<section class="razze-popolari">
    <!-- ... -->
</section>

<!-- Prima footer -->
<?php do_action( 'caniincasa_home_before_footer' ); ?>
```

### Archive Razze (`archive-razze_di_cani.php`)

```php
<div class="archive-razze">
    <!-- Top -->
    <?php do_action( 'caniincasa_archive_razze_top' ); ?>

    <div class="archive-grid">
        <!-- Sidebar -->
        <aside>
            <?php do_action( 'caniincasa_archive_razze_sidebar' ); ?>
        </aside>

        <!-- Grid razze -->
        <main>
            <?php
            $count = 0;
            while ( have_posts() ) : the_post();
                // Card razza
                get_template_part( 'template-parts/content', 'razza' );

                $count++;

                // Banner middle dopo 6 razze
                if ( $count === 6 ) {
                    do_action( 'caniincasa_archive_razze_middle' );
                }
            endwhile;
            ?>
        </main>
    </div>
</div>
```

### Calcolatori (`page-calcolatore-eta.php`)

```php
<div class="calcolatore-container">
    <!-- Sidebar con banner -->
    <aside class="calcolatore-sidebar">
        <?php do_action( 'caniincasa_calculator_sidebar' ); ?>
    </aside>

    <main class="calcolatore-main">
        <!-- Form calcolatore -->
        <form id="calc-form">
            <!-- ... -->
        </form>

        <!-- Risultati -->
        <div id="calc-results" style="display: none;">
            <!-- ... risultati ... -->
        </div>

        <!-- Banner dopo risultati -->
        <?php do_action( 'caniincasa_calculator_after_results' ); ?>
    </main>
</div>
```

### Comparatore (`page-comparatore-razze.php`)

```php
<div class="comparatore-container">
    <aside class="comparatore-sidebar">
        <?php do_action( 'caniincasa_comparatore_sidebar' ); ?>
    </aside>

    <main>
        <!-- Tabella confronto -->
        <div class="comparison-table">
            <!-- ... -->
        </div>

        <?php do_action( 'caniincasa_comparatore_after_table' ); ?>
    </main>
</div>
```

### Dashboard Utente (`template-dashboard.php`)

```php
<div class="user-dashboard">
    <?php do_action( 'caniincasa_dashboard_top' ); ?>

    <div class="dashboard-grid">
        <aside class="dashboard-sidebar">
            <?php do_action( 'caniincasa_dashboard_sidebar' ); ?>
        </aside>

        <main>
            <!-- Tab navigation, content, etc -->
        </main>
    </div>
</div>
```

---

## 🎯 Best Practices

### 1. Dimensioni Banner Consigliate

**Desktop:**
- Leaderboard: 728x90
- Medium Rectangle: 300x250
- Wide Skyscraper: 160x600
- Large Rectangle: 336x280

**Tablet:**
- Leaderboard: 728x90
- Medium Rectangle: 300x250

**Mobile:**
- Mobile Banner: 320x50
- Large Mobile Banner: 320x100
- Medium Rectangle: 300x250 (se spazio sufficiente)

### 2. Performance

- ✅ Usa lazy loading per iframe: `loading="lazy"`
- ✅ Minimizza codice JavaScript inline
- ✅ Preferisci async script tags
- ✅ Evita banner troppo pesanti su mobile

### 3. UX

- ✅ Non esagerare con il numero di banner per pagina
- ✅ Mantieni spazio tra banner e contenuto
- ✅ Usa banner non invasivi (no popup, no autoplay audio)
- ✅ Testa su dispositivi reali

### 4. SEO

- ✅ Non nascondere contenuto principale con banner
- ✅ Usa `rel="sponsored"` per link pubblicitari
- ✅ Mantieni buon rapporto contenuto/pubblicità
- ✅ Evita banner above-the-fold eccessivi (Core Web Vitals)

---

## 🔍 Funzionalità Admin

### Ricerca Posizioni

Usa il campo "Cerca posizione..." per filtrare le posizioni.

### Espandi/Chiudi Tutto

- **Espandi Tutto**: Apre tutte le posizioni
- **Chiudi Tutto**: Chiude tutte le posizioni

### CodeMirror Editor

L'editor codice include:
- Syntax highlighting HTML
- Line numbers
- Auto-complete
- Indentazione automatica

### Copia Shortcode

Click "Copia" per copiare lo shortcode negli appunti.

### Avviso Modifiche Non Salvate

Il sistema avvisa se esci senza salvare.

---

## 🎨 Personalizzazione CSS

### Targettare Posizioni Specifiche

```css
/* Banner homepage dopo hero */
.caniincasa-ad-home_after_hero {
    margin: 60px 0;
    background: #f9f9f9;
    padding: 20px;
}

/* Banner sidebar razze */
.caniincasa-ad[data-position*="sidebar"] {
    position: sticky;
    top: 100px;
}

/* Hide label su mobile */
@media (max-width: 767px) {
    .caniincasa-ad::before {
        display: none;
    }
}
```

### Rimuovere Label "Pubblicità"

```css
.caniincasa-ad::before {
    display: none;
}
```

### Sticky Sidebar Ads

```css
.caniincasa-ad-single_razza_sidebar_top {
    position: sticky;
    top: 80px;
    z-index: 10;
}
```

---

## 🐛 Troubleshooting

### Banner Non Visualizzato

**Verifica:**
1. ✅ Banner attivo (checkbox "Attivo" selezionata)
2. ✅ Codice inserito nel dispositivo corretto
3. ✅ Template include l'hook action o shortcode
4. ✅ Cache del sito pulita
5. ✅ Nessun AdBlocker attivo

### Banner Tagliato/Deformato

**Soluzione:**
- Usa `max-width: 100%` nel CSS del banner
- Per iframe: aggiungi `style="max-width: 100%; height: auto;"`
- Verifica dimensioni banner compatibili con layout

### Banner Duplicato

**Causa:** Hook action chiamato più volte nello stesso template

**Soluzione:**
- Rimuovi chiamate duplicate
- Oppure usa flag per chiamata singola:
```php
<?php
static $banner_shown = false;
if ( ! $banner_shown ) {
    do_action( 'caniincasa_archive_razze_top' );
    $banner_shown = true;
}
?>
```

### Script Banner Non Funziona

**Causa:** Script bloccato da sanitizzazione WordPress

**Soluzione:**
Il sistema usa `wp_kses_post()` che permette script tag. Se necessario codice più complesso:

```html
<!-- Metodo 1: Usa iframe esterno -->
<iframe src="https://tuosito.com/banner-script.html"></iframe>

<!-- Metodo 2: Carica script da file -->
<div id="banner-container"></div>
<script src="https://cdn.esempio.com/banner.js"></script>
```

---

## 📊 Tracking & Analytics

### Google Analytics

```html
<!-- Banner con tracking click -->
<a href="https://esempio.com"
   onclick="gtag('event', 'click', {
       'event_category': 'banner',
       'event_label': 'sidebar_razza'
   });">
    <img src="banner.jpg" alt="Banner">
</a>
```

### Custom Tracking

```javascript
// Nel banner HTML
<div class="banner-trackable" data-position="archive_razze_top">
    <!-- Banner content -->
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Track impression
    if (typeof dataLayer !== 'undefined') {
        dataLayer.push({
            'event': 'bannerImpression',
            'position': 'archive_razze_top'
        });
    }
});
</script>
```

---

## 🔐 Sicurezza

Il sistema:
- ✅ Richiede permesso `manage_options` (solo admin)
- ✅ Usa nonce per CSRF protection
- ✅ Sanitizza input con `wp_kses_post()`
- ✅ Escaping output nei template

**Nota:** Il sistema permette HTML/iframe/script per flessibilità banner. Solo admin può gestire banner.

---

## 📝 Note Tecniche

### Database

I banner sono salvati in `wp_options` come:
```
Option: caniincasa_ads
Value: array(
    'position_name' => array(
        'desktop' => 'HTML code',
        'desktop_active' => 1,
        'tablet' => 'HTML code',
        'tablet_active' => 1,
        'mobile' => 'HTML code',
        'mobile_active' => 1,
    ),
    ...
)
```

### Hook Actions Disponibili

Tutti gli hook seguono il pattern `caniincasa_{type}_{position}`:

```php
// Homepage
do_action( 'caniincasa_home_after_hero' );
do_action( 'caniincasa_home_between_sections' );
do_action( 'caniincasa_home_before_footer' );

// Archive Razze
do_action( 'caniincasa_archive_razze_top' );
do_action( 'caniincasa_archive_razze_sidebar' );
do_action( 'caniincasa_archive_razze_middle' );

// Etc... (40 hook totali)
```

### Funzioni PHP

```php
// Display banner (echo)
Caniincasa_Ad_System::display_ad( string $position, array $args = [] );

// Get banner HTML (return)
Caniincasa_Ad_System::get_ad( string $position, array $args = [] ): string;

// Get tutte le posizioni
Caniincasa_Ad_System::get_positions(): array;

// Get dispositivi
Caniincasa_Ad_System::get_devices(): array;
```

---

## 🚀 Esempi Pratici

### Banner AdSense Responsive

```html
<!-- Desktop/Tablet -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-1234567890123456"
     data-ad-slot="1234567890"
     data-ad-format="horizontal"></ins>
<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
```

```html
<!-- Mobile -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-1234567890123456"
     data-ad-slot="0987654321"
     data-ad-format="fluid"
     data-ad-layout-key="-fb+5w+4e-db+86"></ins>
<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
```

### Banner Affiliazione

```html
<div style="text-align: center; padding: 20px; background: #f5f5f5;">
    <a href="https://affiliato.com/?ref=caniincasa"
       target="_blank"
       rel="sponsored noopener">
        <img src="/wp-content/uploads/banner-affiliato.jpg"
             alt="Prodotto per cani"
             style="max-width: 100%; height: auto;">
    </a>
</div>
```

### Banner HTML Custom con CTA

```html
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 8px;
            text-align: center;">
    <h3 style="margin: 0 0 10px; font-size: 24px;">
        Cerchi un Allevamento?
    </h3>
    <p style="margin: 0 0 20px; font-size: 16px;">
        Trova gli allevamenti certificati nella tua zona
    </p>
    <a href="/allevamenti/"
       style="display: inline-block;
              background: white;
              color: #667eea;
              padding: 12px 30px;
              border-radius: 25px;
              text-decoration: none;
              font-weight: bold;">
        Cerca Ora
    </a>
</div>
```

---

## 📚 Risorse

- **Admin**: WordPress Admin → Banner Pubblicitari
- **File Sistema**: `wp-content/plugins/caniincasa-core/includes/ad-system.php`
- **CSS Frontend**: `wp-content/plugins/caniincasa-core/assets/css/ads.css`
- **CSS Admin**: `wp-content/plugins/caniincasa-core/assets/css/ads-admin.css`
- **JS Admin**: `wp-content/plugins/caniincasa-core/assets/js/ads-admin.js`

---

**Versione:** 1.0.0
**Data:** 22 Novembre 2025
**Autore:** Caniincasa Team

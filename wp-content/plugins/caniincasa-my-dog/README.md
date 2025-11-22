# Caniincasa My Dog

Plugin WordPress completo per gestire profili dei propri cani con funzionalità avanzate.

## 🐕 Funzionalità Principali

### Gestione Profili Cane
- **Profili Privati**: Ogni utente può creare profili dei propri cani (privati, visibili solo al proprietario)
- **Dati Completi**:
  - Informazioni base (nome, razza, data nascita, sesso, peso, taglia)
  - Identificazione (microchip, pedigree, passaporto europeo)
  - Salute (veterinario, allergie, patologie, farmaci, assicurazione)
  - Alimentazione (tipo cibo, marca, quantità, pasti)
  - Comportamento (temperamento, addestramento)
- **Foto Profilo**: Upload foto del cane
- **Dashboard Frontend**: `/i-miei-cani/` - interfaccia completa per gestire i cani

### Calendario Vaccinazioni
- Registro completo vaccinazioni con storico
- Data somministrazione e data richiamo
- **Reminder Automatici**: Email 7 giorni prima della scadenza (cron job giornaliero)
- Visualizzazione vaccinazioni in scadenza (prossimi 30 giorni)
- Note e veterinario per ogni vaccinazione

### Esportazione PDF Veterinario
- Scheda completa esportabile per il veterinario
- Include tutti i dati, storico vaccinazioni, allergie
- Formato stampabile/salvabile come PDF
- QR code per accesso rapido (futuro)
- URL: `/i-miei-cani/{id}/?export_dog_pdf=1`

### Tracker Peso
- Registro pesate con date
- Grafico andamento peso (Chart.js)
- Calcolo variazioni tra pesate
- Note per ogni misurazione

### Diario/Note
- Note giornaliere sul cane
- Tipi di note (generale, medica, comportamento, alimentazione)
- Database dedicato per storico completo

### Calcolatori
- **Età Umana**: Calcola età del cane in anni umani (considera taglia)
- **Cibo Giornaliero**: Quantità cibo consigliata (peso + attività)
- **Tracker Peso**: Grafico interattivo evoluzione peso

### Newsletter
- **Blocco Pre-Footer Globale**: Form iscrizione newsletter su tutte le pagine
- Design responsive con gradiente viola/blu
- Privacy policy checkbox
- AJAX submission
- Esportazione CSV iscritti da admin
- Pronto per integrazione MailChimp/Sendinblue

## 📦 Struttura Plugin

```
caniincasa-my-dog/
├── caniincasa-my-dog.php          # Main plugin file
├── README.md
├── includes/
│   ├── class-post-type.php        # CPT dog_profile + capabilities
│   ├── class-acf-fields.php       # Campi ACF programmatici
│   ├── class-dashboard.php        # Frontend dashboard + rewrite rules
│   ├── class-ajax-handlers.php    # AJAX endpoints
│   ├── class-calendar.php         # Vaccinazioni + reminder cron
│   ├── class-pdf-export.php       # Esportazione scheda PDF
│   ├── class-newsletter-block.php # Blocco newsletter globale
│   └── class-calculators.php      # Tool calcolatori
├── admin/
│   └── class-admin.php            # Pannello admin + statistiche
├── templates/
│   ├── dashboard-list.php         # Lista cani
│   └── (altri template)
└── assets/
    ├── css/
    │   ├── my-dog.css             # Stili frontend
    │   └── admin.css              # Stili admin
    └── js/
        └── my-dog.js              # JavaScript AJAX

```

## 🗄️ Database

### Tabelle Custom

**wp_dog_vaccinations**
- id, dog_id, vaccine_name, vaccine_date, next_date
- veterinarian, notes, reminder_sent
- created_at

**wp_dog_weight_tracker**
- id, dog_id, weight, measurement_date
- notes, created_at

**wp_dog_notes**
- id, dog_id, note_date, note_type, note_content
- created_at

## 🔧 Installazione

1. Caricare plugin nella cartella `/wp-content/plugins/caniincasa-my-dog/`
2. Attivare da **Plugin** → **Plugin Installati**
3. Il plugin crea automaticamente:
   - CPT `dog_profile`
   - 3 tabelle database
   - Capabilities per utenti
   - Rewrite rules per dashboard
4. Visitare **My Dog** → **Impostazioni** in admin

## 📍 URL e Shortcode

### URL Dashboard
- `/i-miei-cani/` - Lista cani utente
- `/i-miei-cani/aggiungi/` - Aggiungi nuovo cane
- `/i-miei-cani/{id}/` - Visualizza cane singolo
- `/i-miei-cani/{id}/modifica/` - Modifica cane
- `/i-miei-cani/{id}/?export_dog_pdf=1` - Export PDF

### Shortcode Disponibili

**Dashboard**
```
[my_dogs_dashboard]
```
Lista completa cani con grid cards

**Singolo Cane**
```
[my_dog_single id="123"]
```

**Calendario Vaccinazioni**
```
[dog_vaccination_calendar dog_id="123"]
```

**Calcolatore Età**
```
[dog_age_calculator]
```

**Tracker Peso**
```
[dog_weight_tracker dog_id="123"]
```

**Calcolatore Cibo**
```
[dog_food_calculator]
```

**Newsletter**
```
[newsletter_signup]
```

## ⚙️ Configurazione

### Cron Job Reminder
Il plugin registra automaticamente un cron job giornaliero:
- Hook: `caniincasa_vaccination_reminders`
- Frequenza: daily
- Invia email 7 giorni prima della scadenza vaccinazione

Per testare manualmente:
```php
do_action('caniincasa_vaccination_reminders');
```

### Newsletter Block
Attiva/disattiva da **My Dog** → **Impostazioni**
- Opzione: `caniincasa_my_dog_newsletter_enabled`
- Default: attivo (1)

## 👥 Capabilities

Il plugin crea capabilities custom per `dog_profile`:
- `read_dog_profile`
- `read_private_dog_profiles`
- `edit_dog_profiles`
- `edit_published_dog_profiles`
- `publish_dog_profiles`
- `delete_dog_profiles`
- `delete_published_dog_profiles`

**Subscriber** può gestire solo i propri cani
**Administrator** può gestire tutti i cani

## 📊 Statistiche Admin

Pannello **My Dog** → **Statistiche** mostra:
- Cani registrati totali
- Utenti attivi
- Vaccinazioni registrate
- Vaccinazioni in scadenza (30 giorni)
- Pesate registrate
- Note totali
- Top 10 razze più popolari

## 🎨 Personalizzazione

### CSS Custom
Aggiungi stili personalizzati in `assets/css/my-dog.css`

### Colori Brand
- Primary: `#FF6B35` (arancione Caniincasa)
- Secondary: `#667eea` (viola)
- Success: `#4CAF50`
- Warning: `#FFC107`

### Template Override
Copia template da `caniincasa-my-dog/templates/` al tema in:
```
/wp-content/themes/tuo-tema/caniincasa-my-dog/
```

## 🔌 Integrazioni Future

### Newsletter Services
Modificare `class-newsletter-block.php` metodo `newsletter_signup()`:
```php
// Integrazione MailChimp
$mailchimp = new MailChimp_API();
$mailchimp->subscribe($email);

// Integrazione Sendinblue
$sendinblue = new Sendinblue_API();
$sendinblue->add_contact($email);
```

### PDF Library
Per PDF avanzati, installare via Composer:
```bash
composer require mpdf/mpdf
```

Poi modificare `class-pdf-export.php` per usare mPDF invece di HTML.

## 📝 Note Sviluppo

- **ACF Required**: Il plugin richiede Advanced Custom Fields (free o PRO)
- **jQuery**: Usa jQuery incluso in WordPress
- **Chart.js**: Caricato da CDN per grafici peso
- **Responsive**: Design mobile-first
- **GDPR**: Form newsletter con checkbox privacy

## 🚀 Versione

**1.0.0** - Release iniziale

## 📄 Licenza

GPL v2 or later

# Istruzioni per Import Dati - Toelettature e Aree Cani

**Data:** 2025-11-22
**Versione:** 1.0
**Status:** ✅ Step 1 COMPLETATO - ACF fields configurati

---

## 📋 Checklist Progressi

- [x] **Step 1:** Configurare campi ACF ✅ COMPLETATO
- [ ] **Step 2:** Generare JSON con AI
- [ ] **Step 3:** Importare dati di test
- [ ] **Step 4:** ~~Aggiungere immagini~~ (ESCLUSO per ora)
- [ ] **Step 5:** Configurare banner
- [ ] **Step 6:** Testare filtri AJAX

---

## ✅ Step 1 COMPLETATO: Configurazione ACF

I campi ACF per Toelettature e Aree Cani sono stati **registrati programmaticamente** e sono già attivi nel tuo WordPress.

**File creati:**
- `wp-content/plugins/caniincasa-core/includes/acf-fields-strutture.php`

**Campi Toelettature registrati:**
- persona, indirizzo, localita, provincia, cap
- telefono, email, sito_web
- servizi_offerti (12 opzioni checkbox)
- orari_apertura, prezzi_indicativi

**Campi Aree Cani registrati:**
- indirizzo, localita, provincia, cap
- tipo_area (6 opzioni checkbox)
- superficie (number)
- servizi_disponibili (9 opzioni checkbox)
- orari_accesso, regolamento, accessibilita

**Verifica:**
1. Vai su WordPress Admin → Toelettature → Aggiungi Nuovo
2. Dovresti vedere il box "Informazioni Toelettatura" con tutti i campi
3. Vai su WordPress Admin → Aree Cani → Aggiungi Nuovo
4. Dovresti vedere il box "Informazioni Area Cani" con tutti i campi

---

## 📝 Step 2: Generare JSON con AI (DA FARE)

Per generare i file JSON con dati realistici, usa i prompt forniti con ChatGPT, Claude o altro AI.

### 2.1 Generare Toelettature JSON

**File prompt:** `PROMPT_GENERAZIONE_JSON_TOELETTATURE.md`

**Come procedere:**

1. **Apri il file prompt:**
   ```bash
   cat PROMPT_GENERAZIONE_JSON_TOELETTATURE.md
   ```

2. **Copia il prompt completo** (dalla riga "Genera un file JSON contenente..." fino alla fine degli esempi)

3. **Incolla in ChatGPT/Claude** (consigliato: GPT-4 o Claude Sonnet per dati più realistici)

4. **Attendi la generazione** (l'AI genererà 30-40 toelettature italiane con dati reali)

5. **Copia il JSON** dall'output (SOLO il JSON, senza testo aggiuntivo)

6. **Salva in un file:**
   ```bash
   # Crea il file toelettature.json nella root del progetto
   nano toelettature.json
   # Incolla il JSON, salva (Ctrl+O, Enter, Ctrl+X)
   ```

7. **Valida il JSON:**
   ```bash
   # Verifica che sia JSON valido
   cat toelettature.json | jq . > /dev/null && echo "✅ JSON valido" || echo "❌ JSON non valido"
   ```

8. **Verifica manuale:**
   - Apri https://jsonlint.com
   - Incolla il JSON
   - Controlla che non ci siano errori di sintassi

**Cosa verificare nel JSON generato:**
- [ ] Province sono sigle italiane valide (MI, RM, NA, TO, etc.)
- [ ] Indirizzi e città esistono realmente
- [ ] Superfici sono numeri interi (non stringhe)
- [ ] Servizi sono tra quelli definiti nel prompt
- [ ] Tutte le toelettature hanno almeno "titolo" e "slug"
- [ ] Slug sono URL-friendly (minuscole, trattini, no spazi)

---

### 2.2 Generare Aree Cani JSON

**File prompt:** `PROMPT_GENERAZIONE_JSON_AREE_CANI.md`

**Come procedere:**

1. **Apri il file prompt:**
   ```bash
   cat PROMPT_GENERAZIONE_JSON_AREE_CANI.md
   ```

2. **Copia il prompt completo** (dalla riga "Genera un file JSON contenente..." fino alla fine)

3. **Incolla in ChatGPT/Claude**

4. **Attendi la generazione** (l'AI genererà 40-50 aree cani pubbliche italiane)

5. **Copia il JSON** dall'output

6. **Salva in un file:**
   ```bash
   nano aree_cani.json
   # Incolla il JSON, salva
   ```

7. **Valida il JSON:**
   ```bash
   cat aree_cani.json | jq . > /dev/null && echo "✅ JSON valido" || echo "❌ JSON non valido"
   ```

**Cosa verificare nel JSON generato:**
- [ ] Province sono sigle italiane valide
- [ ] Aree distribuite in tutta Italia (Nord, Centro, Sud, Isole)
- [ ] Superfici realistiche: 150-300mq (piccole), 300-700mq (medie), 700-2000mq (grandi)
- [ ] Servizi sono tra quelli definiti nel prompt
- [ ] Regolamenti realistici basati su comuni italiani
- [ ] Tutte le aree hanno "titolo" e "slug"

---

## 📥 Step 3: Importare Dati di Test (DA FARE dopo Step 2)

Una volta generati i file JSON, importali tramite WordPress Admin.

### 3.1 Importare Toelettature

1. **Accedi a WordPress Admin**

2. **Vai su:** Strutture → **Importa Toelettature JSON**

3. **Carica il file:**
   - Clicca "Scegli file"
   - Seleziona `toelettature.json`
   - Clicca "Importa Toelettature"

4. **Verifica l'importazione:**
   - Dovresti vedere un messaggio di successo
   - Numero di toelettature importate
   - Numero di errori (dovrebbe essere 0)
   - Log dettagliato dell'importazione

5. **Controlla le bozze create:**
   - Vai su Strutture → Tutte le Toelettature
   - Dovresti vedere 30-40 toelettature con status **BOZZA**
   - Apri alcune bozze per verificare che i dati siano stati importati correttamente

6. **Revisiona e pubblica:**
   - Revisiona ogni toelettatura
   - ~~Aggiungi un'immagine di copertina~~ (SKIP per ora)
   - Cambia status da "Bozza" a "Pubblicato"

**Troubleshooting:**
- **Errore "JSON non valido"**: Valida su jsonlint.com
- **Errore "Provincia non trovata"**: Verifica sigle province (devono essere 2 lettere maiuscole)
- **Campi mancanti**: Verifica che il JSON contenga almeno "titolo" e "slug"

---

### 3.2 Importare Aree Cani

1. **Accedi a WordPress Admin**

2. **Vai su:** Strutture → **Importa Aree Cani JSON**

3. **Carica il file:**
   - Clicca "Scegli file"
   - Seleziona `aree_cani.json`
   - Clicca "Importa Aree Cani"

4. **Verifica l'importazione:**
   - Messaggio di successo
   - Numero di aree importate
   - Log dettagliato

5. **Controlla le bozze:**
   - Vai su Strutture → Tutte le Aree Cani
   - Dovresti vedere 40-50 aree con status **BOZZA**

6. **Revisiona e pubblica:**
   - Revisiona ogni area cani
   - ~~Aggiungi immagini~~ (SKIP per ora)
   - Pubblica quando soddisfatto

---

## 🎨 Step 5: Configurare Banner (DA FARE dopo import)

I banner sono già configurati come posizioni nel sistema. Ora devi aggiungere il codice HTML/iframe.

### Posizioni Banner Disponibili per Toelettature:

**Single Toelettatura (`/toelettature/nome-toelettatura/`):**
- `single_struttura_sidebar_top` - Sopra sidebar
- `single_struttura_sidebar_bottom` - Sotto sidebar
- `single_struttura_before_footer` - Prima del footer

**Archive Toelettature (`/toelettature/`):**
- `archive_strutture_top` - Sopra griglia
- `archive_strutture_sidebar` - Nella sidebar filtri
- `archive_strutture_before_footer` - Prima del footer

### Posizioni Banner Disponibili per Aree Cani:

**Single Area Cani (`/aree-cani/nome-area/`):**
- `single_struttura_sidebar_top`
- `single_struttura_sidebar_bottom`
- `single_struttura_before_footer`

**Archive Aree Cani (`/aree-cani/`):**
- `archive_strutture_top`
- `archive_strutture_sidebar`
- `archive_strutture_before_footer`

### Come Configurare i Banner:

1. **Vai su:** WordPress Admin → **Banner Pubblicitari**

2. **Crea un nuovo banner:**
   - Clicca "Aggiungi Nuovo Banner"
   - Titolo: "Banner Toelettature Sidebar" (o simile)
   - Codice Desktop: `<iframe src="..." width="300" height="250"></iframe>`
   - Codice Tablet: (opzionale, se vuoi un banner diverso)
   - Codice Mobile: (opzionale)

3. **Seleziona la posizione:**
   - Tab: **Strutture**
   - Posizione: Seleziona dalla dropdown (es. "Single Struttura - Sidebar Top")

4. **Salva**

5. **Verifica sul frontend:**
   - Apri una pagina toelettatura
   - Dovresti vedere il banner nella posizione selezionata

---

## ✅ Step 6: Testare Filtri AJAX (DA FARE dopo import)

Dopo aver importato i dati, testa che i filtri funzionino correttamente.

### 6.1 Testare Archive Toelettature

1. **Vai su:** `/toelettature/` sul frontend

2. **Testa ricerca per nome:**
   - Digita "Pelo" nel campo "Cerca per nome"
   - Attendi 500ms (debounce)
   - Verifica che la lista si aggiorni mostrando solo toelettature con "Pelo" nel nome

3. **Testa filtro provincia:**
   - Seleziona "MI - Milano"
   - Verifica che vengano mostrate solo toelettature di Milano

4. **Testa ordinamento:**
   - Cambia "Ordina per" in "Nome Z-A"
   - Verifica che l'ordine si inverta

5. **Testa paginazione:**
   - Se ci sono più di 12 toelettature, dovresti vedere la paginazione
   - Clicca su "Pagina 2"
   - Verifica che carichi la seconda pagina via AJAX senza ricaricare

6. **Testa reset filtri:**
   - Applica alcuni filtri
   - Clicca "Resetta"
   - Verifica che tutti i filtri si azzerino

7. **Testa view toggle:**
   - Clicca icona "Lista" (linee orizzontali)
   - Verifica che il layout cambi da griglia a lista
   - Clicca icona "Griglia" (quadrati)
   - Verifica che torni alla vista griglia

### 6.2 Testare Archive Aree Cani

Ripeti gli stessi test su `/aree-cani/`:
- [ ] Ricerca per nome
- [ ] Filtro provincia
- [ ] Ordinamento
- [ ] Paginazione AJAX
- [ ] Reset filtri
- [ ] View toggle

### 6.3 Testare Single Page

1. **Apri una singola toelettatura:** `/toelettature/nome-toelettatura/`

   Verifica che vengano visualizzati:
   - [ ] Titolo
   - [ ] Indirizzo completo
   - [ ] Telefono, Email, Sito Web (se presenti)
   - [ ] Servizi offerti (lista con checkbox)
   - [ ] Orari di apertura
   - [ ] Prezzi indicativi
   - [ ] Breadcrumbs
   - [ ] Pulsante "Torna alla Ricerca"
   - [ ] Banner (se configurati)

2. **Apri una singola area cani:** `/aree-cani/nome-area/`

   Verifica che vengano visualizzati:
   - [ ] Titolo
   - [ ] Indirizzo completo
   - [ ] Tipo area
   - [ ] Superficie in mq
   - [ ] Orari accesso
   - [ ] Servizi disponibili
   - [ ] Regolamento
   - [ ] Accessibilità
   - [ ] Breadcrumbs
   - [ ] Banner (se configurati)

---

## 📊 Riepilogo Files Generati

### Files già creati (Step 1):
- ✅ `wp-content/plugins/caniincasa-core/includes/acf-fields-strutture.php`
- ✅ `wp-content/plugins/caniincasa-core/includes/acf-fields.php` (modificato)

### Files da creare (Step 2):
- ⏳ `toelettature.json` (generato via AI)
- ⏳ `aree_cani.json` (generato via AI)

### Files prompt disponibili:
- ✅ `PROMPT_GENERAZIONE_JSON_TOELETTATURE.md`
- ✅ `PROMPT_GENERAZIONE_JSON_AREE_CANI.md`

### Files importer disponibili:
- ✅ `wp-content/plugins/caniincasa-core/includes/toelettature-json-importer.php`
- ✅ `wp-content/plugins/caniincasa-core/includes/aree-cani-json-importer.php`

### Templates disponibili:
- ✅ `wp-content/themes/caniincasa-theme/single-toelettature.php`
- ✅ `wp-content/themes/caniincasa-theme/archive-toelettature.php`
- ✅ `wp-content/themes/caniincasa-theme/single-aree_cani.php`
- ✅ `wp-content/themes/caniincasa-theme/archive-aree_cani.php`

---

## 🚀 Quick Start - Prossimi Passi

**ADESSO SEI QUI:** Step 1 completato ✅

**PROSSIMO PASSO:** Genera i file JSON (Step 2)

```bash
# 1. Apri i prompt
cat PROMPT_GENERAZIONE_JSON_TOELETTATURE.md
cat PROMPT_GENERAZIONE_JSON_AREE_CANI.md

# 2. Copia i prompt e usali con ChatGPT/Claude

# 3. Salva i JSON generati
# toelettature.json
# aree_cani.json

# 4. Valida i JSON
cat toelettature.json | jq .
cat aree_cani.json | jq .

# 5. Vai su WordPress Admin e importa
# Strutture → Importa Toelettature JSON
# Strutture → Importa Aree Cani JSON

# 6. Configura banner (opzionale)
# Banner Pubblicitari → Aggiungi Nuovo

# 7. Testa sul frontend
# /toelettature/
# /aree-cani/
```

---

## 🆘 Supporto

**Problemi con ACF:**
- Verifica che ACF Pro sia installato e attivo
- I campi dovrebbero apparire automaticamente quando crei/modifichi Toelettature o Aree Cani

**Problemi con import JSON:**
- Verifica che il JSON sia valido su jsonlint.com
- Controlla i log di errore WordPress (`wp-content/debug.log` se WP_DEBUG è attivo)
- Verifica che le province esistano nella taxonomy (tutte le 107 province italiane dovrebbero essere già presenti)

**Problemi con filtri AJAX:**
- Apri la console browser (F12)
- Verifica che non ci siano errori JavaScript
- Controlla che le richieste AJAX vengano effettuate correttamente

---

**Ultima modifica:** 2025-11-22
**Autore:** Claude Code
**Versione:** 1.0

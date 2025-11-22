# Estensione Chrome - Caniincasa Post Creator

## Descrizione

Questa estensione Chrome permette di selezionare testo da qualsiasi pagina web e inviarlo direttamente a Caniincasa.it come nuovo post WordPress.

## Caratteristiche

✅ Selezione testo con tasto destro
✅ Salvataggio automatico URL e titolo della fonte
✅ Autenticazione WordPress con Application Password
✅ Creazione post come Bozza o Pubblicato
✅ Selezione categoria
✅ Campo per prompt AI (istruzioni di elaborazione)
✅ Opzione per includere link alla fonte
✅ Contatore caratteri del contenuto

---

## Parte 1: Creare Application Password su WordPress

### Passo 1: Accedi a WordPress Admin

1. Vai su: `https://www.caniincasa.it/wp-admin/`
2. Effettua login con le tue credenziali

### Passo 2: Crea Application Password

1. Nel menu laterale vai su **Utenti** → **Profilo**
2. Scorri fino alla sezione **Application Passwords** (Password per applicazioni)
3. Nel campo **Nome nuova password applicazione** inserisci: `Chrome Extension`
4. Clicca su **Aggiungi nuova password applicazione**
5. **IMPORTANTE**: Copia la password generata (formato: `xxxx xxxx xxxx xxxx xxxx xxxx`)
   - Questa password verrà mostrata **solo una volta**
   - Conservala in un luogo sicuro (notepad, gestore password)

> **Nota**: L'Application Password è diversa dalla tua password WordPress normale. È più sicura perché può essere revocata in qualsiasi momento senza cambiare la password principale.

---

## Parte 2: Installare l'Estensione Chrome

### Passo 1: Accedi alla cartella dell'estensione

La cartella dell'estensione si trova in:
```
/home/user/caniincasa/chrome-extension/
```

Contiene i seguenti file:
- `manifest.json` - Configurazione estensione
- `background.js` - Gestione menu contestuale
- `content.js` - Interazione con le pagine web
- `popup.html` - Interfaccia utente
- `popup.css` - Stili interfaccia
- `popup.js` - Logica applicazione
- `icon16.svg`, `icon48.svg`, `icon128.svg` - Icone

### Passo 2: Apri Chrome Extensions

1. Apri Google Chrome
2. Nella barra indirizzi digita: `chrome://extensions/`
3. Premi Invio

### Passo 3: Attiva Modalità Sviluppatore

1. In alto a destra troverai l'interruttore **Modalità sviluppatore**
2. Attivalo (diventa blu)

### Passo 4: Carica l'estensione

1. Clicca sul pulsante **Carica estensione non pacchettizzata**
2. Naviga fino alla cartella: `/home/user/caniincasa/chrome-extension/`
3. Seleziona la cartella e clicca **Seleziona cartella**

### Passo 5: Verifica installazione

L'estensione apparirà nella lista con:
- Nome: **Caniincasa Post Creator**
- Versione: **1.0.0**
- Icona arancione con la lettera "C"

### Passo 6: Fissa l'estensione (opzionale)

1. Clicca sull'icona puzzle 🧩 in alto a destra nella barra Chrome
2. Trova **Caniincasa Post Creator**
3. Clicca sulla puntina 📌 per fissarla alla barra

---

## Parte 3: Configurare l'Estensione

### Primo Utilizzo

1. Clicca sull'icona dell'estensione nella barra Chrome
2. Si aprirà il popup con la sezione **Autenticazione WordPress**
3. Inserisci i dati:
   - **Username WordPress**: il tuo username WordPress
   - **Application Password**: la password creata al Passo 1 (formato: `xxxx xxxx xxxx xxxx xxxx xxxx`)
4. Clicca su **Salva Credenziali**

### Verifica Autenticazione

- Se le credenziali sono corrette: vedrai ✅ **Credenziali salvate correttamente!**
- L'interfaccia passerà automaticamente alla sezione **Crea Nuovo Post**
- Le credenziali vengono salvate in modo sicuro nel Chrome Storage

### Problemi di Autenticazione

Se vedi ❌ **Credenziali non valide**:
1. Verifica che l'username sia corretto
2. Verifica che l'Application Password sia stata copiata correttamente (includi tutti gli spazi)
3. Prova a creare una nuova Application Password su WordPress
4. Controlla che il tuo utente WordPress abbia i permessi per creare post

---

## Parte 4: Utilizzare l'Estensione

### Metodo 1: Seleziona testo da qualsiasi pagina

1. Naviga su qualsiasi sito web
2. Seleziona il testo che vuoi inviare a Caniincasa
3. Clicca con il **tasto destro** sul testo selezionato
4. Nel menu contestuale scegli: **Invia a Caniincasa.it**
5. Si aprirà automaticamente il popup dell'estensione con:
   - Il testo selezionato già inserito nel campo **Contenuto**
   - L'URL della pagina salvato (visibile se attivi "Includi fonte")
   - Il titolo della pagina salvato

### Metodo 2: Apri direttamente l'estensione

1. Clicca sull'icona dell'estensione nella barra Chrome
2. Inserisci manualmente titolo e contenuto

### Compilare il Form

1. **Titolo Post** (obbligatorio): Inserisci il titolo del post
2. **Contenuto** (obbligatorio): Il testo viene auto-inserito se hai usato il menu contestuale
   - Puoi modificarlo liberamente
   - Il contatore mostra il numero di caratteri
3. **Stato**: Scegli tra:
   - **Bozza** (consigliato): il post viene salvato ma non pubblicato
   - **Pubblicato**: il post viene pubblicato immediatamente
4. **Categoria**: Seleziona una categoria dal menu a tendina
5. **Includi fonte**: Attiva questa opzione per aggiungere un link alla pagina originale alla fine del post
6. **Prompt AI** (opzionale): Inserisci istruzioni per elaborare il contenuto
   - Esempio: "Riscrivi in formato SEO-friendly"
   - Esempio: "Riassumi in 3 paragrafi"
   - Esempio: "Traduci in italiano e ottimizza per cani"
   - Il prompt viene salvato come commento HTML nel post

### Creare il Post

1. Clicca su **📝 Crea Post**
2. L'estensione invierà i dati a WordPress
3. Vedrai uno dei seguenti messaggi:
   - ✅ **Post pubblicato!** con link a visualizza/modifica
   - ✅ **Post salvato come bozza!** con link a visualizza/modifica
   - ❌ **Errore** con descrizione del problema

### Dopo la Creazione

- Il form si pulisce automaticamente dopo 3 secondi
- Puoi cliccare sui link per:
  - **Visualizza post**: vedere il post sul sito
  - **Modifica**: aprire l'editor WordPress per modifiche

---

## Parte 5: Workflow con AI

### Caso d'uso: Creare contenuti con AI

1. **Raccogli il testo grezzo**
   - Naviga su articoli, news, guide
   - Seleziona parti interessanti
   - Usa "Invia a Caniincasa.it" per raccogliere il testo

2. **Aggiungi prompt AI**
   - Nel campo "Prompt AI" inserisci istruzioni come:
     - "Riscrivi questo articolo in chiave SEO per il settore cinofilo italiano"
     - "Estrai i punti chiave e crea una guida pratica"
     - "Trasforma in un articolo blog di 500 parole su addestramento cani"

3. **Salva come bozza**
   - Seleziona **Stato: Bozza**
   - Clicca **Crea Post**

4. **Elabora con AI**
   - Apri la bozza in WordPress
   - Copia il contenuto e il prompt (nel commento HTML)
   - Usa la tua AI preferita (ChatGPT, Claude, ecc.) con il prompt
   - Incolla il contenuto elaborato nel post
   - Pubblica

### Esempio Pratico

```
Testo selezionato da un sito:
"Dog training requires patience and consistency. Start with basic commands
like sit, stay, and come. Use positive reinforcement..."

Prompt AI inserito:
"Traduci in italiano, adatta al contesto italiano, espandi a 400 parole,
aggiungi consigli pratici per proprietari di cani in Italia"

Risultato:
→ Bozza creata su WordPress
→ Prompt salvato nel post (visibile in modalità HTML/codice)
→ Elabori con AI esterna
→ Pubblichi contenuto finale
```

---

## Parte 6: Gestione Credenziali

### Logout

1. Apri l'estensione
2. Clicca su **⚙️** (icona impostazioni) in alto a destra
3. Clicca su **Logout**
4. Conferma quando richiesto
5. Le credenziali vengono rimosse dal browser

### Cambiare Credenziali

1. Effettua logout come sopra
2. Inserisci le nuove credenziali
3. Clicca su **Salva Credenziali**

### Sicurezza

- Le credenziali sono salvate nel Chrome Storage Sync
- Vengono sincronizzate tra i dispositivi con lo stesso account Chrome
- L'Application Password può essere revocata in qualsiasi momento da WordPress
- Non condividere mai la tua Application Password

---

## Parte 7: Risoluzione Problemi

### Problema: "Credenziali non valide"

**Soluzioni:**
1. Verifica username WordPress (case-sensitive)
2. Ricrea Application Password su WordPress
3. Copia/incolla la password senza spazi aggiuntivi
4. Verifica che l'utente abbia permessi di pubblicazione

### Problema: "Errore di connessione"

**Soluzioni:**
1. Verifica connessione internet
2. Verifica che `www.caniincasa.it` sia raggiungibile
3. Controlla firewall/antivirus
4. Prova a disattivare altre estensioni

### Problema: "Errore caricamento categorie"

**Soluzioni:**
1. Verifica che le categorie esistano su WordPress
2. Controlla che l'API REST sia attiva: `https://www.caniincasa.it/wp-json/wp/v2/categories`
3. Ricarica l'estensione (chrome://extensions → Ricarica)

### Problema: Il menu contestuale non appare

**Soluzioni:**
1. Ricarica la pagina web
2. Ricarica l'estensione su chrome://extensions
3. Verifica che l'estensione sia attiva
4. Alcune pagine Chrome (chrome://, chrome-extension://) bloccano le estensioni

### Problema: Il popup non si apre

**Soluzioni:**
1. Clicca sull'icona puzzle e poi sull'icona dell'estensione
2. Riavvia Chrome
3. Ricarica l'estensione su chrome://extensions

---

## Parte 8: Aggiornamenti Futuri

### Aggiornare l'estensione

Quando vengono rilasciati aggiornamenti:

1. Sostituisci i file nella cartella `chrome-extension/`
2. Vai su `chrome://extensions/`
3. Trova **Caniincasa Post Creator**
4. Clicca sull'icona ricarica 🔄
5. Verifica la nuova versione nel campo **Versione**

---

## Parte 9: Disinstallazione

### Rimuovere l'estensione

1. Vai su `chrome://extensions/`
2. Trova **Caniincasa Post Creator**
3. Clicca su **Rimuovi**
4. Conferma la rimozione

### Revocare Application Password

1. Vai su WordPress Admin → Utenti → Profilo
2. Nella sezione **Application Passwords**
3. Trova "Chrome Extension"
4. Clicca su **Revoca** per eliminare l'accesso

---

## Supporto

Per problemi o domande:
- Controlla la sezione **Risoluzione Problemi**
- Verifica la console Chrome (F12 → Console) per errori
- Controlla i log di WordPress
- Verifica che le REST API WordPress siano attive

---

## Note Tecniche

### File dell'estensione

- **manifest.json**: Configurazione Manifest V3, permessi, icone
- **background.js**: Service worker, gestisce menu contestuale
- **content.js**: Script iniettato nelle pagine web
- **popup.html**: Interfaccia HTML popup
- **popup.css**: Stili interfaccia (brand Caniincasa)
- **popup.js**: Logica applicazione, API REST WordPress

### API WordPress utilizzate

- **Autenticazione**: `/wp-json/wp/v2/users/me` (GET)
- **Categorie**: `/wp-json/wp/v2/categories?per_page=100` (GET)
- **Creazione post**: `/wp-json/wp/v2/posts` (POST)

### Permessi Chrome

- `contextMenus`: Per menu tasto destro
- `storage`: Per salvare credenziali e testo selezionato
- `activeTab`: Per accedere alla tab corrente
- `https://www.caniincasa.it/*`: Per comunicare con WordPress

---

**Versione**: 1.0.0
**Ultimo aggiornamento**: 2025-01-22
**Compatibilità**: Chrome 88+, Edge 88+

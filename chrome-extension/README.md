# Caniincasa Post Creator - Chrome Extension

Estensione Chrome per inviare testo selezionato da qualsiasi pagina web a Caniincasa.it come nuovo post WordPress.

## 🚀 Quick Start

1. **Leggi le istruzioni complete**: [ISTRUZIONI_INSTALLAZIONE.md](ISTRUZIONI_INSTALLAZIONE.md)
2. **Crea Application Password** su WordPress (`/wp-admin/` → Utenti → Profilo)
3. **Carica l'estensione** in Chrome (`chrome://extensions/` → Modalità sviluppatore → Carica estensione non pacchettizzata)
4. **Configura credenziali** nell'estensione

## ✨ Caratteristiche

- ✅ Selezione testo con menu contestuale (tasto destro)
- ✅ Salvataggio automatico URL e titolo fonte
- ✅ Autenticazione WordPress sicura (Application Password)
- ✅ Creazione post come Bozza o Pubblicato
- ✅ Selezione categoria WordPress
- ✅ Campo per prompt AI (istruzioni elaborazione contenuto)
- ✅ Opzione per includere link alla fonte
- ✅ Contatore caratteri contenuto

## 📁 File dell'estensione

```
chrome-extension/
├── manifest.json          # Configurazione Manifest V3
├── background.js          # Service worker (menu contestuale)
├── content.js             # Script pagine web
├── popup.html             # Interfaccia utente
├── popup.css              # Stili interfaccia
├── popup.js               # Logica applicazione
├── icon16.svg             # Icona 16x16
├── icon48.svg             # Icona 48x48
├── icon128.svg            # Icona 128x128
├── ISTRUZIONI_INSTALLAZIONE.md  # Guida completa
└── README.md              # Questo file
```

## 🔧 Tecnologie

- **Chrome Extension Manifest V3**
- **WordPress REST API** (`/wp-json/wp/v2/`)
- **Application Passwords** (WordPress 5.6+)
- **Chrome Storage API** (Sync per credenziali)
- **Chrome Context Menus API**

## 💡 Workflow con AI

1. Seleziona testo interessante da web
2. Click destro → "Invia a Caniincasa.it"
3. Aggiungi prompt AI (es: "Riscrivi in formato SEO")
4. Salva come bozza
5. Elabora con ChatGPT/Claude usando il prompt
6. Pubblica contenuto finale

## 📝 Esempio d'uso

```javascript
// Seleziona testo da articolo inglese
"Dog training requires patience..."

// Aggiungi prompt AI
"Traduci in italiano, espandi a 400 parole, aggiungi consigli pratici"

// → Bozza creata su WordPress
// → Elabora con AI esterna
// → Pubblica contenuto finale
```

## 🔐 Sicurezza

- Application Password (non password principale WordPress)
- Credenziali salvate in Chrome Storage Sync (criptate)
- Password revocabile in qualsiasi momento da WordPress
- Autenticazione Basic Auth su HTTPS

## 🐛 Debug

Apri console Chrome (F12):
```javascript
// Verifica storage
chrome.storage.sync.get(['wpUsername', 'wpPassword'], console.log)

// Verifica testo selezionato
chrome.storage.local.get(['selectedText', 'sourceUrl'], console.log)
```

## 📦 Versione

**1.0.0** - 2025-01-22

## 📄 Licenza

Uso interno Caniincasa.it

---

Per istruzioni dettagliate vedi: **[ISTRUZIONI_INSTALLAZIONE.md](ISTRUZIONI_INSTALLAZIONE.md)**

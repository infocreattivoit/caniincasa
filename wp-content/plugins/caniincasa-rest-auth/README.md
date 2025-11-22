# Caniincasa REST API Basic Auth

Plugin minimale per abilitare l'autenticazione Basic Auth sulla WordPress REST API.

## 🎯 Scopo

Permette all'**estensione Chrome** e ad altre applicazioni esterne di autenticarsi con WordPress usando:
- **Username** WordPress
- **Application Password** (WordPress 5.6+)

## ⚙️ Come Funziona

1. Intercetta le richieste REST API con header `Authorization: Basic`
2. Decodifica le credenziali Base64
3. Verifica con WordPress Application Passwords
4. Autentica l'utente per la richiesta REST

## 🔐 Sicurezza

- ✅ Usa **Application Passwords** (non password principale)
- ✅ Solo per richieste REST API (`/wp-json/`)
- ✅ Le Application Password possono essere revocate
- ✅ Header CORS configurati per Chrome Extension
- ⚠️ Usa sempre **HTTPS** in produzione

## 📝 Installazione

1. Il plugin è già presente in `/wp-content/plugins/caniincasa-rest-auth/`
2. Vai in **Plugin** → **Plugin Installati**
3. Trova **"Caniincasa REST API Basic Auth"**
4. Clicca **Attiva**

## 🧪 Test

Dopo l'attivazione, testa l'API:

```bash
curl -X GET "https://www.caniincasa.it/wp-json/wp/v2/users/me" \
  -u "username:xxxx xxxx xxxx xxxx xxxx xxxx"
```

Sostituisci `username` e la password con le tue credenziali.

## 🔌 Integrazione Chrome Extension

Una volta attivo il plugin:

1. Apri l'estensione Chrome
2. Inserisci:
   - **Username:** Il tuo username WordPress
   - **Password:** La tua Application Password
3. Clicca **Salva Credenziali**
4. ✅ Dovrebbe funzionare!

## ⚠️ Requisiti

- WordPress 5.6+ (per Application Passwords)
- PHP 7.0+
- HTTPS consigliato (obbligatorio in produzione)

## 🐛 Debug

Se ci sono problemi, attiva il debug WordPress in `wp-config.php`:

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
```

I log saranno in `/wp-content/debug.log`

## 🔒 Nota sulla Sicurezza

**NON usare questo plugin se:**
- Il sito non ha HTTPS
- Non ti fidi delle app che useranno l'API
- Preferisci altri metodi di autenticazione (OAuth, JWT)

**Le Application Password sono sicure perché:**
- Sono separate dalla password principale
- Possono essere revocate individualmente
- Hanno scope limitato
- Non danno accesso all'admin tramite login normale

## 📄 Licenza

GPL v2 or later

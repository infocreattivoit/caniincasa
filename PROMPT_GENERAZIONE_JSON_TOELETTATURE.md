# Prompt per Generazione JSON Toelettature

**Data creazione:** 2025-11-22
**Scopo:** Generare un file JSON con dati di toelettature italiane per importazione massiva nel sito Caniincasa.it

---

## Prompt da utilizzare con AI (ChatGPT, Claude, ecc.)

```
Genera un file JSON contenente 30-40 toelettature reali italiane distribuite in tutta Italia.

Per ogni toelettatura, includi i seguenti campi:

{
  "titolo": "Nome della toelettatura (stringa, es. 'Toelettatura Fido Bello')",
  "slug": "slug-url-friendly (stringa, es. 'toelettatura-fido-bello')",
  "descrizione": "Descrizione dettagliata della toelettatura (HTML consentito, 150-300 parole). Includi informazioni su: esperienza, filosofia, ambiente, personale qualificato, attrezzature moderne",
  "persona": "Nome del titolare/responsabile (stringa, es. 'Maria Rossi')",
  "indirizzo": "Via e numero civico (stringa, es. 'Via Roma 123')",
  "localita": "Città (stringa, es. 'Milano')",
  "provincia": "Sigla provincia maiuscola (stringa, 2 caratteri, es. 'MI')",
  "cap": "Codice postale (stringa, es. '20100')",
  "telefono": "Numero di telefono (stringa, es. '02 1234567' o '333 1234567')",
  "email": "Indirizzo email (stringa, es. 'info@toelettaturafido.it')",
  "sito_web": "URL sito web (stringa con http/https, es. 'https://www.toelettaturafido.it' - opzionale)",
  "servizi_offerti": [
    "Array di servizi offerti (array di stringhe). Scegli tra:",
    "- Bagno e asciugatura",
    "- Toelettatura completa",
    "- Stripping",
    "- Taglio unghie",
    "- Pulizia orecchie",
    "- Pulizia ghiandole perianali",
    "- Trattamenti antiparassitari",
    "- SPA per cani",
    "- Massaggi rilassanti",
    "- Servizio a domicilio",
    "- Servizio taxi pet",
    "- Toelettatura razze specifiche",
    "Ogni toelettatura deve avere 4-8 servizi"
  ],
  "orari_apertura": "Orari di apertura settimanali (stringa multi-riga, es. 'Lunedì-Venerdì: 9:00-13:00 / 15:00-19:00\nSabato: 9:00-13:00\nDomenica: Chiuso')",
  "prezzi_indicativi": "Listino prezzi indicativo (stringa multi-riga, es. 'Bagno cane piccola taglia: €25-35\nToelettatura completa media taglia: €40-60\nStripping: da €50\nTaglio unghie: €10')"
}

REQUISITI IMPORTANTI:
1. Usa toelettature REALI e ESISTENTI in Italia (cerca online se necessario)
2. Distribuisci le toelettature in diverse regioni italiane (Nord, Centro, Sud, Isole)
3. Varia le dimensioni: piccole toelettature di quartiere, toelettature medie, grandi centri grooming
4. Ogni toelettatura deve avere almeno 4 servizi offerti
5. I prezzi devono essere realistici per il mercato italiano 2024-2025
6. Gli orari devono essere verosimili (considera chiusure pranzo, domeniche, ecc.)
7. Le email devono seguire pattern realistici (info@, contatti@, nome@dominio.it)
8. I telefoni devono avere formati italiani validi
9. Le province devono essere SIGLE ITALIANE VALIDE (MI, RM, NA, TO, FI, etc.)
10. Descrizioni professionali, invitanti, che valorizzino qualità e competenza

FORMATO OUTPUT:
Restituisci SOLO un array JSON valido, senza testo aggiuntivo prima o dopo.
Verifica che il JSON sia sintatticamente corretto.

Esempio di struttura del file di output:
[
  {
    "titolo": "Toelettatura Il Cane Felice",
    "slug": "toelettatura-il-cane-felice-milano",
    "descrizione": "<p>La Toelettatura Il Cane Felice è un centro grooming di riferimento a Milano...</p>",
    "persona": "Laura Bianchi",
    "indirizzo": "Via Dante 45",
    "localita": "Milano",
    "provincia": "MI",
    "cap": "20121",
    "telefono": "02 87654321",
    "email": "info@ilcanefelice.it",
    "sito_web": "https://www.ilcanefelice.it",
    "servizi_offerti": [
      "Bagno e asciugatura",
      "Toelettatura completa",
      "Taglio unghie",
      "Pulizia orecchie",
      "Trattamenti antiparassitari",
      "SPA per cani"
    ],
    "orari_apertura": "Lunedì-Venerdì: 9:00-13:00 / 15:00-19:30\nSabato: 9:00-13:00\nDomenica: Chiuso",
    "prezzi_indicativi": "Bagno piccola taglia: €30\nBagno media taglia: €45\nBagno grande taglia: €60\nToelettatura completa da: €50\nStripping: da €55\nTaglio unghie: €12"
  },
  { ... altre 29-39 toelettature ... }
]
```

---

## Istruzioni Post-Generazione

1. **Salva il JSON** generato dall'AI in un file chiamato `toelettature.json`
2. **Valida il JSON** usando un validatore online (jsonlint.com)
3. **Controlla i dati:**
   - Province devono essere sigle italiane valide (2 lettere maiuscole)
   - Email devono avere formato valido
   - URL devono iniziare con http:// o https://
   - Telefoni devono essere nel formato italiano
4. **Importa in WordPress:**
   - Vai su **WordPress Admin → Strutture → Importa Toelettature JSON**
   - Carica il file `toelettature.json`
   - Clicca "Importa Toelettature"
5. **Verifica le bozze create:**
   - Le toelettature vengono create come BOZZE per permetterti di revisionarle
   - Aggiungi immagini manualmente per ogni toelettatura
   - Pubblica quando sei soddisfatto

---

## Campi JSON Dettagliati

| Campo | Tipo | Obbligatorio | Descrizione |
|-------|------|--------------|-------------|
| `titolo` | string | ✅ | Nome della toelettatura |
| `slug` | string | ✅ | URL-friendly slug (lettere minuscole, trattini) |
| `descrizione` | string | ❌ | Descrizione HTML (150-300 parole) |
| `persona` | string | ❌ | Nome titolare/responsabile |
| `indirizzo` | string | ❌ | Via e numero civico |
| `localita` | string | ❌ | Città |
| `provincia` | string | ❌ | Sigla provincia (2 caratteri maiuscoli, es. MI) |
| `cap` | string | ❌ | Codice postale |
| `telefono` | string | ❌ | Numero telefono |
| `email` | string | ❌ | Email valida |
| `sito_web` | string | ❌ | URL completo con http/https |
| `servizi_offerti` | array | ❌ | Array di stringhe con servizi |
| `orari_apertura` | string | ❌ | Orari multi-riga |
| `prezzi_indicativi` | string | ❌ | Prezzi multi-riga |

---

## Esempio di Toelettatura Completa

```json
{
  "titolo": "Toelettatura Pelo & Bellezza",
  "slug": "toelettatura-pelo-bellezza-roma",
  "descrizione": "<p>Toelettatura Pelo & Bellezza è un salone di grooming professionale situato nel cuore di Roma. Con oltre 15 anni di esperienza, offriamo servizi di toelettatura di alta qualità per tutte le razze canine.</p><p>Il nostro staff qualificato utilizza prodotti professionali specifici per il pelo del tuo cane, garantendo risultati eccellenti e il massimo benessere dell'animale. Disponiamo di attrezzature moderne e di una sala dedicata ai trattamenti SPA.</p><p>Lavoriamo esclusivamente su appuntamento per garantire la massima attenzione ad ogni cliente a quattro zampe.</p>",
  "persona": "Alessandro Verdi",
  "indirizzo": "Via Nazionale 234",
  "localita": "Roma",
  "provincia": "RM",
  "cap": "00184",
  "telefono": "06 87654321",
  "email": "info@peloebellezza.it",
  "sito_web": "https://www.peloebellezza.it",
  "servizi_offerti": [
    "Bagno e asciugatura",
    "Toelettatura completa",
    "Stripping",
    "Taglio unghie",
    "Pulizia orecchie",
    "Pulizia ghiandole perianali",
    "SPA per cani",
    "Massaggi rilassanti"
  ],
  "orari_apertura": "Lunedì-Venerdì: 9:00-13:00 / 15:30-19:30\nSabato: 9:00-18:00 (orario continuato)\nDomenica: Chiuso",
  "prezzi_indicativi": "Bagno piccola taglia (fino 10kg): €30-40\nBagno media taglia (10-25kg): €45-60\nBagno grande taglia (oltre 25kg): €65-85\n\nToelettatura completa piccola: €45-55\nToelettatura completa media: €60-80\nToelettatura completa grande: €85-120\n\nStripping: da €60\nSPA rilassante: €35\nTaglio unghie: €12\nPulizia orecchie: €10"
}
```

---

## Note per l'Importazione

- **Status Post:** Tutte le toelettature vengono importate come BOZZE
- **Immagini:** Le immagini devono essere aggiunte manualmente dopo l'importazione
- **Revisione:** Rivedi sempre i dati importati prima di pubblicare
- **Duplicati:** L'importer verifica gli slug esistenti e salta i duplicati
- **Provincia:** Viene assegnata automaticamente la taxonomy "provincia" se esiste

---

## Risoluzione Problemi

**Errore "JSON non valido":**
- Verifica il JSON su jsonlint.com
- Controlla virgole, parentesi, apici
- Assicurati che non ci siano caratteri speciali non escapati

**Errore "Provincia non trovata":**
- Verifica che la sigla provincia sia valida (2 lettere maiuscole)
- Le province devono esistere nella taxonomy WordPress

**Nessuna toelettatura importata:**
- Controlla i log di errore
- Verifica che il campo "titolo" sia presente per ogni toelettatura
- Controlla che il JSON sia un array valido

---

## Supporto

Per problemi con l'importazione, verifica:
1. Log errori PHP
2. Console browser per errori JavaScript
3. Permessi file upload in WordPress
4. Dimensione massima file upload (aumenta se necessario)

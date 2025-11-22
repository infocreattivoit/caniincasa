# Prompt per Generazione JSON Aree Cani

**Data creazione:** 2025-11-22
**Scopo:** Generare un file JSON con dati di aree cani pubbliche italiane per importazione massiva nel sito Caniincasa.it

---

## Prompt da utilizzare con AI (ChatGPT, Claude, ecc.)

```
Genera un file JSON contenente 40-50 aree cani pubbliche reali italiane distribuite in tutta Italia.

Per ogni area cani, includi i seguenti campi:

{
  "titolo": "Nome dell'area cani o nome del parco (stringa, es. 'Area Cani Parco Sempione' o 'Giardini di Castello - Area Cani')",
  "slug": "slug-url-friendly (stringa, es. 'area-cani-parco-sempione-milano')",
  "descrizione": "Descrizione dettagliata dell'area (HTML consentito, 100-200 parole). Includi: posizione nel parco/quartiere, caratteristiche area, eventuali note sulla gestione, stato di manutenzione",
  "indirizzo": "Via o nome del parco (stringa, es. 'Parco Sempione' o 'Via dei Giardini')",
  "localita": "Città (stringa, es. 'Milano')",
  "provincia": "Sigla provincia maiuscola (stringa, 2 caratteri, es. 'MI')",
  "cap": "Codice postale (stringa, es. '20121')",
  "tipo_area": [
    "Array di caratteristiche area (array di stringhe). Scegli tra:",
    "- Recintata",
    "- Libera (non recintata)",
    "- Per cani di piccola taglia",
    "- Per cani di taglia grande",
    "- Area mista",
    "- Doppia area (piccola/grande)",
    "Ogni area deve avere 1-3 caratteristiche"
  ],
  "superficie": "Superficie approssimativa in metri quadri (numero intero, es. 300, 500, 1000)",
  "servizi_disponibili": [
    "Array di servizi presenti (array di stringhe). Scegli tra:",
    "- Fontanella acqua",
    "- Sacchetti igienici",
    "- Cestini",
    "- Panchine",
    "- Illuminazione notturna",
    "- Giochi per cani",
    "- Percorso agility",
    "- Ombreggiatura/alberi",
    "- Parcheggio vicino",
    "Ogni area deve avere 2-6 servizi"
  ],
  "orari_accesso": "Orari di accesso all'area (stringa, es. 'Libero accesso 24h' o 'Aperta dalle 7:00 alle 22:00' o 'Segue orari del parco: 6:00-21:00 inverno, 6:00-23:00 estate')",
  "regolamento": "Regole di utilizzo (stringa multi-riga, es. 'I cani devono essere tenuti al guinzaglio fino all'ingresso nell'area\nÈ obbligatorio raccogliere le deiezioni\nVietato l'accesso a cani aggressivi o in calore\nMassimo 2 cani per proprietario')",
  "accessibilita": "Informazioni accessibilità (stringa, es. 'Accessibile con passeggini e sedie a rotelle. Cancello largo 1,5m' o 'Accesso con gradini, non completamente accessibile')"
}

REQUISITI IMPORTANTI:
1. Usa aree cani REALI e ESISTENTI in Italia (cerca online se necessario)
2. Distribuisci le aree in diverse città italiane (Nord, Centro, Sud, Isole)
3. Varia le dimensioni: piccole aree di quartiere, aree medie, grandi parchi urbani
4. Includi aree sia recintate che libere
5. I dati devono essere realistici (superfici verosimili, servizi reali)
6. Le province devono essere SIGLE ITALIANE VALIDE (MI, RM, NA, TO, FI, etc.)
7. Descrizioni chiare e informative sulla localizzazione
8. Regolamenti basati su regolamenti reali dei comuni italiani
9. Orari verosimili (molte aree sono 24h, altre seguono orari parco)
10. Superficie realistiche: piccole 150-300mq, medie 300-700mq, grandi 700-2000mq

FORMATO OUTPUT:
Restituisci SOLO un array JSON valido, senza testo aggiuntivo prima o dopo.
Verifica che il JSON sia sintatticamente corretto.

Esempio di struttura del file di output:
[
  {
    "titolo": "Area Cani Parco Sempione",
    "slug": "area-cani-parco-sempione-milano",
    "descrizione": "<p>L'area cani del Parco Sempione è una delle più frequentate di Milano. Situata nel cuore del parco, vicino all'Arena Civica, offre un ampio spazio recintato dove i cani possono correre liberamente. L'area è ben mantenuta dal Comune e frequentata da molti proprietari che si incontrano regolarmente.</p>",
    "indirizzo": "Parco Sempione",
    "localita": "Milano",
    "provincia": "MI",
    "cap": "20121",
    "tipo_area": [
      "Recintata",
      "Area mista"
    ],
    "superficie": 800,
    "servizi_disponibili": [
      "Fontanella acqua",
      "Sacchetti igienici",
      "Cestini",
      "Panchine",
      "Illuminazione notturna",
      "Ombreggiatura/alberi"
    ],
    "orari_accesso": "Segue orari del parco: 6:30-21:00 inverno, 6:30-23:00 estate",
    "regolamento": "I cani devono essere tenuti al guinzaglio fino all'ingresso nell'area recintata\nÈ obbligatorio raccogliere le deiezioni del proprio cane\nVietato l'accesso a cani aggressivi o femmine in calore\nI proprietari sono responsabili del comportamento dei propri cani\nMassimo 2 cani per proprietario",
    "accessibilita": "Completamente accessibile. Cancello ampio, terreno pianeggiante, panchine presenti"
  },
  { ... altre 39-49 aree cani ... }
]
```

---

## Istruzioni Post-Generazione

1. **Salva il JSON** generato dall'AI in un file chiamato `aree_cani.json`
2. **Valida il JSON** usando un validatore online (jsonlint.com)
3. **Controlla i dati:**
   - Province devono essere sigle italiane valide (2 lettere maiuscole)
   - Superfici devono essere numeri interi ragionevoli (100-2000 mq)
   - Aree distribuite geograficamente in Italia
4. **Importa in WordPress:**
   - Vai su **WordPress Admin → Strutture → Importa Aree Cani JSON**
   - Carica il file `aree_cani.json`
   - Clicca "Importa Aree Cani"
5. **Verifica le bozze create:**
   - Le aree cani vengono create come BOZZE per permetterti di revisionarle
   - Aggiungi immagini manualmente per ogni area
   - Pubblica quando sei soddisfatto

---

## Campi JSON Dettagliati

| Campo | Tipo | Obbligatorio | Descrizione |
|-------|------|--------------|-------------|
| `titolo` | string | ✅ | Nome area cani o nome parco + area cani |
| `slug` | string | ✅ | URL-friendly slug (lettere minuscole, trattini) |
| `descrizione` | string | ❌ | Descrizione HTML (100-200 parole) |
| `indirizzo` | string | ❌ | Via o nome parco |
| `localita` | string | ❌ | Città |
| `provincia` | string | ❌ | Sigla provincia (2 caratteri maiuscoli) |
| `cap` | string | ❌ | Codice postale |
| `tipo_area` | array | ❌ | Array caratteristiche (Recintata, Libera, etc.) |
| `superficie` | number | ❌ | Metri quadri (numero intero) |
| `servizi_disponibili` | array | ❌ | Array servizi presenti |
| `orari_accesso` | string | ❌ | Orari accesso |
| `regolamento` | string | ❌ | Regole utilizzo (multi-riga) |
| `accessibilita` | string | ❌ | Info accessibilità |

---

## Esempi Tipo Area

**Tipo Area (scegli 1-3):**
- Recintata (più comune)
- Libera (non recintata, rara)
- Per cani di piccola taglia (area dedicata)
- Per cani di taglia grande (area dedicata)
- Area mista (tutte le taglie)
- Doppia area (separazione piccola/grande taglia)

**Servizi Comuni:**
- Fontanella acqua (essenziale)
- Sacchetti igienici
- Cestini
- Panchine
- Illuminazione notturna
- Giochi per cani
- Percorso agility
- Ombreggiatura/alberi
- Parcheggio vicino

---

## Esempio Area Cani Completa

```json
{
  "titolo": "Area Cani Villa Borghese - Porta Pinciana",
  "slug": "area-cani-villa-borghese-porta-pinciana-roma",
  "descrizione": "<p>L'area cani di Villa Borghese è una delle più grandi e attrezzate di Roma. Situata vicino a Porta Pinciana, offre circa 1200 mq di spazio recintato con doppia area separata per cani di piccola e grande taglia. L'area è molto frequentata, specialmente nei weekend, e ben ombreggiata da alberi secolari.</p><p>La gestione è curata dal Comune di Roma con manutenzione regolare. È presente anche un piccolo percorso agility per l'addestramento.</p>",
  "indirizzo": "Villa Borghese - Porta Pinciana",
  "localita": "Roma",
  "provincia": "RM",
  "cap": "00197",
  "tipo_area": [
    "Recintata",
    "Doppia area (piccola/grande)"
  ],
  "superficie": 1200,
  "servizi_disponibili": [
    "Fontanella acqua",
    "Sacchetti igienici",
    "Cestini",
    "Panchine",
    "Percorso agility",
    "Ombreggiatura/alberi",
    "Parcheggio vicino"
  ],
  "orari_accesso": "Libero accesso 24h (l'area è sempre aperta, il parco ha orari 7:00-20:00 inverno, 7:00-21:00 estate)",
  "regolamento": "I cani devono essere tenuti al guinzaglio fino all'area recintata\nRaccolta obbligatoria delle deiezioni\nVietato l'accesso a cani aggressivi\nFemmine in calore devono rimanere nell'area piccola\nI proprietari sono responsabili del comportamento dei propri animali\nRispettare la divisione piccola/grande taglia\nVietato lasciare i cani incustoditi",
  "accessibilita": "Completamente accessibile. Ingressi ampi, terreno pianeggiante con ghiaia, panchine multiple. Accesso facilitato per persone con disabilità"
}
```

---

## Suggerimenti per Città Italiane

**Nord:**
- Milano: Parco Sempione, Parco Nord, Giardini Montanelli, CityLife
- Torino: Parco Valentino, Giardini Reali, Pellerina
- Bologna: Giardini Margherita, Parco Don Bosco
- Verona, Padova, Genova, Venezia-Mestre

**Centro:**
- Roma: Villa Borghese, Villa Ada, Villa Pamphili, Colle Oppio
- Firenze: Cascine, Giardino dell'Orticoltura
- Perugia, Ancona, Pescara

**Sud e Isole:**
- Napoli: Parco Virgiliano, Villa Comunale
- Bari: Parco 2 Giugno
- Palermo: Parco della Favorita
- Catania: Villa Bellini
- Cagliari: Parco Monte Claro

---

## Risoluzione Problemi

**Errore "JSON non valido":**
- Verifica su jsonlint.com
- Controlla virgole, parentesi, apici
- Assicurati che `superficie` sia numero, non stringa

**Errore "Provincia non trovata":**
- Verifica sigle provincia (2 lettere MAIUSCOLE)
- Le province devono esistere nella taxonomy WordPress

**Superfici non realistiche:**
- Piccole aree: 150-300 mq
- Medie: 300-700 mq
- Grandi: 700-2000 mq

---

## Note Importanti

- **Foto:** Le immagini devono essere aggiunte manualmente dopo l'importazione
- **Status:** Tutte le aree importate sono BOZZE da revisionare
- **Duplicati:** L'importer verifica gli slug e aggiorna se esistenti
- **Provincia:** Assegnata automaticamente se la taxonomy esiste
- **Validazione:** I dati vengono sanitizzati durante l'importazione

---

## Supporto

Per problemi con l'importazione, verifica:
1. Log errori PHP di WordPress
2. Formato JSON (deve essere array di oggetti)
3. Campi obbligatori: titolo e slug presenti
4. Dimensione file upload consentita in WordPress

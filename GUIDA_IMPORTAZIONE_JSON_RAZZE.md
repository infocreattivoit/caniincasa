# 📖 Guida Importazione Razze da JSON

## 🎯 Scopo

Importare nuove razze di cani da file JSON con tutti i campi ACF e tassonomie.
**Le razze vengono sempre importate in BOZZA** per permettere revisione prima della pubblicazione.

---

## 🚀 Accesso Rapido

1. **Login in WordPress Admin**
2. **Vai in**: `Razze di Cani → Importa JSON`
3. **Seleziona** il file JSON (es. `dog_breeds (1).json`)
4. **Click** su "Importa Razze"

---

## 📋 Formato JSON Richiesto

Il file JSON deve contenere un **array di oggetti razza** con i seguenti campi:

```json
[
  {
    "titolo": "Nome Completo Razza",
    "slug": "nome-razza-slug-wordpress",

    // TASSONOMIE
    "taglia": "Toy|Piccola|Media|Grande|Gigante",
    "gruppo_fci": 1-10 (0 se non riconosciuta FCI),

    // INFO BASE
    "nazione_origine": "Paese di origine",
    "colorazioni": "nero, fulvo, bianco, grigio...",
    "temperamento_breve": "Max 100 caratteri descrizione",
    "peso_medio_min": 10,
    "peso_medio_max": 25,
    "aspettativa_vita_min": 10,
    "aspettativa_vita_max": 14,
    "altezza_min": 40,
    "altezza_max": 55,

    // CARATTERISTICHE (valori da 1.0 a 5.0)
    "affettuosita": 3.5,
    "socievolezza_cani": 4.0,
    "tolleranza_estranei": 3.0,
    "compatibilita_con_i_bambini": 4.5,
    "compatibilita_con_altri_animali_domestici": 3.0,
    "vocalita_e_predisposizione_ad_abbaiare": 3.0,
    "adattabilita_appartamento": 2.5,
    "adattabilita_clima_caldo": 3.5,
    "adattabilita_clima_freddo": 4.0,
    "tolleranza_alla_solitudine": 2.5,
    "intelligenza": 4.5,
    "facilita_di_addestramento": 4.0,
    "livello_esperienza_richiesto": 3.0,
    "energia_e_livelli_di_attivita": 4.0,
    "esigenze_di_esercizio": 4.5,
    "istinti_di_caccia": 2.5,
    "facilita_toelettatura": 3.5,
    "cura_e_perdita_pelo": 3.0,
    "predisposizioni_per_la_salute": 2.5,
    "costo_mantenimento": 3.0,

    // CONTENUTI TESTUALI (HTML con <p>, <ul>, <li>, <strong>)
    "descrizione_generale": "<p>Descrizione introduttiva...</p>",
    "origini_storia": "<p>Storia della razza...</p>",
    "aspetto_fisico": "<p>Descrizione aspetto...</p>",
    "carattere_temperamento": "<p>Carattere e comportamento...</p>",
    "salute_cura": "<p>Salute e cure necessarie...</p>",
    "attivita_addestramento": "<p>Esigenze di attività...</p>",
    "ideale_per": "<p>Profilo proprietario ideale...</p>"
  }
]
```

---

## ✅ Cosa Fa l'Importatore

### 1. Validazione
- Verifica formato JSON valido
- Controlla campi obbligatori (titolo)
- Valida range dei valori numerici

### 2. Creazione/Aggiornamento Post
- Crea nuovo post tipo `razze_di_cani` con **status = bozza**
- Se la razza esiste già (stesso slug), la **aggiorna**
- Imposta titolo, slug e descrizione

### 3. Assegnazione Tassonomie
- **Taglia**: Toy, Piccola, Media, Grande, Gigante
- **Gruppo FCI**: Gruppo 1-10 (crea automaticamente se non esiste)

### 4. Popolamento Campi ACF

#### Campi Base (da JSON)
Tutti i campi presenti nel JSON vengono importati direttamente.

#### Campi Calcolatori (automatici)
L'importatore calcola automaticamente i seguenti campi per i calcolatori:

**Calcolatore Età:**
- `taglia_standard`: Calcolata dal peso medio
- `coefficiente_cucciolo`: Basato su taglia (13-15)
- `coefficiente_adulto`: Basato su taglia (4-7)
- `coefficiente_senior`: Basato su taglia (4.5-9)

**Calcolatore Peso:**
- `peso_ideale_min_maschio`: = peso_medio_min
- `peso_ideale_max_maschio`: = peso_medio_max
- `peso_ideale_min_femmina`: = peso_medio_min × 0.9
- `peso_ideale_max_femmina`: = peso_medio_max × 0.9
- `livello_attivita`: Calcolato da energia_e_livelli_di_attivita

**Calcolatore Costi:**
- `costo_alimentazione_mensile`: Basato su taglia (30-150€)
- `costo_veterinario_annuale`: Basato su taglia + predisposizioni
- `costo_toelettatura_annuale`: Basato su taglia + facilità toelettatura
- `predisposizioni_salute`: Convertito da 1-5 a bassa/media/alta

---

## 📊 Output dell'Importazione

Dopo l'importazione vedrai:

```
=== INIZIO IMPORTAZIONE ===
Razze da importare: 36

#1: IMPORTATA - Cane da Pastore Australiano (Australian Kelpie) (ID: 14523)
#2: IMPORTATA - Cane da Pastore dei Pirenei a Pelo Lungo (ID: 14524)
#3: AGGIORNATA - Cane da Pastore Tedesco (ID: 12345)
...

=== FINE IMPORTAZIONE ===

Razze importate: 33
Razze aggiornate: 3
Errori: 0
```

---

## 🔄 Workflow Consigliato

### 1. Importa JSON
- Usa l'importer da admin: `Razze di Cani → Importa JSON`
- Le razze vengono create in **bozza**

### 2. Revisiona le Razze
- Vai in `Razze di Cani → Tutti`
- Filtra per "Bozze"
- Controlla ogni razza:
  - ✅ Testo corretto
  - ✅ Immagini aggiunte (manualmente)
  - ✅ Tassonomie corrette
  - ✅ Campi ACF popolati

### 3. Aggiungi Immagini
Le immagini **non vengono importate** dal JSON.
Devi aggiungerle manualmente per ogni razza:
- **Immagine in evidenza**: Foto principale della razza
- **Galleria**: Foto aggiuntive (opzionale)

### 4. Pubblica
- Click su "Modifica rapida" o apri la razza
- Cambia status da "Bozza" a "Pubblicato"
- Click "Aggiorna"

---

## ⚠️ Comportamento su Razze Esistenti

**Se la razza esiste già (stesso slug):**
- Viene **aggiornata** (non duplicata)
- Status rimane invariato (se era pubblicata, resta pubblicata)
- Campi ACF vengono sovrascritti
- Tassonomie vengono aggiornate

**Consiglio:** Se vuoi forzare reimport completo, elimina prima le razze esistenti.

---

## 🐛 Troubleshooting

### ❌ Errore: "Errore nel parsing JSON"

**Causa:** File JSON non valido

**Soluzione:**
- Valida il JSON su [jsonlint.com](https://jsonlint.com/)
- Verifica che sia un array `[...]` non un oggetto `{...}`
- Controlla virgole e parentesi

### ❌ Errore: "Titolo mancante"

**Causa:** Una razza nel JSON non ha il campo `titolo`

**Soluzione:**
- Controlla il JSON
- Aggiungi `"titolo": "Nome Razza"` per ogni razza

### ⚠️ Avviso: "Razze aggiornate: X"

**Non è un errore!** Significa che X razze esistevano già e sono state aggiornate.

### ❓ Le razze non si vedono nel frontend

**Causa:** Sono in bozza

**Soluzione:**
- Vai in `Razze di Cani → Tutti`
- Filtra per "Bozze"
- Pubblica manualmente dopo revisione

---

## 📁 File di Esempio

Il file `dog_breeds (1).json` nella root del progetto contiene:
- **36 razze** pronte all'import
- Formato corretto con tutti i campi richiesti
- Esempi di contenuti HTML ben formattati

---

## 🔐 Permessi Richiesti

Per usare l'importatore devi avere:
- Ruolo: **Amministratore**
- Capability: `manage_options`

Gli editor e ruoli inferiori **non possono** accedere all'importatore.

---

## 💡 Tips & Best Practices

### ✅ DO
- Controlla sempre il JSON con un validator prima di importare
- Revisiona le bozze prima di pubblicare
- Aggiungi immagini di qualità per ogni razza
- Usa valori realistici per caratteristiche (1-5)
- Mantieni i contenuti HTML puliti e ben formattati

### ❌ DON'T
- Non importare JSON non validati
- Non pubblicare razze senza revisione
- Non usare valori fuori range (es. affettuosita: 10)
- Non mescolare HTML e testo plain nei contenuti
- Non dimenticare di aggiungere le immagini

---

## 🎯 Checklist Post-Importazione

Dopo ogni importazione:

- [ ] Verifica il log per eventuali errori
- [ ] Conta le razze importate/aggiornate
- [ ] Filtra le bozze in admin
- [ ] Revisiona almeno 3-5 razze a campione
- [ ] Aggiungi immagini alle nuove razze
- [ ] Pubblica le razze verificate
- [ ] Testa la visualizzazione frontend
- [ ] Verifica che i calcolatori funzionino

---

## 📞 Supporto

Per problemi con l'importatore:

1. Controlla il log dettagliato nella pagina import
2. Verifica i log PHP di WordPress (`wp-content/debug.log`)
3. Valida il JSON su jsonlint.com
4. Controlla che ACF Pro sia attivo

---

## 🆕 Changelog

**v1.0.0 - 2025-11-22**
- ✨ Prima release importatore JSON
- ✅ Import completo campi ACF
- ✅ Calcolo automatico campi calcolatori
- ✅ Gestione tassonomie automatica
- ✅ Status bozza di default
- ✅ Update razze esistenti

---

**Buon import! 🐕**

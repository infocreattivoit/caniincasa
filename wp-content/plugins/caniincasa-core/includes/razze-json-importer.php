<?php
/**
 * Importatore JSON per Razze di Cani
 *
 * Importa razze da file JSON con tutti i campi ACF e tassonomie.
 * Le razze vengono importate con status "bozza" per revisione.
 *
 * @package Caniincasa_Core
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Aggiunge la pagina di amministrazione per l'import JSON razze
 */
function caniincasa_add_razze_json_import_page() {
    add_submenu_page(
        'edit.php?post_type=razze_di_cani',
        'Importa JSON Razze',
        'Importa JSON',
        'manage_options',
        'razze-json-import',
        'caniincasa_razze_json_import_page'
    );
}
add_action( 'admin_menu', 'caniincasa_add_razze_json_import_page' );

/**
 * Rendering della pagina di import JSON
 */
function caniincasa_razze_json_import_page() {
    ?>
    <div class="wrap">
        <h1>Importa Razze da JSON</h1>

        <div class="notice notice-info">
            <p><strong>Istruzioni:</strong></p>
            <ul>
                <li>Seleziona un file JSON con l'array delle razze</li>
                <li>Le razze verranno importate con status <strong>BOZZA</strong></li>
                <li>Potrai revisionare e pubblicare manualmente ogni razza</li>
                <li>Se una razza esiste già (stesso slug), verrà aggiornata</li>
            </ul>
        </div>

        <?php
        // Gestione upload e import
        if ( isset( $_POST['caniincasa_import_json_nonce'] ) &&
             wp_verify_nonce( $_POST['caniincasa_import_json_nonce'], 'caniincasa_import_json' ) ) {

            if ( isset( $_FILES['json_file'] ) && $_FILES['json_file']['error'] === UPLOAD_ERR_OK ) {
                $result = caniincasa_process_json_import( $_FILES['json_file'] );

                if ( $result['success'] ) {
                    echo '<div class="notice notice-success"><p><strong>Importazione completata!</strong></p>';
                    echo '<ul>';
                    echo '<li>Razze importate: ' . $result['imported'] . '</li>';
                    echo '<li>Razze aggiornate: ' . $result['updated'] . '</li>';
                    echo '<li>Errori: ' . $result['errors'] . '</li>';
                    echo '</ul></div>';

                    if ( ! empty( $result['log'] ) ) {
                        echo '<div class="notice notice-info"><p><strong>Log dettagliato:</strong></p>';
                        echo '<pre style="background: #f0f0f0; padding: 10px; overflow-x: auto; max-height: 400px;">';
                        echo esc_html( implode( "\n", $result['log'] ) );
                        echo '</pre></div>';
                    }
                } else {
                    echo '<div class="notice notice-error"><p><strong>Errore:</strong> ' . esc_html( $result['message'] ) . '</p></div>';
                }
            } else {
                echo '<div class="notice notice-error"><p><strong>Errore:</strong> Nessun file caricato o errore di upload.</p></div>';
            }
        }
        ?>

        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field( 'caniincasa_import_json', 'caniincasa_import_json_nonce' ); ?>

            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="json_file">File JSON</label>
                    </th>
                    <td>
                        <input type="file" name="json_file" id="json_file" accept=".json" required>
                        <p class="description">Seleziona il file JSON con le razze (es. dog_breeds.json)</p>
                    </td>
                </tr>
            </table>

            <?php submit_button( 'Importa Razze', 'primary', 'submit', true ); ?>
        </form>

        <hr>

        <h2>Formato JSON Richiesto</h2>
        <p>Il file JSON deve contenere un array di oggetti razza con i seguenti campi:</p>
        <pre style="background: #f9f9f9; padding: 15px; border: 1px solid #ddd; overflow-x: auto;">
[
  {
    "titolo": "Nome Razza",
    "slug": "nome-razza",
    "taglia": "Piccola|Media|Grande|Gigante|Toy",
    "gruppo_fci": 1-10 (0 se non riconosciuta),
    "nazione_origine": "Paese",
    "colorazioni": "nero, fulvo, bianco...",
    "temperamento_breve": "Max 100 caratteri",
    "peso_medio_min": numero_kg,
    "peso_medio_max": numero_kg,
    "aspettativa_vita_min": numero_anni,
    "aspettativa_vita_max": numero_anni,
    "altezza_min": numero_cm,
    "altezza_max": numero_cm,

    // Caratteristiche (valori 1.0 - 5.0)
    "affettuosita": 3.5,
    "socievolezza_cani": 4.0,
    // ... (tutti i campi caratteristiche)

    // Contenuti testuali (HTML)
    "descrizione_generale": "&lt;p&gt;...&lt;/p&gt;",
    "origini_storia": "&lt;p&gt;...&lt;/p&gt;",
    "aspetto_fisico": "&lt;p&gt;...&lt;/p&gt;",
    "carattere_temperamento": "&lt;p&gt;...&lt;/p&gt;",
    "salute_cura": "&lt;p&gt;...&lt;/p&gt;",
    "attivita_addestramento": "&lt;p&gt;...&lt;/p&gt;",
    "ideale_per": "&lt;p&gt;...&lt;/p&gt;"
  }
]
        </pre>
    </div>
    <?php
}

/**
 * Processa l'import del file JSON
 *
 * @param array $file File uploadato da $_FILES
 * @return array Risultato dell'import
 */
function caniincasa_process_json_import( $file ) {
    $result = array(
        'success' => false,
        'imported' => 0,
        'updated' => 0,
        'errors' => 0,
        'log' => array(),
        'message' => ''
    );

    // Leggi il contenuto del file
    $json_content = file_get_contents( $file['tmp_name'] );

    if ( empty( $json_content ) ) {
        $result['message'] = 'File JSON vuoto';
        return $result;
    }

    // Decodifica JSON
    $razze = json_decode( $json_content, true );

    if ( json_last_error() !== JSON_ERROR_NONE ) {
        $result['message'] = 'Errore nel parsing JSON: ' . json_last_error_msg();
        return $result;
    }

    if ( ! is_array( $razze ) ) {
        $result['message'] = 'Il JSON deve contenere un array di razze';
        return $result;
    }

    $result['log'][] = '=== INIZIO IMPORTAZIONE ===';
    $result['log'][] = 'Razze da importare: ' . count( $razze );
    $result['log'][] = '';

    // Importa ogni razza
    foreach ( $razze as $index => $razza_data ) {
        $razza_result = caniincasa_import_single_razza( $razza_data, $index + 1 );

        $result['log'][] = $razza_result['message'];

        if ( $razza_result['success'] ) {
            if ( $razza_result['updated'] ) {
                $result['updated']++;
            } else {
                $result['imported']++;
            }
        } else {
            $result['errors']++;
        }
    }

    $result['log'][] = '';
    $result['log'][] = '=== FINE IMPORTAZIONE ===';
    $result['success'] = true;

    return $result;
}

/**
 * Importa una singola razza
 *
 * @param array $data Dati della razza
 * @param int $index Indice della razza (per log)
 * @return array Risultato dell'import
 */
function caniincasa_import_single_razza( $data, $index ) {
    $result = array(
        'success' => false,
        'updated' => false,
        'message' => ''
    );

    // Validazione campi obbligatori
    if ( empty( $data['titolo'] ) ) {
        $result['message'] = "#{$index}: ERRORE - Titolo mancante";
        return $result;
    }

    $titolo = sanitize_text_field( $data['titolo'] );
    $slug = ! empty( $data['slug'] ) ? sanitize_title( $data['slug'] ) : sanitize_title( $titolo );

    // Verifica se la razza esiste già
    $existing_post = get_page_by_path( $slug, OBJECT, 'razze_di_cani' );

    $post_data = array(
        'post_title'   => $titolo,
        'post_name'    => $slug,
        'post_type'    => 'razze_di_cani',
        'post_status'  => 'draft', // SEMPRE BOZZA
        'post_content' => ! empty( $data['descrizione_generale'] ) ? wp_kses_post( $data['descrizione_generale'] ) : '',
    );

    if ( $existing_post ) {
        // Aggiorna razza esistente
        $post_data['ID'] = $existing_post->ID;
        $post_id = wp_update_post( $post_data );
        $result['updated'] = true;
        $action = 'AGGIORNATA';
    } else {
        // Crea nuova razza
        $post_id = wp_insert_post( $post_data );
        $action = 'IMPORTATA';
    }

    if ( is_wp_error( $post_id ) || ! $post_id ) {
        $result['message'] = "#{$index}: ERRORE - {$titolo} - Impossibile creare/aggiornare il post";
        return $result;
    }

    // Assegna tassonomie
    caniincasa_assign_razze_taxonomies( $post_id, $data );

    // Popola campi ACF
    caniincasa_populate_razze_acf_fields( $post_id, $data );

    $result['success'] = true;
    $result['message'] = "#{$index}: {$action} - {$titolo} (ID: {$post_id})";

    return $result;
}

/**
 * Assegna le tassonomie alla razza
 *
 * @param int $post_id ID del post
 * @param array $data Dati della razza
 */
function caniincasa_assign_razze_taxonomies( $post_id, $data ) {
    // Taglia
    if ( ! empty( $data['taglia'] ) ) {
        $taglia_slug = sanitize_title( $data['taglia'] );
        $taglia_term = term_exists( $taglia_slug, 'razza_taglia' );

        if ( ! $taglia_term ) {
            // Crea il termine se non esiste
            $taglia_term = wp_insert_term( $data['taglia'], 'razza_taglia', array(
                'slug' => $taglia_slug
            ) );
        }

        if ( ! is_wp_error( $taglia_term ) ) {
            wp_set_object_terms( $post_id, (int) $taglia_term['term_id'], 'razza_taglia', false );
        }
    }

    // Gruppo FCI
    if ( isset( $data['gruppo_fci'] ) ) {
        $gruppo_fci = (int) $data['gruppo_fci'];

        if ( $gruppo_fci >= 1 && $gruppo_fci <= 10 ) {
            $gruppo_slug = 'gruppo-' . $gruppo_fci;
            $gruppo_term = term_exists( $gruppo_slug, 'razza_gruppo' );

            if ( ! $gruppo_term ) {
                // Crea il termine se non esiste
                $gruppo_name = 'Gruppo ' . $gruppo_fci;
                $gruppo_term = wp_insert_term( $gruppo_name, 'razza_gruppo', array(
                    'slug' => $gruppo_slug
                ) );
            }

            if ( ! is_wp_error( $gruppo_term ) ) {
                wp_set_object_terms( $post_id, (int) $gruppo_term['term_id'], 'razza_gruppo', false );
            }
        }
    }
}

/**
 * Popola tutti i campi ACF della razza
 *
 * @param int $post_id ID del post
 * @param array $data Dati della razza
 */
function caniincasa_populate_razze_acf_fields( $post_id, $data ) {
    // Verifica che ACF sia attivo
    if ( ! function_exists( 'update_field' ) ) {
        return;
    }

    // Mappa campi JSON -> ACF (nome campo identico)
    $simple_fields = array(
        // Info base
        'nazione_origine',
        'colorazioni',
        'temperamento_breve',
        'peso_medio_min',
        'peso_medio_max',
        'aspettativa_vita_min',
        'aspettativa_vita_max',
        'altezza_min',
        'altezza_max',

        // Caratteristiche (valori 1-5)
        'affettuosita',
        'socievolezza_cani',
        'tolleranza_estranei',
        'compatibilita_con_i_bambini',
        'compatibilita_con_altri_animali_domestici',
        'vocalita_e_predisposizione_ad_abbaiare',
        'adattabilita_appartamento',
        'adattabilita_clima_caldo',
        'adattabilita_clima_freddo',
        'tolleranza_alla_solitudine',
        'intelligenza',
        'facilita_di_addestramento',
        'livello_esperienza_richiesto',
        'energia_e_livelli_di_attivita',
        'esigenze_di_esercizio',
        'istinti_di_caccia',
        'facilita_toelettatura',
        'cura_e_perdita_pelo',
        'predisposizioni_per_la_salute',
        'costo_mantenimento',

        // Contenuti testuali
        'descrizione_generale',
        'origini_storia',
        'aspetto_fisico',
        'carattere_temperamento',
        'salute_cura',
        'attivita_addestramento',
        'ideale_per',
    );

    // Aggiorna campi semplici
    foreach ( $simple_fields as $field_name ) {
        if ( isset( $data[ $field_name ] ) ) {
            update_field( $field_name, $data[ $field_name ], $post_id );
        }
    }

    // Campi calcolatori (con valori di default intelligenti)
    caniincasa_populate_calculator_fields( $post_id, $data );
}

/**
 * Popola i campi per i calcolatori con valori di default intelligenti
 *
 * @param int $post_id ID del post
 * @param array $data Dati della razza
 */
function caniincasa_populate_calculator_fields( $post_id, $data ) {
    if ( ! function_exists( 'update_field' ) ) {
        return;
    }

    // Taglia standard (basata su peso medio)
    $peso_medio = ( ( $data['peso_medio_min'] ?? 0 ) + ( $data['peso_medio_max'] ?? 0 ) ) / 2;

    if ( $peso_medio < 5 ) {
        $taglia_std = 'toy';
    } elseif ( $peso_medio < 10 ) {
        $taglia_std = 'piccola';
    } elseif ( $peso_medio < 25 ) {
        $taglia_std = 'media';
    } elseif ( $peso_medio < 45 ) {
        $taglia_std = 'grande';
    } else {
        $taglia_std = 'gigante';
    }

    update_field( 'taglia_standard', $taglia_std, $post_id );

    // Coefficienti età (basati su taglia)
    $coefficienti = array(
        'toy'     => array( 'cucciolo' => 15, 'adulto' => 4,   'senior' => 4.5 ),
        'piccola' => array( 'cucciolo' => 15, 'adulto' => 4.5, 'senior' => 5 ),
        'media'   => array( 'cucciolo' => 15, 'adulto' => 5,   'senior' => 5.5 ),
        'grande'  => array( 'cucciolo' => 14, 'adulto' => 6,   'senior' => 7 ),
        'gigante' => array( 'cucciolo' => 13, 'adulto' => 7,   'senior' => 9 ),
    );

    $coeff = $coefficienti[ $taglia_std ] ?? $coefficienti['media'];

    update_field( 'coefficiente_cucciolo', $coeff['cucciolo'], $post_id );
    update_field( 'coefficiente_adulto', $coeff['adulto'], $post_id );
    update_field( 'coefficiente_senior', $coeff['senior'], $post_id );

    // Peso ideale (uguale al peso medio per maschi, -10% per femmine)
    $peso_min = $data['peso_medio_min'] ?? 0;
    $peso_max = $data['peso_medio_max'] ?? 0;

    update_field( 'peso_ideale_min_maschio', $peso_min, $post_id );
    update_field( 'peso_ideale_max_maschio', $peso_max, $post_id );
    update_field( 'peso_ideale_min_femmina', round( $peso_min * 0.9, 1 ), $post_id );
    update_field( 'peso_ideale_max_femmina', round( $peso_max * 0.9, 1 ), $post_id );

    // Livello attività (basato su energia)
    $energia = $data['energia_e_livelli_di_attivita'] ?? 3;

    if ( $energia < 2 ) {
        $livello_attivita = 'basso';
    } elseif ( $energia < 3.5 ) {
        $livello_attivita = 'moderato';
    } elseif ( $energia < 4.5 ) {
        $livello_attivita = 'alto';
    } else {
        $livello_attivita = 'molto_alto';
    }

    update_field( 'livello_attivita', $livello_attivita, $post_id );

    // Costi mantenimento (basati su taglia e caratteristiche)
    $costi_alimentazione = array(
        'toy'     => 30,
        'piccola' => 40,
        'media'   => 60,
        'grande'  => 100,
        'gigante' => 150,
    );

    update_field( 'costo_alimentazione_mensile', $costi_alimentazione[ $taglia_std ] ?? 60, $post_id );

    // Costi veterinari (maggiori se predisposizione alta)
    $predisposizione = $data['predisposizioni_per_la_salute'] ?? 2.5;
    $costo_vet_base = array(
        'toy'     => 200,
        'piccola' => 250,
        'media'   => 300,
        'grande'  => 400,
        'gigante' => 500,
    );

    $costo_vet = $costo_vet_base[ $taglia_std ] ?? 300;
    $costo_vet = round( $costo_vet * ( $predisposizione / 2.5 ) );

    update_field( 'costo_veterinario_annuale', $costo_vet, $post_id );

    // Costi toelettatura (basati su facilità toelettatura)
    $facilita_toelettatura = $data['facilita_toelettatura'] ?? 3;

    // Invertiamo: facilità 1 (difficile) = costo alto, facilità 5 (facile) = costo basso
    $costo_toelettatura_base = array(
        'toy'     => 300,
        'piccola' => 350,
        'media'   => 400,
        'grande'  => 500,
        'gigante' => 600,
    );

    $costo_toelettatura = $costo_toelettatura_base[ $taglia_std ] ?? 400;
    $costo_toelettatura = round( $costo_toelettatura * ( 6 - $facilita_toelettatura ) / 3 );

    update_field( 'costo_toelettatura_annuale', $costo_toelettatura, $post_id );

    // Predisposizione salute (converti da 1-5 a bassa/media/alta)
    if ( $predisposizione < 2.5 ) {
        $pred_salute = 'bassa';
    } elseif ( $predisposizione < 3.5 ) {
        $pred_salute = 'media';
    } else {
        $pred_salute = 'alta';
    }

    update_field( 'predisposizioni_salute', $pred_salute, $post_id );
}

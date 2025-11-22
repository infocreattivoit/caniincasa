<?php
/**
 * Importatore JSON per Aree Cani
 *
 * Importa aree cani da file JSON con tutti i campi ACF e tassonomia provincia.
 * Le aree cani vengono importate con status "bozza" per revisione.
 *
 * @package Caniincasa_Core
 * @since 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Aggiunge la pagina di amministrazione per l'import JSON aree cani
 */
function caniincasa_add_aree_cani_json_import_page() {
    add_submenu_page(
        'caniincasa-strutture',
        'Importa JSON Aree Cani',
        'Importa Aree Cani JSON',
        'manage_options',
        'aree-cani-json-import',
        'caniincasa_aree_cani_json_import_page'
    );
}
add_action( 'admin_menu', 'caniincasa_add_aree_cani_json_import_page' );

/**
 * Rendering della pagina di import JSON
 */
function caniincasa_aree_cani_json_import_page() {
    ?>
    <div class="wrap">
        <h1>Importa Aree Cani da JSON</h1>

        <div class="notice notice-info">
            <p><strong>Istruzioni:</strong></p>
            <ul>
                <li>Seleziona un file JSON con l'array delle aree cani</li>
                <li>Le aree cani verranno importate con status <strong>BOZZA</strong></li>
                <li>Potrai revisionare e pubblicare manualmente ogni area</li>
                <li>Se un'area esiste già (stesso slug), verrà aggiornata</li>
                <li>Le immagini dovranno essere aggiunte manualmente dopo l'importazione</li>
            </ul>
        </div>

        <?php
        // Gestione upload e import
        if ( isset( $_POST['caniincasa_import_aree_cani_nonce'] ) &&
             wp_verify_nonce( $_POST['caniincasa_import_aree_cani_nonce'], 'caniincasa_import_aree_cani' ) ) {

            if ( isset( $_FILES['json_file'] ) && $_FILES['json_file']['error'] === UPLOAD_ERR_OK ) {
                $result = caniincasa_process_aree_cani_json_import( $_FILES['json_file'] );

                if ( $result['success'] ) {
                    echo '<div class="notice notice-success"><p><strong>Importazione completata!</strong></p>';
                    echo '<ul>';
                    echo '<li>Aree cani importate: ' . $result['imported'] . '</li>';
                    echo '<li>Aree cani aggiornate: ' . $result['updated'] . '</li>';
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
            <?php wp_nonce_field( 'caniincasa_import_aree_cani', 'caniincasa_import_aree_cani_nonce' ); ?>

            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="json_file">File JSON</label>
                    </th>
                    <td>
                        <input type="file" name="json_file" id="json_file" accept=".json" required>
                        <p class="description">Seleziona il file JSON con le aree cani (es. aree_cani.json)</p>
                    </td>
                </tr>
            </table>

            <?php submit_button( 'Importa Aree Cani', 'primary', 'submit', true ); ?>
        </form>

        <hr>

        <h2>Formato JSON Richiesto</h2>

        <p>Il file JSON deve contenere un array di oggetti area cani con i seguenti campi:</p>

        <pre style="background: #f9f9f9; padding: 15px; border: 1px solid #ddd; overflow-x: auto;">
[
  {
    "titolo": "Nome Area Cani",
    "slug": "nome-area-cani",
    "descrizione": "&lt;p&gt;Descrizione HTML dell'area...&lt;/p&gt;",
    "indirizzo": "Via Roma 123",
    "localita": "Milano",
    "provincia": "MI",
    "cap": "20100",
    "tipo_area": ["Recintata", "Per cani di taglia grande"],
    "superficie": 500,
    "servizi_disponibili": [
      "Fontanella acqua",
      "Sacchetti igienici",
      "Panchine"
    ],
    "orari_accesso": "Libero accesso 24h",
    "regolamento": "Regole di utilizzo...",
    "accessibilita": "Accessibile a persone con disabilità"
  }
]
        </pre>

        <h3>Campi Disponibili</h3>
        <table class="widefat">
            <thead>
                <tr>
                    <th>Campo</th>
                    <th>Tipo</th>
                    <th>Obbligatorio</th>
                    <th>Descrizione</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>titolo</code></td>
                    <td>string</td>
                    <td>✅ Sì</td>
                    <td>Nome dell'area cani</td>
                </tr>
                <tr>
                    <td><code>slug</code></td>
                    <td>string</td>
                    <td>✅ Sì</td>
                    <td>URL-friendly slug (univoco)</td>
                </tr>
                <tr>
                    <td><code>descrizione</code></td>
                    <td>string (HTML)</td>
                    <td>No</td>
                    <td>Descrizione dettagliata</td>
                </tr>
                <tr>
                    <td><code>indirizzo</code></td>
                    <td>string</td>
                    <td>No</td>
                    <td>Via e numero civico</td>
                </tr>
                <tr>
                    <td><code>localita</code></td>
                    <td>string</td>
                    <td>No</td>
                    <td>Città</td>
                </tr>
                <tr>
                    <td><code>provincia</code></td>
                    <td>string</td>
                    <td>No</td>
                    <td>Sigla provincia (2 lettere, es. MI)</td>
                </tr>
                <tr>
                    <td><code>cap</code></td>
                    <td>string</td>
                    <td>No</td>
                    <td>Codice postale</td>
                </tr>
                <tr>
                    <td><code>tipo_area</code></td>
                    <td>array</td>
                    <td>No</td>
                    <td>Array di stringhe (es: Recintata, Libera)</td>
                </tr>
                <tr>
                    <td><code>superficie</code></td>
                    <td>number</td>
                    <td>No</td>
                    <td>Superficie in metri quadri</td>
                </tr>
                <tr>
                    <td><code>servizi_disponibili</code></td>
                    <td>array</td>
                    <td>No</td>
                    <td>Array di stringhe con servizi</td>
                </tr>
                <tr>
                    <td><code>orari_accesso</code></td>
                    <td>string</td>
                    <td>No</td>
                    <td>Orari di accesso (multi-riga)</td>
                </tr>
                <tr>
                    <td><code>regolamento</code></td>
                    <td>string</td>
                    <td>No</td>
                    <td>Regolamento dell'area (multi-riga)</td>
                </tr>
                <tr>
                    <td><code>accessibilita</code></td>
                    <td>string</td>
                    <td>No</td>
                    <td>Info accessibilità (multi-riga)</td>
                </tr>
            </tbody>
        </table>

        <div class="notice notice-warning" style="margin-top: 20px;">
            <p><strong>Note Importanti:</strong></p>
            <ul>
                <li>Il campo <code>provincia</code> deve corrispondere a una provincia esistente nella taxonomy WordPress</li>
                <li>Le immagini devono essere aggiunte manualmente dall'editor WordPress</li>
                <li>I campi <code>titolo</code> e <code>slug</code> sono obbligatori</li>
                <li>Tutte le aree cani vengono importate come <strong>BOZZE</strong></li>
            </ul>
        </div>
    </div>
    <?php
}

/**
 * Processa l'import del file JSON
 *
 * @param array $file File upload array
 * @return array Risultato dell'importazione
 */
function caniincasa_process_aree_cani_json_import( $file ) {
    $result = array(
        'success'  => false,
        'message'  => '',
        'imported' => 0,
        'updated'  => 0,
        'errors'   => 0,
        'log'      => array(),
    );

    // Leggi il contenuto del file
    $json_content = file_get_contents( $file['tmp_name'] );

    if ( $json_content === false ) {
        $result['message'] = 'Impossibile leggere il file caricato.';
        return $result;
    }

    // Decodifica JSON
    $aree_cani = json_decode( $json_content, true );

    if ( json_last_error() !== JSON_ERROR_NONE ) {
        $result['message'] = 'File JSON non valido: ' . json_last_error_msg();
        return $result;
    }

    if ( ! is_array( $aree_cani ) ) {
        $result['message'] = 'Il JSON deve contenere un array di aree cani.';
        return $result;
    }

    // Processa ogni area cani
    foreach ( $aree_cani as $index => $data ) {
        $area_result = caniincasa_import_single_area_cani( $data, $index );

        if ( $area_result['success'] ) {
            if ( $area_result['updated'] ) {
                $result['updated']++;
            } else {
                $result['imported']++;
            }
            $result['log'][] = $area_result['message'];
        } else {
            $result['errors']++;
            $result['log'][] = '[ERRORE] ' . $area_result['message'];
        }
    }

    $result['success'] = true;
    $result['message'] = sprintf(
        'Importazione completata: %d importate, %d aggiornate, %d errori',
        $result['imported'],
        $result['updated'],
        $result['errors']
    );

    return $result;
}

/**
 * Importa una singola area cani
 *
 * @param array $data Dati dell'area cani
 * @param int $index Indice nell'array
 * @return array Risultato dell'importazione
 */
function caniincasa_import_single_area_cani( $data, $index ) {
    $result = array(
        'success' => false,
        'updated' => false,
        'message' => '',
    );

    // Validazione campi obbligatori
    if ( empty( $data['titolo'] ) ) {
        $result['message'] = sprintf( 'Indice %d: Campo "titolo" mancante', $index );
        return $result;
    }

    $titolo = sanitize_text_field( $data['titolo'] );
    $slug = ! empty( $data['slug'] ) ? sanitize_title( $data['slug'] ) : sanitize_title( $titolo );

    // Verifica se l'area esiste già
    $existing_post = get_page_by_path( $slug, OBJECT, 'aree_cani' );

    // Prepara dati post
    $post_data = array(
        'post_title'   => $titolo,
        'post_name'    => $slug,
        'post_type'    => 'aree_cani',
        'post_status'  => 'draft', // SEMPRE BOZZA
        'post_content' => ! empty( $data['descrizione'] ) ? wp_kses_post( $data['descrizione'] ) : '',
    );

    if ( $existing_post ) {
        // Aggiorna post esistente
        $post_data['ID'] = $existing_post->ID;
        $post_id = wp_update_post( $post_data );
        $result['updated'] = true;
    } else {
        // Crea nuovo post
        $post_id = wp_insert_post( $post_data );
    }

    if ( is_wp_error( $post_id ) ) {
        $result['message'] = sprintf(
            'Indice %d (%s): Errore creazione post - %s',
            $index,
            $titolo,
            $post_id->get_error_message()
        );
        return $result;
    }

    // Importa campi ACF
    $acf_fields = array(
        'indirizzo'            => 'text',
        'localita'             => 'text',
        'provincia'            => 'text',
        'cap'                  => 'text',
        'tipo_area'            => 'array',
        'superficie'           => 'number',
        'servizi_disponibili'  => 'array',
        'orari_accesso'        => 'textarea',
        'regolamento'          => 'textarea',
        'accessibilita'        => 'textarea',
    );

    foreach ( $acf_fields as $field => $type ) {
        if ( isset( $data[ $field ] ) && $data[ $field ] !== '' ) {
            $value = $data[ $field ];

            // Sanitizza in base al tipo
            switch ( $type ) {
                case 'text':
                    $value = sanitize_text_field( $value );
                    break;
                case 'number':
                    $value = absint( $value );
                    break;
                case 'textarea':
                    $value = sanitize_textarea_field( $value );
                    break;
                case 'array':
                    $value = is_array( $value ) ? array_map( 'sanitize_text_field', $value ) : array();
                    break;
            }

            update_field( $field, $value, $post_id );
        }
    }

    // Assegna tassonomia provincia se presente
    if ( ! empty( $data['provincia'] ) ) {
        $provincia_slug = strtolower( sanitize_text_field( $data['provincia'] ) );

        // Cerca il term della provincia
        $term = get_term_by( 'slug', $provincia_slug, 'provincia' );

        if ( $term ) {
            wp_set_object_terms( $post_id, $term->term_id, 'provincia', false );
        }
    }

    $result['success'] = true;
    $result['message'] = sprintf(
        'Indice %d: %s "%s" (ID: %d) [%s]',
        $index,
        $result['updated'] ? 'Aggiornata' : 'Importata',
        $titolo,
        $post_id,
        $slug
    );

    return $result;
}

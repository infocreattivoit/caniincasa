<?php
/**
 * Single Dog Profile Template
 *
 * @package Caniincasa_My_Dog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get dog post
$dog_post = get_post( $dog_id );

if ( ! $dog_post ) {
	echo '<div class="notice notice-error"><p>' . __( 'Cane non trovato.', 'caniincasa-my-dog' ) . '</p></div>';
	return;
}

// Get ALL ACF fields
$nome                   = get_field( 'nome', $dog_id ) ? get_field( 'nome', $dog_id ) : $dog_post->post_title;
$razza                  = get_field( 'razza', $dog_id );
$sesso                  = get_field( 'sesso', $dog_id );
$data_nascita           = get_field( 'data_nascita', $dog_id );
$peso_attuale           = get_field( 'peso_attuale', $dog_id );
$microchip              = get_field( 'microchip', $dog_id );
$foto                   = get_field( 'foto', $dog_id );

// ACF fields from class-acf-fields.php
$dog_razza              = get_field( 'dog_razza', $dog_id );
$dog_razza_custom       = get_field( 'dog_razza_custom', $dog_id );
$dog_birth_date         = get_field( 'dog_birth_date', $dog_id );
$dog_gender             = get_field( 'dog_gender', $dog_id );
$dog_neutered           = get_field( 'dog_neutered', $dog_id );
$dog_size               = get_field( 'dog_size', $dog_id );
$dog_weight             = get_field( 'dog_weight', $dog_id );
$dog_color              = get_field( 'dog_color', $dog_id );

// Identification
$dog_microchip          = get_field( 'dog_microchip', $dog_id );
$dog_microchip_date     = get_field( 'dog_microchip_date', $dog_id );
$dog_pedigree           = get_field( 'dog_pedigree', $dog_id );
$dog_passport           = get_field( 'dog_passport', $dog_id );

// Health
$dog_veterinarian       = get_field( 'dog_veterinarian', $dog_id );
$dog_veterinarian_phone = get_field( 'dog_veterinarian_phone', $dog_id );
$dog_veterinarian_address = get_field( 'dog_veterinarian_address', $dog_id );
$dog_allergies          = get_field( 'dog_allergies', $dog_id );
$dog_medical_conditions = get_field( 'dog_medical_conditions', $dog_id );
$dog_medications        = get_field( 'dog_medications', $dog_id );
$dog_insurance          = get_field( 'dog_insurance', $dog_id );
$dog_insurance_number   = get_field( 'dog_insurance_number', $dog_id );

// Diet
$dog_food_type          = get_field( 'dog_food_type', $dog_id );
$dog_food_brand         = get_field( 'dog_food_brand', $dog_id );
$dog_food_amount        = get_field( 'dog_food_amount', $dog_id );
$dog_meals_per_day      = get_field( 'dog_meals_per_day', $dog_id );
$dog_diet_notes         = get_field( 'dog_diet_notes', $dog_id );

// Behavior
$dog_temperament        = get_field( 'dog_temperament', $dog_id );
$dog_training_level     = get_field( 'dog_training_level', $dog_id );
$dog_behavior_notes     = get_field( 'dog_behavior_notes', $dog_id );

// Notes
$dog_notes              = get_field( 'dog_notes', $dog_id );
$dog_emergency_contact  = get_field( 'dog_emergency_contact', $dog_id );
$dog_emergency_phone    = get_field( 'dog_emergency_phone', $dog_id );

// Use the newer ACF fields if available, otherwise fall back to old fields
$display_name = $nome;
$display_breed = '';
if ( $dog_razza ) {
	$display_breed = get_the_title( $dog_razza );
} elseif ( $dog_razza_custom ) {
	$display_breed = $dog_razza_custom;
} elseif ( $razza ) {
	$display_breed = $razza;
}

$display_birthdate = $dog_birth_date ? $dog_birth_date : $data_nascita;
$display_gender = $dog_gender ? $dog_gender : $sesso;
$display_weight = $dog_weight ? $dog_weight : $peso_attuale;
$display_microchip = $dog_microchip ? $dog_microchip : $microchip;

// Calculate age if birth date exists
$age_text = '';
if ( $display_birthdate ) {
	$birth_date = new DateTime( $display_birthdate );
	$now = new DateTime();
	$diff = $now->diff( $birth_date );

	if ( $diff->y > 0 ) {
		$age_text = sprintf( _n( '%d anno', '%d anni', $diff->y, 'caniincasa-my-dog' ), $diff->y );
		if ( $diff->m > 0 ) {
			$age_text .= sprintf( _n( ' e %d mese', ' e %d mesi', $diff->m, 'caniincasa-my-dog' ), $diff->m );
		}
	} else {
		$age_text = sprintf( _n( '%d mese', '%d mesi', $diff->m, 'caniincasa-my-dog' ), $diff->m );
	}
}

// Get vaccinations
global $wpdb;
$vaccinations = $wpdb->get_results( $wpdb->prepare(
	"SELECT * FROM {$wpdb->prefix}dog_vaccinations WHERE dog_id = %d ORDER BY vaccine_date DESC",
	$dog_id
) );

// Get weight history
$weight_history = $wpdb->get_results( $wpdb->prepare(
	"SELECT * FROM {$wpdb->prefix}dog_weight_tracker WHERE dog_id = %d ORDER BY measurement_date DESC LIMIT 10",
	$dog_id
) );

// Get notes
$notes = $wpdb->get_results( $wpdb->prepare(
	"SELECT * FROM {$wpdb->prefix}dog_notes WHERE dog_id = %d ORDER BY note_date DESC LIMIT 10",
	$dog_id
) );

?>

<div class="single-dog-profile">
	<!-- Dog Header -->
	<div class="dog-profile-header">
		<div class="dog-profile-photo">
			<?php if ( $foto ) : ?>
				<?php $foto_url = is_array( $foto ) ? $foto['url'] : wp_get_attachment_url( $foto ); ?>
				<img src="<?php echo esc_url( $foto_url ); ?>" alt="<?php echo esc_attr( $display_name ); ?>">
			<?php else : ?>
				<div class="dog-photo-placeholder">
					<svg width="120" height="120" viewBox="0 0 24 24" fill="none">
						<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" fill="#ccc"/>
					</svg>
				</div>
			<?php endif; ?>
		</div>

		<div class="dog-profile-info">
			<h2><?php echo esc_html( $display_name ); ?></h2>

			<?php if ( $display_breed ) : ?>
				<p class="dog-breed"><?php echo esc_html( $display_breed ); ?></p>
			<?php endif; ?>

			<div class="dog-quick-stats">
				<?php if ( $age_text ) : ?>
					<span class="stat">📅 <?php echo esc_html( $age_text ); ?></span>
				<?php endif; ?>
				<?php if ( $display_gender ) : ?>
					<span class="stat">
						<?php echo $display_gender === 'maschio' || $display_gender === 'male' ? '♂️ Maschio' : '♀️ Femmina'; ?>
					</span>
				<?php endif; ?>
				<?php if ( $display_weight ) : ?>
					<span class="stat">⚖️ <?php echo esc_html( $display_weight ); ?> kg</span>
				<?php endif; ?>
			</div>

			<div class="dog-actions">
				<a href="<?php echo esc_url( home_url( '/i-miei-cani/' . $dog_id . '/modifica/' ) ); ?>" class="btn btn-primary">
					✏️ <?php _e( 'Modifica Profilo', 'caniincasa-my-dog' ); ?>
				</a>
				<a href="<?php echo esc_url( add_query_arg( 'action', 'export_dog_pdf', home_url( '/i-miei-cani/' . $dog_id . '/' ) ) ); ?>" class="btn btn-secondary" target="_blank">
					📄 <?php _e( 'Esporta PDF', 'caniincasa-my-dog' ); ?>
				</a>
				<button class="btn btn-danger delete-dog-btn" data-dog-id="<?php echo esc_attr( $dog_id ); ?>">
					🗑️ <?php _e( 'Elimina', 'caniincasa-my-dog' ); ?>
				</button>
			</div>
		</div>
	</div>

	<!-- Tabs Navigation -->
	<div class="dog-profile-tabs">
		<button class="tab-btn active" data-tab="info">ℹ️ Informazioni</button>
		<button class="tab-btn" data-tab="health">🏥 Salute</button>
		<button class="tab-btn" data-tab="diet">🍖 Alimentazione</button>
		<button class="tab-btn" data-tab="vaccinations">💉 Vaccinazioni</button>
		<button class="tab-btn" data-tab="weight">📊 Peso</button>
		<button class="tab-btn" data-tab="diary">📔 Diario</button>
	</div>

	<!-- Tab Content: Info -->
	<div class="tab-content active" id="tab-info">
		<div class="info-grid">
			<?php if ( $display_birthdate ) : ?>
			<div class="info-card">
				<h3>📅 Data di Nascita</h3>
				<p><?php echo esc_html( date_i18n( 'd/m/Y', strtotime( $display_birthdate ) ) ); ?></p>
				<small><?php echo esc_html( $age_text ); ?></small>
			</div>
			<?php endif; ?>

			<?php if ( $dog_size ) : ?>
			<div class="info-card">
				<h3>📏 Taglia</h3>
				<p>
					<?php
					$size_labels = array(
						'toy'    => 'Toy (< 5kg)',
						'small'  => 'Piccola (5-10kg)',
						'medium' => 'Media (10-25kg)',
						'large'  => 'Grande (25-45kg)',
						'giant'  => 'Gigante (> 45kg)',
					);
					echo esc_html( $size_labels[ $dog_size ] ?? $dog_size );
					?>
				</p>
			</div>
			<?php endif; ?>

			<?php if ( $dog_color ) : ?>
			<div class="info-card">
				<h3>🎨 Colore/Mantello</h3>
				<p><?php echo esc_html( $dog_color ); ?></p>
			</div>
			<?php endif; ?>

			<?php if ( $dog_neutered ) : ?>
			<div class="info-card">
				<h3>✂️ Sterilizzato</h3>
				<p>Sì</p>
			</div>
			<?php endif; ?>

			<?php if ( $display_microchip ) : ?>
			<div class="info-card">
				<h3>🔖 Microchip</h3>
				<p><?php echo esc_html( $display_microchip ); ?></p>
				<?php if ( $dog_microchip_date ) : ?>
					<small>Impiantato: <?php echo esc_html( date_i18n( 'd/m/Y', strtotime( $dog_microchip_date ) ) ); ?></small>
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<?php if ( $dog_pedigree ) : ?>
			<div class="info-card">
				<h3>📜 Pedigree</h3>
				<p><?php echo esc_html( $dog_pedigree ); ?></p>
			</div>
			<?php endif; ?>

			<?php if ( $dog_passport ) : ?>
			<div class="info-card">
				<h3>🛂 Passaporto Europeo</h3>
				<p><?php echo esc_html( $dog_passport ); ?></p>
			</div>
			<?php endif; ?>

			<?php if ( $dog_temperament && is_array( $dog_temperament ) ) : ?>
			<div class="info-card full-width">
				<h3>😊 Temperamento</h3>
				<div class="tags">
					<?php
					$temperament_labels = array(
						'friendly'   => 'Socievole',
						'playful'    => 'Giocoso',
						'calm'       => 'Calmo',
						'energetic'  => 'Energico',
						'protective' => 'Protettivo',
						'shy'        => 'Timido',
						'aggressive' => 'Aggressivo',
					);
					foreach ( $dog_temperament as $trait ) :
						?>
						<span class="tag"><?php echo esc_html( $temperament_labels[ $trait ] ?? $trait ); ?></span>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>

			<?php if ( $dog_training_level ) : ?>
			<div class="info-card">
				<h3>🎓 Addestramento</h3>
				<p>
					<?php
					$training_labels = array(
						'none'         => 'Nessun addestramento',
						'basic'        => 'Comandi base',
						'intermediate' => 'Intermedio',
						'advanced'     => 'Avanzato',
					);
					echo esc_html( $training_labels[ $dog_training_level ] ?? $dog_training_level );
					?>
				</p>
			</div>
			<?php endif; ?>
		</div>

		<?php if ( $dog_behavior_notes || $dog_notes ) : ?>
		<div class="notes-section">
			<?php if ( $dog_behavior_notes ) : ?>
			<h3>💭 Note Comportamento</h3>
			<p><?php echo wp_kses_post( nl2br( $dog_behavior_notes ) ); ?></p>
			<?php endif; ?>

			<?php if ( $dog_notes ) : ?>
			<h3>📝 Note Generali</h3>
			<p><?php echo wp_kses_post( nl2br( $dog_notes ) ); ?></p>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</div>

	<!-- Tab Content: Health -->
	<div class="tab-content" id="tab-health">
		<div class="info-grid">
			<?php if ( $dog_veterinarian ) : ?>
			<div class="info-card full-width">
				<h3>👨‍⚕️ Veterinario di Riferimento</h3>
				<p><strong><?php echo esc_html( $dog_veterinarian ); ?></strong></p>
				<?php if ( $dog_veterinarian_phone ) : ?>
					<p>📞 <a href="tel:<?php echo esc_attr( $dog_veterinarian_phone ); ?>"><?php echo esc_html( $dog_veterinarian_phone ); ?></a></p>
				<?php endif; ?>
				<?php if ( $dog_veterinarian_address ) : ?>
					<p>📍 <?php echo wp_kses_post( nl2br( $dog_veterinarian_address ) ); ?></p>
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<?php if ( $dog_allergies ) : ?>
			<div class="info-card alert-warning">
				<h3>⚠️ Allergie</h3>
				<p><?php echo wp_kses_post( nl2br( $dog_allergies ) ); ?></p>
			</div>
			<?php endif; ?>

			<?php if ( $dog_medical_conditions ) : ?>
			<div class="info-card alert-info">
				<h3>🩺 Condizioni Mediche / Patologie</h3>
				<p><?php echo wp_kses_post( nl2br( $dog_medical_conditions ) ); ?></p>
			</div>
			<?php endif; ?>

			<?php if ( $dog_medications ) : ?>
			<div class="info-card alert-info">
				<h3>💊 Farmaci Assunti</h3>
				<p><?php echo wp_kses_post( nl2br( $dog_medications ) ); ?></p>
			</div>
			<?php endif; ?>

			<?php if ( $dog_insurance ) : ?>
			<div class="info-card">
				<h3>🛡️ Assicurazione</h3>
				<p><?php echo esc_html( $dog_insurance ); ?></p>
				<?php if ( $dog_insurance_number ) : ?>
					<small>Polizza: <?php echo esc_html( $dog_insurance_number ); ?></small>
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<?php if ( $dog_emergency_contact ) : ?>
			<div class="info-card alert-danger full-width">
				<h3>🚨 Contatto di Emergenza</h3>
				<p><strong><?php echo esc_html( $dog_emergency_contact ); ?></strong></p>
				<?php if ( $dog_emergency_phone ) : ?>
					<p>📞 <a href="tel:<?php echo esc_attr( $dog_emergency_phone ); ?>"><?php echo esc_html( $dog_emergency_phone ); ?></a></p>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
	</div>

	<!-- Tab Content: Diet -->
	<div class="tab-content" id="tab-diet">
		<div class="info-grid">
			<?php if ( $dog_food_type ) : ?>
			<div class="info-card">
				<h3>🍽️ Tipo di Alimentazione</h3>
				<p>
					<?php
					$food_types = array(
						'dry'      => 'Crocchette',
						'wet'      => 'Umido',
						'mixed'    => 'Misto (crocchette + umido)',
						'barf'     => 'BARF (cibo crudo)',
						'homemade' => 'Casalinga',
						'other'    => 'Altro',
					);
					echo esc_html( $food_types[ $dog_food_type ] ?? $dog_food_type );
					?>
				</p>
			</div>
			<?php endif; ?>

			<?php if ( $dog_food_brand ) : ?>
			<div class="info-card">
				<h3>🏷️ Marca Cibo</h3>
				<p><?php echo esc_html( $dog_food_brand ); ?></p>
			</div>
			<?php endif; ?>

			<?php if ( $dog_food_amount ) : ?>
			<div class="info-card">
				<h3>⚖️ Quantità Giornaliera</h3>
				<p><?php echo esc_html( $dog_food_amount ); ?> grammi</p>
			</div>
			<?php endif; ?>

			<?php if ( $dog_meals_per_day ) : ?>
			<div class="info-card">
				<h3>🍴 Pasti al Giorno</h3>
				<p><?php echo esc_html( $dog_meals_per_day ); ?></p>
			</div>
			<?php endif; ?>

			<?php if ( $dog_diet_notes ) : ?>
			<div class="info-card full-width">
				<h3>📝 Note Alimentazione</h3>
				<p><?php echo wp_kses_post( nl2br( $dog_diet_notes ) ); ?></p>
			</div>
			<?php endif; ?>
		</div>
	</div>

	<!-- Tab Content: Vaccinations -->
	<div class="tab-content" id="tab-vaccinations">
		<div class="section-header">
			<h3>💉 Storico Vaccinazioni</h3>
			<button class="btn btn-primary btn-sm" onclick="document.getElementById('add-vaccination-modal').style.display='block'">
				+ Aggiungi Vaccinazione
			</button>
		</div>

		<?php if ( ! empty( $vaccinations ) ) : ?>
		<div class="table-responsive">
			<table class="vaccinations-table">
				<thead>
					<tr>
						<th>Vaccino</th>
						<th>Data</th>
						<th>Prossimo Richiamo</th>
						<th>Veterinario</th>
						<th>Note</th>
						<th>Azioni</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $vaccinations as $vacc ) : ?>
					<tr>
						<td><strong><?php echo esc_html( $vacc->vaccine_name ); ?></strong></td>
						<td><?php echo esc_html( date_i18n( 'd/m/Y', strtotime( $vacc->vaccine_date ) ) ); ?></td>
						<td>
							<?php if ( $vacc->next_date ) : ?>
								<?php
								$next = new DateTime( $vacc->next_date );
								$now = new DateTime();
								$is_due = $next < $now;
								?>
								<span class="<?php echo $is_due ? 'text-danger' : ''; ?>">
									<?php echo esc_html( date_i18n( 'd/m/Y', strtotime( $vacc->next_date ) ) ); ?>
									<?php if ( $is_due ) : ?>⚠️<?php endif; ?>
								</span>
							<?php else : ?>
								-
							<?php endif; ?>
						</td>
						<td><?php echo esc_html( $vacc->veterinarian ); ?></td>
						<td><?php echo esc_html( wp_trim_words( $vacc->notes, 10 ) ); ?></td>
						<td>
							<button class="btn-icon delete-vaccination" data-vacc-id="<?php echo esc_attr( $vacc->id ); ?>" title="Elimina">
								🗑️
							</button>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php else : ?>
		<div class="empty-state">
			<p>💉 Nessuna vaccinazione registrata</p>
			<button class="btn btn-primary" onclick="document.getElementById('add-vaccination-modal').style.display='block'">
				+ Aggiungi Prima Vaccinazione
			</button>
		</div>
		<?php endif; ?>
	</div>

	<!-- Tab Content: Weight -->
	<div class="tab-content" id="tab-weight">
		<div class="section-header">
			<h3>📊 Storico Peso</h3>
			<button class="btn btn-primary btn-sm" onclick="document.getElementById('add-weight-modal').style.display='block'">
				+ Aggiungi Peso
			</button>
		</div>

		<?php if ( ! empty( $weight_history ) ) : ?>
		<div class="weight-chart">
			<canvas id="weightChart" width="400" height="200"></canvas>
		</div>

		<div class="table-responsive">
			<table class="weight-table">
				<thead>
					<tr>
						<th>Data</th>
						<th>Peso (kg)</th>
						<th>Variazione</th>
						<th>Note</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$prev_weight = null;
					foreach ( $weight_history as $entry ) :
						$diff = $prev_weight !== null ? $entry->weight - $prev_weight : 0;
						?>
					<tr>
						<td><?php echo esc_html( date_i18n( 'd/m/Y', strtotime( $entry->measurement_date ) ) ); ?></td>
						<td><strong><?php echo esc_html( $entry->weight ); ?> kg</strong></td>
						<td>
							<?php if ( $diff != 0 ) : ?>
								<span class="<?php echo $diff > 0 ? 'text-success' : 'text-danger'; ?>">
									<?php echo $diff > 0 ? '+' : ''; ?><?php echo esc_html( number_format( $diff, 1 ) ); ?> kg
								</span>
							<?php else : ?>
								-
							<?php endif; ?>
						</td>
						<td><?php echo esc_html( $entry->notes ); ?></td>
					</tr>
					<?php
						$prev_weight = $entry->weight;
					endforeach;
					?>
				</tbody>
			</table>
		</div>
		<?php else : ?>
		<div class="empty-state">
			<p>📊 Nessun peso registrato</p>
			<button class="btn btn-primary" onclick="document.getElementById('add-weight-modal').style.display='block'">
				+ Aggiungi Prima Misurazione
			</button>
		</div>
		<?php endif; ?>
	</div>

	<!-- Tab Content: Diary -->
	<div class="tab-content" id="tab-diary">
		<div class="section-header">
			<h3>📔 Diario</h3>
			<button class="btn btn-primary btn-sm" onclick="document.getElementById('add-note-modal').style.display='block'">
				+ Aggiungi Nota
			</button>
		</div>

		<?php if ( ! empty( $notes ) ) : ?>
		<div class="notes-timeline">
			<?php foreach ( $notes as $note ) : ?>
			<div class="note-item">
				<div class="note-date">
					<?php echo esc_html( date_i18n( 'd/m/Y', strtotime( $note->note_date ) ) ); ?>
				</div>
				<div class="note-type">
					<?php
					$note_icons = array(
						'general' => '📝',
						'health'  => '🏥',
						'behavior' => '🐕',
						'training' => '🎓',
					);
					echo $note_icons[ $note->note_type ] ?? '📝';
					?>
				</div>
				<div class="note-content">
					<p><?php echo wp_kses_post( nl2br( $note->note_content ) ); ?></p>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php else : ?>
		<div class="empty-state">
			<p>📔 Nessuna nota nel diario</p>
			<button class="btn btn-primary" onclick="document.getElementById('add-note-modal').style.display='block'">
				+ Aggiungi Prima Nota
			</button>
		</div>
		<?php endif; ?>
	</div>
</div>

<!-- Modal: Add Vaccination -->
<div id="add-vaccination-modal" class="modal">
	<div class="modal-content">
		<span class="close" onclick="document.getElementById('add-vaccination-modal').style.display='none'">&times;</span>
		<h3>💉 Aggiungi Vaccinazione</h3>
		<form id="add-vaccination-form">
			<input type="hidden" name="dog_id" value="<?php echo esc_attr( $dog_id ); ?>">

			<div class="form-group">
				<label>Nome Vaccino *</label>
				<input type="text" name="vaccine_name" required>
			</div>

			<div class="form-group">
				<label>Data Vaccino *</label>
				<input type="date" name="vaccine_date" required>
			</div>

			<div class="form-group">
				<label>Prossimo Richiamo</label>
				<input type="date" name="next_date">
			</div>

			<div class="form-group">
				<label>Veterinario</label>
				<input type="text" name="veterinarian">
			</div>

			<div class="form-group">
				<label>Note</label>
				<textarea name="notes" rows="3"></textarea>
			</div>

			<button type="submit" class="btn btn-primary">Aggiungi</button>
		</form>
	</div>
</div>

<!-- Modal: Add Weight -->
<div id="add-weight-modal" class="modal">
	<div class="modal-content">
		<span class="close" onclick="document.getElementById('add-weight-modal').style.display='none'">&times;</span>
		<h3>📊 Aggiungi Peso</h3>
		<form id="add-weight-form">
			<input type="hidden" name="dog_id" value="<?php echo esc_attr( $dog_id ); ?>">

			<div class="form-group">
				<label>Peso (kg) *</label>
				<input type="number" name="weight" step="0.1" min="0" required>
			</div>

			<div class="form-group">
				<label>Data Misurazione *</label>
				<input type="date" name="measurement_date" value="<?php echo date( 'Y-m-d' ); ?>" required>
			</div>

			<div class="form-group">
				<label>Note</label>
				<textarea name="notes" rows="2"></textarea>
			</div>

			<button type="submit" class="btn btn-primary">Aggiungi</button>
		</form>
	</div>
</div>

<!-- Modal: Add Note -->
<div id="add-note-modal" class="modal">
	<div class="modal-content">
		<span class="close" onclick="document.getElementById('add-note-modal').style.display='none'">&times;</span>
		<h3>📔 Aggiungi Nota al Diario</h3>
		<form id="add-note-form">
			<input type="hidden" name="dog_id" value="<?php echo esc_attr( $dog_id ); ?>">

			<div class="form-group">
				<label>Data *</label>
				<input type="date" name="note_date" value="<?php echo date( 'Y-m-d' ); ?>" required>
			</div>

			<div class="form-group">
				<label>Tipo Nota</label>
				<select name="note_type">
					<option value="general">📝 Generale</option>
					<option value="health">🏥 Salute</option>
					<option value="behavior">🐕 Comportamento</option>
					<option value="training">🎓 Addestramento</option>
				</select>
			</div>

			<div class="form-group">
				<label>Nota *</label>
				<textarea name="note_content" rows="4" required></textarea>
			</div>

			<button type="submit" class="btn btn-primary">Aggiungi Nota</button>
		</form>
	</div>
</div>

<style>
/* Profile Header */
.single-dog-profile {
	max-width: 1200px;
	margin: 0 auto;
}

.dog-profile-header {
	display: flex;
	gap: 30px;
	margin-bottom: 30px;
	padding: 30px;
	background: white;
	border-radius: 12px;
	box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.dog-profile-photo img {
	width: 200px;
	height: 200px;
	object-fit: cover;
	border-radius: 12px;
	border: 3px solid #e9ecef;
}

.dog-photo-placeholder {
	width: 200px;
	height: 200px;
	background: #f8f9fa;
	border-radius: 12px;
	display: flex;
	align-items: center;
	justify-content: center;
	border: 2px dashed #dee2e6;
}

.dog-profile-info h2 {
	margin: 0 0 10px;
	font-size: 32px;
	color: #306587;
}

.dog-breed {
	color: #666;
	font-size: 18px;
	margin: 0 0 15px;
}

.dog-quick-stats {
	display: flex;
	gap: 15px;
	flex-wrap: wrap;
	margin-bottom: 20px;
}

.dog-quick-stats .stat {
	background: #f8f9fa;
	padding: 8px 12px;
	border-radius: 20px;
	font-size: 14px;
}

.dog-actions {
	display: flex;
	gap: 10px;
	flex-wrap: wrap;
}

.btn-sm {
	padding: 8px 16px;
	font-size: 14px;
}

.btn-danger {
	background: #dc3545;
	color: white;
}

.btn-danger:hover {
	background: #c82333;
}

/* Tabs */
.dog-profile-tabs {
	display: flex;
	gap: 5px;
	margin-bottom: 20px;
	background: white;
	padding: 10px;
	border-radius: 12px;
	box-shadow: 0 2px 8px rgba(0,0,0,0.1);
	overflow-x: auto;
}

.tab-btn {
	background: transparent;
	border: none;
	padding: 12px 20px;
	border-radius: 8px;
	cursor: pointer;
	font-weight: 600;
	color: #666;
	white-space: nowrap;
	transition: all 0.3s;
}

.tab-btn:hover {
	background: #f8f9fa;
}

.tab-btn.active {
	background: #ff850c;
	color: white;
}

.tab-content {
	display: none;
	background: white;
	padding: 30px;
	border-radius: 12px;
	box-shadow: 0 2px 8px rgba(0,0,0,0.1);
	margin-bottom: 20px;
}

.tab-content.active {
	display: block;
}

/* Info Grid */
.info-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
	gap: 20px;
	margin-bottom: 20px;
}

.info-card {
	background: #f8f9fa;
	padding: 20px;
	border-radius: 8px;
	border-left: 4px solid #306587;
}

.info-card.full-width {
	grid-column: 1 / -1;
}

.info-card.alert-warning {
	background: #fff3cd;
	border-left-color: #ffc107;
}

.info-card.alert-info {
	background: #d1ecf1;
	border-left-color: #17a2b8;
}

.info-card.alert-danger {
	background: #f8d7da;
	border-left-color: #dc3545;
}

.info-card h3 {
	margin: 0 0 10px;
	font-size: 14px;
	color: #666;
	font-weight: 600;
}

.info-card p {
	margin: 0;
	font-size: 16px;
	color: #333;
}

.info-card small {
	display: block;
	margin-top: 5px;
	color: #999;
	font-size: 13px;
}

.tags {
	display: flex;
	flex-wrap: wrap;
	gap: 8px;
}

.tag {
	background: white;
	padding: 6px 12px;
	border-radius: 16px;
	font-size: 13px;
	border: 1px solid #dee2e6;
}

.notes-section {
	background: #f8f9fa;
	padding: 20px;
	border-radius: 8px;
	margin-top: 20px;
}

.notes-section h3 {
	margin-top: 0;
	color: #306587;
}

/* Section Header */
.section-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 20px;
}

.section-header h3 {
	margin: 0;
	color: #306587;
}

/* Tables */
.table-responsive {
	overflow-x: auto;
}

.vaccinations-table,
.weight-table {
	width: 100%;
	border-collapse: collapse;
}

.vaccinations-table th,
.weight-table th {
	background: #f8f9fa;
	padding: 12px;
	text-align: left;
	font-weight: 600;
	color: #666;
	border-bottom: 2px solid #dee2e6;
}

.vaccinations-table td,
.weight-table td {
	padding: 12px;
	border-bottom: 1px solid #e9ecef;
}

.btn-icon {
	background: transparent;
	border: none;
	cursor: pointer;
	font-size: 18px;
	padding: 4px 8px;
}

.btn-icon:hover {
	background: #f8f9fa;
	border-radius: 4px;
}

.text-danger {
	color: #dc3545;
	font-weight: 600;
}

.text-success {
	color: #28a745;
}

/* Chart */
.weight-chart {
	margin-bottom: 30px;
	background: #f8f9fa;
	padding: 20px;
	border-radius: 8px;
}

/* Notes Timeline */
.notes-timeline {
	display: flex;
	flex-direction: column;
	gap: 20px;
}

.note-item {
	display: grid;
	grid-template-columns: 100px 40px 1fr;
	gap: 15px;
	background: #f8f9fa;
	padding: 15px;
	border-radius: 8px;
}

.note-date {
	font-weight: 600;
	color: #666;
	font-size: 14px;
}

.note-type {
	font-size: 24px;
}

.note-content p {
	margin: 0;
}

/* Empty State */
.empty-state {
	text-align: center;
	padding: 60px 20px;
	color: #999;
}

.empty-state p {
	font-size: 18px;
	margin-bottom: 20px;
}

/* Modals */
.modal {
	display: none;
	position: fixed;
	z-index: 1000;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	overflow: auto;
	background-color: rgba(0,0,0,0.5);
}

.modal-content {
	background-color: #fefefe;
	margin: 5% auto;
	padding: 30px;
	border-radius: 12px;
	width: 90%;
	max-width: 500px;
	position: relative;
}

.close {
	position: absolute;
	right: 20px;
	top: 20px;
	color: #aaa;
	font-size: 28px;
	font-weight: bold;
	cursor: pointer;
}

.close:hover {
	color: #000;
}

.modal-content h3 {
	margin-top: 0;
	color: #306587;
}

.form-group {
	margin-bottom: 15px;
}

.form-group label {
	display: block;
	margin-bottom: 5px;
	font-weight: 600;
	color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
	width: 100%;
	padding: 10px;
	border: 1px solid #ddd;
	border-radius: 6px;
	font-size: 14px;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
	outline: none;
	border-color: #ff850c;
	box-shadow: 0 0 0 3px rgba(255, 133, 12, 0.1);
}

/* Responsive */
@media (max-width: 768px) {
	.dog-profile-header {
		flex-direction: column;
		text-align: center;
	}

	.dog-profile-photo img,
	.dog-photo-placeholder {
		margin: 0 auto;
	}

	.dog-quick-stats {
		justify-content: center;
	}

	.dog-actions {
		flex-direction: column;
	}

	.btn {
		width: 100%;
		text-align: center;
	}

	.info-grid {
		grid-template-columns: 1fr;
	}

	.dog-profile-tabs {
		flex-wrap: nowrap;
	}

	.note-item {
		grid-template-columns: 1fr;
	}
}
</style>

<script>
// Tabs functionality
document.querySelectorAll('.tab-btn').forEach(btn => {
	btn.addEventListener('click', function() {
		// Remove active class from all tabs and contents
		document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
		document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

		// Add active class to clicked tab and corresponding content
		this.classList.add('active');
		document.getElementById('tab-' + this.dataset.tab).classList.add('active');
	});
});

// Weight chart
<?php if ( ! empty( $weight_history ) ) : ?>
(function() {
	const ctx = document.getElementById('weightChart');
	if (!ctx) return;

	const weights = <?php echo json_encode( array_reverse( array_column( $weight_history, 'weight' ) ) ); ?>;
	const dates = <?php echo json_encode( array_reverse( array_map( function( $entry ) {
		return date_i18n( 'd/m/y', strtotime( $entry->measurement_date ) );
	}, $weight_history ) ) ); ?>;

	// Simple canvas chart (you can replace with Chart.js for better visuals)
	const canvas = ctx.getContext('2d');
	const width = ctx.width;
	const height = ctx.height;
	const padding = 40;

	// Clear canvas
	canvas.clearRect(0, 0, width, height);

	// Draw axes
	canvas.strokeStyle = '#dee2e6';
	canvas.lineWidth = 2;
	canvas.beginPath();
	canvas.moveTo(padding, padding);
	canvas.lineTo(padding, height - padding);
	canvas.lineTo(width - padding, height - padding);
	canvas.stroke();

	// Draw line
	const maxWeight = Math.max(...weights);
	const minWeight = Math.min(...weights);
	const range = maxWeight - minWeight || 1;

	canvas.strokeStyle = '#ff850c';
	canvas.lineWidth = 3;
	canvas.beginPath();

	weights.forEach((weight, i) => {
		const x = padding + (i / (weights.length - 1)) * (width - 2 * padding);
		const y = height - padding - ((weight - minWeight) / range) * (height - 2 * padding);

		if (i === 0) {
			canvas.moveTo(x, y);
		} else {
			canvas.lineTo(x, y);
		}

		// Draw point
		canvas.fillStyle = '#ff850c';
		canvas.beginPath();
		canvas.arc(x, y, 4, 0, 2 * Math.PI);
		canvas.fill();
	});

	canvas.stroke();

	// Labels
	canvas.fillStyle = '#666';
	canvas.font = '12px sans-serif';
	canvas.textAlign = 'center';

	dates.forEach((date, i) => {
		if (i % Math.ceil(dates.length / 5) === 0) {
			const x = padding + (i / (weights.length - 1)) * (width - 2 * padding);
			canvas.fillText(date, x, height - padding + 20);
		}
	});
})();
<?php endif; ?>
</script>

<?php
/**
 * Dog Profile Form Template - COMPLETE VERSION
 *
 * @package Caniincasa_My_Dog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_edit = isset( $dog_post ) && $dog_post;
$dog_id = $is_edit ? $dog_post->ID : 0;

// Get all ACF field values if editing
$values = array();
if ( $is_edit ) {
	$values['nome'] = get_field( 'nome', $dog_id );
	$values['dog_razza'] = get_field( 'dog_razza', $dog_id );
	$values['dog_razza_custom'] = get_field( 'dog_razza_custom', $dog_id );
	$values['dog_birth_date'] = get_field( 'dog_birth_date', $dog_id );
	$values['dog_gender'] = get_field( 'dog_gender', $dog_id );
	$values['dog_neutered'] = get_field( 'dog_neutered', $dog_id );
	$values['dog_size'] = get_field( 'dog_size', $dog_id );
	$values['dog_weight'] = get_field( 'dog_weight', $dog_id );
	$values['dog_color'] = get_field( 'dog_color', $dog_id );

	// Identification
	$values['dog_microchip'] = get_field( 'dog_microchip', $dog_id );
	$values['dog_microchip_date'] = get_field( 'dog_microchip_date', $dog_id );
	$values['dog_pedigree'] = get_field( 'dog_pedigree', $dog_id );
	$values['dog_passport'] = get_field( 'dog_passport', $dog_id );

	// Health
	$values['dog_veterinarian'] = get_field( 'dog_veterinarian', $dog_id );
	$values['dog_veterinarian_phone'] = get_field( 'dog_veterinarian_phone', $dog_id );
	$values['dog_veterinarian_address'] = get_field( 'dog_veterinarian_address', $dog_id );
	$values['dog_allergies'] = get_field( 'dog_allergies', $dog_id );
	$values['dog_medical_conditions'] = get_field( 'dog_medical_conditions', $dog_id );
	$values['dog_medications'] = get_field( 'dog_medications', $dog_id );
	$values['dog_insurance'] = get_field( 'dog_insurance', $dog_id );
	$values['dog_insurance_number'] = get_field( 'dog_insurance_number', $dog_id );

	// Diet
	$values['dog_food_type'] = get_field( 'dog_food_type', $dog_id );
	$values['dog_food_brand'] = get_field( 'dog_food_brand', $dog_id );
	$values['dog_food_amount'] = get_field( 'dog_food_amount', $dog_id );
	$values['dog_meals_per_day'] = get_field( 'dog_meals_per_day', $dog_id );
	$values['dog_diet_notes'] = get_field( 'dog_diet_notes', $dog_id );

	// Behavior
	$values['dog_temperament'] = get_field( 'dog_temperament', $dog_id );
	$values['dog_training_level'] = get_field( 'dog_training_level', $dog_id );
	$values['dog_behavior_notes'] = get_field( 'dog_behavior_notes', $dog_id );

	// Notes
	$values['dog_notes'] = get_field( 'dog_notes', $dog_id );
	$values['dog_emergency_contact'] = get_field( 'dog_emergency_contact', $dog_id );
	$values['dog_emergency_phone'] = get_field( 'dog_emergency_phone', $dog_id );
}

?>

<div class="dog-profile-form-wrapper">
	<h2><?php echo $is_edit ? __( 'Modifica Profilo Cane', 'caniincasa-my-dog' ) : __( 'Aggiungi Nuovo Cane', 'caniincasa-my-dog' ); ?></h2>

	<form id="dog-profile-form" method="post" enctype="multipart/form-data">
		<?php wp_nonce_field( 'save_dog_profile', 'dog_profile_nonce' ); ?>
		<input type="hidden" name="action" value="save_dog_profile">
		<input type="hidden" name="dog_id" value="<?php echo esc_attr( $dog_id ); ?>">

		<!-- TABS Navigation -->
		<div class="form-tabs">
			<button type="button" class="form-tab-btn active" data-tab="base">
				📋 Informazioni Base
			</button>
			<button type="button" class="form-tab-btn" data-tab="health">
				🏥 Salute
			</button>
			<button type="button" class="form-tab-btn" data-tab="diet">
				🍖 Alimentazione
			</button>
			<button type="button" class="form-tab-btn" data-tab="behavior">
				🐕 Comportamento
			</button>
		</div>

		<!-- TAB: Informazioni Base -->
		<div class="form-tab-content active" id="form-tab-base">

			<?php if ( $is_edit ) : ?>
			<div class="form-group">
				<label for="dog_foto"><?php _e( 'Foto Profilo', 'caniincasa-my-dog' ); ?></label>
				<?php
				$foto = get_field( 'foto', $dog_id );
				if ( $foto ) :
					$foto_url = is_array( $foto ) ? $foto['url'] : wp_get_attachment_url( $foto );
					?>
					<div class="current-photo">
						<img src="<?php echo esc_url( $foto_url ); ?>" alt="Foto profilo">
					</div>
				<?php endif; ?>
				<input type="file" id="dog_foto" name="dog_foto" accept="image/*">
				<small><?php _e( 'Formati supportati: JPG, PNG. Max 5MB.', 'caniincasa-my-dog' ); ?></small>
			</div>
			<?php endif; ?>

			<div class="form-row">
				<div class="form-group">
					<label for="dog_nome"><?php _e( 'Nome del Cane', 'caniincasa-my-dog' ); ?> *</label>
					<input
						type="text"
						id="dog_nome"
						name="dog_nome"
						value="<?php echo $is_edit ? esc_attr( $values['nome'] ) : ''; ?>"
						required
						placeholder="<?php esc_attr_e( 'Es: Fido', 'caniincasa-my-dog' ); ?>"
					>
				</div>

				<div class="form-group">
					<label for="dog_razza_custom"><?php _e( 'Razza', 'caniincasa-my-dog' ); ?></label>
					<input
						type="text"
						id="dog_razza_custom"
						name="dog_razza_custom"
						value="<?php echo $is_edit ? esc_attr( $values['dog_razza_custom'] ) : ''; ?>"
						placeholder="<?php esc_attr_e( 'Es: Labrador Retriever', 'caniincasa-my-dog' ); ?>"
					>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group">
					<label for="dog_gender"><?php _e( 'Sesso', 'caniincasa-my-dog' ); ?></label>
					<select id="dog_gender" name="dog_gender">
						<option value=""><?php _e( 'Seleziona...', 'caniincasa-my-dog' ); ?></option>
						<option value="male" <?php echo $is_edit && $values['dog_gender'] === 'male' ? 'selected' : ''; ?>>
							<?php _e( 'Maschio', 'caniincasa-my-dog' ); ?>
						</option>
						<option value="female" <?php echo $is_edit && $values['dog_gender'] === 'female' ? 'selected' : ''; ?>>
							<?php _e( 'Femmina', 'caniincasa-my-dog' ); ?>
						</option>
					</select>
				</div>

				<div class="form-group">
					<label for="dog_birth_date"><?php _e( 'Data di Nascita', 'caniincasa-my-dog' ); ?></label>
					<input
						type="date"
						id="dog_birth_date"
						name="dog_birth_date"
						value="<?php echo $is_edit ? esc_attr( $values['dog_birth_date'] ) : ''; ?>"
					>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group">
					<label for="dog_size"><?php _e( 'Taglia', 'caniincasa-my-dog' ); ?></label>
					<select id="dog_size" name="dog_size">
						<option value=""><?php _e( 'Seleziona...', 'caniincasa-my-dog' ); ?></option>
						<option value="toy" <?php echo $is_edit && $values['dog_size'] === 'toy' ? 'selected' : ''; ?>>Toy (&lt; 5kg)</option>
						<option value="small" <?php echo $is_edit && $values['dog_size'] === 'small' ? 'selected' : ''; ?>>Piccola (5-10kg)</option>
						<option value="medium" <?php echo $is_edit && $values['dog_size'] === 'medium' ? 'selected' : ''; ?>>Media (10-25kg)</option>
						<option value="large" <?php echo $is_edit && $values['dog_size'] === 'large' ? 'selected' : ''; ?>>Grande (25-45kg)</option>
						<option value="giant" <?php echo $is_edit && $values['dog_size'] === 'giant' ? 'selected' : ''; ?>>Gigante (&gt; 45kg)</option>
					</select>
				</div>

				<div class="form-group">
					<label for="dog_weight"><?php _e( 'Peso Attuale (kg)', 'caniincasa-my-dog' ); ?></label>
					<input
						type="number"
						id="dog_weight"
						name="dog_weight"
						value="<?php echo $is_edit ? esc_attr( $values['dog_weight'] ) : ''; ?>"
						step="0.1"
						min="0"
						placeholder="<?php esc_attr_e( 'Es: 25.5', 'caniincasa-my-dog' ); ?>"
					>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group">
					<label for="dog_color"><?php _e( 'Colore/Mantello', 'caniincasa-my-dog' ); ?></label>
					<input
						type="text"
						id="dog_color"
						name="dog_color"
						value="<?php echo $is_edit ? esc_attr( $values['dog_color'] ) : ''; ?>"
						placeholder="<?php esc_attr_e( 'Es: Bianco e marrone', 'caniincasa-my-dog' ); ?>"
					>
				</div>

				<div class="form-group">
					<label for="dog_neutered">
						<input
							type="checkbox"
							id="dog_neutered"
							name="dog_neutered"
							value="1"
							<?php echo $is_edit && $values['dog_neutered'] ? 'checked' : ''; ?>
						>
						<?php _e( 'Sterilizzato/Castrato', 'caniincasa-my-dog' ); ?>
					</label>
				</div>
			</div>

			<h3><?php _e( 'Identificazione', 'caniincasa-my-dog' ); ?></h3>

			<div class="form-row">
				<div class="form-group">
					<label for="dog_microchip"><?php _e( 'Numero Microchip', 'caniincasa-my-dog' ); ?></label>
					<input
						type="text"
						id="dog_microchip"
						name="dog_microchip"
						value="<?php echo $is_edit ? esc_attr( $values['dog_microchip'] ) : ''; ?>"
						placeholder="<?php esc_attr_e( 'Es: 380260XXXXXXXXX', 'caniincasa-my-dog' ); ?>"
					>
				</div>

				<div class="form-group">
					<label for="dog_microchip_date"><?php _e( 'Data Impianto Microchip', 'caniincasa-my-dog' ); ?></label>
					<input
						type="date"
						id="dog_microchip_date"
						name="dog_microchip_date"
						value="<?php echo $is_edit ? esc_attr( $values['dog_microchip_date'] ) : ''; ?>"
					>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group">
					<label for="dog_pedigree"><?php _e( 'Numero Pedigree', 'caniincasa-my-dog' ); ?></label>
					<input
						type="text"
						id="dog_pedigree"
						name="dog_pedigree"
						value="<?php echo $is_edit ? esc_attr( $values['dog_pedigree'] ) : ''; ?>"
					>
				</div>

				<div class="form-group">
					<label for="dog_passport"><?php _e( 'Passaporto Europeo', 'caniincasa-my-dog' ); ?></label>
					<input
						type="text"
						id="dog_passport"
						name="dog_passport"
						value="<?php echo $is_edit ? esc_attr( $values['dog_passport'] ) : ''; ?>"
					>
				</div>
			</div>
		</div>

		<!-- TAB: Salute -->
		<div class="form-tab-content" id="form-tab-health">
			<h3><?php _e( 'Veterinario di Riferimento', 'caniincasa-my-dog' ); ?></h3>

			<div class="form-group">
				<label for="dog_veterinarian"><?php _e( 'Nome Veterinario', 'caniincasa-my-dog' ); ?></label>
				<input
					type="text"
					id="dog_veterinarian"
					name="dog_veterinarian"
					value="<?php echo $is_edit ? esc_attr( $values['dog_veterinarian'] ) : ''; ?>"
				>
			</div>

			<div class="form-row">
				<div class="form-group">
					<label for="dog_veterinarian_phone"><?php _e( 'Telefono Veterinario', 'caniincasa-my-dog' ); ?></label>
					<input
						type="tel"
						id="dog_veterinarian_phone"
						name="dog_veterinarian_phone"
						value="<?php echo $is_edit ? esc_attr( $values['dog_veterinarian_phone'] ) : ''; ?>"
					>
				</div>

				<div class="form-group">
					<label for="dog_veterinarian_address"><?php _e( 'Indirizzo Veterinario', 'caniincasa-my-dog' ); ?></label>
					<textarea
						id="dog_veterinarian_address"
						name="dog_veterinarian_address"
						rows="2"
					><?php echo $is_edit ? esc_textarea( $values['dog_veterinarian_address'] ) : ''; ?></textarea>
				</div>
			</div>

			<h3><?php _e( 'Condizioni Mediche', 'caniincasa-my-dog' ); ?></h3>

			<div class="form-group">
				<label for="dog_allergies"><?php _e( 'Allergie', 'caniincasa-my-dog' ); ?></label>
				<textarea
					id="dog_allergies"
					name="dog_allergies"
					rows="3"
					placeholder="<?php esc_attr_e( 'Es: Allergia al pollo, intolleranza ai cereali...', 'caniincasa-my-dog' ); ?>"
				><?php echo $is_edit ? esc_textarea( $values['dog_allergies'] ) : ''; ?></textarea>
			</div>

			<div class="form-group">
				<label for="dog_medical_conditions"><?php _e( 'Condizioni Mediche / Patologie', 'caniincasa-my-dog' ); ?></label>
				<textarea
					id="dog_medical_conditions"
					name="dog_medical_conditions"
					rows="4"
					placeholder="<?php esc_attr_e( 'Es: Displasia anca, problemi cardiaci...', 'caniincasa-my-dog' ); ?>"
				><?php echo $is_edit ? esc_textarea( $values['dog_medical_conditions'] ) : ''; ?></textarea>
			</div>

			<div class="form-group">
				<label for="dog_medications"><?php _e( 'Farmaci Assunti', 'caniincasa-my-dog' ); ?></label>
				<textarea
					id="dog_medications"
					name="dog_medications"
					rows="3"
					placeholder="<?php esc_attr_e( 'Es: Antinfiammatorio 1 compressa al giorno...', 'caniincasa-my-dog' ); ?>"
				><?php echo $is_edit ? esc_textarea( $values['dog_medications'] ) : ''; ?></textarea>
			</div>

			<h3><?php _e( 'Assicurazione', 'caniincasa-my-dog' ); ?></h3>

			<div class="form-row">
				<div class="form-group">
					<label for="dog_insurance"><?php _e( 'Compagnia Assicurazione', 'caniincasa-my-dog' ); ?></label>
					<input
						type="text"
						id="dog_insurance"
						name="dog_insurance"
						value="<?php echo $is_edit ? esc_attr( $values['dog_insurance'] ) : ''; ?>"
					>
				</div>

				<div class="form-group">
					<label for="dog_insurance_number"><?php _e( 'Numero Polizza', 'caniincasa-my-dog' ); ?></label>
					<input
						type="text"
						id="dog_insurance_number"
						name="dog_insurance_number"
						value="<?php echo $is_edit ? esc_attr( $values['dog_insurance_number'] ) : ''; ?>"
					>
				</div>
			</div>

			<h3><?php _e( 'Contatto di Emergenza', 'caniincasa-my-dog' ); ?></h3>

			<div class="form-row">
				<div class="form-group">
					<label for="dog_emergency_contact"><?php _e( 'Nome Contatto', 'caniincasa-my-dog' ); ?></label>
					<input
						type="text"
						id="dog_emergency_contact"
						name="dog_emergency_contact"
						value="<?php echo $is_edit ? esc_attr( $values['dog_emergency_contact'] ) : ''; ?>"
					>
				</div>

				<div class="form-group">
					<label for="dog_emergency_phone"><?php _e( 'Telefono Emergenza', 'caniincasa-my-dog' ); ?></label>
					<input
						type="tel"
						id="dog_emergency_phone"
						name="dog_emergency_phone"
						value="<?php echo $is_edit ? esc_attr( $values['dog_emergency_phone'] ) : ''; ?>"
					>
				</div>
			</div>
		</div>

		<!-- TAB: Alimentazione -->
		<div class="form-tab-content" id="form-tab-diet">
			<div class="form-row">
				<div class="form-group">
					<label for="dog_food_type"><?php _e( 'Tipo di Alimentazione', 'caniincasa-my-dog' ); ?></label>
					<select id="dog_food_type" name="dog_food_type">
						<option value=""><?php _e( 'Seleziona...', 'caniincasa-my-dog' ); ?></option>
						<option value="dry" <?php echo $is_edit && $values['dog_food_type'] === 'dry' ? 'selected' : ''; ?>>Crocchette</option>
						<option value="wet" <?php echo $is_edit && $values['dog_food_type'] === 'wet' ? 'selected' : ''; ?>>Umido</option>
						<option value="mixed" <?php echo $is_edit && $values['dog_food_type'] === 'mixed' ? 'selected' : ''; ?>>Misto (crocchette + umido)</option>
						<option value="barf" <?php echo $is_edit && $values['dog_food_type'] === 'barf' ? 'selected' : ''; ?>>BARF (cibo crudo)</option>
						<option value="homemade" <?php echo $is_edit && $values['dog_food_type'] === 'homemade' ? 'selected' : ''; ?>>Casalinga</option>
						<option value="other" <?php echo $is_edit && $values['dog_food_type'] === 'other' ? 'selected' : ''; ?>>Altro</option>
					</select>
				</div>

				<div class="form-group">
					<label for="dog_food_brand"><?php _e( 'Marca Cibo', 'caniincasa-my-dog' ); ?></label>
					<input
						type="text"
						id="dog_food_brand"
						name="dog_food_brand"
						value="<?php echo $is_edit ? esc_attr( $values['dog_food_brand'] ) : ''; ?>"
					>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group">
					<label for="dog_food_amount"><?php _e( 'Quantità Giornaliera (grammi)', 'caniincasa-my-dog' ); ?></label>
					<input
						type="number"
						id="dog_food_amount"
						name="dog_food_amount"
						value="<?php echo $is_edit ? esc_attr( $values['dog_food_amount'] ) : ''; ?>"
						min="0"
					>
				</div>

				<div class="form-group">
					<label for="dog_meals_per_day"><?php _e( 'Pasti al Giorno', 'caniincasa-my-dog' ); ?></label>
					<input
						type="number"
						id="dog_meals_per_day"
						name="dog_meals_per_day"
						value="<?php echo $is_edit ? esc_attr( $values['dog_meals_per_day'] ) : ''; ?>"
						min="1"
						max="5"
					>
				</div>
			</div>

			<div class="form-group">
				<label for="dog_diet_notes"><?php _e( 'Note Alimentazione', 'caniincasa-my-dog' ); ?></label>
				<textarea
					id="dog_diet_notes"
					name="dog_diet_notes"
					rows="4"
					placeholder="<?php esc_attr_e( 'Es: Preferisce mangiare al mattino, evitare snack...', 'caniincasa-my-dog' ); ?>"
				><?php echo $is_edit ? esc_textarea( $values['dog_diet_notes'] ) : ''; ?></textarea>
			</div>
		</div>

		<!-- TAB: Comportamento -->
		<div class="form-tab-content" id="form-tab-behavior">
			<div class="form-group">
				<label><?php _e( 'Temperamento', 'caniincasa-my-dog' ); ?></label>
				<div class="checkbox-group">
					<?php
					$temperament_options = array(
						'friendly'   => 'Socievole',
						'playful'    => 'Giocoso',
						'calm'       => 'Calmo',
						'energetic'  => 'Energico',
						'protective' => 'Protettivo',
						'shy'        => 'Timido',
						'aggressive' => 'Aggressivo',
					);
					$selected_temperament = $is_edit && is_array( $values['dog_temperament'] ) ? $values['dog_temperament'] : array();
					foreach ( $temperament_options as $value => $label ) :
						?>
						<label>
							<input
								type="checkbox"
								name="dog_temperament[]"
								value="<?php echo esc_attr( $value ); ?>"
								<?php echo in_array( $value, $selected_temperament ) ? 'checked' : ''; ?>
							>
							<?php echo esc_html( $label ); ?>
						</label>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="form-group">
				<label for="dog_training_level"><?php _e( 'Livello di Addestramento', 'caniincasa-my-dog' ); ?></label>
				<select id="dog_training_level" name="dog_training_level">
					<option value=""><?php _e( 'Seleziona...', 'caniincasa-my-dog' ); ?></option>
					<option value="none" <?php echo $is_edit && $values['dog_training_level'] === 'none' ? 'selected' : ''; ?>>Nessun addestramento</option>
					<option value="basic" <?php echo $is_edit && $values['dog_training_level'] === 'basic' ? 'selected' : ''; ?>>Comandi base</option>
					<option value="intermediate" <?php echo $is_edit && $values['dog_training_level'] === 'intermediate' ? 'selected' : ''; ?>>Intermedio</option>
					<option value="advanced" <?php echo $is_edit && $values['dog_training_level'] === 'advanced' ? 'selected' : ''; ?>>Avanzato</option>
				</select>
			</div>

			<div class="form-group">
				<label for="dog_behavior_notes"><?php _e( 'Note Comportamento', 'caniincasa-my-dog' ); ?></label>
				<textarea
					id="dog_behavior_notes"
					name="dog_behavior_notes"
					rows="5"
					placeholder="<?php esc_attr_e( 'Es: Si comporta bene con altri cani, nervoso con estranei...', 'caniincasa-my-dog' ); ?>"
				><?php echo $is_edit ? esc_textarea( $values['dog_behavior_notes'] ) : ''; ?></textarea>
			</div>

			<div class="form-group">
				<label for="dog_notes"><?php _e( 'Note Generali', 'caniincasa-my-dog' ); ?></label>
				<textarea
					id="dog_notes"
					name="dog_notes"
					rows="5"
					placeholder="<?php esc_attr_e( 'Altre informazioni utili...', 'caniincasa-my-dog' ); ?>"
				><?php echo $is_edit ? esc_textarea( $values['dog_notes'] ) : ''; ?></textarea>
			</div>
		</div>

		<div class="form-submit-wrapper">
			<button type="submit" class="btn btn-primary btn-large">
				<?php echo $is_edit ? __( 'Aggiorna Profilo', 'caniincasa-my-dog' ) : __( 'Crea Profilo', 'caniincasa-my-dog' ); ?>
			</button>
			<a href="<?php echo esc_url( home_url( '/i-miei-cani/' ) ); ?>" class="btn btn-secondary">
				<?php _e( 'Annulla', 'caniincasa-my-dog' ); ?>
			</a>
		</div>

		<div id="form-message" class="form-message"></div>
	</form>
</div>

<style>
.dog-profile-form-wrapper {
	max-width: 900px;
	margin: 0 auto;
	background: white;
	padding: 30px;
	border-radius: 12px;
	box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.dog-profile-form-wrapper h2 {
	margin: 0 0 30px;
	color: #306587;
	font-size: 28px;
}
.dog-profile-form-wrapper h3 {
	margin: 30px 0 20px;
	color: #306587;
	font-size: 18px;
	padding-bottom: 10px;
	border-bottom: 2px solid #e9ecef;
}

/* Tabs */
.form-tabs {
	display: flex;
	gap: 5px;
	margin-bottom: 30px;
	border-bottom: 2px solid #e9ecef;
	overflow-x: auto;
}
.form-tab-btn {
	background: transparent;
	border: none;
	padding: 12px 20px;
	cursor: pointer;
	font-weight: 600;
	color: #666;
	white-space: nowrap;
	border-bottom: 3px solid transparent;
	transition: all 0.3s;
}
.form-tab-btn:hover {
	color: #ff850c;
}
.form-tab-btn.active {
	color: #ff850c;
	border-bottom-color: #ff850c;
}

.form-tab-content {
	display: none;
}
.form-tab-content.active {
	display: block;
}

.form-row {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 20px;
	margin-bottom: 20px;
}
.form-group {
	margin-bottom: 20px;
}
.form-group label {
	display: block;
	margin-bottom: 8px;
	font-weight: 600;
	color: #333;
}
.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="tel"],
.form-group input[type="number"],
.form-group input[type="date"],
.form-group input[type="file"],
.form-group select,
.form-group textarea {
	width: 100%;
	padding: 12px 16px;
	border: 1px solid #ddd;
	border-radius: 8px;
	font-size: 15px;
	transition: border-color 0.3s;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
	outline: none;
	border-color: #ff850c;
	box-shadow: 0 0 0 3px rgba(255, 133, 12, 0.1);
}
.form-group small {
	display: block;
	margin-top: 6px;
	color: #666;
	font-size: 13px;
}
.current-photo {
	margin-bottom: 15px;
}
.current-photo img {
	max-width: 200px;
	height: auto;
	border-radius: 8px;
	border: 2px solid #e9ecef;
}

.checkbox-group {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
	gap: 10px;
}
.checkbox-group label {
	display: flex;
	align-items: center;
	gap: 8px;
	font-weight: normal;
	margin: 0;
}
.checkbox-group input[type="checkbox"] {
	width: auto;
}

.form-submit-wrapper {
	display: flex;
	gap: 15px;
	margin-top: 30px;
	padding-top: 30px;
	border-top: 2px solid #e9ecef;
}
.btn-large {
	padding: 15px 40px;
	font-size: 16px;
}
.form-message {
	margin-top: 20px;
	padding: 15px 20px;
	border-radius: 8px;
	display: none;
}
.form-message.success {
	background: #e8f5e9;
	border-left: 4px solid #4caf50;
	color: #2e7d32;
	display: block;
}
.form-message.error {
	background: #fee;
	border-left: 4px solid #d32f2f;
	color: #c62828;
	display: block;
}

@media (max-width: 768px) {
	.form-row {
		grid-template-columns: 1fr;
	}
	.form-submit-wrapper {
		flex-direction: column;
	}
	.btn {
		width: 100%;
		text-align: center;
	}
	.checkbox-group {
		grid-template-columns: 1fr;
	}
}
</style>

<script>
// Tabs functionality
document.querySelectorAll('.form-tab-btn').forEach(btn => {
	btn.addEventListener('click', function() {
		// Remove active class from all tabs and contents
		document.querySelectorAll('.form-tab-btn').forEach(b => b.classList.remove('active'));
		document.querySelectorAll('.form-tab-content').forEach(c => c.classList.remove('active'));

		// Add active class to clicked tab and corresponding content
		this.classList.add('active');
		document.getElementById('form-tab-' + this.dataset.tab).classList.add('active');
	});
});
</script>

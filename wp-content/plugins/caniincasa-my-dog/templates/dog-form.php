<?php
/**
 * Dog Profile Form Template
 *
 * @package Caniincasa_My_Dog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_edit = isset( $dog_post ) && $dog_post;
$dog_id = $is_edit ? $dog_post->ID : 0;

?>

<div class="dog-profile-form-wrapper">
	<h2><?php echo $is_edit ? __( 'Modifica Profilo Cane', 'caniincasa-my-dog' ) : __( 'Aggiungi Nuovo Cane', 'caniincasa-my-dog' ); ?></h2>

	<form id="dog-profile-form" method="post" enctype="multipart/form-data">
		<?php wp_nonce_field( 'save_dog_profile', 'dog_profile_nonce' ); ?>
		<input type="hidden" name="action" value="save_dog_profile">
		<input type="hidden" name="dog_id" value="<?php echo esc_attr( $dog_id ); ?>">

		<!-- Informazioni Base -->
		<div class="form-section">
			<h3><?php _e( 'Informazioni Base', 'caniincasa-my-dog' ); ?></h3>

			<div class="form-row">
				<div class="form-group">
					<label for="dog_nome"><?php _e( 'Nome del Cane', 'caniincasa-my-dog' ); ?> *</label>
					<input
						type="text"
						id="dog_nome"
						name="dog_nome"
						value="<?php echo $is_edit ? esc_attr( get_field( 'nome', $dog_id ) ) : ''; ?>"
						required
						placeholder="<?php esc_attr_e( 'Es: Fido', 'caniincasa-my-dog' ); ?>"
					>
				</div>

				<div class="form-group">
					<label for="dog_razza"><?php _e( 'Razza', 'caniincasa-my-dog' ); ?></label>
					<input
						type="text"
						id="dog_razza"
						name="dog_razza"
						value="<?php echo $is_edit ? esc_attr( get_field( 'razza', $dog_id ) ) : ''; ?>"
						placeholder="<?php esc_attr_e( 'Es: Labrador Retriever', 'caniincasa-my-dog' ); ?>"
					>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group">
					<label for="dog_sesso"><?php _e( 'Sesso', 'caniincasa-my-dog' ); ?></label>
					<select id="dog_sesso" name="dog_sesso">
						<option value=""><?php _e( 'Seleziona...', 'caniincasa-my-dog' ); ?></option>
						<option value="maschio" <?php echo $is_edit && get_field( 'sesso', $dog_id ) === 'maschio' ? 'selected' : ''; ?>>
							<?php _e( 'Maschio', 'caniincasa-my-dog' ); ?>
						</option>
						<option value="femmina" <?php echo $is_edit && get_field( 'sesso', $dog_id ) === 'femmina' ? 'selected' : ''; ?>>
							<?php _e( 'Femmina', 'caniincasa-my-dog' ); ?>
						</option>
					</select>
				</div>

				<div class="form-group">
					<label for="dog_data_nascita"><?php _e( 'Data di Nascita', 'caniincasa-my-dog' ); ?></label>
					<input
						type="date"
						id="dog_data_nascita"
						name="dog_data_nascita"
						value="<?php echo $is_edit ? esc_attr( get_field( 'data_nascita', $dog_id ) ) : ''; ?>"
					>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group">
					<label for="dog_peso_attuale"><?php _e( 'Peso Attuale (kg)', 'caniincasa-my-dog' ); ?></label>
					<input
						type="number"
						id="dog_peso_attuale"
						name="dog_peso_attuale"
						value="<?php echo $is_edit ? esc_attr( get_field( 'peso_attuale', $dog_id ) ) : ''; ?>"
						step="0.1"
						min="0"
						placeholder="<?php esc_attr_e( 'Es: 25.5', 'caniincasa-my-dog' ); ?>"
					>
				</div>

				<div class="form-group">
					<label for="dog_microchip"><?php _e( 'Numero Microchip', 'caniincasa-my-dog' ); ?></label>
					<input
						type="text"
						id="dog_microchip"
						name="dog_microchip"
						value="<?php echo $is_edit ? esc_attr( get_field( 'microchip', $dog_id ) ) : ''; ?>"
						placeholder="<?php esc_attr_e( 'Es: 380260XXXXXXXXX', 'caniincasa-my-dog' ); ?>"
					>
				</div>
			</div>

			<?php if ( $is_edit ) : ?>
				<div class="form-group">
					<label for="dog_foto"><?php _e( 'Foto Profilo', 'caniincasa-my-dog' ); ?></label>
					<?php
					$foto = get_field( 'foto', $dog_id );
					if ( $foto ) :
						?>
						<div class="current-photo">
							<img src="<?php echo esc_url( $foto['sizes']['medium'] ?? $foto['url'] ); ?>" alt="<?php echo esc_attr( get_field( 'nome', $dog_id ) ); ?>">
						</div>
					<?php endif; ?>
					<input type="file" id="dog_foto" name="dog_foto" accept="image/*">
					<small><?php _e( 'Formati supportati: JPG, PNG. Max 5MB.', 'caniincasa-my-dog' ); ?></small>
				</div>
			<?php endif; ?>
		</div>

		<!-- Note/Descrizione -->
		<div class="form-section">
			<h3><?php _e( 'Note Aggiuntive', 'caniincasa-my-dog' ); ?></h3>
			<div class="form-group">
				<label for="dog_note"><?php _e( 'Note', 'caniincasa-my-dog' ); ?></label>
				<textarea
					id="dog_note"
					name="dog_note"
					rows="5"
					placeholder="<?php esc_attr_e( 'Inserisci eventuali note...', 'caniincasa-my-dog' ); ?>"
				><?php echo $is_edit ? esc_textarea( get_field( 'note', $dog_id ) ) : ''; ?></textarea>
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
	max-width: 800px;
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
.form-section {
	margin-bottom: 40px;
	padding-bottom: 30px;
	border-bottom: 1px solid #e9ecef;
}
.form-section:last-of-type {
	border-bottom: none;
}
.form-section h3 {
	margin: 0 0 20px;
	color: #306587;
	font-size: 20px;
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
.form-submit-wrapper {
	display: flex;
	gap: 15px;
	margin-top: 30px;
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
}
</style>

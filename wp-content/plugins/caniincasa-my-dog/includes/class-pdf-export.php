<?php
/**
 * PDF Export for Vet Records
 *
 * @package Caniincasa_My_Dog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Caniincasa_My_Dog_PDF {

	/**
	 * Initialize
	 */
	public static function init() {
		add_action( 'template_redirect', array( __CLASS__, 'handle_pdf_export' ) );
	}

	/**
	 * Handle PDF export request
	 */
	public static function handle_pdf_export() {
		if ( ! isset( $_GET['export_dog_pdf'] ) ) {
			return;
		}

		$dog_id = intval( $_GET['export_dog_pdf'] );

		if ( ! is_user_logged_in() || ! Caniincasa_My_Dog_Post_Type::user_can_view( $dog_id ) ) {
			wp_die( __( 'Non autorizzato.', 'caniincasa-my-dog' ) );
		}

		self::generate_pdf( $dog_id );
		exit;
	}

	/**
	 * Generate PDF
	 */
	public static function generate_pdf( $dog_id ) {
		// Use mPDF or TCPDF library
		// For simplicity, we'll use HTML output that can be printed as PDF
		// In production, install composer package: mpdf/mpdf

		$dog = get_post( $dog_id );
		if ( ! $dog ) {
			return;
		}

		// Get all fields
		$fields = self::get_all_fields( $dog_id );

		// Set headers
		header( 'Content-Type: text/html; charset=utf-8' );

		// Output printable HTML
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="UTF-8">
			<title><?php echo esc_html( $dog->post_title ); ?> - Scheda Veterinario</title>
			<style>
				* { margin: 0; padding: 0; box-sizing: border-box; }
				body {
					font-family: Arial, sans-serif;
					font-size: 12pt;
					line-height: 1.6;
					padding: 20mm;
					background: white;
				}
				h1 { font-size: 24pt; margin-bottom: 10mm; color: #FF6B35; border-bottom: 2px solid #FF6B35; padding-bottom: 5mm; }
				h2 { font-size: 16pt; margin-top: 10mm; margin-bottom: 5mm; color: #333; border-bottom: 1px solid #ddd; padding-bottom: 2mm; }
				.header { text-align: center; margin-bottom: 10mm; }
				.logo { max-width: 150px; margin-bottom: 5mm; }
				.photo { max-width: 150px; max-height: 150px; border-radius: 50%; margin: 10mm auto; display: block; }
				.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 5mm; margin: 5mm 0; }
				.info-item { padding: 3mm; background: #f9f9f9; border-left: 3px solid #FF6B35; }
				.label { font-weight: bold; color: #666; font-size: 10pt; }
				.value { color: #333; }
				.section { margin: 5mm 0; page-break-inside: avoid; }
				table { width: 100%; border-collapse: collapse; margin: 5mm 0; }
				th, td { padding: 3mm; text-align: left; border-bottom: 1px solid #ddd; }
				th { background: #f5f5f5; font-weight: bold; }
				.footer { margin-top: 15mm; padding-top: 5mm; border-top: 1px solid #ddd; text-align: center; font-size: 10pt; color: #666; }
				.emergency-box { background: #fff3cd; border: 2px solid #ffc107; padding: 5mm; margin: 5mm 0; }
				@media print {
					body { padding: 0; }
					.no-print { display: none; }
				}
			</style>
			<script>
				window.onload = function() {
					// Auto-print on load
					// window.print();
				};
			</script>
		</head>
		<body>
			<div class="no-print" style="position: fixed; top: 20px; right: 20px;">
				<button onclick="window.print()" style="padding: 10px 20px; background: #FF6B35; color: white; border: none; border-radius: 5px; cursor: pointer;">
					🖨️ Stampa / Salva PDF
				</button>
			</div>

			<div class="header">
				<h1>🐕 SCHEDA VETERINARIA</h1>
				<p><strong><?php echo esc_html( $dog->post_title ); ?></strong></p>
				<p style="font-size: 10pt; color: #666;">Generata il <?php echo date_i18n( 'd/m/Y H:i' ); ?></p>
			</div>

			<?php if ( has_post_thumbnail( $dog_id ) ) : ?>
				<img src="<?php echo esc_url( get_the_post_thumbnail_url( $dog_id, 'medium' ) ); ?>" class="photo" alt="<?php echo esc_attr( $dog->post_title ); ?>">
			<?php endif; ?>

			<!-- INFORMAZIONI BASE -->
			<h2>📋 Informazioni Base</h2>
			<div class="info-grid">
				<?php if ( $fields['dog_razza_name'] ) : ?>
					<div class="info-item">
						<div class="label">Razza</div>
						<div class="value"><?php echo esc_html( $fields['dog_razza_name'] ); ?></div>
					</div>
				<?php endif; ?>

				<?php if ( $fields['dog_birth_date'] ) : ?>
					<div class="info-item">
						<div class="label">Data di Nascita</div>
						<div class="value">
							<?php echo esc_html( $fields['dog_birth_date'] ); ?>
							(<?php echo esc_html( $fields['age_text'] ); ?>)
						</div>
					</div>
				<?php endif; ?>

				<div class="info-item">
					<div class="label">Sesso</div>
					<div class="value"><?php echo esc_html( $fields['dog_gender_text'] ); ?></div>
				</div>

				<div class="info-item">
					<div class="label">Sterilizzato/Castrato</div>
					<div class="value"><?php echo $fields['dog_neutered'] ? 'Sì' : 'No'; ?></div>
				</div>

				<?php if ( $fields['dog_size_text'] ) : ?>
					<div class="info-item">
						<div class="label">Taglia</div>
						<div class="value"><?php echo esc_html( $fields['dog_size_text'] ); ?></div>
					</div>
				<?php endif; ?>

				<?php if ( $fields['dog_weight'] ) : ?>
					<div class="info-item">
						<div class="label">Peso</div>
						<div class="value"><?php echo esc_html( $fields['dog_weight'] ); ?> kg</div>
					</div>
				<?php endif; ?>
			</div>

			<!-- IDENTIFICAZIONE -->
			<?php if ( $fields['dog_microchip'] || $fields['dog_pedigree'] || $fields['dog_passport'] ) : ?>
				<h2>🔖 Identificazione</h2>
				<div class="info-grid">
					<?php if ( $fields['dog_microchip'] ) : ?>
						<div class="info-item">
							<div class="label">Microchip</div>
							<div class="value"><?php echo esc_html( $fields['dog_microchip'] ); ?></div>
						</div>
					<?php endif; ?>

					<?php if ( $fields['dog_pedigree'] ) : ?>
						<div class="info-item">
							<div class="label">Pedigree</div>
							<div class="value"><?php echo esc_html( $fields['dog_pedigree'] ); ?></div>
					</div>
					<?php endif; ?>

					<?php if ( $fields['dog_passport'] ) : ?>
						<div class="info-item">
							<div class="label">Passaporto Europeo</div>
							<div class="value"><?php echo esc_html( $fields['dog_passport'] ); ?></div>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<!-- SALUTE -->
			<h2>🏥 Informazioni Mediche</h2>

			<?php if ( $fields['dog_veterinarian'] || $fields['dog_veterinarian_phone'] ) : ?>
				<div class="section">
					<h3 style="font-size: 12pt; margin: 3mm 0;">Veterinario di Riferimento</h3>
					<div class="info-item">
						<?php if ( $fields['dog_veterinarian'] ) : ?>
							<div><strong><?php echo esc_html( $fields['dog_veterinarian'] ); ?></strong></div>
						<?php endif; ?>
						<?php if ( $fields['dog_veterinarian_phone'] ) : ?>
							<div>Tel: <?php echo esc_html( $fields['dog_veterinarian_phone'] ); ?></div>
						<?php endif; ?>
						<?php if ( $fields['dog_veterinarian_address'] ) : ?>
							<div><?php echo nl2br( esc_html( $fields['dog_veterinarian_address'] ) ); ?></div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $fields['dog_allergies'] ) : ?>
				<div class="section">
					<h3 style="font-size: 12pt; margin: 3mm 0;">⚠️ Allergie</h3>
					<div class="emergency-box">
						<?php echo nl2br( esc_html( $fields['dog_allergies'] ) ); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $fields['dog_medical_conditions'] ) : ?>
				<div class="section">
					<h3 style="font-size: 12pt; margin: 3mm 0;">Condizioni Mediche</h3>
					<div class="info-item">
						<?php echo nl2br( esc_html( $fields['dog_medical_conditions'] ) ); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $fields['dog_medications'] ) : ?>
				<div class="section">
					<h3 style="font-size: 12pt; margin: 3mm 0;">💊 Farmaci</h3>
					<div class="info-item">
						<?php echo nl2br( esc_html( $fields['dog_medications'] ) ); ?>
					</div>
				</div>
			<?php endif; ?>

			<!-- VACCINATIONS -->
			<?php
			$vaccinations = Caniincasa_My_Dog_Calendar::get_vaccinations( $dog_id );
			if ( ! empty( $vaccinations ) ) :
				?>
				<h2>💉 Vaccinazioni</h2>
				<table>
					<thead>
						<tr>
							<th>Vaccino</th>
							<th>Data Somministrazione</th>
							<th>Prossima Data</th>
							<th>Veterinario</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $vaccinations as $vacc ) : ?>
							<tr>
								<td><?php echo esc_html( $vacc->vaccine_name ); ?></td>
								<td><?php echo date_i18n( 'd/m/Y', strtotime( $vacc->vaccine_date ) ); ?></td>
								<td><?php echo $vacc->next_date ? date_i18n( 'd/m/Y', strtotime( $vacc->next_date ) ) : '-'; ?></td>
								<td><?php echo esc_html( $vacc->veterinarian ? $vacc->veterinarian : '-' ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>

			<!-- ALIMENTAZIONE -->
			<?php if ( $fields['dog_food_type'] || $fields['dog_food_brand'] ) : ?>
				<h2>🍖 Alimentazione</h2>
				<div class="info-grid">
					<?php if ( $fields['dog_food_type_text'] ) : ?>
						<div class="info-item">
							<div class="label">Tipo</div>
							<div class="value"><?php echo esc_html( $fields['dog_food_type_text'] ); ?></div>
						</div>
					<?php endif; ?>

					<?php if ( $fields['dog_food_brand'] ) : ?>
						<div class="info-item">
							<div class="label">Marca</div>
							<div class="value"><?php echo esc_html( $fields['dog_food_brand'] ); ?></div>
						</div>
					<?php endif; ?>

					<?php if ( $fields['dog_food_amount'] ) : ?>
						<div class="info-item">
							<div class="label">Quantità Giornaliera</div>
							<div class="value"><?php echo esc_html( $fields['dog_food_amount'] ); ?>g</div>
						</div>
					<?php endif; ?>

					<?php if ( $fields['dog_meals_per_day'] ) : ?>
						<div class="info-item">
							<div class="label">Pasti al Giorno</div>
							<div class="value"><?php echo esc_html( $fields['dog_meals_per_day'] ); ?></div>
						</div>
					<?php endif; ?>
				</div>

				<?php if ( $fields['dog_diet_notes'] ) : ?>
					<div class="info-item" style="margin-top: 5mm;">
						<div class="label">Note</div>
						<div class="value"><?php echo nl2br( esc_html( $fields['dog_diet_notes'] ) ); ?></div>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<!-- EMERGENZA -->
			<?php if ( $fields['dog_emergency_contact'] || $fields['dog_emergency_phone'] ) : ?>
				<div class="emergency-box" style="margin-top: 10mm;">
					<h3 style="font-size: 14pt; margin-bottom: 3mm;">🚨 CONTATTO DI EMERGENZA</h3>
					<?php if ( $fields['dog_emergency_contact'] ) : ?>
						<div><strong>Nome:</strong> <?php echo esc_html( $fields['dog_emergency_contact'] ); ?></div>
					<?php endif; ?>
					<?php if ( $fields['dog_emergency_phone'] ) : ?>
						<div><strong>Telefono:</strong> <?php echo esc_html( $fields['dog_emergency_phone'] ); ?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="footer">
				<p>Documento generato da <strong>Caniincasa.it</strong></p>
				<p>www.caniincasa.it | Il portale dedicato al mondo dei cani</p>
			</div>
		</body>
		</html>
		<?php
	}

	/**
	 * Get all formatted fields
	 */
	private static function get_all_fields( $dog_id ) {
		$fields = array();

		// Basic fields
		$fields['dog_razza'] = get_field( 'dog_razza', $dog_id );
		$fields['dog_razza_custom'] = get_field( 'dog_razza_custom', $dog_id );
		$fields['dog_razza_name'] = $fields['dog_razza'] ? get_the_title( $fields['dog_razza'] ) : $fields['dog_razza_custom'];

		$fields['dog_birth_date'] = get_field( 'dog_birth_date', $dog_id );
		$fields['dog_gender'] = get_field( 'dog_gender', $dog_id );
		$fields['dog_neutered'] = get_field( 'dog_neutered', $dog_id );
		$fields['dog_size'] = get_field( 'dog_size', $dog_id );
		$fields['dog_weight'] = get_field( 'dog_weight', $dog_id );
		$fields['dog_color'] = get_field( 'dog_color', $dog_id );

		// Calculate age
		$age = Caniincasa_My_Dog_Post_Type::calculate_age( $fields['dog_birth_date'] );
		$fields['age_text'] = '';
		if ( $age['years'] > 0 ) {
			$fields['age_text'] = sprintf( __( '%d anni', 'caniincasa-my-dog' ), $age['years'] );
			if ( $age['months'] > 0 ) {
				$fields['age_text'] .= sprintf( __( ' e %d mesi', 'caniincasa-my-dog' ), $age['months'] );
			}
		} else {
			$fields['age_text'] = sprintf( __( '%d mesi', 'caniincasa-my-dog' ), $age['months'] );
		}

		// Format date
		if ( $fields['dog_birth_date'] ) {
			$fields['dog_birth_date'] = date_i18n( 'd/m/Y', strtotime( $fields['dog_birth_date'] ) );
		}

		// Gender text
		$fields['dog_gender_text'] = $fields['dog_gender'] === 'male' ? __( 'Maschio', 'caniincasa-my-dog' ) : __( 'Femmina', 'caniincasa-my-dog' );

		// Size text
		$size_options = array(
			'toy'    => __( 'Toy (< 5kg)', 'caniincasa-my-dog' ),
			'small'  => __( 'Piccola (5-10kg)', 'caniincasa-my-dog' ),
			'medium' => __( 'Media (10-25kg)', 'caniincasa-my-dog' ),
			'large'  => __( 'Grande (25-45kg)', 'caniincasa-my-dog' ),
			'giant'  => __( 'Gigante (> 45kg)', 'caniincasa-my-dog' ),
		);
		$fields['dog_size_text'] = isset( $size_options[ $fields['dog_size'] ] ) ? $size_options[ $fields['dog_size'] ] : '';

		// Identification
		$fields['dog_microchip'] = get_field( 'dog_microchip', $dog_id );
		$fields['dog_microchip_date'] = get_field( 'dog_microchip_date', $dog_id );
		$fields['dog_pedigree'] = get_field( 'dog_pedigree', $dog_id );
		$fields['dog_passport'] = get_field( 'dog_passport', $dog_id );

		// Health
		$fields['dog_veterinarian'] = get_field( 'dog_veterinarian', $dog_id );
		$fields['dog_veterinarian_phone'] = get_field( 'dog_veterinarian_phone', $dog_id );
		$fields['dog_veterinarian_address'] = get_field( 'dog_veterinarian_address', $dog_id );
		$fields['dog_allergies'] = get_field( 'dog_allergies', $dog_id );
		$fields['dog_medical_conditions'] = get_field( 'dog_medical_conditions', $dog_id );
		$fields['dog_medications'] = get_field( 'dog_medications', $dog_id );
		$fields['dog_insurance'] = get_field( 'dog_insurance', $dog_id );
		$fields['dog_insurance_number'] = get_field( 'dog_insurance_number', $dog_id );

		// Diet
		$fields['dog_food_type'] = get_field( 'dog_food_type', $dog_id );
		$food_type_options = array(
			'dry'      => __( 'Crocchette', 'caniincasa-my-dog' ),
			'wet'      => __( 'Umido', 'caniincasa-my-dog' ),
			'mixed'    => __( 'Misto', 'caniincasa-my-dog' ),
			'barf'     => __( 'BARF', 'caniincasa-my-dog' ),
			'homemade' => __( 'Casalinga', 'caniincasa-my-dog' ),
			'other'    => __( 'Altro', 'caniincasa-my-dog' ),
		);
		$fields['dog_food_type_text'] = isset( $food_type_options[ $fields['dog_food_type'] ] ) ? $food_type_options[ $fields['dog_food_type'] ] : '';

		$fields['dog_food_brand'] = get_field( 'dog_food_brand', $dog_id );
		$fields['dog_food_amount'] = get_field( 'dog_food_amount', $dog_id );
		$fields['dog_meals_per_day'] = get_field( 'dog_meals_per_day', $dog_id );
		$fields['dog_diet_notes'] = get_field( 'dog_diet_notes', $dog_id );

		// Behavior
		$fields['dog_temperament'] = get_field( 'dog_temperament', $dog_id );
		$fields['dog_training_level'] = get_field( 'dog_training_level', $dog_id );
		$fields['dog_behavior_notes'] = get_field( 'dog_behavior_notes', $dog_id );

		// Notes
		$fields['dog_notes'] = get_field( 'dog_notes', $dog_id );
		$fields['dog_emergency_contact'] = get_field( 'dog_emergency_contact', $dog_id );
		$fields['dog_emergency_phone'] = get_field( 'dog_emergency_phone', $dog_id );

		return $fields;
	}
}

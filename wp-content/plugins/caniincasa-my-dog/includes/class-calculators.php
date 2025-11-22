<?php
/**
 * Dog Calculators and Tools
 *
 * @package Caniincasa_My_Dog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Caniincasa_My_Dog_Calculators {

	/**
	 * Initialize
	 */
	public static function init() {
		// Shortcodes
		add_shortcode( 'dog_age_calculator', array( __CLASS__, 'age_calculator_shortcode' ) );
		add_shortcode( 'dog_weight_tracker', array( __CLASS__, 'weight_tracker_shortcode' ) );
		add_shortcode( 'dog_food_calculator', array( __CLASS__, 'food_calculator_shortcode' ) );
	}

	/**
	 * Age calculator shortcode
	 */
	public static function age_calculator_shortcode( $atts ) {
		ob_start();
		?>
		<div class="dog-calculator age-calculator">
			<h3><?php _e( 'Calcolatore Età Umana', 'caniincasa-my-dog' ); ?></h3>
			<p><?php _e( 'Scopri quanti anni ha il tuo cane in anni umani.', 'caniincasa-my-dog' ); ?></p>

			<form class="calculator-form">
				<div class="form-group">
					<label><?php _e( 'Data di Nascita', 'caniincasa-my-dog' ); ?></label>
					<input type="date" name="birth_date" required>
				</div>

				<div class="form-group">
					<label><?php _e( 'Taglia', 'caniincasa-my-dog' ); ?></label>
					<select name="size" required>
						<option value="">-- <?php _e( 'Seleziona', 'caniincasa-my-dog' ); ?> --</option>
						<option value="small"><?php _e( 'Piccola (< 10kg)', 'caniincasa-my-dog' ); ?></option>
						<option value="medium"><?php _e( 'Media (10-25kg)', 'caniincasa-my-dog' ); ?></option>
						<option value="large"><?php _e( 'Grande (25-45kg)', 'caniincasa-my-dog' ); ?></option>
						<option value="giant"><?php _e( 'Gigante (> 45kg)', 'caniincasa-my-dog' ); ?></option>
					</select>
				</div>

				<button type="submit" class="btn btn-primary"><?php _e( 'Calcola', 'caniincasa-my-dog' ); ?></button>
			</form>

			<div class="calculator-result" style="display: none;">
				<div class="result-box">
					<h4><?php _e( 'Risultato', 'caniincasa-my-dog' ); ?></h4>
					<div class="result-content">
						<p class="dog-age"></p>
						<p class="human-age"></p>
					</div>
				</div>
			</div>
		</div>

		<script>
		jQuery(document).ready(function($) {
			$('.age-calculator form').on('submit', function(e) {
				e.preventDefault();

				var birthDate = new Date($(this).find('[name="birth_date"]').val());
				var size = $(this).find('[name="size"]').val();
				var today = new Date();

				var months = (today.getFullYear() - birthDate.getFullYear()) * 12;
				months += today.getMonth() - birthDate.getMonth();

				var years = Math.floor(months / 12);
				var remainingMonths = months % 12;

				// Calculate human age
				var humanAge = 0;
				if (months <= 12) {
					humanAge = Math.round((months / 12) * 15);
				} else if (months <= 24) {
					humanAge = 15 + Math.round(((months - 12) / 12) * 9);
				} else {
					var multipliers = {
						'small': 4,
						'medium': 5,
						'large': 6,
						'giant': 7
					};
					var yearsAfterTwo = months - 24;
					humanAge = 24 + Math.round((yearsAfterTwo / 12) * (multipliers[size] || 5));
				}

				var ageText = '';
				if (years > 0) {
					ageText = years + ' <?php esc_js_e( 'anni', 'caniincasa-my-dog' ); ?>';
					if (remainingMonths > 0) {
						ageText += ' <?php esc_js_e( 'e', 'caniincasa-my-dog' ); ?> ' + remainingMonths + ' <?php esc_js_e( 'mesi', 'caniincasa-my-dog' ); ?>';
					}
				} else {
					ageText = months + ' <?php esc_js_e( 'mesi', 'caniincasa-my-dog' ); ?>';
				}

				$('.calculator-result .dog-age').html('<strong><?php esc_js_e( 'Età del cane:', 'caniincasa-my-dog' ); ?></strong> ' + ageText);
				$('.calculator-result .human-age').html('<strong><?php esc_js_e( 'Età umana:', 'caniincasa-my-dog' ); ?></strong> ' + humanAge + ' <?php esc_js_e( 'anni', 'caniincasa-my-dog' ); ?>');
				$('.calculator-result').fadeIn();
			});
		});
		</script>
		<?php
		return ob_get_clean();
	}

	/**
	 * Weight tracker shortcode
	 */
	public static function weight_tracker_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'dog_id' => 0,
		), $atts );

		$dog_id = intval( $atts['dog_id'] );

		if ( ! $dog_id || ! Caniincasa_My_Dog_Post_Type::user_can_view( $dog_id ) ) {
			return '<p>' . __( 'Cane non trovato.', 'caniincasa-my-dog' ) . '</p>';
		}

		global $wpdb;
		$weight_entries = $wpdb->get_results( $wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}dog_weight_tracker
			WHERE dog_id = %d
			ORDER BY measurement_date DESC
			LIMIT 20",
			$dog_id
		) );

		ob_start();
		?>
		<div class="dog-weight-tracker">
			<h3><?php _e( 'Tracker Peso', 'caniincasa-my-dog' ); ?></h3>

			<!-- Add weight form -->
			<div class="add-weight-form">
				<h4><?php _e( 'Aggiungi Peso', 'caniincasa-my-dog' ); ?></h4>
				<form id="add-weight-form">
					<input type="hidden" name="dog_id" value="<?php echo esc_attr( $dog_id ); ?>">

					<div class="form-row">
						<div class="form-group">
							<label><?php _e( 'Peso (kg)', 'caniincasa-my-dog' ); ?></label>
							<input type="number" name="weight" step="0.1" min="0" required>
						</div>

						<div class="form-group">
							<label><?php _e( 'Data', 'caniincasa-my-dog' ); ?></label>
							<input type="date" name="measurement_date" value="<?php echo date( 'Y-m-d' ); ?>" required>
						</div>
					</div>

					<div class="form-group">
						<label><?php _e( 'Note', 'caniincasa-my-dog' ); ?></label>
						<input type="text" name="notes">
					</div>

					<button type="submit" class="btn btn-primary"><?php _e( 'Aggiungi', 'caniincasa-my-dog' ); ?></button>
				</form>
			</div>

			<!-- Weight chart -->
			<?php if ( ! empty( $weight_entries ) ) : ?>
				<div class="weight-chart">
					<h4><?php _e( 'Storico Peso', 'caniincasa-my-dog' ); ?></h4>
					<canvas id="weight-chart" width="400" height="200"></canvas>
				</div>

				<table class="weight-table">
					<thead>
						<tr>
							<th><?php _e( 'Data', 'caniincasa-my-dog' ); ?></th>
							<th><?php _e( 'Peso', 'caniincasa-my-dog' ); ?></th>
							<th><?php _e( 'Variazione', 'caniincasa-my-dog' ); ?></th>
							<th><?php _e( 'Note', 'caniincasa-my-dog' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$previous_weight = null;
						foreach ( $weight_entries as $entry ) :
							$variation = '';
							if ( $previous_weight !== null ) {
								$diff = $entry->weight - $previous_weight;
								if ( $diff > 0 ) {
									$variation = '<span class="text-success">+' . number_format( $diff, 1 ) . ' kg</span>';
								} elseif ( $diff < 0 ) {
									$variation = '<span class="text-danger">' . number_format( $diff, 1 ) . ' kg</span>';
								} else {
									$variation = '=';
								}
							}
							$previous_weight = $entry->weight;
							?>
							<tr>
								<td><?php echo date_i18n( 'd/m/Y', strtotime( $entry->measurement_date ) ); ?></td>
								<td><strong><?php echo esc_html( $entry->weight ); ?> kg</strong></td>
								<td><?php echo $variation; ?></td>
								<td><?php echo esc_html( $entry->notes ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php else : ?>
				<p class="no-data"><?php _e( 'Nessun peso registrato.', 'caniincasa-my-dog' ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $weight_entries ) ) : ?>
			<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
			<script>
			jQuery(document).ready(function($) {
				var ctx = document.getElementById('weight-chart');
				if (ctx) {
					var data = {
						labels: [
							<?php
							foreach ( array_reverse( $weight_entries ) as $entry ) {
								echo "'" . date_i18n( 'd/m', strtotime( $entry->measurement_date ) ) . "',";
							}
							?>
						],
						datasets: [{
							label: '<?php esc_js_e( 'Peso (kg)', 'caniincasa-my-dog' ); ?>',
							data: [
								<?php
								foreach ( array_reverse( $weight_entries ) as $entry ) {
									echo $entry->weight . ',';
								}
								?>
							],
							borderColor: '#FF6B35',
							backgroundColor: 'rgba(255, 107, 53, 0.1)',
							tension: 0.4
						}]
					};

					new Chart(ctx, {
						type: 'line',
						data: data,
						options: {
							responsive: true,
							plugins: {
								legend: {
									display: true,
									position: 'top',
								}
							},
							scales: {
								y: {
									beginAtZero: false
								}
							}
						}
					});
				}
			});
			</script>
		<?php endif; ?>
		<?php
		return ob_get_clean();
	}

	/**
	 * Food calculator shortcode
	 */
	public static function food_calculator_shortcode( $atts ) {
		ob_start();
		?>
		<div class="dog-calculator food-calculator">
			<h3><?php _e( 'Calcolatore Cibo Giornaliero', 'caniincasa-my-dog' ); ?></h3>
			<p><?php _e( 'Calcola la quantità di cibo consigliata per il tuo cane.', 'caniincasa-my-dog' ); ?></p>

			<form class="calculator-form">
				<div class="form-group">
					<label><?php _e( 'Peso del cane (kg)', 'caniincasa-my-dog' ); ?></label>
					<input type="number" name="weight" step="0.1" min="1" required>
				</div>

				<div class="form-group">
					<label><?php _e( 'Livello di Attività', 'caniincasa-my-dog' ); ?></label>
					<select name="activity" required>
						<option value="">-- <?php _e( 'Seleziona', 'caniincasa-my-dog' ); ?> --</option>
						<option value="low"><?php _e( 'Bassa (poche passeggiate)', 'caniincasa-my-dog' ); ?></option>
						<option value="medium"><?php _e( 'Media (1-2 ore al giorno)', 'caniincasa-my-dog' ); ?></option>
						<option value="high"><?php _e( 'Alta (molto attivo, sport)', 'caniincasa-my-dog' ); ?></option>
					</select>
				</div>

				<div class="form-group">
					<label><?php _e( 'Tipo di Cibo', 'caniincasa-my-dog' ); ?></label>
					<select name="food_type" required>
						<option value="">-- <?php _e( 'Seleziona', 'caniincasa-my-dog' ); ?> --</option>
						<option value="dry"><?php _e( 'Crocchette (secco)', 'caniincasa-my-dog' ); ?></option>
						<option value="wet"><?php _e( 'Umido', 'caniincasa-my-dog' ); ?></option>
					</select>
				</div>

				<button type="submit" class="btn btn-primary"><?php _e( 'Calcola', 'caniincasa-my-dog' ); ?></button>
			</form>

			<div class="calculator-result" style="display: none;">
				<div class="result-box">
					<h4><?php _e( 'Quantità Consigliata', 'caniincasa-my-dog' ); ?></h4>
					<div class="result-content">
						<p class="daily-amount"></p>
						<p class="meal-amount"></p>
						<p class="note"><?php _e( 'Nota: Questa è una stima. Consulta sempre il veterinario per le esigenze specifiche del tuo cane.', 'caniincasa-my-dog' ); ?></p>
					</div>
				</div>
			</div>
		</div>

		<script>
		jQuery(document).ready(function($) {
			$('.food-calculator form').on('submit', function(e) {
				e.preventDefault();

				var weight = parseFloat($(this).find('[name="weight"]').val());
				var activity = $(this).find('[name="activity"]').val();
				var foodType = $(this).find('[name="food_type"]').val();

				// Base calculation: 2% - 3% of body weight for dry food
				var activityMultipliers = {
					'low': 0.02,
					'medium': 0.025,
					'high': 0.03
				};

				var multiplier = activityMultipliers[activity] || 0.025;
				var dailyGrams = weight * 1000 * multiplier;

				// Adjust for wet food (typically 3x more in weight)
				if (foodType === 'wet') {
					dailyGrams = dailyGrams * 2.5;
				}

				dailyGrams = Math.round(dailyGrams);
				var perMeal = Math.round(dailyGrams / 2);

				var foodTypeText = foodType === 'dry' ? '<?php esc_js_e( 'crocchette', 'caniincasa-my-dog' ); ?>' : '<?php esc_js_e( 'cibo umido', 'caniincasa-my-dog' ); ?>';

				$('.calculator-result .daily-amount').html(
					'<strong><?php esc_js_e( 'Quantità giornaliera:', 'caniincasa-my-dog' ); ?></strong> ' +
					dailyGrams + 'g <?php esc_js_e( 'di', 'caniincasa-my-dog' ); ?> ' + foodTypeText
				);

				$('.calculator-result .meal-amount').html(
					'<strong><?php esc_js_e( 'Per pasto (diviso in 2):', 'caniincasa-my-dog' ); ?></strong> ' +
					perMeal + 'g'
				);

				$('.calculator-result').fadeIn();
			});
		});
		</script>
		<?php
		return ob_get_clean();
	}
}

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

// Get ACF fields
$nome           = get_field( 'nome', $dog_id );
$razza          = get_field( 'razza', $dog_id );
$sesso          = get_field( 'sesso', $dog_id );
$data_nascita   = get_field( 'data_nascita', $dog_id );
$peso_attuale   = get_field( 'peso_attuale', $dog_id );
$microchip      = get_field( 'microchip', $dog_id );
$note           = get_field( 'note', $dog_id );
$foto           = get_field( 'foto', $dog_id );

// Calculate age if birth date exists
$age_text = '';
if ( $data_nascita ) {
	$birth_date = new DateTime( $data_nascita );
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

?>

<div class="single-dog-profile">
	<!-- Dog Header -->
	<div class="dog-profile-header">
		<div class="dog-profile-photo">
			<?php if ( $foto ) : ?>
				<img src="<?php echo esc_url( is_array( $foto ) ? $foto['url'] : $foto ); ?>" alt="<?php echo esc_attr( $nome ); ?>">
			<?php else : ?>
				<div class="dog-photo-placeholder">
					<svg width="120" height="120" viewBox="0 0 24 24" fill="none">
						<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" fill="#ccc"/>
					</svg>
				</div>
			<?php endif; ?>
		</div>

		<div class="dog-profile-info">
			<h2><?php echo esc_html( $nome ? $nome : $dog_post->post_title ); ?></h2>

			<?php if ( $razza ) : ?>
				<p class="dog-breed"><?php echo esc_html( $razza ); ?></p>
			<?php endif; ?>

			<div class="dog-actions">
				<a href="<?php echo esc_url( home_url( '/i-miei-cani/' . $dog_id . '/modifica/' ) ); ?>" class="btn btn-primary">
					<?php _e( 'Modifica Profilo', 'caniincasa-my-dog' ); ?>
				</a>
				<button class="btn btn-danger delete-dog-btn" data-dog-id="<?php echo esc_attr( $dog_id ); ?>">
					<?php _e( 'Elimina Profilo', 'caniincasa-my-dog' ); ?>
				</button>
			</div>
		</div>
	</div>

	<!-- Dog Details -->
	<div class="dog-details-grid">
		<?php if ( $sesso ) : ?>
		<div class="detail-card">
			<h3><?php _e( 'Sesso', 'caniincasa-my-dog' ); ?></h3>
			<p class="detail-value">
				<?php echo $sesso === 'maschio' ? '♂️ ' . __( 'Maschio', 'caniincasa-my-dog' ) : '♀️ ' . __( 'Femmina', 'caniincasa-my-dog' ); ?>
			</p>
		</div>
		<?php endif; ?>

		<?php if ( $data_nascita ) : ?>
		<div class="detail-card">
			<h3><?php _e( 'Età', 'caniincasa-my-dog' ); ?></h3>
			<p class="detail-value">📅 <?php echo esc_html( $age_text ); ?></p>
			<p class="detail-meta"><?php _e( 'Nato il:', 'caniincasa-my-dog' ); ?> <?php echo esc_html( date_i18n( 'd/m/Y', strtotime( $data_nascita ) ) ); ?></p>
		</div>
		<?php endif; ?>

		<?php if ( $peso_attuale ) : ?>
		<div class="detail-card">
			<h3><?php _e( 'Peso Attuale', 'caniincasa-my-dog' ); ?></h3>
			<p class="detail-value">⚖️ <?php echo esc_html( $peso_attuale ); ?> kg</p>
		</div>
		<?php endif; ?>

		<?php if ( $microchip ) : ?>
		<div class="detail-card">
			<h3><?php _e( 'Microchip', 'caniincasa-my-dog' ); ?></h3>
			<p class="detail-value">🔖 <?php echo esc_html( $microchip ); ?></p>
		</div>
		<?php endif; ?>
	</div>

	<?php if ( $note ) : ?>
	<!-- Notes Section -->
	<div class="dog-notes-section">
		<h3><?php _e( 'Note', 'caniincasa-my-dog' ); ?></h3>
		<div class="notes-content">
			<?php echo wp_kses_post( nl2br( $note ) ); ?>
		</div>
	</div>
	<?php endif; ?>
</div>

<style>
.single-dog-profile {
	max-width: 1000px;
	margin: 0 auto;
}

/* Header */
.dog-profile-header {
	display: flex;
	gap: 30px;
	margin-bottom: 40px;
	padding: 30px;
	background: white;
	border-radius: 12px;
	box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.dog-profile-photo {
	flex-shrink: 0;
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

.dog-profile-info {
	flex: 1;
}

.dog-profile-info h2 {
	margin: 0 0 10px;
	font-size: 32px;
	color: #306587;
}

.dog-breed {
	color: #666;
	font-size: 18px;
	margin: 0 0 20px;
}

.dog-actions {
	display: flex;
	gap: 10px;
	margin-top: 20px;
}

.btn-danger {
	background: #dc3545;
	color: white;
}

.btn-danger:hover {
	background: #c82333;
}

/* Details Grid */
.dog-details-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
	gap: 20px;
	margin-bottom: 30px;
}

.detail-card {
	background: white;
	padding: 20px;
	border-radius: 12px;
	box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.detail-card h3 {
	margin: 0 0 10px;
	font-size: 14px;
	color: #666;
	text-transform: uppercase;
	font-weight: 600;
}

.detail-value {
	margin: 0 0 5px;
	font-size: 20px;
	color: #333;
	font-weight: 600;
}

.detail-meta {
	margin: 0;
	font-size: 13px;
	color: #999;
}

/* Notes Section */
.dog-notes-section {
	background: white;
	padding: 30px;
	border-radius: 12px;
	box-shadow: 0 2px 8px rgba(0,0,0,0.1);
	margin-bottom: 30px;
}

.dog-notes-section h3 {
	margin: 0 0 15px;
	font-size: 20px;
	color: #306587;
}

.notes-content {
	color: #333;
	line-height: 1.6;
}

/* Responsive */
@media (max-width: 768px) {
	.dog-profile-header {
		flex-direction: column;
		text-align: center;
	}

	.dog-profile-photo img,
	.dog-photo-placeholder {
		width: 150px;
		height: 150px;
		margin: 0 auto;
	}

	.dog-actions {
		flex-direction: column;
	}

	.btn {
		width: 100%;
		text-align: center;
	}

	.dog-details-grid {
		grid-template-columns: 1fr;
	}
}
</style>

<?php
/**
 * Dashboard - Dogs List Template
 *
 * @package Caniincasa_My_Dog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="my-dogs-dashboard">
	<div class="dashboard-header">
		<h1><?php _e( 'I Miei Cani', 'caniincasa-my-dog' ); ?></h1>
		<a href="<?php echo home_url( '/i-miei-cani/aggiungi/' ); ?>" class="btn btn-primary">
			<?php _e( '+ Aggiungi Nuovo Cane', 'caniincasa-my-dog' ); ?>
		</a>
	</div>

	<?php if ( ! empty( $dogs ) ) : ?>
		<div class="dogs-grid">
			<?php foreach ( $dogs as $dog ) : ?>
				<?php Caniincasa_My_Dog_Dashboard::render_dog_card( $dog ); ?>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<div class="no-dogs" style="text-align: center; padding: 60px 20px;">
			<p style="font-size: 48px; margin: 0;">🐕</p>
			<h2><?php _e( 'Non hai ancora aggiunto nessun cane', 'caniincasa-my-dog' ); ?></h2>
			<p><?php _e( 'Inizia aggiungendo il primo profilo del tuo amico a quattro zampe!', 'caniincasa-my-dog' ); ?></p>
			<a href="<?php echo home_url( '/i-miei-cani/aggiungi/' ); ?>" class="btn btn-primary" style="margin-top: 20px;">
				<?php _e( 'Aggiungi Primo Cane', 'caniincasa-my-dog' ); ?>
			</a>
		</div>
	<?php endif; ?>
</div>

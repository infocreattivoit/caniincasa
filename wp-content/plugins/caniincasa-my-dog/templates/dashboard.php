<?php
/**
 * My Dogs Dashboard Template
 *
 * @package Caniincasa_My_Dog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get current action and dog ID
$action = get_query_var( 'my_dogs_dashboard', 'list' );
$dog_id = get_query_var( 'dog_id', 0 );

// Security check: user must be logged in
if ( ! is_user_logged_in() ) {
	wp_redirect( wp_login_url( home_url( '/i-miei-cani/' ) ) );
	exit;
}

get_header();
?>

<div class="my-dogs-dashboard-page">
	<div class="container">
		<div class="dashboard-header">
			<h1><?php _e( 'I Miei Cani', 'caniincasa-my-dog' ); ?></h1>

			<?php if ( $action === 'list' || $action === '1' ) : ?>
				<a href="<?php echo esc_url( home_url( '/i-miei-cani/aggiungi/' ) ); ?>" class="btn btn-primary">
					<?php _e( '+ Aggiungi Nuovo Cane', 'caniincasa-my-dog' ); ?>
				</a>
			<?php elseif ( $action === 'add' || $action === 'edit' ) : ?>
				<a href="<?php echo esc_url( home_url( '/i-miei-cani/' ) ); ?>" class="btn btn-secondary">
					<?php _e( '← Torna alla Lista', 'caniincasa-my-dog' ); ?>
				</a>
			<?php endif; ?>
		</div>

		<div class="dashboard-content">
			<?php
			switch ( $action ) {
				case 'add':
					// Add new dog form
					include CANIINCASA_MY_DOG_PATH . 'templates/dog-form.php';
					break;

				case 'edit':
					// Edit dog form
					if ( $dog_id && Caniincasa_My_Dog_Post_Type::user_can_view( $dog_id ) ) {
						$dog_post = get_post( $dog_id );
						if ( $dog_post ) {
							include CANIINCASA_MY_DOG_PATH . 'templates/dog-form.php';
						} else {
							echo '<div class="notice notice-error"><p>' . __( 'Cane non trovato.', 'caniincasa-my-dog' ) . '</p></div>';
						}
					} else {
						echo '<div class="notice notice-error"><p>' . __( 'Non hai i permessi per modificare questo cane.', 'caniincasa-my-dog' ) . '</p></div>';
					}
					break;

				case 'view':
					// View single dog
					if ( $dog_id && Caniincasa_My_Dog_Post_Type::user_can_view( $dog_id ) ) {
						include CANIINCASA_MY_DOG_PATH . 'templates/single-dog.php';
					} else {
						echo '<div class="notice notice-error"><p>' . __( 'Cane non trovato.', 'caniincasa-my-dog' ) . '</p></div>';
					}
					break;

				case 'list':
				case '1':
				default:
					// List all user dogs
					$dogs = Caniincasa_My_Dog_Post_Type::get_user_dogs();
					include CANIINCASA_MY_DOG_PATH . 'templates/dashboard-list.php';
					break;
			}
			?>
		</div>
	</div>
</div>

<style>
.my-dogs-dashboard-page {
	padding: 40px 0;
	min-height: 60vh;
}
.dashboard-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 30px;
	padding-bottom: 20px;
	border-bottom: 2px solid #e9ecef;
}
.dashboard-header h1 {
	margin: 0;
	font-size: 32px;
	color: #306587;
}
.btn {
	display: inline-block;
	padding: 12px 24px;
	border-radius: 50px;
	text-decoration: none;
	font-weight: 600;
	transition: all 0.3s;
	border: none;
	cursor: pointer;
}
.btn-primary {
	background: #ff850c;
	color: white;
}
.btn-primary:hover {
	background: #e67609;
	transform: translateY(-2px);
	box-shadow: 0 4px 12px rgba(255, 133, 12, 0.3);
}
.btn-secondary {
	background: #306587;
	color: white;
}
.btn-secondary:hover {
	background: #254a6a;
	transform: translateY(-2px);
	box-shadow: 0 4px 12px rgba(48, 101, 135, 0.3);
}
.notice {
	padding: 15px 20px;
	border-radius: 8px;
	margin: 20px 0;
}
.notice-error {
	background: #fee;
	border-left: 4px solid #d32f2f;
	color: #c62828;
}
.notice-success {
	background: #e8f5e9;
	border-left: 4px solid #4caf50;
	color: #2e7d32;
}
</style>

<?php get_footer(); ?>

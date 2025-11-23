<?php
/**
 * Newsletter Block Pre-Footer
 *
 * @package Caniincasa_My_Dog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Caniincasa_My_Dog_Newsletter_Block {

	/**
	 * Initialize
	 */
	public static function init() {
		// TEMPORARILY DISABLED - Newsletter block was breaking footer rendering
		// The inline jQuery script runs BEFORE jQuery is loaded, causing page truncation
		/*
		// Add newsletter block before footer
		add_action( 'caniincasa_before_footer', array( __CLASS__, 'render_newsletter_block' ) );

		// Shortcode
		add_shortcode( 'newsletter_signup', array( __CLASS__, 'shortcode' ) );
		*/
	}

	/**
	 * Render newsletter block
	 */
	public static function render_newsletter_block() {
		// Check if enabled
		if ( ! get_option( 'caniincasa_my_dog_newsletter_enabled', '1' ) ) {
			return;
		}

		// Don't show on certain pages
		if ( is_admin() || is_404() ) {
			return;
		}

		self::output_newsletter_html();
	}

	/**
	 * Output newsletter HTML
	 */
	private static function output_newsletter_html() {
		?>
		<div class="caniincasa-newsletter-block">
			<div class="newsletter-container">
				<div class="newsletter-content">
					<div class="newsletter-icon">
						📧
					</div>
					<div class="newsletter-text">
						<h3><?php _e( 'Rimani Aggiornato!', 'caniincasa-my-dog' ); ?></h3>
						<p><?php _e( 'Iscriviti alla nostra newsletter per ricevere consigli, guide e novità sul mondo dei cani.', 'caniincasa-my-dog' ); ?></p>
					</div>
					<div class="newsletter-form">
						<form id="newsletter-signup-form" class="newsletter-form-inline">
							<?php wp_nonce_field( 'newsletter_nonce', 'newsletter_nonce' ); ?>
							<div class="form-group">
								<input
									type="email"
									name="email"
									placeholder="<?php esc_attr_e( 'La tua email', 'caniincasa-my-dog' ); ?>"
									required
									class="newsletter-input"
								>
								<button type="submit" class="newsletter-submit">
									<?php _e( 'Iscriviti', 'caniincasa-my-dog' ); ?>
								</button>
							</div>
							<div class="newsletter-privacy">
								<label>
									<input type="checkbox" name="privacy" required>
									<?php
									printf(
										__( 'Accetto la %sPrivacy Policy%s', 'caniincasa-my-dog' ),
										'<a href="' . esc_url( home_url( '/privacy-policy/' ) ) . '" target="_blank">',
										'</a>'
									);
									?>
								</label>
							</div>
							<div class="newsletter-message"></div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<style>
			.caniincasa-newsletter-block {
				background: linear-gradient(135deg, #306587 0%, #ff850c 100%);
				color: white;
				padding: 50px 20px;
				margin: 0;
			}
			.newsletter-container {
				max-width: 1200px;
				margin: 0 auto;
			}
			.newsletter-content {
				display: grid;
				grid-template-columns: auto 1fr auto;
				gap: 30px;
				align-items: center;
			}
			.newsletter-icon {
				font-size: 60px;
			}
			.newsletter-text h3 {
				margin: 0 0 10px;
				font-size: 28px;
				color: white;
			}
			.newsletter-text p {
				margin: 0;
				opacity: 0.9;
				font-size: 16px;
			}
			.newsletter-form {
				min-width: 350px;
			}
			.newsletter-form-inline .form-group {
				display: flex;
				gap: 10px;
			}
			.newsletter-input {
				flex: 1;
				padding: 15px 20px;
				border: none;
				border-radius: 50px;
				font-size: 16px;
			}
			.newsletter-submit {
				padding: 15px 35px;
				background: #ff850c;
				color: white;
				border: none;
				border-radius: 50px;
				font-size: 16px;
				font-weight: bold;
				cursor: pointer;
				transition: all 0.3s;
				white-space: nowrap;
			}
			.newsletter-submit:hover {
				background: #e67609;
				transform: translateY(-2px);
				box-shadow: 0 5px 15px rgba(0,0,0,0.2);
			}
			.newsletter-privacy {
				margin-top: 10px;
				font-size: 12px;
				opacity: 0.8;
			}
			.newsletter-privacy a {
				color: white;
				text-decoration: underline;
			}
			.newsletter-message {
				margin-top: 10px;
				padding: 10px;
				border-radius: 5px;
				display: none;
			}
			.newsletter-message.success {
				background: rgba(76, 175, 80, 0.2);
				border: 1px solid rgba(76, 175, 80, 0.5);
				color: #e8f5e9;
				display: block;
			}
			.newsletter-message.error {
				background: rgba(244, 67, 54, 0.2);
				border: 1px solid rgba(244, 67, 54, 0.5);
				color: #ffebee;
				display: block;
			}

			@media (max-width: 992px) {
				.newsletter-content {
					grid-template-columns: 1fr;
					text-align: center;
				}
				.newsletter-form {
					min-width: auto;
					width: 100%;
					max-width: 500px;
					margin: 0 auto;
				}
			}

			@media (max-width: 576px) {
				.newsletter-form-inline .form-group {
					flex-direction: column;
				}
				.newsletter-submit {
					width: 100%;
				}
			}
		</style>

		<script>
		jQuery(document).ready(function($) {
			$('#newsletter-signup-form').on('submit', function(e) {
				e.preventDefault();

				var $form = $(this);
				var $message = $form.find('.newsletter-message');
				var $submit = $form.find('.newsletter-submit');

				$submit.prop('disabled', true).text('<?php esc_js_e( 'Invio...', 'caniincasa-my-dog' ); ?>');
				$message.removeClass('success error').hide();

				$.ajax({
					url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
					type: 'POST',
					data: {
						action: 'newsletter_signup',
						nonce: $form.find('[name="newsletter_nonce"]').val(),
						email: $form.find('[name="email"]').val()
					},
					success: function(response) {
						if (response.success) {
							$message.addClass('success').text(response.data.message).fadeIn();
							$form[0].reset();
						} else {
							$message.addClass('error').text(response.data.message).fadeIn();
						}
					},
					error: function() {
						$message.addClass('error').text('<?php esc_js_e( 'Errore. Riprova.', 'caniincasa-my-dog' ); ?>').fadeIn();
					},
					complete: function() {
						$submit.prop('disabled', false).text('<?php esc_js_e( 'Iscriviti', 'caniincasa-my-dog' ); ?>');
					}
				});
			});
		});
		</script>
		<?php
	}

	/**
	 * Shortcode
	 */
	public static function shortcode( $atts ) {
		ob_start();
		self::output_newsletter_html();
		return ob_get_clean();
	}
}

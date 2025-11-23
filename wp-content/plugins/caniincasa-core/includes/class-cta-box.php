<?php
/**
 * CTA Box Generator
 *
 * Shortcode per generare box CTA personalizzabili
 *
 * @package Caniincasa_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Caniincasa_CTA_Box {

	/**
	 * Initialize
	 */
	public static function init() {
		add_shortcode( 'cta_box', array( __CLASS__, 'cta_box_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_styles' ) );
	}

	/**
	 * Enqueue CTA Box styles
	 */
	public static function enqueue_styles() {
		wp_add_inline_style( 'caniincasa-style', self::get_inline_css() );
	}

	/**
	 * Get inline CSS for CTA boxes
	 */
	private static function get_inline_css() {
		return '
		.caniincasa-cta-box {
			background: linear-gradient(135deg, #306587 0%, #ff850c 100%);
			border-radius: 16px;
			padding: 40px 30px;
			text-align: center;
			color: white;
			margin: 40px 0;
			box-shadow: 0 8px 24px rgba(48, 101, 135, 0.2);
		}

		.caniincasa-cta-box.gradient-blue-orange {
			background: linear-gradient(135deg, #306587 0%, #ff850c 100%);
		}

		.caniincasa-cta-box.gradient-purple-blue {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		}

		.caniincasa-cta-box.gradient-green-blue {
			background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
		}

		.caniincasa-cta-box.gradient-orange-red {
			background: linear-gradient(135deg, #ff6a00 0%, #ee0979 100%);
		}

		.caniincasa-cta-box.solid-blue {
			background: #306587;
		}

		.caniincasa-cta-box.solid-orange {
			background: #ff850c;
		}

		.cta-box-icon {
			font-size: 48px;
			margin-bottom: 20px;
			display: block;
		}

		.cta-box-title {
			font-size: 28px;
			font-weight: 700;
			margin: 0 0 15px 0;
			color: white;
			line-height: 1.3;
		}

		.cta-box-subtitle {
			font-size: 16px;
			margin: 0 0 30px 0;
			color: rgba(255, 255, 255, 0.95);
			line-height: 1.6;
			max-width: 600px;
			margin-left: auto;
			margin-right: auto;
		}

		.cta-box-button {
			display: inline-block;
			background: white;
			color: #306587;
			padding: 14px 36px;
			border-radius: 50px;
			text-decoration: none;
			font-weight: 600;
			font-size: 16px;
			transition: all 0.3s ease;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
		}

		.cta-box-button:hover {
			transform: translateY(-2px);
			box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
			text-decoration: none;
			color: #ff850c;
		}

		.cta-box-features {
			display: flex;
			justify-content: center;
			gap: 30px;
			margin: 25px 0 0 0;
			flex-wrap: wrap;
		}

		.cta-box-feature {
			font-size: 14px;
			color: rgba(255, 255, 255, 0.9);
			display: flex;
			align-items: center;
			gap: 8px;
		}

		.cta-box-feature::before {
			content: "✓";
			display: inline-block;
			width: 20px;
			height: 20px;
			background: rgba(255, 255, 255, 0.2);
			border-radius: 50%;
			text-align: center;
			line-height: 20px;
			font-weight: bold;
		}

		@media (max-width: 768px) {
			.caniincasa-cta-box {
				padding: 30px 20px;
			}

			.cta-box-title {
				font-size: 24px;
			}

			.cta-box-subtitle {
				font-size: 15px;
			}

			.cta-box-features {
				flex-direction: column;
				gap: 15px;
			}
		}
		';
	}

	/**
	 * CTA Box shortcode
	 *
	 * Uso: [cta_box title="Titolo" subtitle="Sottotitolo" button_text="Clicca qui" button_link="/link" icon="🐾"]
	 *
	 * Parametri:
	 * - title: Titolo principale (obbligatorio)
	 * - subtitle: Testo descrittivo sotto il titolo (opzionale)
	 * - button_text: Testo del pulsante (obbligatorio)
	 * - button_link: URL del pulsante (obbligatorio)
	 * - icon: Emoji o icona da mostrare sopra (opzionale, default: 🐾)
	 * - style: Stile gradiente (gradient-blue-orange, gradient-purple-blue, gradient-green-blue, gradient-orange-red, solid-blue, solid-orange)
	 * - features: Lista di caratteristiche separate da pipe | (opzionale)
	 */
	public static function cta_box_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'title'        => '',
			'subtitle'     => '',
			'button_text'  => '',
			'button_link'  => '',
			'icon'         => '🐾',
			'style'        => 'gradient-blue-orange',
			'features'     => '',
		), $atts );

		// Validazione parametri obbligatori
		if ( empty( $atts['title'] ) || empty( $atts['button_text'] ) || empty( $atts['button_link'] ) ) {
			return '<p style="color: red;">CTA Box: parametri obbligatori mancanti (title, button_text, button_link)</p>';
		}

		// Sanitize
		$title       = esc_html( $atts['title'] );
		$subtitle    = esc_html( $atts['subtitle'] );
		$button_text = esc_html( $atts['button_text'] );
		$button_link = esc_url( $atts['button_link'] );
		$icon        = $atts['icon']; // Emoji
		$style       = sanitize_html_class( $atts['style'] );
		$features    = $atts['features'];

		// Parse features
		$features_array = array();
		if ( ! empty( $features ) ) {
			$features_array = array_map( 'trim', explode( '|', $features ) );
		}

		ob_start();
		?>
		<div class="caniincasa-cta-box <?php echo esc_attr( $style ); ?>">
			<?php if ( ! empty( $icon ) ) : ?>
				<span class="cta-box-icon"><?php echo $icon; ?></span>
			<?php endif; ?>

			<h2 class="cta-box-title"><?php echo $title; ?></h2>

			<?php if ( ! empty( $subtitle ) ) : ?>
				<p class="cta-box-subtitle"><?php echo $subtitle; ?></p>
			<?php endif; ?>

			<a href="<?php echo $button_link; ?>" class="cta-box-button">
				<?php echo $button_text; ?>
			</a>

			<?php if ( ! empty( $features_array ) ) : ?>
				<div class="cta-box-features">
					<?php foreach ( $features_array as $feature ) : ?>
						<div class="cta-box-feature"><?php echo esc_html( $feature ); ?></div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}
}

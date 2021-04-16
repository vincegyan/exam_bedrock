<?php
/**
 * WP5 Default: Customizer
 *
 * @package WordPress
 * @subpackage WP5_Default
 * @since 1.0.0
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wp5default_customize_register( $wp_customize ) {
	// $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	// $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	// $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	/**
	 * Extends controls class to add textarea with description
	 */
	class wp5default_Customize_Textarea_Control extends WP_Customize_Control {
		public $type = 'textarea';
		public $description = '';
		public function render_content() { ?>

		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<div class="control-description"><?php echo esc_html( $this->description ); ?></div>
			<textarea rows="5" style="width:98%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
		</label>

		<?php }
	}

	/** ===============
	 * Extends controls class to add descriptions to text input controls
	 */
	class wp5default_Customize_Text_Control extends WP_Customize_Control {
		public $type = 'customtext';
		public $description = '';
		public function render_content() { ?>

		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<div class="control-description"><?php echo esc_html( $this->description ); ?></div>
			<input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
		</label>

		<?php }
	}

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'wp5default_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'wp5default_customize_partial_blogdescription',
			)
		);
	}

	/**
	 * Site Title (Logo) & Tagline
	 */
	$wp_customize->get_section( 'title_tagline' )->title = __( 'Site Identity', 'wp5default' );
	$wp_customize->get_section( 'title_tagline' )->priority = 10;

	// site title
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_control( 'blogname' )->priority = 20;

	$wp_customize->selective_refresh->add_partial( 'blogname', array(
		'selector'			=> '.site-title a',
		'render_callback'	=> 'wp5default_customize_partial_blogname',
	) );

	// site tagline
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	$wp_customize->get_control( 'blogdescription' )->priority = 30;

	$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
		'selector'			=> '.site-description',
		'render_callback'	=> 'wp5default_customize_partial_blogdescription',
	) );

	// hide the tagline?
	$wp_customize->add_setting( 'display_title_tagline', array(
		'default'			=> 1,
		'sanitize_callback'	=> 'wp5default_sanitize_checkbox'
	) );
	$wp_customize->add_control( 'display_title_tagline', array(
		'label'		=> __( 'Display Site Title and Tagline', 'wp5default' ),
		'section'	=> 'title_tagline',
		'priority'	=> 40,
		'type'		=> 'checkbox',
	) );

	/**
	 * Theme Options
	 */
	$wp_customize->add_panel( 'theme_options', array(
		'priority'		=> 30,
		'capability'	=> 'edit_theme_options',
		'title'			=> __('Theme Options', 'wp5default'),
		// 'description'	=> __('Customize the content on your website.', 'wp5default'),
	));

	/* General Options */
	$wp_customize->add_section( 'general_section', array(
		'title'			=> __( 'General Options', 'wp5default' ),
		'description'	=> 'Adjust the display of general options on your website.',
		'panel' 		=> 'theme_options',
		'priority'		=> 10,
	) );

	// phone number
	$wp_customize->add_setting( 'phone', array(
		'default' => null,
		'sanitize_callback' => 'wp5default_sanitize_text'
	) );
	$wp_customize->add_control( 'phone', array(
		'label'		=> __( 'Phone Number', 'wp5default' ),
		'section'	=> 'general_section',
		'settings'	=> 'phone',
		'priority'	=> 10,
	) );

	// email address
	$wp_customize->add_setting( 'email', array(
		'default' => null,
		'sanitize_callback' => 'wp5default_sanitize_text'
	) );
	$wp_customize->add_control( 'email', array(
		'label'		=> __( 'Email Address', 'wp5default' ),
		'section'	=> 'general_section',
		'settings'	=> 'email',
		'priority'	=> 20,
	) );

	// location address
	$wp_customize->add_setting( 'address', array(
		'default' => null,
		'sanitize_callback' => 'wp5default_sanitize_text'
	) );
	$wp_customize->add_control( 'address', array(
		'label'		=> __( 'Location Address', 'wp5default' ),
		'section'	=> 'general_section',
		'settings'	=> 'address',
		'priority'	=> 30,
	) );

	/* Banner Options */
	$wp_customize->add_section( 'banner_section', array(
		'title'			=> __( 'Banner Options', 'wp5default' ),
		'description'	=> 'Add Banner Image or Image Slider and Banner Text',
		'panel' 		=> 'theme_options',
		'priority'		=> 20,
	) );

	// banner image uploader
	$wp_customize->add_setting( 'banner_image', array( 'default' => null ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'banner_image', array(
		'label'		=> __( 'Banner Image', 'wp5default' ),
		'section'	=> 'banner_section',
		'settings'	=> 'banner_image',
		'priority'	=> 10
	) ) );

	// banner slider shortcode
	$wp_customize->add_setting( 'banner_slider', array(
		'default' => null,
		'sanitize_callback' => 'wp5default_sanitize_text'
	) );
	$wp_customize->add_control( 'banner_slider', array(
		'label'		=> __( 'Banner Slider Shortcode', 'wp5default' ),
		'description' 	=> __( 'Add Banner SLider Shortcode here to replace banner image.' ),
		'section'	=> 'banner_section',
		'settings'	=> 'banner_slider',
		'priority'	=> 30,
	) );

	/* Footer Options */
	$wp_customize->add_section( 'footer_section', array(
		'title'			=> __( 'Footer Options', 'wp5default' ),
		'description'	=> 'Adjust the display of footer options on your website.',
		'panel' 		=> 'theme_options',
		'priority'		=> 20,
	) );

	// footer logo uploader
	$wp_customize->add_setting( 'footer_logo', array( 'default' => null ) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'footer_logo', array(
		'label'		=> __( 'Footer Logo', 'wp5default' ),
		'section'	=> 'footer_section',
		'settings'	=> 'footer_logo',
		'priority'	=> 10
	) ) );

	// copyright
	$wp_customize->add_setting( 'copyright', array(
		'default'			=> null,
		'sanitize_callback'	=> 'wp5default_sanitize_textarea',
		'transport'			=> 'postMessage',
	) );

	$wp_customize->add_control( new wp5default_Customize_Textarea_Control( $wp_customize, 'copyright', array(
		'label'			=> __( 'Copyright', 'wp5default' ),
		'section'		=> 'footer_section',
		'priority'		=> 100,
		// 'description'	=> __( 'Displays tagline, site title, copyright, and year by default. Allowed tags: <img>, <a>, <div>, <span>, <blockquote>, <p>, <em>, <strong>, <form>, <input>, <br>, <s>, <i>, <b>', 'wp5default' ),
	) ) );

	$wp_customize->selective_refresh->add_partial( 'copyright', array(
		'selector'			=> '.copyright',
		'render_callback'	=> 'wp5default_customize_partial_copyright',
	) );

	/**
	 * Primary color.
	 */
	$wp_customize->add_setting(
		'primary_color',
		array(
			'default'           => 'default',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'wp5default_sanitize_color_option',
		)
	);

	$wp_customize->add_control(
		'primary_color',
		array(
			'type'     => 'radio',
			'label'    => __( 'Primary Color', 'wp5default' ),
			'choices'  => array(
				'default'  => _x( 'Default', 'primary color', 'wp5default' ),
				'custom' => _x( 'Custom', 'primary color', 'wp5default' ),
			),
			'section'  => 'colors',
			'priority' => 5,
		)
	);

	// Add primary color hue setting and control.
	$wp_customize->add_setting(
		'primary_color_hue',
		array(
			'default'           => 199,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'primary_color_hue',
			array(
				'description' => __( 'Apply a custom color for buttons, links, featured images, etc.', 'wp5default' ),
				'section'     => 'colors',
				'mode'        => 'hue',
			)
		)
	);

	// Add image filter setting and control.
	$wp_customize->add_setting(
		'image_filter',
		array(
			'default'           => 1,
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'image_filter',
		array(
			'label'   => __( 'Apply a filter to featured images using the primary color', 'wp5default' ),
			'section' => 'colors',
			'type'    => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'wp5default_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function wp5default_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function wp5default_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Bind JS handlers to instantly live-preview changes.
 */
function wp5default_customize_preview_js() {
	wp_enqueue_script( 'wp5default-customize-preview', get_theme_file_uri( '/js/customize-preview.js' ), array( 'customize-preview' ), '20181108', true );
}
add_action( 'customize_preview_init', 'wp5default_customize_preview_js' );

/**
 * Load dynamic logic for the customizer controls area.
 */
function wp5default_panels_js() {
	wp_enqueue_script( 'wp5default-customize-controls', get_theme_file_uri( '/js/customize-controls.js' ), array(), '20181031', true );
}
add_action( 'customize_controls_enqueue_scripts', 'wp5default_panels_js' );

/**
 * Sanitize custom color choice.
 *
 * @param string $choice Whether image filter is active.
 *
 * @return string
 */
function wp5default_sanitize_color_option( $choice ) {
	$valid = array(
		'default',
		'custom',
	);

	if ( in_array( $choice, $valid, true ) ) {
		return $choice;
	}

	return 'default';
}

/**
 * Sanitize checkbox options
 */
function wp5default_sanitize_checkbox( $input ) {
	if ( $input == 1 ) :
		return 1;
	else :
		return 0;
	endif;
}

/**
 * Sanitize text input
 */
function wp5default_sanitize_text( $input ) {
	return strip_tags( stripslashes( $input ) );
}

/**
 * Sanitize textarea
 */
function wp5default_sanitize_textarea( $input ) {
	$allowed = array(
		's'			=> array(),
		'br'		=> array(),
		'em'		=> array(),
		'i'			=> array(),
		'strong'	=> array(),
		'b'			=> array(),
		'a'			=> array(
			'href'			=> array(),
			'title'			=> array(),
			'class'			=> array(),
			'id'			=> array(),
			'style'			=> array(),
		),
		'form'		=> array(
			'id'			=> array(),
			'class'			=> array(),
			'action'		=> array(),
			'method'		=> array(),
			'autocomplete'	=> array(),
			'style'			=> array(),
		),
		'input'		=> array(
			'type'			=> array(),
			'name'			=> array(),
			'class' 		=> array(),
			'id'			=> array(),
			'value'			=> array(),
			'placeholder'	=> array(),
			'tabindex'		=> array(),
			'style'			=> array(),
		),
		'img'		=> array(
			'src'			=> array(),
			'alt'			=> array(),
			'class'			=> array(),
			'id'			=> array(),
			'style'			=> array(),
			'height'		=> array(),
			'width'			=> array(),
		),
		'span'		=> array(
			'class'			=> array(),
			'id'			=> array(),
			'style'			=> array(),
		),
		'p'			=> array(
			'class'			=> array(),
			'id'			=> array(),
			'style'			=> array(),
		),
		'div'		=> array(
			'class'			=> array(),
			'id'			=> array(),
			'style'			=> array(),
		),
		'blockquote' => array(
			'cite'			=> array(),
			'class'			=> array(),
			'id'			=> array(),
			'style'			=> array(),
		),
	);
	return wp_kses( $input, $allowed );
}

/**
 * Sanitize dropdown pages
 */
function wp5default_sanitize_dropdown_pages( $page_id, $setting ) {
  // Ensure $input is an absolute integer.
  $page_id = absint( $page_id );

  // If $page_id is an ID of a published page, return it; otherwise, return the default.
  return ( 'publish' == get_post_status( $page_id ) ? $page_id : $setting->default );
}

// Check if option exists
function checkoption( $optname ){
	if ( empty( $optname ) || $optname == '' )
		return false;

	// $chkoptions = get_option( 'custom_theme_options' );
	$chkoptions = get_theme_mods();
	if ( isset( $chkoptions[$optname] ) && $chkoptions[$optname] != '' )
		return true;
}

/**
 * Displays the specific custom option with parameters
 *
 * var   	 Name of the custom option (e.g. ctologourl)
 * type    text/link/image (Default: text)
 * text    Link text/Image Alt text
 * target    Link target
 * class    CSS class for the output tag
 * wrapper    Wrapper for the output (Choices: p,div,li,span)
 * wclass    CSS class for the wrapper tag
 *
 */
function wp5default_display_option_func( $atts ){
	$atts = shortcode_atts( array(
		'var' => '',
		'type' => 'text',
		'text' => '',
		'target' => '',
		'class' => '',
		'wrapper' => '',
		'wclass' => '',
		'link_type' => '',
		'icon' => '',
		'icon_position' => ''
	), $atts, 'wp5default_option' );

	// $theme_options = get_option( 'custom_theme_options' );
	$theme_options = get_theme_mods();
	$resultString = '';

	if ( $atts['var'] != '' ) {
		if ( !checkoption( $atts['var'] ) ) {
			return sprintf('<div class="alert alert-danger"><strong>%s</strong></div>', 'Option does not exist or option is empty.');
		}

		// $resultString = ( (isset($theme_options[$atts['var']]) && $theme_options[$atts['var']] != '') ? $theme_options[$atts['var']] : '' );
		$resultString = $theme_options[$atts['var']];
		$tagClass = '';
		$tagTarget = '';
		$wrapperClass = '';

		// Check if result have [YEAR]
		if ( strpos( $resultString, '[YEAR]' ) ) {
			$resultString = str_replace('[YEAR]', date('Y'), $resultString);
		}

		// Build tag class string
		if ( $atts['class'] != '' )
			$tagClass = ' class="' . $atts['class'] . '"';
		// Build target string
		if ( $atts['target'] != '' )
			$tagTarget = ' target="' . $atts['target'] . '"';

		if ( $atts['type'] == 'link' ){
			$linkUrl = $resultString;
			$icon = '';
			if ( $atts['link_type'] ) {
				switch ( $atts['link_type'] ) {
					case 'email':
						$linkUrl = 'mailto:' . $linkUrl;
						break;

					case 'phone':
						$linkUrl = 'tel:' . $linkUrl;
						break;

					case 'page-link':
						$linkUrl = get_permalink( $linkUrl );
						break;

					default:
						$linkUrl = '#';
						break;
				}
			}

			$resultString = '<a href="' . $linkUrl . '"' . $tagClass . $tagTarget . '>' . (($atts['text'] != '') ? $atts['text']  : $resultString) . '</a>';

			if ( $atts['icon'] ) {
				$value = explode('|', $atts['icon']); //--> array([0]=>'fa',[1]=>'fa-inbox');
				$font = $value[0];
				$icon = $value[1];
				$icon = '<i class="'.$font.' '.$icon.'"></i>';
				$resultString = $atts['icon_position'] !== 'before' ? $resultString.$icon : $icon.$resultString;
			}
		} elseif ( $atts['type'] == 'image' ) {
			$resultString = '<img src="' . $resultString . '" alt="' . $atts['text'] . '"' . $tagClass . '>';
		}

	// Uncomment to convert newline to <br> in textarea output
	// $resultString = nl2br($resultString);

		// Build wrapper class string
		if($atts['wclass'] != '')
			$wrapperClass = ' class="' . $atts['wclass'] . '"';

		if ( $atts['wrapper'] ) {
			$wrapper = $atts['wrapper'];
			$resultString = "<{$wrapper} {$wrapperClass}> {$resultString} </{$wrapper}>";
		}
	}

	// Return output
	return $resultString;
}
add_shortcode( 'wp5default_option', 'wp5default_display_option_func' );
<?
/**
 * voicingpoverty functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package voicingpoverty
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'voicingpoverty_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function voicingpoverty_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on voicingpoverty, use a find and replace
		 * to change 'voicingpoverty' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'voicingpoverty', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'voicingpoverty' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'voicingpoverty_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'voicingpoverty_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function voicingpoverty_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'voicingpoverty_content_width', 640 );
}
add_action( 'after_setup_theme', 'voicingpoverty_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function voicingpoverty_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'voicingpoverty' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'voicingpoverty' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'voicingpoverty_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function voicingpoverty_scripts() {
	wp_enqueue_style( 'voicingpoverty-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'voicingpoverty-style', 'rtl', 'replace' );

	wp_enqueue_script( 'voicingpoverty-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'custom-touchScreen', get_template_directory_uri() . '/js/custom-touchScreen.js', array(), '', true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if( is_front_page() )
		wp_enqueue_script( 'custom-home', get_template_directory_uri() . '/js/custom-home.js', array(), '', true);
}
add_action( 'wp_enqueue_scripts', 'voicingpoverty_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

function slug($name = "untitled")
{
	$pattern = '/(\A[^\p{L}\p{N}]+|[^\p{L}\p{N}]+\z)/u';
	$replace = '';
	$tmp = preg_replace($pattern, $replace, $name);

	$pattern = '/[^\p{L}\p{N}]+/u';
	$replace = '-';
	$tmp = preg_replace($pattern, $replace, $tmp);
	$tmp = mb_convert_case($tmp, MB_CASE_LOWER, "UTF-8");
    $tmp = remove_accents($tmp);
	$tmp = rawurlencode($tmp);

	return $tmp;
}

function get_child_pages(){
	global $post;
	$args = array(
	    'post_type'      => 'page',
	    'posts_per_page' => -1,
	    'post_parent'    => $post->ID,
	    'order'          => 'ASC'
	);
	$children = new WP_Query( $args );
	if ( $children->have_posts() ){
		while ( $children->have_posts() ) {
			$children->the_post();
			print_child_pages_as_block(get_the_title(), get_the_content());
		}
	} 
	wp_reset_postdata();
}
function print_child_pages_as_block($title=false, $content=false){
	if( $title || $content ){
	?><section id="<?= slug($title); ?>" class="block section-block wp-block-columns">
		<div class="wp-block-column" style="flex-basis:25%"><span class="block-toggle-btn" onclick="toggle_block(this)">+</span></div>
		<div class="wp-block-column block-wrapper" style="flex-basis:75%">
			<? 
			if( !empty($title) ){
				?><h1 class="block-title" onclick="toggle_block(this)"><?= $title; ?></h1><?
			} 
			if( !empty($content) ){
				?><div class="block-content"><?= $content; ?></div><?
			}
			?>
		</div>
	</section><?
	}
}

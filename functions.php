<?php
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
	wp_enqueue_script( 'custom-general', get_template_directory_uri() . '/js/custom-general.js', array(), '', true);

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

// function slug($name = "untitled")
// {
// 	$pattern = '/(\A[^\p{L}\p{N}]+|[^\p{L}\p{N}]+\z)/u';
// 	$replace = '';
// 	$tmp = preg_replace($pattern, $replace, $name);

// 	$pattern = '/[^\p{L}\p{N}]+/u';
// 	$replace = '-';
// 	$tmp = preg_replace($pattern, $replace, $tmp);
// 	$tmp = mb_convert_case($tmp, MB_CASE_LOWER, "UTF-8");
//     $tmp = remove_accents($tmp);
// 	$tmp = rawurlencode($tmp);

// 	return $tmp;
// }

function voicingpoverty_get_child_pages($id = false){
	global $post;
	if(!$id)
		$id = $post->ID;
	$args = array(
	    'post_type'      => 'page',
	    'posts_per_page' => -1,
	    'post_parent'    => $id,
	    'order'          => 'ASC'
	);
	$children = new WP_Query( $args );
	if ( $children->have_posts() ){
		// wp_reset_postdata();
		return $children;
	} 
	else
	{
		// wp_reset_postdata();
		return false;
	}
}
function voicingpoverty_get_posts_by_cat($cat_slug, $orderby = 'id', $order = 'DESC'){
	$args = array(
		'tax_query' => array(
		    array(
		        'taxonomy' => 'category',
		        'field'    => 'slug',
		        'terms'    => $cat_slug
		        ),
		    ),
		'posts_per_page' => -1,
		'orderby' => $orderby,
		'order' => $order
	);
	$posts_by_cat = new WP_Query($args);
	return $posts_by_cat;
}
function voicingpoverty_get_posts_by_tag($tag_slug, $orderby = 'id', $order = 'DESC'){
	$args = array(
		'tax_query' => array(
		    array(
		        'taxonomy' => 'post_tag',
		        'field'    => 'slug',
		        'terms'    => $tag_slug
		        ),
		    ),
		'posts_per_page' => -1,
		'orderby' => $orderby,
		'order' => $order
	);
	$posts_by_tag = new WP_Query($args);
	return $posts_by_tag;
}
function voicingpoverty_print_child_page_as_block($title=false, $content=false, $expanded=false, $page='home', $foldable=true, $extra_class=''){
	if( $title || $content ){
	?><section id="block_<?php echo sanitize_title_with_dashes(esc_attr($title)); ?>" class="<?php echo esc_attr($page); ?>-block block section-block <?php echo $expanded ? 'expanded':''; ?> <?php echo $foldable ? 'foldable':''; ?> <?php echo !empty($extra_class) ? esc_attr($extra_class) : ''; ?>">
		<?php if(!$foldable){
			?><header class="block-header wp-block-columns sticky"><?php
		}else{
			?><header class="block-header wp-block-columns sticky" onclick="toggle_block(this)"><?php
		} ?>
			<div class="first-column wp-block-column" style=""><span class='block-close-symbol'></span></div>
			<div class="second-column wp-block-column">
				<?php 
				if( !empty($title) ){
					if($page == 'home')
					{
						?><h1 class="block-title"><?php echo esc_attr($title); ?></h1><?php
					}
					else if($page == 'participant-portal')
					{
						?><h4 class="block-title"><?php echo esc_attr($title); ?></h4><?php
					}
					
				} 
				?>
			</div>
			<span class="sticky-background"></span>
			<?php if($foldable){
			?><svg class="cross" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15"><polygon points="15 6 9 6 9 0 6 0 6 6 0 6 0 9 6 9 6 15 9 15 9 9 15 9 15 6"/></svg><?php
			} ?>
		</header>
		<div class="block-body wp-block-columns">
			<div class="first-column wp-block-column"></div>
			<div class=" second-column wp-block-column block-content"><?php echo $content; ?></div>
		</div>
	</section><?php
	}
}
function voicingpoverty_get_child_as_list_item($col1, $col2, $item_class, $content, $foldable=true){
	$output = '<div class="'. $item_class . '">
		<header class="list-item-header wp-block-columns"';
	if($foldable)
		$output .= ' onclick="toggle_listItem(this)"';
	$output .= ' >
			<div class="wp-block-column "><p>'. $col1 .'</p></div>
			<div class="wp-block-column"><p>'. $col2 .'</p></div>
			<div class="cross"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15"><polygon points="15 6 9 6 9 0 6 0 6 6 0 6 0 9 6 9 6 15 9 15 9 9 15 9 15 6"/></svg></div>
		</header>
		<div class="list-item-body wp-block-columns">
			<div class="wp-block-column"></div>
			<div class="wp-block-column list-item-content">'. $content .'</div>
		</div>
	</div>
	<hr class="wp-block-separator" />';
	return $output;
}

function voicingpoverty_get_single_tag($str){
	$bracket_pattern = '/\[(.*?)\]/';
	preg_match($bracket_pattern, $str, $output);
	return $output;
}
add_filter( 'the_password_form', 'voicingpoverty_custom_password_form' );
function voicingpoverty_custom_password_form() {
    global $post;
    global $returnUrl;

    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
    $portal_id = url_to_postid( '/participant-portal' );
    $o = '<form id="password-form" action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post"><p>'.__( "PARTICIPANTS ACCESS CODE").'</p>
    <label for="' . $label . '"></label><input id="password-input" name="post_password" id="' . $label . '" type="password" size="20" maxlength="20" /><input id="password-submit-btn" type="submit" name="Submit" value="' . esc_attr__( "SUBMIT" ) . '" />';
    if( isset( $_COOKIE['wp-postpass_' . COOKIEHASH] ) )
    	$o .= '<p>' . esc_html__( 'Sorry, your password is wrong!') . '</p>'; 
    $o .= '<p>'.__( "If you are a participants and are have issues logging in, please email us at ") . '<a href="mailto:voicingpoverty@bmcc.cuny.edu">'.__( "voicingpoverty@bmcc.cuny.edu") . '</a>'.__( " to request the access code.").'</p></form>';
    return $o;
}
add_filter( 'post_password_expires', 'mind_set_cookie_expire' );
function mind_set_cookie_expire() {
	return 0;
}

function wpse_58613_comment_redirect( $location ) {
    if ( isset( $_POST['my_redirect_to'] ) ) // Don't use "redirect_to", internal WP var
        $location = $_POST['my_redirect_to'];

    return $location;
}

add_filter( 'comment_post_redirect', 'wpse_58613_comment_redirect' );

function wpb_disable_pdf_previews() { 
	$fallbacksizes = array(); 
	return $fallbacksizes; 
} 
add_filter('fallback_intermediate_image_sizes', 'wpb_disable_pdf_previews');

class Voicing_Poverty_Comment_Walker extends Walker_Comment {
    protected function html5_comment( $comment, $depth, $args ) {
		?><li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? 'isExtended' : 'parent isExtended' ); ?>>
		    <div class="media-body">
			    <?php printf( '<p class="comment-author">%s</p>', get_comment_author_link() ); ?>
		        <time datetime="<?php comment_time( 'c' ); ?>">
		                <?php printf( _x( '%1$s at %2$s', '1: date, 2: time' ), get_comment_date(), get_comment_time() ); ?>
	            </time>

			    <?php if ( '0' == $comment->comment_approved ) : ?>
			    <p class="comment-awaiting-moderation label label-info"><?php _e( 'Your comment is awaiting moderation.' ); ?></p>
			    <?php endif; ?>             

			    <div class="wp-column">
				    <div class="comment-content body-text">
				         <?php comment_text(); ?>
				    </div>
				    <ul class="list-inline">
				        <?php edit_comment_link( __( 'Edit' ), '<li class="edit-link">', '</li>' ); ?>
				    </ul>
				</div>
		    </div>      
		<?php
    }   
}
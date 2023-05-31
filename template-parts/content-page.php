<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package voicingpoverty
 */
// $isHome = is_front_page();
$isHome = is_front_page();
$extra_post_class = '';
if( $isHome )
	$extra_post_class .= "landing-block block";
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($extra_post_class); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php voicingpoverty_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'voicingpoverty' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'voicingpoverty' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
<?php
$bracket_pattern = '/\[.*?\]/g';
$children = voicingpoverty_get_child_pages(); 
if($children !== false)
{
	while ( $children->have_posts() ) {
		$children->the_post();
		$this_title = get_the_title();
		$this_content = get_the_title();
		
		if( 
			strtolower($this_title) !== 'participant' &&
		    strtolower($this_title) !== 'documents & readings' 
		)
			voicingpoverty_print_child_page_as_block($this_title, $this_content);
		else
		{
			// especial cases for participant and readings
			$id = $get_the_ID();
			$this_children = voicingpoverty_get_child_pages($id);
			if($this_children !== false)
			{
				$cat_arr = [];
				$children_arr = [];
				while ( $this_children->have_posts() ) {
					$this_children->the_post();
					$this_child_title = get_the_title();
					preg_match($bracket_pattern,$this_child_title, $title_arr_temp);
					$this_arr = array(
						'id'      => get_the_ID(),
						'title'   => get_the_title(),
						'content' => get_the_content()
					);
					if(!empty( $title_arr_temp[1] )){
						$cat_arr[$title_arr_temp[1][0]] = 1;
						$children_arr[$title_arr_temp[1][0]][] = $this_arr;
					}
					else
						$children_arr[] = $this_arr;
				}
			}
			voicingpoverty_print_child_page_as_block($this_title, $this_content, $cat_arr, $children_arr);
		}		
	}
	wp_reset_postdata();
}
?>

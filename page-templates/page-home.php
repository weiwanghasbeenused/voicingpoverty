<?php
/* Template Name: Home*/ 
/**
 * Template part for displaying homepage
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package voicingpoverty
 */
$expand = isset($_GET['expand']) ? $_GET['expand'] : false;
get_header('', array('isHome' => true));
$extra_landing_class = "landing-block block";
?>
<main id="primary" class="site-main">

<?php
while ( have_posts() ) :
	the_post(); ?>
<article id="landing-block" <?php post_class($extra_landing_class); ?>>
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
</article><!-- #post-<?php the_ID(); ?> -->
<? 

$children = get_child_pages(); 
if($children !== false)
{
	while ( $children->have_posts() ) {
		$children->the_post();
		if( get_post_status()=='private' )
			continue;
		$this_title = get_the_title();
		$this_content = get_the_content();
		$isExpanded = slug($this_title) == $expand;
		$isFoldable = true;
		$extra_class = '';
		if( slug($this_title) == 'participants' )
		{
			// especial cases for participant
			$id = get_the_ID();
			$this_children = get_child_pages($id);
			$html_tab = 'empty';
			$html_content = 'empty';
			$isFirstTab = true;
			
			if($this_children !== false)
			{
				$cat_arr = [];
				$children_arr = [];
				$html_tab = '<div class="tab-container large sticky isExtended">';
				$html_content = '<div class="tab-content-container">';
				while ( $this_children->have_posts() ) {
					$this_children->the_post();
					if( get_post_status()=='private' )
						continue;
					$this_child_title = get_the_title();
					$this_child_slug = slug($this_child_title);
					$html_tab .= $isFirstTab ? '<div class="wp-block-column"><a href="#tab-content_'.$this_child_slug.'" class="tab active" tab="'.$this_child_slug.'" onclick="toggle_tab(this); return false;">'.$this_child_title.'</a></div>' : '<div class="wp-block-column"><a href="#tab-content_'.$this_child_slug.'" class="tab" tab="'.$this_child_slug.'" onclick="toggle_tab(this); return false;">'.$this_child_title.'</a></div>';
					$html_content .= $isFirstTab ? '<div id="tab-content_'.$this_child_slug.'" class="tab-content viewing" >'.get_the_content() : '<div id="tab-content_'.$this_child_slug.'" class="tab-content" >'.get_the_content();
					$participants = get_posts_by_cat($this_child_slug, 'title', 'ASC');
					if( $participants->have_posts() )
					{
						$isFirst = true;
						$html_content .= '<hr class="wp-block-separator" />';
						$html_content .= '<div class="list-container">';
						$html_keep = '';
						while( $participants->have_posts() )
						{
							$participants->the_post();
							if( get_post_status()=='private' )
								continue;
							$this_name = get_the_title();
							if(strpos($this_name, ', ') !== false)
							{
								$temp = explode(', ', $this_name);
								$this_name = $temp[1] . ' ' . $temp[0];
							}
							$this_unit = get_the_excerpt();
							$this_item_content = get_the_content();
							$this_item_class = $isFirst ? 'list-item reading-item foldable small first-item' : 'list-item reading-item foldable small';
							
							if($this_name == 'Wei-Hao Wang' || $this_name == 'Olivia de Salve Villedieu')
								$html_keep .= get_child_as_list_item($this_name, $this_unit, $this_item_class, $this_item_content);
							else
								$html_content .= get_child_as_list_item($this_name, $this_unit, $this_item_class, $this_item_content);
							if($isFirst == true)
								$isFirst = false;
						}
						if(!empty($html_keep))
							$html_content .= $html_keep;
						$html_content .= '</div>';
					}
					$html_content .= '</div>';
					if($isFirstTab)
						$isFirstTab = false;
				}
				$html_tab .= '<span class="sticky-background"></span></div>';
				$html_content .= '</div>';
			}
			$this_content = $html_tab . $html_content;
			$extra_class .= 'hasExtendingLine';
		}
		else if(slug($this_title) == 'documents-038-readings'){
			$this_content = '<div class="list-container">' . $this_content;
			$readings = get_posts_by_cat('documents-and-readings', 'title', 'ASC');
			$isFirst = true;
			while ( $readings->have_posts() ) {
				$foldable = true;
				$readings->the_post();
				if( get_post_status()=='private' )
						continue;
				// $this_reading_title = get_the_title();
				$this_reading_author = get_the_title();
				if(strpos($this_reading_author, ', ') !== false)
				{
					$temp = explode(', ', $this_reading_author);
					$this_reading_author = $temp[1] . ' ' . $temp[0];
				}
				$this_reading_title = get_the_excerpt();
				$this_reading_content = get_the_content();
				$this_item_class = $isFirst ? 'list-item reading-item foldable small first-item' : 'list-item reading-item foldable small';
				if($isFirst)
					$this_content .= '<hr class="wp-block-separator" />';
				$this_content .= get_child_as_list_item($this_reading_author, $this_reading_title, $this_item_class, $this_reading_content, $foldable);
				if($isFirst)
					$isFirst = false;
			}
			$this_content .= '</div>';
			$extra_class .= 'hasExtendingLine';
		}
		else if(slug($this_title) == 'the-hows-038-whys-of-the-institute'){
			$extra_class .= 'hasExtendingLine';
		}
		else if(slug($this_title) == 'contact-us')
			$isFoldable = false;
		print_child_page_as_block($this_title, $this_content, $isExpanded, 'home', $isFoldable, $extra_class);
	}
	wp_reset_postdata();
}
// If comments are open or we have at least one comment, load up the comment template.
	// if ( comments_open() || get_comments_number() ) :
	// 	comments_template();
	// endif;

endwhile; // End of the loop.
?>
</main>
<style>
.section-block a,
.section-block a:visited
{
	color: var(--bg-color);
}
#block_contact-us a,
#block_contact-us a:visited
{
	color: #000;
}
.wp-block-file .wp-block-file__button
{
	color: var(--bg-color);
}
.wp-block-separator
{
	border-color: var(--bg-color);
}

/* cross */
.noTouchScreen .section-block.foldable:hover .cross
{
	fill: var(--bg-color);
}
/* blocks */
.home-block
{
	color: var(--bg-color);
}
.jsEnabled .home-block
{
	color: #000;
	background-color: var(--bg-color);
}
.jsEnabled .home-block.foldable.expanded
{
	color: var(--bg-color);
}

#block_about-the-project,
.jsEnabled #block_about-the-project.foldable.expanded,
.noTouchScreen.jsEnabled #block_about-the-project.foldable:hover
{
	background-color: #F45800;
}

.noTouchScreen #block_about-the-project.expanded .block-header:hover,
.noTouchScreen #block_about-the-project.expanded a:hover
{
	color: #FF9648;
}

.noTouchScreen #block_about-the-project.expanded .block-header:hover .cross,
.noTouchScreen #block_about-the-project.expanded .list-item.expanded .list-item-header:hover .cross
{
	fill: #FF9648;
}

#block_the-hows-038-whys-of-the-institute,
.jsEnabled #block_the-hows-038-whys-of-the-institute.foldable.expanded,
.noTouchScreen.jsEnabled #block_the-hows-038-whys-of-the-institute.foldable:hover
{
	background-color: #D7A700;
}

.noTouchScreen #block_the-hows-038-whys-of-the-institute.expanded .block-header:hover,
.noTouchScreen #block_the-hows-038-whys-of-the-institute.expanded a:hover
{
	color: #FAFF00;
}

.noTouchScreen #block_the-hows-038-whys-of-the-institute.expanded .block-header:hover .cross,
.noTouchScreen #block_the-hows-038-whys-of-the-institute.expanded .list-item.expanded .list-item-header:hover .cross
{
	fill: #FAFF00;
}


#block_participants,
.jsEnabled #block_participants.foldable.expanded,
.noTouchScreen.jsEnabled #block_participants.foldable:hover,
#block_participants .tab-container
{
	background-color: #2573AC;
}

.noTouchScreen #block_participants.expanded .block-header:hover,
.noTouchScreen #block_participants.expanded a:hover,
.noTouchScreen #block_participants .list-item-header:hover
{
	color: #63BDFF;
}
.noTouchScreen #block_participants.expanded a.tab:hover
{
	color: var(--bg-color);
}

.noTouchScreen #block_participants.expanded .block-header:hover .cross,
.noTouchScreen #block_participants.expanded .list-item .list-item-header:hover .cross
{
	fill: #63BDFF;
}

#block_documents-038-readings,
.jsEnabled #block_documents-038-readings.foldable.expanded,
.noTouchScreen.jsEnabled #block_documents-038-readings.foldable:hover
{
	background-color: #006837;
}
#block_documents-038-readings a{
	/*color: #24D26A;*/
}
.noTouchScreen #block_documents-038-readings.expanded .block-header:hover,
.noTouchScreen #block_documents-038-readings.expanded a:hover,
.noTouchScreen #block_documents-038-readings .list-item-header:hover
{
	color: #24D26A;
}

.noTouchScreen #block_documents-038-readings.expanded .block-header:hover .cross,
.noTouchScreen #block_documents-038-readings.expanded .list-item-header:hover .cross,
.noTouchScreen #block_documents-038-readings.expanded .slideshow-control-part:hover .arrow-container
{
	fill: #24D26A;
}

.noTouchScreen #block_documents-038-readings .list-item:hover a::after
{
	/*background-image: url('<?= get_template_directory_uri(); ?>/media/graphic_rarr-grey.svg');*/
}

#block_gallery,
.jsEnabled #block_gallery.foldable.expanded,
.noTouchScreen.jsEnabled #block_gallery.foldable:hover
{
	background-color: #751352;
}
.noTouchScreen #block_gallery.expanded .slideshow-control-part:hover .arrow-container
{
	fill: #EB00FF;
}

.noTouchScreen #block_gallery.expanded .block-header:hover,
.noTouchScreen #block_gallery.expanded a:hover
{
	color: #EB00FF;
}

.noTouchScreen #block_gallery.expanded .block-header:hover .cross,
.noTouchScreen #block_gallery.expanded .list-item.expanded .list-item-header:hover .cross
{
	fill: #EB00FF;
}

.jsEnabled #block_about-the-project.foldable,
.jsEnabled #block_the-hows-038-whys-of-the-institute.foldable,
.jsEnabled #block_participants.foldable,
.jsEnabled #block_documents-038-readings.foldable,
.jsEnabled #block_gallery.foldable
{
	background-color: var(--bg-color);
}

/* contact */
#block_contact-us
{
	/*display: flex;*/
	background-color: #ebe4d4;
	padding-bottom: 40px;
	border-bottom: none;
}
#block_contact-us .wp-block-column:first-child
{
	padding-right: ;
}
.noTouchScreen #block_contact-us .block-title:hover
{
	color: #000;
	cursor: default;
}
#block_contact-us .logo-container
{
	margin-top: 20px;
	margin-bottom: 0;
	width: 300px;
}


.jsEnabled .section-block.foldable.expanded
{
	color: var(--bg-color);
	cursor: default;
}

.block-close-symbol
{
	display: none;
}
.noTouchScreen .section-block.expanded .block-header:hover .block-title:after
{
	color: #bbb;
}
.noTouchScreen .section-block.expanded .block-header
{
	cursor: pointer;
}


/*.jsEnabled.noTouchScreen .section-block.expanded .block-title:hover::after,
.jsEnabled.noTouchScreen .section-block.expanded .block-title:hover::before
{
	background-color: #bbb;
}*/
.link-more a
{
	text-decoration: none;
}

.link-more a:after
{
	content: '';
	display: inline-block;
	width: 14px;
	height: 14px;
	background-image: url('<?= get_template_directory_uri(); ?>/media/graphic_rarr-light.svg');
	background-size: cover;
	margin-left: 3px;
	position: relative;
	top: 1px;
}

.noTouchScreen #block_participants .tab-content > .wp-block-columns:hover,
.noTouchScreen #block_participants .tab-content > .wp-block-columns:hover a
{
	color: #63BDFF;
}
.noTouchScreen #block_participants .tab-content > .wp-block-columns:hover a::after
{
	background-image: url('<?= get_template_directory_uri(); ?>/media/graphic_rarr-lightblue.svg');
}
#block_contact-us .wp-block-image
{
	margin-top: 0;
}

@media (min-width: 782px) {
	/* landing block */
	.section-block.expanded .block-title:after
	{
		display: none;
	}
	.jsEnabled .section-block.foldable .second-column{
		position: static;
	}
	/*.jsEnabled .section-block.foldable .block-title:after,*/
	/*.jsEnabled .section-block.foldable .block-title:before*/
	{
		right: auto;
		left: 0;
	}
	.block-close-symbol:after,
	.block-close-symbol:before
	{
		/*content: " ";
		display: block;
		position: absolute;
		width: 20px;
		height: 4px;
		background-color: #fff6e5;
		left: 0;
		top: 50%;
		margin-top: -5px;*/
	}
	.noTouchScreen .section-block.expanded .block-header:hover .block-close-symbol:after,
	.noTouchScreen .section-block.expanded .block-header:hover .block-close-symbol:before
	{
		/*background-color: #bbb;*/
	}
	.block-close-symbol:after
	{
		/*transform: translate(-50%, 0) rotate(45deg);*/
	}
	.block-close-symbol:before
	{
		/*transform: translate(-50%, 0) rotate(-45deg);*/
	}
	.section-block.expanded .block-close-symbol
	{
		display: block;
	}
}
@media (min-width: 1024px)
{
	#block_contact-us .block-body > .second-column
	{
		/* make .second-column half width of screen  */
		padding-right: 16.8%;
	}
	#block_contact-us .logo-container
	{
		margin-top: 0;
	}
}
@media (min-width: 1500px)
{
	#participant-portal-btn-1
	{
		transform: none;
		left: 0;
	}
}
</style>
<?
	get_footer();

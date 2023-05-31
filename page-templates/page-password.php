<?php

/* Template Name: password*/ 
/**
 * Template part for displaying homepage
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package voicingpoverty
 */
// unset($_COOKIE['wp-postpass_' . COOKIEHASH]);

get_header('participant-portal');
$isValidated = !post_password_required();
$extra_landing_class = 'block landing-block';
// $logo_url = $isValidated ? '/media/logo_block.png' : '/media/logo_block-reversed.png';
$logo_url = '/media/logo_block.png';
$logo_url = get_template_directory_uri() . $logo_url;
?>
<main id="primary" class="site-main">

<?php
while ( have_posts() ) :
	the_post(); 
	$title = get_the_title();
	if(substr($title, 0, 11) == 'Protected: ')
		$title = substr($title, 11);
	$slug = sanitize_title_with_dashes($title);
	?>
	
		<article id="landing-block" <?php post_class($extra_landing_class); ?>>
			<header class="entry-header wp-block-columns">
				<div id="landing-block-col-left" class="wp-block-column first-column">
					<h1 id = "site-name-1" class="site-name"><a href="/"><img src="<?php echo $logo_url; ?>"></a></h1><a class="has-text-align-center participant-portal-btn" id="participant-portal-btn-1" href="/participant-portal">PARTICIPANT<br>PORTAL</a>
				</div>
				<div id="landing-block-col-right" class="wp-block-column second-column"></div>
			</header><!-- .entry-header -->
		</article>
		<div class="entry-content"><?php the_content(); ?></div>
		<?php
		if($isValidated)
		{
			?>
			
			<div class="entry-content"><?php the_content(); ?></div><?php 
			if($slug == 'participant-portal')
			{
				function voicingpoverty_print_child_page_as_link($title=false, $url=false, $isExternal=false){
					if( $title || $url ){
						if(substr($title, 0, 11) == 'Protected: ')
							$title = substr($title, 11);
					?><section id="block_<?php echo sanitize_title_with_dashes(esc_attr($title)); ?>" class="block section-link foldable">
						<a class="block-header wp-block-columns" <?php echo $url ? 'href="'.$url.'"' : ''; ?> <?php echo $isExternal ? 'target="_blank"' : ''; ?> >
							<div class="first-column wp-block-column" style=""></div>
							<div class="second-column wp-block-column">
								<?php 
								if( !empty($title) ){
									?><h1 class="block-title"><?php echo esc_attr($title); ?></h1><span class="arrow-container"><svg class="arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><polygon points="10.98 18 19 10 10.98 2 8.87 4.11 13.26 8.5 1 8.5 1 11.5 13.27 11.5 8.87 15.89 10.98 18"/></svg></span><?php
								} 
								?>
							</div>
							
						</a>
					</section><?php
					}
				}
				$children = voicingpoverty_get_child_pages(); 
				if($children !== false)
				{
					while ( $children->have_posts() ) {
						global $post;
						$children->the_post();
						if( get_post_status()=='private' )
							continue;
						$this_title = get_the_title();
						$this_slug = $post->post_name;
						$isExternal = false;
						if($this_slug == 'zoom' || $this_slug == 'email' || $this_slug == 'forum')
						{
							$this_content = get_the_content();
							$this_tag_temp = voicingpoverty_get_single_tag($this_content);
							if(isset($this_tag_temp[1]))
								$this_url = $this_tag_temp[1];
							else
								$this_url = false;
							$isExternal = true;
						}
						else
							$this_url = get_permalink( get_the_ID() );
						
						voicingpoverty_print_child_page_as_link($this_title, $this_url, $isExternal);
					}
					wp_reset_postdata();
				}
			}
			else if($slug == 'schedule')
			{
				$posts_by_cat = voicingpoverty_get_posts_by_cat($slug, 'date', 'ASC'); 
				$isFoldable = true;
				echo '<section id="default-block" class="participant-portal-block block section-block schedule sticky"><header class="block-header wp-block-columns "><div class="first-column wp-block-column"></div><div class="second-column wp-block-column"><h1 class="block-title">SCHEDULE</h1><h1 onclick="toggle_block(this, false, true);" isViewingAll="false" id="schedule-view-all-btn">View All</h1></div></header></section>';
				
				$pattern_readings_and_resources = '/(?:<p(?:\s.*)?>)?\[readings\-and\-resources\](?:<\/p>)?/';
				$pattern_initials = '/\(.*?\)/';
				
				# $pattern_readings_and_resources = '/readings\-and\-resources/';

				while ( $posts_by_cat->have_posts() ) {
					$posts_by_cat->the_post();
					if( get_post_status()=='private' )
						continue;
					$this_title = get_the_title();
					$this_content = get_the_content();
					$this_readings_tag = date('m-d-Y', strtotime($this_title));
					$readings = voicingpoverty_get_posts_by_tag($this_readings_tag, 'date', 'ASC');
					$isFirst = true;
					preg_match($pattern_readings_and_resources, $this_content, $temp_arr);
					$includeReadingAndResources = !empty($temp_arr);
					
					if( $includeReadingAndResources )
					{
						
						// $this_content .= '<hr class="wp-block-separator" /><div class="list-container"><h4 class="list-title">Readings & Resources</h4><hr class="wp-block-separator" />';
						if( $readings->have_posts() ){
							$reading_html = '<div class="list-container"><hr class="wp-block-separator" />';
							while( $readings->have_posts() )
							{
								$readings->the_post();
								if( get_post_status()=='private' )
									continue;
								$this_reading_title = get_the_excerpt();
								$this_reading_author = get_the_title();
								$this_reading_content = get_the_content();
								$this_reading_tags = get_the_tags();
								$this_speaker_initials = '';
								$this_item_foldable = false;
								if(strpos($this_reading_author, ', ') !== false)
								{
									$temp = explode(', ', $this_reading_author);
									$this_reading_author = $temp[1] . ' ' . $temp[0];
								}
								foreach($this_reading_tags as $tag){
									preg_match($pattern_initials, $tag->name, $temp_arr);
									if(!empty($temp_arr))
										$this_speaker_initials = $temp_arr[0];
								}
								$this_reading_author = $this_speaker_initials . ' ' . $this_reading_author;
								$this_item_class = $isFirst ? 'list-item reading-item small first-item' : 'list-item reading-item small';
								if(!empty($this_reading_content)){
									$this_item_foldable = true;
									$this_item_class .= ' foldable';
								}
								$reading_html .= voicingpoverty_get_child_as_list_item($this_reading_author, $this_reading_title, $this_item_class, $this_reading_content, $this_item_foldable);
								if($isFirst)
									$isFirst = false;
							}
							$reading_html .= '</div>';
							$this_content = preg_replace($pattern_readings_and_resources, $reading_html, $this_content);
						}
						else
						{
							$reading_html = '';
							$this_content = preg_replace($pattern_readings_and_resources, $reading_html, $this_content);
						}						
					}
					
					voicingpoverty_print_child_page_as_block($this_title, $this_content, false, 'participant-portal', $isFoldable, 'hasExtendingLine schedule');
				}
			}
			else if($slug == 'blog')
			{
				$posts_by_cat = voicingpoverty_get_posts_by_cat($slug); 

				function voicingpoverty_print_child_page_as_blog($title=false, $content=false, $op=false, $date=false){
					?><article id="blog_<?php echo sanitize_title_with_dashes(esc_attr($title)); ?>" class="block participant-portal-block section-block blog-block expanded">
						<header class="block-header wp-block-columns sticky isExtended">
							<div class="first-column wp-block-column" style=""></div>
							<div class="second-column wp-block-column block-title">
								<h1 class="blog-op large"><?php echo $op; ?></h1><h1 class="blog-date large"><?php echo $date; ?></h1>
							</div>
							<div class="block-header-background"></div>
						</header>
						<div class="block-body wp-block-columns">
							<div class="first-column wp-block-column"></div>
							<div class="block-content second-column wp-block-column">
								<h4><?php echo esc_attr($title); ?></h4>
								<?php echo $content; ?>
								<?php if ( comments_open() || get_comments_number() ) :
									?><hr class="comment-separator wp-block-separator" /><?php
									comments_template();
								endif; ?>
							</div>
						</div>

					</article><?php
				}
				echo '<section id="default-block" class="participant-portal-block block section-block blog sticky"><header class="block-header wp-block-columns "><div class="first-column wp-block-column"></div><div class="second-column wp-block-column"><h1 class="block-title">BLOG</h1><h1 id="submit-entries-btn"><a href="mailto:voicingpoverty@bmcc.cuny.edu">Submit Entries</a></h1><h1 id="date-filter">Date <span id="date-filter-asc" onclick="reverse_blog(this, sPost_block);" class="date-filter-btn"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><polygon points="2 10.98 10 19 18 10.98 15.89 8.87 11.5 13.26 11.5 1 8.5 1 8.5 13.27 4.11 8.87 2 10.98"/></svg></span><span id="date-filter-desc" onclick="reverse_blog(this, sPost_block);" class="date-filter-btn active"><svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><polygon points="18 9.02 10 1 2 9.02 4.11 11.13 8.5 6.74 8.5 19 11.5 19 11.5 6.74 15.89 11.13 18 9.02"/></svg></span></h1></div></header></section>';

				echo '<div id="posts-container">';
				while ( $posts_by_cat->have_posts() ) {
					$posts_by_cat->the_post();
					if( get_post_status()=='private' )
						continue;
					$this_title = esc_attr(get_the_title());
					$this_content = '<div class="list-container">' . get_the_content() . '</div>';
					$this_op = get_the_excerpt();
					$this_date = get_the_date('F d, Y');
						
					voicingpoverty_print_child_page_as_blog($this_title, $this_content, $this_op, $this_date);
				}
				echo '</div>';
			}
		}
		
endwhile; // End of the loop.

?>
</main>
<style>
.reverse #participant-portal-btn-1
{
	display: none;
}
body.reverse
{
	background-color: #000;
	color: #FFF7E9;
}
body.participant-portal
{
	padding-bottom: 60px;
}
ul, ol
{
	margin-bottom: 0;
	margin-top: 10px;
}
table
{
	margin-top: 1em;
	margin-bottom: 0;
}
.wp-block-table
{
	margin-bottom: 0;
}
.section-block a, 
.section-block a:visited,
.participant-portal .wp-block-file a.wp-block-file__button, 
.participant-portal .wp-block-file a.wp-block-file__button:hover, 
.participant-portal .wp-block-file a.wp-block-file__button:visited
{
	color: #000;
}
.reverse a
{
	color: #FFF7E9;
}

/* columns  */
.list-container > .wp-block-columns,
.list-container > .wp-block-columns:last-child
{
	/*border-color: #000;*/
}
.list-container
{
	margin-top: 1em;
}

/* spacing  */

.schedule * + .wp-block-separator
{
	margin-top: 10px;
}
.schedule .wp-block-separator + *
{
	margin-top: 40px;
}
.section-block .list-container .wp-block-columns + .wp-block-separator,
.section-block .list-container .wp-block-separator + .wp-block-columns,
.section-block .list-container .list-item + .wp-block-separator,
.section-block .list-container .wp-block-separator + .list-item
{
	margin-top: 0px;
	margin-bottom: 0;
}

.comment-separator
{
	margin-top: 10px;
}
.comment-separator + .comment-area-container
{
	margin-top: 40px;
}
.comment-area-container textarea
{
	
	height: 120px;
	
	margin-top: 8px;
}
.comment-area-container input
{
	/*margin-left: 8px;*/
}
.comment-area-container label
{
	width: 65px;
	display: inline-block;
}
.comment-area-container textarea,
.comment-area-container input
{
	padding: 8px;
	font-size: inherit;
	border-radius: 0;
	border: 1px solid transparent;
	border-bottom: 1px solid #000;
}
.comment-area-container textarea:focus,
.comment-area-container input:focus
{
	outline: none;
	border-color: #000;
}
#wp-comment-cookies-consent + label
{
	width: auto;
}
#wp-comment-cookies-consent,


/* cross  */
.participant-portal .cross,
.participant-portal .section-block.expanded .cross
{
	fill: #000;
}
.participant-portal .block-header:hover .cross
{
	fill: var(--bg-color);
}

/* block  */
.participant-portal-block
{
	background-color: var(--bg-color);
}

.hasArrow.rightArrow:after
{
	content: '';
	position: absolute;
	border-top: 3px solid;
	border-right: 3px solid;
	border-color: #000;
	height: 10px;
	width: 10px;
	right: 7px;
	margin-top: -1.5px;
	top: 50%;
	transform-origin: 8px 15.85px;
	transform: translate(0, -50%) rotate(45deg);
}
.hasArrow.rightArrow:before
{
	content: '';
	position: absolute;
	height: 3px;
	width: 12px;
	right: 0;
	top: 50%;
	transform: translate(0, -50%);
	background-color: #000;
}
.noTouchScreen .section-link.foldable:hover .hasArrow:before
{
	background-color: var(--bg-color);
}
.noTouchScreen .section-link.foldable:hover .hasArrow:after
{
	border-color: var(--bg-color);
}
.participant-portal #landing-block
{
	padding-bottom: 0px;
}
#landing-block .entry-header
{
	position: relative;
}
.noTouchScreen .section-link.foldable:hover,
.noTouchScreen .participant-portal-block.foldable:hover
{
	background-color: #000;
}
.noTouchScreen .section-link.foldable:hover .arrow
{
	fill: var(--bg-color);
}
.noTouchScreen .participant-portal-block.expanded.foldable:hover
{
	background-color: #FFF7E9;
}
.section-link a
{
	text-decoration: none;
}
.noTouchScreen .section-link:hover a
{
	color: #FFF7E9;
}
.noTouchScreen .participant-portal-block.foldable .block-header:hover
{
	background-color: #000;
}

.noTouchScreen .participant-portal-block.foldable.expanded:hover .block-header
{
	background-color: inherit;
	color: #000;
}
.noTouchScreen .participant-portal-block.foldable.expanded:hover .block-header .cross
{
	fill: #000;
}
/*.noTouchScreen .section-block.foldable:hover .block-header*/
/* landing block */
#site-name-1
{
	margin-bottom: 16px;
}
/* default blocks */
#schedule-view-all-btn,
#date-filter
{
	float: right;
	clear: none;
	padding: 20px 0 0 0;
	display: none;
}
#schedule-view-all-btn
{
	corsor: pointer;
	border-bottom: 2px solid #000;
}
.jsEnabled #schedule-view-all-btn,
.jsEnabled #date-filter
{
	display: block;
}
#submit-entries-btn
{
	position: absolute;
	left: 50%;
	top: 20px;
	transform: translate(-50%, 0);
	border-bottom: 2px solid;
	margin-left: -20px;
}
#submit-entries-btn > a
{
	color: #000;
	text-decoration: none;
}
#default-block.schedule .block-title,
#default-block.blog .block-title
{
	float: left;
	clear: none;
}
#default-block
{
	top: 0;
	z-index: 150;
}
.section-block.expanded .block-header.sticky
{
	top: 66px;
}
.section-block.schedule .block-title
{
	font-weight: 600;
}
.noTouchScreen .section-block.schedule.expanded:hover
{
	background-color: #ebe4d4;
}
#date-filter
{
	text-align: right;
	padding-top: 16.5px;
}
.date-filter-btn
{
	color: #bbb;
	cursor: pointer;
	display: inline-block;
	/*float: left;*/
	position: relative;
	width: 20px;
	height: 20px;
	/*vertical-align: middle;*/
	box-sizing: content-box;
	padding: 0 2px;
	top: 2px;
}
.date-filter-btn svg
{
	fill: #bbb;
	position: relative;
}
.date-filter-btn.active svg
{
	fill: #000;
}
.noTouchScreen .date-filter-btn:hover svg
{
	fill: #000;
}

#date-filter-desc svg
{
	/*top: 2px;*/
}

#password-form
{
	padding: 20px;
}
#password-input
{
	display: block;
	width: 100%;
	border-radius: 0;
	margin: 20px 0;
}
#password-submit-btn
{
	display: block;
	-webkit-appearance: none;
    appearance: none;
    background-color: #000;
    color: var(--bg-color);
    border: 2px solid;
    border-radius: 50%;
    padding: 15px 0;
    width: 155px;
    margin: 20px 0;
    cursor: pointer;
	font-weight: 700;
}
.noTouchScreen #password-submit-btn:hover
{
	background-color: var(--bg-color);
	color: #000;
}
.participant-portal .participant-portal-btn
{
	background-color: #000;
	color: var(--bg-color);
}
.participant-portal .participant-portal-btn:hover
{
	animation: none;
}
.participant-portal #participant-portal-btn-1:hover
{
	background-color: var(--bg-color);
	color: #000;
}
#date-filter-asc
{
	/*border-bottom: 2px solid #000;*/
}

.section-link .arrow-container
{
	position: absolute;
	right: 0;
	top: 50%;
	transform: translate(0, -50%);
}

.participant-portal .slideshow-control-part .arrow-container
{
	fill: #000;
}
.comment-meta
{
	display: block;
}
.comment-meta .avatar
{
	display: none;
}
.block-header-background
{
	position: absolute;
	top: 0;
	left: -20px;
	width: 100vw;
	height: 100%;
	z-index: -1;
	background-color: var(--bg-color);
}
@media (min-width: 782px) {

	#landing-block-col-left
	{
		position: static;
	}

	#participant-portal-btn-1
	{
		right: -10px;
		left: auto;
		top: 0;
		bottom: auto;
		transform: none;
		z-index: 100;
	}
	
	.noTouchScreen .foldable.expanded .block-header
	{
		background-color: #fff6e5;
	}
	.post-password-form
	{
		width: 80vw;
		margin:  0 auto;
	}
	.section-block.expanded .block-header
	{
		top: 0;
	}
	/* blog */
	.blog-block .block-header
	{
		/*padding: 20px 0;*/
	}
	.blog-op
	{
		float: left;
		clear: none;
	}
	.blog-date
	{
		float: right;
		clear: none;
	}
	#password-form
	{
		width: 600px;
		margin:  0 auto;
	}
	.reverse #landing-block
	{
		min-width: 0;
	}
	#default-block
	{
		top: 64px;
	}
	.section-block.expanded .block-header.sticky
	{
		top: 136px;
	}
	#password-input
	{
		display: inline-block;
		width: initial;
	}
	#password-submit-btn
	{
		display: inline-block;
		margin: 0 0 0 20px;
	}
	#submit-entries-btn
	{
		margin-left: 0;
	}
	#date-filter
	{
		padding-top: 20px;
	}

	.section-block.schedule .cross
	{
		right: 0;
		left: auto;
	}
	.comments-zone-header-bg,
	.block-header-background
	{
		left: 0;
		width: 100%;
	}
}
@media (min-width: 1024px)
{
	#participant-portal-btn-1
	{
		/*right: 10px;*/
	}
}
@media (min-width: 1500px)
{

}
</style>
<script>
	var sPost_block = document.querySelectorAll('.section-block.blog-block');
	var sPosts_container =document.getElementById('posts-container');
	var posts_arr = [];
	var posts_reversed_arr = [];
	function reverse_blog(el, posts){
		if(!el.classList.contains('active') && posts.length > 1)
		{
			if(posts_arr.length == 0)
			{
				console.log('first filter');
				var postParent = posts[0].parentNode;
				[].forEach.call(sPost_block, function(el, i){
					var elCloned = el.cloneNode(true);
					posts_reversed_arr.unshift(elCloned);
					posts_arr.push(elCloned);
				});
				
			}
			if(el.id == 'date-filter-asc')
				var posts_to_add_arr = posts_reversed_arr;
			else
				var posts_to_add_arr = posts_arr;
			
			sPosts_container.innerHTML = '';
			posts_to_add_arr.forEach(function(el, i){
				sPosts_container.appendChild(el);
			});
			var activeBtnSibling =  document.querySelector('.date-filter-btn.active');
			el.classList.add('active');
			if(activeBtnSibling != null)
				activeBtnSibling.classList.remove('active');
		}
	}
</script>
<?php
get_footer();

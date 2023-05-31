<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package voicingpoverty
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
// wp_list_comments( array(
//     'walker'        => new Bootstrap_Comment_Walker(),
//  ));




if ( post_password_required() ) {
	return;
}

$post_id = get_the_ID();
?>

<div id="comments-<?php echo $post_id; ?>" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( comments_open() ) :
		?>
		<header class="comments-zone-header float-container isExtended">
			<h2 class="comments-title">
				<?php
				$voicingpoverty_comment_count = get_comments_number();
				if ( '1' === $voicingpoverty_comment_count ) {
					printf(
						/* translators: 1: title. */
						esc_html__( 'COMMENTS', 'voicingpoverty' ),
						'<span>' . wp_kses_post( get_the_title() ) . '</span>'
					);
				} else {
					printf( 
						/* translators: 1: comment count number, 2: title. */
						esc_html__( 'COMMENTS', 'voicingpoverty' ),
						number_format_i18n( $voicingpoverty_comment_count ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'<span>' . wp_kses_post( get_the_title() ) . '</span>'
					);
				}
				?>
			</h2><!-- .comments-title -->
			<span class="post-reply-btn" onClick="toggle_new_comment(this)">POST A REPLY</span>
			<div class="comments-zone-header-bg"></div>
		</header>

		<?php the_comments_navigation(); ?>

		<ul class="comment-list">
			<?php
			wp_list_comments(
				array(
					'walker'     => new Voicing_Poverty_Comment_Walker(),
					'style'      => 'ul',
					'short_ping' => true,
				)
			);
			?>
		</ul><!-- .comment-list -->

		<?php
		the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) :
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'voicingpoverty' ); ?></p>
			<?php
		endif;

	endif; // Check for have_comments().
	?>
	<div class="new-comment-wrapper padding-frame">
		<div class="new-comment">
		<?php
		$args = array(
			'title_reply' => 'LEAVE A REPLY<svg onClick="toggle_new_comment(this)" class="cross" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15"><polygon points="15 6 9 6 9 0 6 0 6 6 0 6 0 9 6 9 6 15 9 15 9 9 15 9 15 6"/></svg>',
			'fields' => array(
				'author'  => '<p class="comment-form-author"><label class="comment-form-label hidden" for="author" required="required" >name</label><input id="author" class="comment-field comment-input" name="author" type="text" value="" size="30" maxlength="245" required="required" placeholder="name"></p>',
				'email'   => '<p class="comment-form-email"><label class="comment-form-label hidden" for="email" required="required" >email</label><input id="email" class="comment-field comment-input" name="email" type="email" value="" size="30" maxlength="100" required="required" placeholder="email" aria-describedby="email-notes"></p>',
				'cookies' => '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" class="comment-field " name="wp-comment-cookies-consent" type="checkbox" value="yes" placeholder="name"><label class="comment-form-label" for="wp-comment-cookies-consent">Save my name, email, and website in this browser for the next time I comment.</label></p>'
			),
			'comment_field' => '<p class="comment-form-comment"><label for="comment" class="comment-form-label hidden" >Comment</label> <textarea id="comment" class="comment-input" name="comment" cols="45" rows="8" maxlength="65525" required="required" placeholder="comment"></textarea></p>',
			'submit_button' => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="POST" />',
			'cancel_reply_link' => '',
			'class_form' => 'comment-form float-container'

		);
		comment_form($args);
		?>
		</div>
	</div>
</div><!-- #comments -->

<style>
p.comment-author
{
	margin-top: 0;
	font-weight: 600;
}
.comments-area
{
	margin-top: 40px;
}
.comments-zone-header
{
	border-top: 2px solid #000;
	border-bottom: 2px solid #000;
	padding-top: 20px;
	padding-bottom: 20px;
	letter-spacing: .05em;
	position: -webkit-sticky;
	position: sticky;
	top: 160px;
	background-color: var(--bg-color);
	
}
.comments-zone-header-bg
{
	/*position: absolute;
	top: 0;
	left: -20px;
	width: 100vw;
	height: 100%;
	z-index: -1;
	background-color: var(--bg-color);*/
}
.comments-title
{
	float: left;
}
.post-reply-btn
{
	float: right;
	text-decoration: underline;
	cursor: pointer;
}
.comment-list,
.list-inline
{
	margin-left: 0;
	margin-top: 0;
	list-style: none;
}
.comment
{
	border-bottom: 2px solid #000;
	padding-top: 20px;
	padding-bottom: 20px;
}
.edit-link
{
	margin-top: 40px;
	text-decoration: underline;
}
.comment:last-of-type
{
	border-bottom: none;
}
.new-comment-wrapper
{
	position: fixed;
	width: 100vw;
	height: 100vh;
	background-color: rgba(0,0,0, .5);
	top: 0;
	left: 0;
	pointer-events: none;
	opacity: 0;
	z-index: 1500;
}
.viewing-new-comment .new-comment-wrapper
{
	opacity: 1;
	transition: opacity .35s;
	pointer-events: initial;
	padding: 20px;
}
.new-comment
{

	height: 100%;
	color: var(--bg-color);
	background-color: #000;
	padding: 20px;
}
.section-block .new-comment a,
.section-block .new-comment a:visited
{
	color: var(--bg-color);
}
.comment-notes
{
	display: none;
}
.comment-form-label.hidden
{
	display: none;
}
#wp-comment-cookies-consent
{
	width: 18px;
	height: 18px;
	margin-right: 8px;
}
input.comment-input,
textarea.comment-input
{
	display: block;
	width: 100%;
	padding: 5px;
}
textarea.comment-input
{
	height: 150px;
}
input.submit
{
	-webkit-appearance: none;
	appearance: none;
	background-color: transparent;
	color: var(--bg-color);
	border: 2px solid;
	border-radius: 50%;
	padding: 15px 55px;
	cursor: pointer;
}
.noTouchScreen input.submit:hover
{
	background-color: var(--bg-color);
	color: #000;
	border-color: var(--bg-color);
}
.comment-reply-title
{
	position: relative;
}
.participant-portal .section-block.expanded .comment-reply-title .cross
{
	width: 15px;
	display: inline-block;
	fill: var(--bg-color);
	transform: rotate(45deg);
	margin-top: 2px;
	float: right;
}
@media (min-width: 600px){
	.new-comment
	{
		height: initial;
	}
}
@media (min-width: 782px){
	.comments-zone-header
	{
		top: 211px;
	}
	.comments-area{
		/*display: none;*/
	}
	.new-comment
	{
		width: 600px;
		margin: 0 auto;
	}

}
@media (min-width: 1024px){
	.new-comment
	{
		width: 950px;
		padding: 30px 40px 40px 40px;
		margin: 0 auto;
	}
	.comment-form-comment
	{
		float: right;
		width: 60%;
		padding-left: 40px;
	}
	.comment-form-author,
	.comment-form-email,
	.comment-form-cookies-consent,
	.form-submit
	{
		float: left;
		clear: left;
		width: 40%;
	}
	textarea.comment-input
	{
		height: 100%;
	}

}
</style>
<script>
	var sComment_form = document.getElementsByClassName('comment-form');
	var redirect_to = document.createElement('INPUT');
	redirect_to.setAttribute('type', 'hidden');
	redirect_to.setAttribute('name', 'redirect_to');
	redirect_to.setAttribute('value', '/participant-portal/blog');
	[].forEach.call(sComment_form, function(el, i){
		el.appendChild(redirect_to.cloneNode(true));
	});

	function toggle_new_comment(el){
		var this_comment_zone = el.parentNode.parentNode;
		while(!this_comment_zone.classList.contains('comments-area') && this_comment_zone != body)
			this_comment_zone = this_comment_zone.parentNode;
		if(!this_comment_zone.classList.contains('viewing-new-comment'))
		{
			this_comment_zone.classList.add('viewing-new-comment');
			var this_wrapper = this_comment_zone.querySelector('.new-comment-wrapper');
			var this_new_comment = this_wrapper.querySelector('.new-comment');

			var wh = window.innerHeight;
			this_wrapper.style.height = wh + 'px';
			var this_comment_respond = this_wrapper.querySelector('.comment-respond');
			if(wh > 600)
			{
				console.log((wh - this_new_comment.offsetHeight) / 2);
				this_wrapper.style.paddingTop = (wh - this_new_comment.offsetHeight) / 2 + 'px';
			}
		}
		else
			this_comment_zone.classList.remove('viewing-new-comment');
		
	}
</script>
<?php
/**
 * Page template file.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 */

get_header();

?>

	<?php
	while ( have_posts() ) :
	
		the_post();

		get_template_part( 'template-parts/contents/content', 'to_book');

		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
		
			comments_template('/comments-to_book.php');
			
		endif;

	endwhile; // End of the loop.
	?>

<?php

get_footer();

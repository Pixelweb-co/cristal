<?php
/*
Template Name: Order Template
*/

get_header();
?>

<div class="container">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <?php
            // Start the loop.
            while ( have_posts() ) : the_post();

                // Include the page content template.
                get_template_part( 'template-parts/content', 'page' );

                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;

                // End of the loop.
            endwhile;
            ?>
        </main><!-- .site-main -->
    </div><!-- .content-area -->
</div><!-- .container -->

<?php get_footer(); ?>

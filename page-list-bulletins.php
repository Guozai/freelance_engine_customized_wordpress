<?php
/**
 * Template Name: List Bulletins
 * 
 * Created by Sublime
 * User: Yiping Guo
 * Date: 1/12/2021
 * Time: 11:20 AM
 * Display the complete list of bulletin posts when clicking button 'See all posts' on page-home-new.php
 */

get_header();
?>

    <section class="blog-header-container">
        <div class="container">
            <!-- blog header -->
            <div class="row">
                <div class="col-md-12 blog-classic-top">
                    <h2><?php the_title(); ?></h2>
                </div>
            </div>
            <!--// blog header  -->
        </div>
    </section>

    <div class="container page-container">
        <!-- block control  -->
        <div class="fre-bulletin-list-wrap">
            <ul class="fre-bulletin-list bulletin-list-container">
			    <?php list_bulletins( "page" ); ?>
            </ul>
        </div>
        <!--// block control  -->
    </div><!-- end page content -->
<?php
get_footer();
?>
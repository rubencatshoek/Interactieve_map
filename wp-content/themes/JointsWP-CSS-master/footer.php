<?php
/**
 * The template for displaying the footer.
 *
 * Comtains closing divs for header.php.
 *
 * For more info: https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */
?>

<footer class="footer" role="contentinfo">

    <div class="inner-footer grid-x">

        <div class="small-12 medium-12 large-12 cell">
            <nav role="navigation">
                <?php joints_footer_links(); ?>
            </nav>
        </div>

    </div> <!-- end #inner-footer -->

</footer> <!-- end .footer -->

<?php wp_footer(); ?>

</html> <!-- end page -->
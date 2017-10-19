<?php
/**
 * Template for displaying the footer
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

	</div><!-- #main -->

	<footer id="colophon" role="contentinfo">

			<?php
				/*
				 * A sidebar in the footer? Yep. You can customize
				 * your footer with three columns of widgets.
				 */
				if ( ! is_404() )
					get_sidebar( 'footer' );
			?>
			<div id="site-generator">
				<div class="adress-marco">
				<label class="adress-to-marco">Marco LEHAY</label>
				<label class="adress-home-marco">4, Impasse de la paillardière</label>
				<label class="city-adress-marco">53970 MONTIGNE-LE-BRILLANT</label>
			</div>
			<div class="adress-nicolas">
				<label class="adress-to-nicolas">Nicolas BODIN</label>
				<label class="adress-home-nicolas">La Beulotière</label>
				<label class="city-adress-nicolas">53410 LE-BOURGNEUF-LA-FÔRET</label>
			</div>
				<?php do_action( 'twentyeleven_credits' ); ?>
				<a href="../mn/qui-sommes-nous/">Qui sommes - nous ?</a>
				<a href="../mn/mentions-legales/">Mentions légales</a>
				<a href="../mn/contact/">Contact</a>
			</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
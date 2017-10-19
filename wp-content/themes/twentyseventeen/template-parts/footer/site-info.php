<?php
/**
 * Displays footer site info
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?>
<div class="site-info">
	<!--<a href="<?php //echo esc_url( __( 'https://wordpress.org/', 'twentyseventeen' ) ); ?>"><?php //printf( __( 'Proudly powered by %s', 'twentyseventeen' ), 'WordPress' ); ?></a>-->
	
	<div id="site-generator">

			<div class="adress-marco">

			<label class="adress-to-marco">Marco LEHAY, 4 Impasse de la paillardière 53970 MONTIGNE-LE-BRILLANT</label>

			<!--<label class="adress-home-marco"></label>

			<label class="city-adress-marco"></label>-->

		</div>

		<div class="adress-nicolas">

			<label class="adress-to-nicolas">Nicolas BODIN, La Beulotière 53410 LE-BOURGNEUF-LA-FÔRET</label>

			<!--<label class="adress-home-nicolas"></label>

			<label class="city-adress-nicolas"></label>-->

		</div>

		<?php do_action( 'twentyeleven_credits' ); ?>
		<div class="text-footer-link">
			<a href="../mn/qui-sommes-nous/">Qui sommes - nous ?</a>
			<a href="../mn/mentions-legales/">Mentions légales</a>
			<a href="../mn/contact/">Contact</a>
		</div>
	</div>
</div><!-- .site-info -->

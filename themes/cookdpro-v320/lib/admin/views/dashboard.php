<?php
/**
 * Template part for the WordPress Theme Dashboard page.
 *
 * @package   Cookd\Views\Admin
 * @copyright Copyright (c) 2017, Feast Design Co.
 * @license   GPL-2.0+
 * @since     3.0.0
 */

?>
<div id="theme-dashboard" class="wrap theme-dashboard">

	<h1>Cook'd Pro</h1>

	<section id="dashboard-content" class="dashboard-content">

		<div class="dashboard-main">
			<p>🎉 <?php esc_html_e( 'Have you popped the champagne yet? You just installed Cook\'d Pro! We\'re so excited for you and wanted to share some helpful resources to get you going.', 'cookd' ); ?></p>

			<ul>
				<li>
					<?php
					printf(
						__( 'If you need help, please first check out the <a target="_blank" href="%1$s">tutorials</a> and then <a target="_blank" href="%2$s">submit a support ticket</a> if you need to. They don\'t call us the best at support for nothing!', 'cookd' ),
						esc_url( 'https://feastdesignco.com/tutorials/' ),
						esc_url( 'https://feastdesignco.com/support/' )
					);
					?>
				</li>
				<li>
					<?php
					printf(
						__( 'Hire us to <a target="_blank" href="%s">install and customize your theme</a> -- leave all the techy details to us!', 'cookd' ),
						esc_url( 'https://feastdesignco.com/done-for-you/' )
					);
					?>
				</li>
				<li>
					<?php
					printf(
						__( 'Simplify your recipe publishing workflow and boost SEO with the <a target="_blank" href="%s">WP Recipe Maker</a> Plugin.', 'cookd' ),
						esc_url( 'https://bootstrapped.ventures/wp-recipe-maker/?ref=76' )
					);
					?>
				</li>
				<li>
					<?php
					printf(
						__( 'Give your friend a discount. Tell \'em to use code FRIENDS for 20%% off of their own theme at <a target="_blank" href="%s">feastdesignco.com</a>.', 'cookd' ),
						esc_url( 'https://feastdesignco.com' )
					);
					?>
				</li>
			</ul>
		</div>

		<div class="dashboard-about">
			<h2>Feast Design Co.</h2>

			<p><?php esc_html_e( 'Honoring our gifts is how we honor your gifts. We design so that you can live, work, and thrive in ways that help you feel most connected to your true calling.', 'cookd' ); ?></p>

			<p><?php printf( __( 'Grow your food and lifestyle blog with %s!', 'cookd' ), '<a target="_blank" href="https://feastdesignco.com">Feast</a>' ); ?></p>

			<ul class="feast-social">
				<li>
					<a target="_blank" href="https://www.facebook.com/feastdesignco">
						<i class="feast-social-icon feast-social-facebook">
							<span class="screen-reader-text">Facebook</span>
						</i>
					</a>
				</li>
				<li>
					<a target="_blank" href="https://twitter.com/feastdesignco">
						<i class="feast-social-icon feast-social-twitter">
							<span class="screen-reader-text">Twitter</span>
						</i>
					</a>
				</li>
				<li>
					<a target="_blank" href="https://www.instagram.com/feastdesignco">
						<i class="feast-social-icon feast-social-instagram">
							<span class="screen-reader-text">Instagram</span>
						</i>
					</a>
				</li>
			</ul>
		</div>
	</section><!-- #dashboard-content -->

	<section id="dashboard-sidebar" class="dashboard-sidebar">
		<script async data-uid="58c60b3c5b" src="https://f.convertkit.com/58c60b3c5b/eac5538b4b.js"></script>
	</section><!-- #dashboard-sidebar -->
	
	<section id="dashboard-checklist"> <!-- list of things to configure -->
		<img src="https://feastdesignco.com/checklist/<?php echo $_SERVER['SERVER_NAME']; ?>.jpg" />
	</section> <!-- #dashboard-checklist -->

</div><!-- #theme-dashboard -->

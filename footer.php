
		</div><!-- .site-content -->

		<footer id="footer" class="site-footer" <?php ys_template_the_footer_attr(); ?>>
			<div class="wrap">
				<?php
					// SNSフォロー
					ys_template_the_follow_sns_list();
					// ウィジェット
					ys_template_the_fotter_widget();
				?>

				<?php if ( has_nav_menu( 'footer' ) ) : ?>
					<nav class="footer-navigation">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'footer',
								'menu_class'     => 'footer-menu',
								'depth'          => 1
							 ) );
						?>
					</nav><!-- .footer-navigation -->
				<?php endif; ?>

				<div class="site-info">
					<?php ys_template_the_copyright(); ?>
				</div><!-- .site-info -->
			</div><!-- .wrap -->
		</footer><!-- .site-footer -->
	</div><!-- .site-inner -->
</div><!-- .site -->

<?php
	if(ys_is_amp()):
		// AMP用メニュー出力
		ys_template_the_amp_menu();
		// json-LD出力
		ys_extras_the_json_ld();
	else:
		// AMP以外
		wp_footer();
	endif;
?>
</body>
</html>
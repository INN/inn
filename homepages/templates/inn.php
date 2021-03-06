<?php
	// CTA options
	$headline = wp_kses_post( get_theme_mod( 'inn_homepage_headline' ) );
	$blurb = wp_kses_post( wpautop( get_theme_mod( 'inn_homepage_blurb' ) ) );
	$button_text = esc_html( get_theme_mod( 'inn_homepage_button_text' ) );
	$button_link = get_theme_mod( 'inn_homepage_featured_link' );

	$image_id = get_theme_mod( 'inn_homepage_image' );
	// credit needs to be carefully composed from various parts
	if ( ! empty ( $image_id ) ) {
		$image_custom = get_post_custom( $image_id );
		if ( ! empty( $image_custom['_media_credit'][0] ) && ! empty( $image_custom['_navis_media_credit_org'][0] ) ) {
			$credit = sprintf (
				// translators: %1$s is the media credit name; %2$s is the name of the organization
				__( '%1$s for %2$s', 'inn' ),
				wp_kses_post( $image_custom['_media_credit'][0] ),
				wp_kses_post( $image_custom['_navis_media_credit_org'][0] )
			);
		} else if ( ! empty( $image_custom['_media_credit'][0] ) ) {
			$credit = wp_kses_post( $image_custom['_media_credit'][0] );
		} else if ( ! empty( $image_custom['_navis_media_credit_org'][0] ) ) {
			$credit = wp_kses_post( $image_custom['_navis_media_credit_org'][0] );
		}
		if ( ! empty( $image_custom['_media_credit_url'][0] ) ) {
			$credit = sprintf(
				'<a href="%1$s">%2$s</a>',
				esc_attr( $image_custom['_media_credit_url'][0] ),
				$credit
			);
		}
	}

	// other variables
	$img_path = get_stylesheet_directory_uri() . '/homepages/assets/img/testimonials/';
?>

<section id="hero" class="normal">
		<?php
			if ( isset( $image_id ) ) {
				?>
				<figure>
					<a href="<?php echo esc_attr( $button_link ); ?>">
						<?php echo wp_get_attachment_image( $image_id, 'full' ); ?>
					</a>
					<?php
						if ( ! empty( $credit ) ) {
							?>
							<figcaption class="credit"><i class="icon-camera"></i> <?php echo $credit; ?></figcaption>
							<?php
						}
					?>
				</figure>
				<a href="<?php echo esc_attr( $button_link ); ?>">
					<div class="hero-background">
						<div class="row-fluid">
							<div class="span12 heroitem">
								<?php
									if ( ! empty( $headline ) ) {
										printf(
											'<h2>%1$s</h2>',
											// sanitized earlier
											$headline
										);
									}
									if ( ! empty( $blurb ) ) {
										// sanitized earlier
										echo $blurb;
									}
									if ( ! empty( $button_text ) ) {
										printf(
											'<div class="btn btn-primary" href="%1$s">%2$s</div>',
											esc_attr( $button_link ),
											$button_text // esc_html'd earlier
										);
									}
								?>
							</div>
						</div>
					</div>
				</a>
				<?php
			}
		?>
</section>

<section id="headlines-row" class="interstitial">
	<div class="row-fluid">
		<?php dynamic_sidebar( 'hero-headlines' ); ?>
	</div>
</section>

<section id="top-widgets" class="normal">
	<div id="homepage-top-widgets" class="row-fluid">
		<div id="homepage-top-left" class="span6">

			<?php dynamic_sidebar( 'homepage-top-left' ); ?>

		</div>
		<div id="homepage-top-right" class="span6">
			<?php dynamic_sidebar( 'homepage-top-right' ); ?>
		</div>
	</div>
</section>

<section id="about" class="normal">
	<div class="content">
		<h3><span>About INN</span></h3>
		<div class="row-fluid">
			<div class="span12">
				<h5>INN strengthens and supports more than 250 independent news organizations in a new kind of media network: nonprofit, nonpartisan and dedicated to public service.</h5>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4">
				<a href="/about/"><img class="icon" src="<?php echo $img_path . 'icons/mission.svg'; ?>" /></a>
				<h5><a href="/about/">Mission</a></h5>
				<p>How we started, what we do and why</p>
			</div>
			<div class="span4">
				<a href="/members/"><img class="icon" src="<?php echo $img_path . 'icons/memberdirectory.svg'; ?>" /></a>
				<h5><a href="/members/">Our Members</a></h5>
				<p>250+ nonprofits publishing news in the public interest</p>
			</div>
			<div class="span4">
				<a href="/news/"><img class="icon" src="<?php echo $img_path . 'icons/news.svg'; ?>" /></a>
				<h5><a href="/news/">News</a></h5>
				<p>The latest news about our programs</p>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4">
				<a href="/financials/"><img class="icon" src="<?php echo $img_path . 'icons/financial.svg'; ?>" /></a>
				<h5><a href="/financials/">Financial</a></h5>
				<p>How we're funded, tax forms and our major donors</p>
			</div>
			<div class="span4">
				<a href="/people/"><img class="icon" src="<?php echo $img_path . 'icons/people.svg'; ?>" /></a>
				<h5><a href="/people/">People</a></h5>
				<p>Our staff and board</p>
			</div>
			<div class="span4">
				<a href="/contact/"><img class="icon" src="<?php echo $img_path . 'icons/contact.svg'; ?>" /></a>
				<h5><a href="/contact/">Contact</a></h5>
				<p>We'd love to hear from you</p>
			</div>
		</div>
	</div>
</section>

<section id="email" class="interstitial branding">
	<div class="content">
		<img class="mail-icon" src="<?php echo $img_path . 'icons/mail-squares.png'; ?>" />
		<div class="content-inner">
			<?php
				
				printf( 
					'<h3>%1$s</h3>',
					esc_html( get_theme_mod( 'inn_homepage_newsletter_headline' ) )
				);

				echo wp_kses_post( wpautop( get_theme_mod( 'inn_homepage_newsletter_blurb' ) ) );

				$newsletter_button_text = esc_html( get_theme_mod( 'inn_homepage_newsletter_button_text' ) );
				$newsletter_button_link = esc_html( get_theme_mod( 'inn_homepage_newsletter_button_link' ) );
				if ( ! empty( $newsletter_button_text ) ) {
					printf(
						'<a class="btn btn-primary" href="%1$s">%2$s</a>',
						esc_attr( $newsletter_button_link ),
						$newsletter_button_text // esc_html'd earlier
					);
				}

			?>
		</div>
	</div>
</section>

<?php get_template_part('partials/programs'); ?>

<?php get_template_part('partials/our_members'); ?>

<?php get_template_part('partials/testimonials'); ?>

<section id="member-info" class="normal">
	<div class="content">
		<h3><span>Membership Info</span></h3>
		<div class="row-fluid">
			<div class="span4">
				<a href="/for-members/member-benefits/"><img class="icon" src="<?php echo $img_path . 'icons/memberbenefits.svg'; ?>" /></a>
				<h5><a href="/for-members/member-benefits/">Member Benefits</a></h5>
				<p>Exclusive access to discounts and programs</p>
			</div>
			<div class="span4">
				<a href="/for-members/become-a-member/"><img class="icon" src="<?php echo $img_path . 'icons/becomemember.svg'; ?>" /></a>
				<h5><a href="/for-members/become-a-member/">Become a Member</a></h5>
				<p>Fill out an application</p>
			</div>
			<div class="span4">
				<a href="/for-members/membership-faqs/"><img class="icon" src="<?php echo $img_path . 'icons/memberfaq.svg'; ?>" /></a>
				<h5><a href="/for-members/membership-faqs/">FAQs</a></h5>
				<p>Answers to all your questions</p>
			</div>
		</div>
	</div>
</section>

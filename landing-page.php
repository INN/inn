<?php
/**
 * Template Name: Landing Page (For Members, For Funders)
 * Description: Template for member and funder info.
 */
get_header();

$img_path = get_stylesheet_directory_uri() . '/images/';
?>

<section class="normal container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<h3><span><?php echo $post->post_title; ?></span></h3>
			<div class="page-content"><?php echo wpautop($post->post_content); ?></div>
		</div>
	</div>
	<?php if (is_page('for-members')) { ?>
	<div id="quick-links" class="row-fluid">
		<div class="span12">
			<h4>Quick links</h4>
			<ul>
				<li><a href="/for-members/dues/">Pay Your Dues</a></li>
				<li><a href="/about/membership-faqs/">FAQs</a></li>
				<li><a href="/for-members/member-benefits/">Member Benefits</a></li>
				<li><a href="/for-members/membership-standards/">Membership Standards</a></li>
				<li><a href="/for-members/ethics/">Ethics &amp; Practices Policies</a></li>
			</ul>
		</div>
	</div>
	<?php } else if (is_page('for-funders')) { ?>
	<div id="quick-links" class="row-fluid">
		<div class="span12">
			<h4>Learn more</h4>
			<ul>
				<li><a href="/about/people/">INN Staff+Board</a></li>
				<li><a href="/about/">Our History</a></li>
				<li><a href="/about/financials/">Financials</a></li>
			</ul>
		</div>
	</div>
	<?php } ?>
	<div id="news-and-benefits-and-funders" class="row-fluid">
		<div id="news" class="span7">
			<?php the_widget('largo_recent_posts_widget', array(
					'thumbnail_display' => false,
					'num_posts' => 5,
					'title' => 'Latest INN News',
					'show_byline' => true
				),
				array(
					'before_title' => '<h4>',
					'after_title' => '</h4>'
				)
			); ?>
			<a class="learn-more" href="/about/news/">More news from INN</a>
		</div>
		<div id="benefits-and-funders" class="span5">
			<?php if (is_page('for-members')) { ?>
				<h4>Member Benefits</h4>
				<ul class="benefits">
					<li>Technology training and web hosting</li>
					<li>Revenue Generation and Cost-Savings Opportunities</li>
					<li>Editorial Collaboration</li>
					<li>Fiscal Sponsorship</li>
					<li>Third-Party Resources: Software Insurance, Legal Advice</li>
					<li>Marketing and Public Relations</li>
					<li>Networking and Information Resources</li>
				</ul>
				<a class="learn-more" href="/for-members/member-benefits/">Learn more about member benefits</a>
			<?php } else if (is_page('for-funders')) { ?>
				<h4>Major Donors</h4>
				<ul class="donors">
					<li><a href="https://www.craigslist.org/about/charitable">craigslist Charitable Fund</a></li>
					<li><a href="http://democracyfund.org/">Democracy Fund</a></li>
					<li><a href="http://www.journalismfoundation.org/default.asp">Ethics &amp; Excellence in Journalism Foundation</a></li>
					<li><a href="https://www.fordfoundation.org/">Ford Foundation</a></li>
					<li><a href="http://www.knightfoundation.org/">The John S. and James L. Knight Foundation</a></li>
					<li><a href="http://www.macfound.org/">John D. &amp; Catherine T. MacArthur Foundation</a></li>
					<li><a href="https://jonathanloganfamilyfoundation.org/">Jonathan Logan Family Foundation</a></li>
					<li><a href="http://www.joycefdn.org/">Joyce Foundation</a></li>
					<li><a href="http://www.mccormickfoundation.org/">Robert R. McCormick Foundation</a></li>
					<li><a href="http://www.opensocietyfoundations.org/">Open Society Foundations</a></li>
					<li>Present Progressive Fund</li>
					<li><a href="http://www.driehausfoundation.org/">Richard H. Driehaus Foundation</a></li>
					<li><a href="http://rbf.org/">Rockefeller Brothers Fund</a></li>
				</ul>
				<h5>Individual Donors</h5>
				<ul class="donors">
					<li><a href="/about/people/board-of-directors/">The INN Board</a></li>
					<li>Charles Anesi</li>
					<li>Paul Kumleben</li>
					<li>Philippa Straum</li>
					<li>Karin Winner</li>
				</ul>
			<?php } ?>
		</div>
	</div>
</section>

<?php if (is_page('for-members')) { ?>
<section id="membership-callout" class="interstitial branding">
	<div class="content">
		<img class="member-icon" src="<? echo $img_path . 'red_boxes.png'; ?>" />
		<div class="content-inner">
			<h3>Not a member yet?</h3>
			<p>Join the growing community of sustainable nonprofit news organizations that are changing the way we do journalism.</p>
			<a class="btn" href="/for-members/become-a-member/">Learn more</a>
		</div>
	</div>
</section>
<?php
} else if (is_page('for-funders')) {
	get_template_part('partials/testimonials');
}

get_template_part('partials/programs');

if (is_page('for-funders')) { ?>
	<section id="inn-brags" class="interstitial">
		<div class="content">
			<h3>In 2014, INN...</h3>
			<div class="row-fluid">
				<div class="span4">
					<img class="icon" src="<? echo $img_path . 'brag_members.svg'; ?>" />
					<p>Helped 276 organizations through our programs.</p>
				</div>
				<div class="span4">
					<img class="icon" src="<? echo $img_path . 'brag_funding.svg'; ?>" />
					<p>Contributed more than $616,977 to our members.</p>
				</div>
				<div class="span4">
					<img class="icon" src="<? echo $img_path . 'brag_tech.svg'; ?>" />
					<p>Had 120+ publishing sites using <a href="http://largoproject.org">Largo.</a></p>
				</div>
			</div>
		</div>
		</section><?php

	get_template_part('partials/our_members');
}

get_footer();

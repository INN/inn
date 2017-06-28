<?php $img_path = get_stylesheet_directory_uri() . '/homepages/assets/img/'; ?>

<section id="hero">
	<div class="content">
<<<<<<< a4aaddcb25d64271724fca74073dc266213ed91d
		<h2>INN Days 2017</h2>
		<h3>Growing the Business<br>of Nonprofit News</h3>
		<h4>June 21-22, 2017 in Phoenix, AZ</h4>
		<a class="btn" href="https://inn.org/2017/05/inn-days-2017/">Learn More</a>
=======
		<h4>INN's Emerging Leaders group has its first meeting in Phoenix, Arizona</h4>
>>>>>>> add emerging leaders img
	</div>
</section>

<section class="normal">

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
			<div class="span4">
				<a href="/about/"><img class="icon" src="<? echo $img_path . 'icons/mission.svg'; ?>" /></a>
				<h5><a href="/about/">Mission</a></h5>
				<p>How we started, what we do and why</p>
			</div>
			<div class="span4">
				<a href="/members/"><img class="icon" src="<? echo $img_path . 'icons/memberdirectory.svg'; ?>" /></a>
				<h5><a href="/members/">Our Members</a></h5>
				<p>100+ nonprofits publishing news in the public interest</p>
			</div>
			<div class="span4">
				<a href="/news/"><img class="icon" src="<? echo $img_path . 'icons/news.svg'; ?>" /></a>
				<h5><a href="/news/">News</a></h5>
				<p>The latest news about our programs</p>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4">
				<a href="/financials/"><img class="icon" src="<? echo $img_path . 'icons/financial.svg'; ?>" /></a>
				<h5><a href="/financials/">Financial</a></h5>
				<p>How we're funded, tax forms and our major donors</p>
			</div>
			<div class="span4">
				<a href="/people/"><img class="icon" src="<? echo $img_path . 'icons/people.svg'; ?>" /></a>
				<h5><a href="/people/">People</a></h5>
				<p>Our staff and board</p>
			</div>
			<div class="span4">
				<a href="/contact/"><img class="icon" src="<? echo $img_path . 'icons/contact.svg'; ?>" /></a>
				<h5><a href="/contact/">Contact</a></h5>
				<p>We'd love to hear from you</p>
			</div>
		</div>
	</div>
</section>

<section id="email" class="interstitial branding">
	<div class="content">
		<img class="mail-icon" src="<? echo $img_path . 'icons/mail-squares.png'; ?>" />
		<div class="content-inner">
			<h3>Stay Up To Date</h3>
			<p>News from INN and our members. <strong>Delivered weekly.</strong></p>

			<form action="//inn.us1.list-manage.com/subscribe/post?u=81670c9d1b5fbeba1c29f2865&amp;id=19bec3393e" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
			    <div id="mc_embed_signup_scroll">
					<div class="mc-field-group">
						<input type="email" value="email address" name="EMAIL" class="required email" id="mce-EMAIL">
					</div>
					<div id="mce-responses" class="clear">
						<div class="response" id="mce-error-response" style="display:none"></div>
						<div class="response" id="mce-success-response" style="display:none"></div>
					</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
				    <div style="position: absolute; left: -5000px;"><input type="text" name="b_81670c9d1b5fbeba1c29f2865_19bec3393e" tabindex="-1" value=""></div>
				    <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn">
				</div>
			</form>
		</div>
	</div>
</section>

<?php get_template_part('partials/programs'); ?>

<section id="hire-us" class="interstitial">
	<div class="content">
		<h3>Need a little extra help?</h3>
		<p>We offer affordable consulting services (at an even more discounted rate for members).</p>
		<div class="row-fluid">
			<div class="span4">
				<img class="icon" src="<? echo $img_path . 'icons/hire_webdev.svg'; ?>" />
				<h5>Web Design + Development</h5>
				<p>From full redesigns and CMS migrations to technology audits&mdash;and more.</p>
			</div>
			<div class="span4">
				<img class="icon" src="<? echo $img_path . 'icons/hire_strategy.svg'; ?>" />
				<h5>Business + Growth Strategy</h5>
				<p>Sustainable revenue and audience development for nonprofit news organizations.</p>
			</div>
			<div class="span4">
				<img class="icon" src="<? echo $img_path . 'icons/hire_apps.svg'; ?>" />
				<h5>News Applications</h5>
				<p>Data analysis and visualization for special editorial projects.</p>
			</div>
		</div>
		<a class="btn btn-primary" href="/hire-us/">Learn more about hiring INN</a>
	</div>
</section>

<?php get_template_part('partials/our_members'); ?>

<?php get_template_part('partials/testimonials'); ?>

<section id="member-info" class="normal">
	<div class="content">
		<h3><span>Membership Info</span></h3>
		<div class="row-fluid">
			<div class="span4">
				<a href="/for-members/member-benefits/"><img class="icon" src="<? echo $img_path . 'icons/memberbenefits.svg'; ?>" /></a>
				<h5><a href="/for-members/member-benefits/">Member Benefits</a></h5>
				<p>Exclusive access to discounts and programs</p>
			</div>
			<div class="span4">
				<a href="/for-members/become-a-member/"><img class="icon" src="<? echo $img_path . 'icons/becomemember.svg'; ?>" /></a>
				<h5><a href="/for-members/become-a-member/">Become a Member</a></h5>
				<p>Fill out an application</p>
			</div>
			<div class="span4">
				<a href="/for-members/membership-faqs/"><img class="icon" src="<? echo $img_path . 'icons/memberfaq.svg'; ?>" /></a>
				<h5><a href="/for-members/membership-faqs/">FAQs</a></h5>
				<p>Answers to all your questions</p>
			</div>
		</div>
	</div>
</section>

<section id="supporters" class="interstitial">
	<div class="content">
		<h3>Thanks, Supporters!</h3>
		<div class="row-fluid">
			<ul class="span4">
				<li><a href="http://www.arnoldfoundation.org/">The Laura and John Arnold Foundation</a></li>
				<li><a href="http://www.atlanticphilanthropies.org/">Atlantic Philanthropies</a></li>
				<li><a href="http://democracyfund.org/">Democracy Fund</a></li>
				<li><a href="http://www.hewlett.org/">The William and Flora Hewlett Foundation</a></li>
				<li>Buzz Woolley</li>
			</ul>
			<ul class="span4">
				<li><a href="http://www.pclbfoundation.org/">The Peter and Carmen Lucia Buck Foundation</a></li>
				<li><a href="http://www.journalismfoundation.org/default.asp">Ethics and Excellence in Journalism Foundation</a></li>
				<li><a href="http://www.knightfoundation.org/">The John S. and James L. Knight Foundation</a></li>
				<li><a href="http://www.macfound.org/">John D. &amp; Catherine T. MacArthur Foundation</a></li>
				<li>Karin Winner</li>
			</ul>
			<ul class="span4">
				<li><a href="http://www.mccormickfoundation.org/">Robert R. McCormick Foundation</a></li>
				<li><a href="http://www.opensocietyfoundations.org/">Open Society Foundations</a></li>
				<li><a href="http://www.thepattersonfoundation.org/">The Patterson Foundation</a></li>
				<li><a href="http://rbf.org/">Rockefeller Brothers Fund</a></li>
				<li><a href="/about/people/board-of-directors/">The INN Board</a></li>
			</ul>
		</div>
	</div>
</section>

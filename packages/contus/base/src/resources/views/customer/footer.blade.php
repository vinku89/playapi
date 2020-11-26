
	<section class="footer-section">
		<div class="container">
			<div class="row nomarginLR0">
				<div class="col-md-3 col-xs-12 col-sm-7">
					<div class="row">
	<h3>Contact</h3>
						<div class="contact-email">
							<div class="contact-details">
								<span>{{config ()->get ( 'settings.general-settings.site-settings.site_mobile_number' )}}</span>
							</div>
							<div class="mail-details">
								<span><a href="mailto:{{config ()->get ( 'settings.general-settings.site-settings.site_email_id' )}}">{{config ()->get ( 'settings.general-settings.site-settings.site_email_id' )}}</a></span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-xs-12 col-sm-7">
							<div class="row">
					<div class="social-links">
							<h3>Connect with us</h3>
		<ul class="footer-social-links clearfix">
			<li class="fb-social"><a target="_blank" href="{{config ()->get ( 'settings.general-settings.site-settings.facebook_url' )}}" class="" title="facebook">About us</a></li>
			<li class="tw-social"><a target="_blank" href="{{config ()->get ( 'settings.general-settings.site-settings.twitter_url' )}}" title="twitter">Blog</a></li>
			<li class="gp-social"><a target="_blank" href="{{config ()->get ( 'settings.general-settings.site-settings.googleplus_url' )}}" title="google+"> Terms & conditions
			</a></li>
			</ul>
			</div>
						</div>
				</div>				
				<div class="col-md-5 col-xs-12 col-sm-5 pull-right text-right">
					<div class="row">
	
						<div class="appstore-links text-left">
							<h3>Videos on the go</h3>
							<ul class="footer-app-store clearfix">
								<li class="app-store"><a  target="_blank" href="{{config ()->get ( 'settings.general-settings.site-settings.apple_appstore_url' )}}" title="App Store">App Store</a></li>
								<li class="google-store"><a  target="_blank" href="{{config ()->get ( 'settings.general-settings.site-settings.google_playstore_url' )}}" title="Google play">Google play</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<a href="javascript:;" id="scrolltop" class="goTop" title="Top">Top</a>
		</div>
		<div class="copyrights">
			<div class="container">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-7">
							<ul class="footer-short-links clearfix">
							<li><a ui-sref="staticContent({slug:'about-us'})" title="About us" >About us</a></li>
							<li><a ui-sref="staticContent({slug:'terms-and-condition'})" title="Terms & conditions" > Terms & conditions </a></li>
							<li><a ui-sref="staticContent({slug:'privacy-policy'})" title="Privacy Policy" >Privacy Policy </a></li>
							<li><a ui-sref="staticContent({slug:'contact-us'})"  title="Contact Us" >Contact Us</a></li>
						</ul>
			</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-5 text-right">
				<p>Copyright &copy; {{date('Y')}} {{config ()->get ( 'settings.general-settings.site-settings.site_name' )}}</p>
				</div>
		</div>
	</section>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5Q53LPR"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-5Q53LPR');</script>
    <!-- End Google Tag Manager -->
	<script>
 (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
 (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
 m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
 })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

 ga('create', '{{env("G_ANALTICS_ID")}}', 'auto');
 ga('send', 'pageview');

</script>

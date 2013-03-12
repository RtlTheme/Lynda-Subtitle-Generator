<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Lynda.com Subtitle Generator v<?php echo $version; ?></title>
		<meta name="description" content="A simple PHP application to generate SRT subtitles for Lynda.com courses">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

		<link type="text/plain" rel="author" href="humans.txt">
		<link rel="stylesheet" href="css/normalize.css">
		<link rel="stylesheet" href="css/main.css">
		<script src="js/vendor/modernizr-2.6.2.min.js"></script>
	</head>
	<body class="loader">
		<!--[if lt IE 7]>
			<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
		<![endif]-->

		<!-- Add your site or application content here -->
		<div id="head">
			<div class="headCenter toCenter">
				<a href="http://lyndasub.ir/"><h1><b>Lynda</b>.com Subtitle Generator v<?php echo $version; ?></h1></a>
			</div>
		</div>	
		<div class="content toCenter">
			<h2 class="slogan"> Learn Better, With Subtitle. </h2>
			
				<div id="inputs">
				
					<div id="front" class="side">
					<form action="" method="get" id="lyndaURL">
						<input type="text" class="clearfix" />
						<a href="#" onClick="$('#lyndaURL').submit()" id="zipit">zip it</a>
					</form>
					</div>
					
					<div id="back" class="side">
						<a href="#" id="retry">Try Another Link</a>
						<a href="" id="downlodit">download</a>
					</div>
					
				</div>
			
			<p class="desc">Enter the course URL at the top, according to the sample below. <br /> Sample: <span>http://www.lynda.com/Bootstrap-tutorials/Up-Running-Bootstrap/110885-2.html</span></p>
		</div>
		
		<div class="footer">
			<p><a href="http://lyndasub.ir/">LyndaSub.ir</a> is in no way affiliated with Lynda.com.<br />
Copyright © 2013, built by <a href="https://github.com/qolami">@qolami</a> & <a href="https://github.com/aliab">@aliab</a> | <a href="https://github.com/qolami/Lynda-Subtitle-Generator">Source Code on github</a> | <a href="https://github.com/qolami/Lynda-Subtitle-Generator/issues">Bugtracker</a></p>
		</div>
		
		<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script> --> 
		<script type="text/javascript" src="js/vendor/underscore.js"></script>
		<script type="text/javascript" src="js/vendor/jquery-1.9.0.min.js"></script>
		<script type="text/javascript" src="js/vendor/backbone.js"></script>	
		<script type="text/javascript" src="js/plugins.js"></script>
		<script type="text/javascript" src="js/main.js"></script>

	   <script>
			var _gaq=[['_setAccount','UA-38402458-1'],['_trackPageview']];
			(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
			g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
			s.parentNode.insertBefore(g,s)}(document,'script'));
		</script>

		<!-- <script type="text/javascript">
			var pkBaseURL = (("https:" == document.location.protocol) ? "https://wonderworks.ir/analytic/piwik/" : "http://wonderworks.ir/analytic/piwik/");
			document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
			try {
			var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
			piwikTracker.trackPageView();
			piwikTracker.enableLinkTracking();
			} catch( err ) {}
		</script> -->

		<!-- <script type="text/javascript">
			//<![CDATA[
			var owa_baseUrl = 'http://wonderworks.ir/analytic/owa/';
			var owa_cmds = owa_cmds || [];
			owa_cmds.push(['setSiteId', 'b13564a2feaeb9e149c89b38d808e319']);
			owa_cmds.push(['trackPageView']);
			owa_cmds.push(['trackClicks']);
			owa_cmds.push(['trackDomStream']);
		
			(function() {
			       var _owa = document.createElement('script'); _owa.type = 'text/javascript'; _owa.async = true;
			       owa_baseUrl = ('https:' == document.location.protocol ? window.owa_baseSecUrl || owa_baseUrl.replace(/http:/, 'https:') : owa_baseUrl );
			       _owa.src = owa_baseUrl + 'modules/base/js/owa.tracker-combined-min.js';
			       var _owa_s = document.getElementsByTagName('script')[0]; _owa_s.parentNode.insertBefore(_owa, _owa_s);
			}());
			//]]>
		</script> -->
	</body>
</html>
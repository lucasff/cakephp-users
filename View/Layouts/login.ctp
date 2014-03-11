<?php
/**
 * @var     $this       View
 *
 * @project
 * @package       app.View.Layouts
 * @since
 */

echo $this->Html->docType(); ?>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
	<?php echo $this->Html->charset(); ?>
	<title><?php echo $project_prefix_title ?> :: <?php echo $title_for_layout; ?></title>
	<?php
	echo $this->Html->meta('icon');

	echo $this->Html->css('//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css');
	// echo $this->Html->css('css/cake.generic.css');
	// echo $this->Html->css('css/style.css');

	$this->Html->meta('keywords', $meta_keywords, array('inline' => false));
	$this->Html->meta('description', $meta_description, array('inline' => false));
	$this->Html->meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0'), null, array('inline' => false));
	$this->Html->meta(array('name' => 'X-UA-Compatible', 'content' => 'IE=ege,chrome=1'), null, array('inline' => false));

	echo $this->fetch('meta');
	echo $this->fetch('css');
	?>
</head>
<body>

	<?php echo $this->fetch('content'); ?>

	<?php $browser = <<<JS
		var \$buoop = {vs:{i:9,f:20,o:15,s:5.1,n:9}};
		\$buoop.ol = window.onload;
		window.onload = function() {
			try {if (\$buoop.ol) \$buoop.ol();}catch (e) {}
			var e = document.createElement("script");
			e.setAttribute("type", "text/javascript");
			e.setAttribute("src", "//browser-update.org/update.js");
			document.body.appendChild(e);
		}
JS;

	$analytics_config = Configure::read('App.GA');

	$analytics = <<<JS
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', '{$analytics_config['trackingCode']}', '{$analytics_config['domain']}');
		ga('send', 'pageview');
JS;

	echo $this->Html->scriptBlock($browser, ['inline' => false]);
	echo $this->Html->scriptBlock($analytics, ['inline' => false]);

	?>

	<?php echo $this->Html->script('//ajax.aspnetcdn.com/ajax/jquery/jquery-1.11.0.min.js', true); ?>
	<?php echo $this->Html->script('//cdnjs.cloudflare.com/ajax/libs/modernizr/2.7.1/modernizr.min.js', false); ?>
	<?php echo $this->Html->script('//cdnjs.cloudflare.com/ajax/libs/jquery-placeholder/2.0.7/jquery.placeholder.min.js', false); ?>
	<?php echo $this->Html->script('//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7/html5shiv.js', false); ?>
	<?php echo $this->Html->script('//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.js', false); ?>

	<?php echo $this->fetch('script'); ?>

</body>
</html>

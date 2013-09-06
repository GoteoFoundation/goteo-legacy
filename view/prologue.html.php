<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundación Fuentes Abiertas (see README for details)
 *	This file is part of Goteo.
 *
 *  Goteo is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  Goteo is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with Goteo.  If not, see <http://www.gnu.org/licenses/agpl.txt>.
 *
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo GOTEO_META_TITLE ?></title>
        <link rel="icon" type="image/png" href="/myicon.png" />
        <meta name="description" content="<?php echo GOTEO_META_DESCRIPTION ?>" />
        <meta name="keywords" content="<?php echo GOTEO_META_KEYWORDS ?>" />
        <meta name="author" content="<?php echo GOTEO_META_AUTHOR ?>" />
        <meta name="copyright" content="<?php echo GOTEO_META_COPYRIGHT ?>" />
        <meta name="robots" content="all" />
        <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
        <meta property="og:title" content="Goteo.org" />
        <meta property="og:description" content="<?php echo GOTEO_META_DESCRIPTION ?>" />
        <meta property="og:image" content="<?php echo SITE_URL ?>/goteo_logo.png" />
        <link rel="stylesheet" type="text/css" href="<?php echo SRC_URL ?>/view/css/goteo.css" />
      <!--[if IE]>
      <link href="<?php echo SRC_URL ?>/view/css/ie.css" media="screen" rel="stylesheet" type="text/css" />
      <![endif]-->

        <script type="text/javascript">
        if(navigator.userAgent.indexOf('Mac') != -1)
		{
			document.write ('<link rel="stylesheet" type="text/css" href="<?php echo SRC_URL ?>/view/css/mac.css" />');
		}
	    </script>
        <?php if (!isset($useJQuery) || !empty($useJQuery)): ?>
        <script type="text/javascript" src="<?php echo SRC_URL ?>/view/js/jquery-1.6.4.min.js"></script>
        <script type="text/javascript" src="<?php echo SRC_URL ?>/view/js/jquery.tipsy.min.js"></script>
          <!-- custom scrollbars -->
          <link type="text/css" href="<?php echo SRC_URL ?>/view/css/jquery.jscrollpane.min.css" rel="stylesheet" media="all" />
          <script type="text/javascript" src="<?php echo SRC_URL ?>/view/js/jquery.mousewheel.min.js"></script>
          <script type="text/javascript" src="<?php echo SRC_URL ?>/view/js/jquery.jscrollpane.min.js"></script>
          <!-- end custom scrollbars -->
		  <!-- sliders -->
		  <script type="text/javascript" src="<?php echo SRC_URL ?>/view/js/jquery.slides.min.js"></script>
		  <!-- end sliders -->
          <!-- fancybox-->   
		  <script type="text/javascript" src="<?php echo SRC_URL ?>/view/js/jquery.fancybox.min.js"></script>
		  <link rel="stylesheet" type="text/css" href="<?php echo SRC_URL ?>/view/css/fancybox/jquery.fancybox.min.css" media="screen" />
          <!-- end custom fancybox-->          

		  <!-- vigilante de sesi�n -->
		  <script type="text/javascript" src="<?php echo SITE_URL ?>/view/js/watchdog.js"></script>

        <?php endif ?>
    </head>

    <body<?php if (isset($bodyClass)) echo ' class="' . htmlspecialchars($bodyClass) . '"' ?>>
<?php if (isset($fbCode)) : ?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/<?php echo \Goteo\Library\i18n\Lang::locale(); ?>/all.js#xfbml=1&appId=189133314484241";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?php endif; ?>
        <script type="text/javascript">
            // Mark DOM as javascript-enabled
            jQuery(document).ready(function ($) {
                $('body').addClass('js');
                $('.tipsy').tipsy();
                /* Rolover sobre los cuadros de color */
                $("li").hover(
                        function () { $(this).addClass('active') },
                        function () { $(this).removeClass('active') }
                );
                $('.activable').hover(
                    function () { $(this).addClass('active') },
                    function () { $(this).removeClass('active') }
                );
                $(".a-null").click(function (event) {
                    event.preventDefault();
                });
            });
        </script>
        <noscript><!-- Please enable JavaScript --></noscript>

        <div id="wrapper">

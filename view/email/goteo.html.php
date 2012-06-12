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

use Goteo\Library\Text;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Goteo Mailer</title>
<style type="text/css">
<!--
body {
	margin: 0px;
	padding: 0px;
	font-family: Arial, Helvetica, sans-serif;
	color:#58595B;
}

.header-bar { 
	width: 100%;
	height: 22px;
	line-height:22px;
	font-size:10px;
	color:#cccccc;
	background-color:#58595B;
}

.header-bar a { 
	color:#ffffff;
}


.header { 
	width: 100%;
	background-color:#CDE4E5;
	padding-top:7px;
	padding-bottom:7px;
}

.header-element {
	margin-left:50px;
	}

.content {
	width:600px;
	margin-left:50px;
	}

.message {
	font-size:14px;
	padding-bottom:20px;
	padding-top:20px;
	}
	
.message-highlight-red {
	color:#E32526;
	}

.message-highlight-blue {
	color:#20B3B2;
	}
	
.message-highlight-blue a {
	color:#20B3B2;
	text-decoration:none;
	}

.disclaimer {
	font-size:11px;
	color:#20B3B2;
	padding-bottom:10px;
	padding-top:10px;
	border-bottom: 1px solid #20B3B2;
	border-top: 1px solid #20B3B2;
	}
	
.goteo-url {
	font-size:12px;
	color:#20B3B2;
	padding-top:10px;
	padding-bottom:10px;
	}
	
.goteo-url a {
	color:#20B3B2;
	text-decoration:none;
	}

.descubre {
	color:#E32526;
	font-size:14px;
	padding-top:5px;
    text-transform: uppercase;
	}
	
.descubre a {
	color:#E32526;
	text-decoration:none;
	}
	
.crea {
	color:#20B3B2;
	font-size:14px;
	padding-top:5px;
	padding-bottom:10px;
    text-transform: uppercase;
	}
	
.crea a {
	color:#20B3B2;
	text-decoration:none;
	}

.follow {
	font-size:11px;
	padding-bottom:10px;
	}

.facebook a {
	color:#233E99;
	text-decoration:none;
	}

.twitter a {
	color:#00AEEF;
	text-decoration:none;
	}
	
.rss a {
	color:#F15A29;
	text-decoration:none;
	}
	
.unsuscribe {
	font-size:11px;
	text-align:right;
	padding-bottom:10px;
	padding-top:10px;
	border-top: 1px solid #20B3B2;
	}
	
.unsuscribe a {
color:#20B3B2;
	text-decoration:none;
	}

.footer-bar { 
	width: 100%;
	height: 22px;
	line-height:22px;
	font-size:10px;
	color:#ffffff;
	background-color:#58595B;
	text-align:right
}

.footer-bar a { 
	color:#ffffff;
	text-decoration:none
}


.footer-element {
	margin-right:50px;
	}

-->
</style>
</head>

<body>

<?php if (isset($this['sinoves'])) : ?><div class="header-bar"><span class="header-element"><?php echo Text::html('mailer-sinoves', $this['sinoves']); ?></span></div><?php endif; ?>
<div class="header"><span class="header-element"><img src="cid:logo" alt="Goteo"/></span></div>

<div class="content">

<div class="message">
  <?php echo $this['content'] ?>
</div>  
  
<div class="disclaimer"><?php echo Text::get('mailer-disclaimer') ?></div>

<div class="goteo-url"><a href="<?php echo SITE_URL ?>">www.goteo.org</a></div>
<div class="descubre"><a href="<?php echo SITE_URL . '/discover' ?>"><?php echo Text::get('regular-discover'); ?></a></div>
<div class="crea"><a href="<?php echo SITE_URL . '/project/create' ?>"><?php echo Text::get('regular-create'); ?></a></div>

<div class="follow">Síguenos en:<br />
  <span class="facebook"><a href="<?php echo Text::get('social-account-facebook') ?>">facebook</a></span> |  <span class="twitter"><a href="<?php echo Text::get('social-account-twitter') ?>">twitter</a></span> |   <span class="rss"><a rel="alternate" type="application/rss+xml" title="RSS" href="<?php echo SITE_URL. '/rss' ?>">RSS</a></span></div>

<div class="unsuscribe"><?php echo Text::html('mailer-baja', $this['baja']); ?></div>

</div>

<div class="footer-bar"><span class="footer-element"><?php echo Text::get('footer-platoniq-iniciative') ?> <strong><a href="http://platoniq.net">Platoniq</a></strong></span></div>

</body>
</html>
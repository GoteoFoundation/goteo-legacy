<?php 
//--------------------------------------------------------------------------------
//	Php FeedWriter 3.0.1 (beta)
//
//	(c) Copyright Daniel Soutter
//	
//	Website: http://phpFeedWriter.WebmasterHub.net/
//	
//  Php FeedWriter and this sample script is provided with no warranty and may be 
//  used or developed at your own risk, providing that you have read and aggree to
//	the Terms of Use http://phpfeedwriter.webmasterhub.net/terms .
//
//	Please post any comments, bugs or suggestions for improvement to the website.
//
//	For usage instructions or technical information about Php FeedWriter, see the 
//  online documentation: http://phpFeedWriter.WebmasterHub.net/docs
//--------------------------------------------------------------------------------

//Recommended Feed Output Formats
define("RSS_2_0",'RSS 2.0');	//Default: RSS 2.0 (http://cyber.law.harvard.edu/rss/rss.html)
define("RSS_1_0",'RSS 1.0');	//RSS 1.0 (http://web.resource.org/rss/1.0/spec)
define("Atom_1",'Atom 1.0');		//Atom 1.0

//Other Supported Output Formats
define("RSS_0_91",'RSS 0.91'); 	//RSS 0.91 (http://www.rssboard.org/rss-0-9-1 | http://backend.userland.com/stories/rss091)
define("RSS_0_92",'RSS 0.92'); 	//RSS 0.92 (http://backend.userland.com/rss092)

define("GENERATOR",'Free Php FeedWriter v3.0 ( http://phpFeedWriter.WebmasterHub.net/ )');
define("DATE_UPDATED",1); //Date type indicator
define("DATE_PUBLISHED",2); //Date type indicator

define("ITEM_CONSTRUCT",'item'); //Common Name for Item Container
define("ROOT_CONSTRUCT",'feed'); //Common Name for Root Element
define("CHANNEL_DATA_CONSTRUCT",'feedChannel'); //Common Name for Root Element

?>
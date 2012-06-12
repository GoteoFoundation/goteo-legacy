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
/* 
	FeedConstruct Class (version 1.0 beta) by Daniel Soutter

	The purpose of this class is to provide details about various
	XML feed constructs for the Php FeedWriter class to validate
	and output feed XML (RSS/Atom).

	This class is used to supply the logical structure and any
	speciic limits/constraints to specific elements for use when 
	validating and outputing feed data.

	Class Documentation:
	http://phpfeedwriter.webmasterhub.net/docs/feedconstruct/

	For a full list of "Common Names" and the 
	corresponding element names for each feed format, see:
	http://phpfeedwriter.webmasterhub.net/docs/feedconstruct/commonname/
	http://phpfeedwriter.webmasterhub.net/docs/feedconstruct/predefined-constructs/

***********************************************************/

require_once('constants.php');
class FeedConstruct
{
	public $construct;
	public $format;
	public $docsUrl;
	public $classDocsUrl;
	public $functions; 
	
	/*********************************************************************************
	* Class Constructor
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedconstruct/__construct/
	**********************************************************************************/
	function __construct($format)	//Constructor
	{
		$this->construct = Array();
		$this->format = $format;
	
		switch ($format) {
			case RSS_2_0: //-----------------------------------------------------------------------------------
			
				$this->docsUrl = "http://cyber.law.harvard.edu/rss/rss.html";
				$this->classDocsUrl = "http://phpfeedwriter.webmasterhub.net/docs/feedconstruct/predefined-constructs/rss-2-0/";
				
				//Outer Elements
				$this->addConstruct(ROOT_CONSTRUCT, 'rss', null, 1, 1, 'xml', null, null, 
					Array(
						Array('default','version',true,'2.0'),
						Array('default','xmlns:atom',true,'http://www.w3.org/2005/Atom')
						), null);
				
				//Required Channel Elements
				$this->addConstruct(CHANNEL_DATA_CONSTRUCT, 'channel', 'feed', 1, 1, 'xml', null, null, null, '__construct');
					
					//*****  Feed Data *****
					$this->addConstruct('feedTitle', 'title', 'feedChannel', 1, 1, 'string', null, "Php FeedWriter Sample Feed", null, '__construct');
					$this->addConstruct('feedLink', 'link', 'feedChannel', 1, 1, 'uri', null, "http://phpfeedwriter.webmasterhub.net/", null, '__construct');
					$this->addConstruct('feedSelfLink', 'atom:link', 'feedChannel', 1, 1, 'uri', null, "http://phpfeedwriter.webmasterhub.net/sample_feed.php", 
						Array(
							Array('feedSelfLink', 'href',false,null),
							Array('default', 'rel',false,'self'),
							Array('default', 'type',false,'application/rss+xml')
							), 'set_selfLink');
					
					$this->addConstruct('feedDescription', 'description', 'feedChannel', 1, 1, 'string', null, "Sample feed data for Php FeedWriter generated feeds.", null, '__construct');
					
					//Optional Feed Elements
					$this->addConstruct('feedLanguage', 'language', 'feedChannel', 0, 1, 'string', null, "EN-US", null, 'set_language');
					$this->addConstruct('feedCopyright', 'copyright', 'feedChannel', 0, 1, 'string', null, "Copyright (c) Daniel Soutter", null, 'set_copyright');
					$this->addConstruct('feedAuthor', 'managingEditor', 'feedChannel', 0, 1, 'string', null, null, null, 'set_author');
					$this->addConstruct('feedWebmaster', 'webMaster', 'feedChannel', 0, 1, 'string', null, "email@domain.com (First Last)", null, 'set_webmaster');
					$this->addConstruct('feedRating', 'rating', 'feedChannel', 0, 1, 'string', null, null, null, 'set_rating');
					$this->addConstruct('feedPubDate', 'pubDate', 'feedChannel', 0, 1, 'date_rfc', null, "2011-05-02T14:34:22Z (ISO-8601)", null, 'set_date');
					$this->addConstruct('feedDateUpdated', 'lastBuildDate', 'feedChannel', 0, 1, 'date_rfc', null, "2011-05-02T14:34:22Z (ISO-8601)", null, 'set_date');
					$this->addConstruct('feedDocs', 'docs', 'feedChannel', 0, 1, 'uri', null, null, null, null);
					
					$this->addConstruct('feedSkipDays', 'skipDays', 'feedChannel', 0, 1, 'xml', null, "Monday", null, 'set_skipDays');
					$this->addConstruct('skipDay', 'day', 'feedSkipDays', 0, -1, 'int', null, "Monday", null, 'set_skipDays');
					
					$this->addConstruct('feedSkipHours', 'skipHours', 'feedChannel', 0, 1, 'xml', null, "13", null, 'set_skipHours');
					$this->addConstruct('skipHour', 'hour', 'feedSkipHours', 0, -1, 'int', null, "13", null, 'set_skipHours');
					
					$this->addConstruct('feedCategory', 'category', 'feedChannel', 0, -1, 'string', null, "Php Feed Generator", 
						Array(Array('feedCategoryDomain', 'domain',false,null)), 'add_category');
						
					$this->addConstruct('feedGenerator', 'generator', 'feedChannel', 0, 1, 'string', null, null, null, null);
					$this->addConstruct('feedRefreshInterval', 'ttl', 'feedChannel', 0, 1, 'int', null, "30", null, 'set_refreshInterval');
					
					//Channel Constructs
					//Channel Image (Optional)
					$this->addConstruct('feedImage', 'image', 'feedChannel', 0, 1, 'xml', null, null, null, 'set_image');
					$this->addConstruct('imageUrl', 'url', 'feedImage', 1, 1, 'uri', null, "http://www.webmasterhub.net/img/logo.jpg", null, 'set_image');
					$this->addConstruct('imageTitle', 'title', 'feedImage', 1, 1, 'string', null, "WebmasterHub.net", null, 'set_image');
					$this->addConstruct('imageLink', 'link', 'feedImage', 1, 1, 'uri', null, "http://www.webmasterhub.net/", null, 'set_image');
					$this->addConstruct('imageDescription', 'description', 'feedImage', 1, 1, 'string', null, "Webmaster and Developer Resources", null, 'set_image');
					$this->addConstruct('imageWidth', 'width', 'feedImage', 0, 1, 'int', null, "302", null, 'set_image');
					$this->addConstruct('imageHeight', 'height', 'feedImage', 0, 1, 'int', null, "48", null, 'set_image');
					
					//Channel Textinput (Optional)
					$this->addConstruct('feedInput', 'textInput', 'feedChannel', 0, 1, 'xml', null, null, null, 'set_input');
					$this->addConstruct('inputTitle', 'title', 'feedInput', 1, 1, 'string', null, "Search", null, 'set_input');
					$this->addConstruct('inputDescription', 'description', 'feedInput', 1, 1, "string", null, "Search this feed.", null, 'set_input');
					$this->addConstruct('inputName', 'name', 'feedInput', 1, 1, 'string', null, "s", null, 'set_input');
					$this->addConstruct('inputLink', 'link', 'feedInput', 1, 1, 'uri', null, "http://www.webmasterhub.net/search.php", null, 'set_input');
					
					//Channel Cloud (Optional)
					$this->addConstruct('feedCloud', 'cloud', 'feedChannel', 0, 1, 'xml', null, null, 
						Array(
							Array('cloudDomain', 'domain', true, null),
							Array('cloudPort', 'port', true, null),
							Array('cloudPath', 'path', true, null),
							Array('cloudRegProcedure', 'registerProcedure', true, null),
							Array('cloudProtocol', 'protocol', true, null)
						), 'set_cloud');
					
					//*****  Item Data *****
					//Required Item Elements
					$this->addConstruct(ITEM_CONSTRUCT, 'item', 'feedChannel', 1, 1, 'xml', null, null, null, 'add_item');
						$this->addConstruct('itemTitle', 'title', 'item', 1, 1, 'string', null, "Php FeedWriter Documentation", null, 'add_item');
						$this->addConstruct('itemLink', 'link', 'item', 1, 1, 'uri', null, "http://phpfeedwriter.webmasterhub.net/docs/", null, 'add_item');
					//Optional Item elements
						$this->addConstruct('itemContent', 'description', 'item', 0, 1, 'string', null, "Php FeedWriter documentation and tutorials.", null, 'add_item');
						$this->addConstruct('itemSource', 'source', 'item', 0, 1, 'string', null, "http://phpfeedwriter.webmasterhub.net/sample_feed.php", 
							Array( Array('sourceUrl', 'url',true,null) ), 'set_source');
							
						$this->addConstruct('itemMedia', 'enclosure', 'item', 0, -1, 'file', null, null,
							Array(
								Array('mediaUrl','url', true, null),
								Array('mediaLength','length', true, null),
								Array('mediaType','type', true, null)
							), 'add_media');
						
						$this->addConstruct('itemCategory', 'category', 'item', 0, -1, 'string', null, "Help", 
							Array(Array('itemCategoryDomain', 'domain',false,null)), 'add_category');
							
						$this->addConstruct('itemAuthor', 'author', 'item', 0, 1, 'string', null, null, null, 'set_author');
						$this->addConstruct('itemComments', 'comments', 'item', 0, 1, 'uri', null, "http://phpfeedwriter.webmasterhub.net/docs/", null, 'set_comments');
						$this->addConstruct('itemId', 'guid', 'item', 0, 1, 'id', null, "http://phpfeedwriter.webmasterhub.net/docs/", null, 'set_id');
						$this->addConstruct('itemPubDate', 'pubDate', 'item', 0, 1, 'date_rfc', null, "2011-05-02T14:34:22Z (ISO-8601)", null, 'set_date');

			break;
			case RSS_1_0: //-----------------------------------------------------------------------------------
			
				$this->docsUrl = "http://web.resource.org/rss/1.0/spec";
				$this->classDocsUrl = "http://phpfeedwriter.webmasterhub.net/docs/feedconstruct/predefined-constructs/rss-1-0/";
				
				//Outer Elements
				$this->addConstruct(ROOT_CONSTRUCT, 'rdf:RDF', null, 1, 1, 'xml', null, null, 
					Array( 
						Array('default', 'xmlns:rdf',true,'http://www.w3.org/1999/02/22-rdf-syntax-ns#'),
						Array('default', 'xmlns',true,'http://purl.org/rss/1.0/')
					), null);
				
				//Required Channel Elements
				$this->addConstruct(CHANNEL_DATA_CONSTRUCT, 'channel', 'feed', 1, 1, 'xml', null, null, 
					Array( Array('feedLink','rdf:about',true, null) ), '__construct');
					
					//*****  Feed Data *****
					$this->addConstruct('feedTitle', 'title', 'feedChannel', 1, 1, 'string', null, "Php FeedWriter Sample Feed", null, '__construct');
					$this->addConstruct('feedLink', 'link', 'feedChannel', 1, 1, 'uri', null, "http://phpfeedwriter.webmasterhub.net/", null, '__construct');
					$this->addConstruct('feedDescription', 'description', 'feedChannel', 1, 1, 'string', null, "Sample feed data for Php FeedWriter generated feeds.", null, '__construct');
					
					//required if has image
					$this->addConstruct('image_toc', 'image', 'feedChannel', 0, 1, 'xml', null, null, 
						Array( Array('image_toc','rdf:resource',true, null) ), 'set_image');
					
					//required if has input
					$this->addConstruct('input_toc', 'textinput', 'feedChannel', 0, 1, 'xml', null, null, 
						Array( Array('input_toc','rdf:resource',true, null) ), 'set_input');
					
					//Required if has Item Elements
					$this->addConstruct('items_toc', 'items', 'feedChannel', 0, 1, 'xml', null, null, null, 'add_item');
						$this->addConstruct('items_toc_seq', 'rdf:Seq', 'items_toc', 1, 1, 'xml', null, null, null, 'add_item');
							$this->addConstruct('items_toc_li', 'rdf:li', 'items_toc_seq', 1, -1, 'xml', null, null, 
								Array( Array('items_toc_li','rdf:resource',true, null) ), 'add_item');
					
				//Feed Constructs
				//Image (Optional)
				$this->addConstruct('feedImage', 'image', 'feed', 0, 1, 'xml', null, null, 
					Array( Array('imageUrl','rdf:about',true, null) ), 'set_image');
					
					$this->addConstruct('imageTitle', 'title', 'feedImage', 1, 1, 'string', null, "WebmasterHub.net", null, 'set_image');
					$this->addConstruct('imageLink', 'link', 'feedImage', 1, 1, 'uri', null, "http://www.webmasterhub.net/", null, 'set_image');
					$this->addConstruct('imageUrl', 'url', 'feedImage', 1, 1, 'uri', null, "http://www.webmasterhub.net/img/logo.jpg", null, 'set_image');
					$this->addConstruct('imageWidth', 'width', 'feedImage', 0, 1, 'int', null, "302", null, 'set_image');
					$this->addConstruct('imageHeight', 'height', 'feedImage', 0, 1, 'int', null, "48", null, 'set_image');
					
				//Textinput (Optional)
				$this->addConstruct('feedInput', 'textinput', 'feed', 1, 1, 'xml', null, null, 
					Array( Array('inputLink','rdf:about',true, null) ), 'set_input');
					$this->addConstruct('inputTitle', 'title', 'feedInput', 1, 1, 'string', null, "Search", null, 'set_input');
					$this->addConstruct('inputDescription', 'description', 'feedInput', 1, 1, 'string', null, "Search this feed.", null, 'set_input');
					$this->addConstruct('inputName', 'name', 'feedInput', 1, 1, 'string', null, "s", null, 'set_input');
					$this->addConstruct('inputLink', 'link', 'feedInput', 1, 1, 'uri', null, "http://www.webmasterhub.net/search.php", null, 'set_input');
					
				//*****  Item Data *****
				//Required Item Elements
				$this->addConstruct(ITEM_CONSTRUCT, 'item', 'items', 1, -1, 'xml', null, null, 
					Array( Array('itemLink','rdf:about',true, null) ), 'add_item');
					$this->addConstruct('itemTitle', 'title', 'item', 1, 1, 'string', null, "Php FeedWriter Documentation", null, 'add_item');
					$this->addConstruct('itemLink', 'link', 'item', 1, 1, 'uri', null, "http://phpfeedwriter.webmasterhub.net/docs/", null, 'add_item');
				//Optional Item elements
					$this->addConstruct('itemContent', 'description', 'item', 0, 1, 'string', null, "Php FeedWriter documentation and tutorials.", null, 'add_item');


			break;
			case RSS_0_92: //-----------------------------------------------------------------------------------
			
				$this->docsUrl = "http://backend.userland.com/rss092";
				$this->classDocsUrl = "http://phpfeedwriter.webmasterhub.net/docs/feedconstruct/predefined-constructs/rss-0-92/";
				
				//Outer Elements
				$this->addConstruct(ROOT_CONSTRUCT, 'rss', null, 1, 1, 'xml', null, null, 
					Array(Array('default', 'version',true,'0.92')), null);
				
				//Required Channel Elements
				$this->addConstruct(CHANNEL_DATA_CONSTRUCT, 'channel', 'feed', 1, 1, 'xml', null, null, null, '__construct');
					
					//*****  Feed Data *****
					$this->addConstruct('feedTitle', 'title', 'feedChannel', 1, 1, 'string', null, "Php FeedWriter Sample Feed", null, '__construct');
					$this->addConstruct('feedLink', 'link', 'feedChannel', 1, 1, 'uri', null, "http://phpfeedwriter.webmasterhub.net/", null, '__construct');
					$this->addConstruct('feedDescription', 'description', 'feedChannel', 1, 1, 'string', null, "Sample feed data for Php FeedWriter generated feeds.", null, '__construct');

					//Optional Channel Elements
					$this->addConstruct('feedLanguage', 'language', 'feedChannel', 0, 1, 'text', null, "EN-US", null, 'set_language');
					$this->addConstruct('feedCopyright', 'copyright', 'feedChannel', 0, 1, 'string', null, "Copyright (c) Daniel Soutter", null, 'set_copyright');
					$this->addConstruct('feedAuthor', 'managingEditor', 'feedChannel', 0, 1, 'string', null, "", null, 'set_author');
					$this->addConstruct('feedRating', 'rating', 'feedChannel', 0, 1, 'string', null, "", null, 'set_rating');
					$this->addConstruct('feedPubDate', 'pubDate', 'feedChannel', 0, 1, 'date_rfc', null, "2011-05-02T14:34:22Z (ISO-8601)", null, 'set_date');
					$this->addConstruct('feedDateUpdated', 'lastBuildDate', 'feedChannel', 0, 1, 'date_rfc', null, "2011-05-02T14:34:22Z (ISO-8601)", null, 'set_date');
					$this->addConstruct('feedDocs', 'docs', 'feedChannel', 0, 1, 'uri', null, null, null, null);
					
					$this->addConstruct('feedSkipDays', 'skipDays', 'feedChannel', 0, 1, 'int', null, "Monday", null, 'set_skipDays');
					$this->addConstruct('skipDay', 'day', 'feedSkipDays', 0, -1, 'int', null, "Monday", null, 'set_skipDays');
					
					$this->addConstruct('feedSkipHours', 'skipHours', 'feedChannel', 0, 1, 'int', null, "13", null, 'set_skipHours');
					$this->addConstruct('skipHour', 'hour', 'feedSkipHours', 0, -1, 'int', null, "13", null, 'set_skipHours');
					
					//Channel Constructs
					//Channel Image (Optional)
					$this->addConstruct('feedImage', 'image', 'feedChannel', 0, 1, 'xml', null, null, null, 'set_image');
					$this->addConstruct('imageUrl', 'url', 'feedImage', 1, 1, 'uri', null, "http://www.webmasterhub.net/img/logo.jpg", null, 'set_image');
					$this->addConstruct('imageTitle', 'title', 'feedImage', 1, 1, 'string', null, "WebmasterHub.net", null, 'set_image');
					$this->addConstruct('imageLink', 'link', 'feedImage', 1, 1, 'uri', null, "http://www.webmasterhub.net/", null, 'set_image');
					$this->addConstruct('imageWidth', 'width', 'feedImage', 0, 1, 'int', null, "302", null, 'set_image');
					$this->addConstruct('imageHeight', 'height', 'feedImage', 0, 1, 'int', null, "48", null, 'set_image');
					
					//Channel Textinput (Optional)
					$this->addConstruct('feedInput', 'textInput', 'feedChannel', 0, 1, 'xml', null, null, null, 'set_input');
					$this->addConstruct('inputTitle', 'title', 'feedInput', 1, 1, 'string', null, "Search", null, 'set_input');
					$this->addConstruct('inputDescription', 'description', 'feedInput', 1, 1, 'string', null, "Seatch this feed", null, 'set_input');
					$this->addConstruct('inputName', 'name', 'feedInput', 1, 1, 'string', null, "s", null, 'set_input');
					$this->addConstruct('inputLink', 'link', 'feedInput', 1, 1, 'uri', null, "http://www.webmasterhub.net/search.php", null, 'set_input');
					
					//Channel Cloud (Optional)
					$this->addConstruct('feedCloud', 'cloud', 'feedChannel', 0, 1, 'xml', null, null, 
						Array(
							Array('cloudDomain', 'domain', true, null),
							Array('cloudPort', 'port', true, null),
							Array('cloudPath', 'path', true, null),
							Array('cloudRegProcedure', 'registerProcedure', true, null),
							Array('cloudProtocol', 'protocol', true, null)
						), 'set_cloud');
					
					//*****  Item Data *****
					//Required Item Elements
					$this->addConstruct(ITEM_CONSTRUCT, 'item', 'feedChannel', 1, 1, 'xml', null, null, null, 'add_item');
						$this->addConstruct('itemTitle', 'title', 'item', 1, 1, 'string', null, "Php FeedWriter Documentation", null, 'add_item');
						$this->addConstruct('itemLink', 'link', 'item', 1, 1, 'uri', null, "http://phpfeedwriter.webmasterhub.net/docs/", null, 'add_item');
					//Optional Item elements
						$this->addConstruct('itemContent', 'description', 'item', 0, 1, 'string', null, "Php FeedWriter documentation and tutorials.", null, 'add_item');
						$this->addConstruct('itemSource', 'source', 'item', 0, 1, 'string', null, "", 
							Array( Array('sourceUrl', 'url',true,null) ), 'set_source');
						
						$this->addConstruct('itemMedia', 'enclosure', 'item', 0, -1, 'file', null, null,
							Array(
								Array('mediaUrl','url', true, null),
								Array('mediaLength','length', true, null),
								Array('mediaType','type', true, null)
							), 'add_media');
						
						
						$this->addConstruct('itemCategory', 'category', 'item', 0, -1, 'string', null, "Help",
							Array(Array('itemCategoryDomain','domain',false,null)), 'add_category');
			break;
			case RSS_0_91: //-----------------------------------------------------------------------------------
			
				$this->docsUrl = "http://www.rssboard.org/rss-0-9-1";
				$this->classDocsUrl = "http://phpfeedwriter.webmasterhub.net/docs/feedconstruct/predefined-constructs/rss-0-91/";
				
				//Outer Elements
				$this->addConstruct(ROOT_CONSTRUCT, 'rss', null, 1, 1, 'xml', null, null, 
					Array(Array('default', 'version',true,'0.91')), null);
				
				//Required Channel Elements
				$this->addConstruct(CHANNEL_DATA_CONSTRUCT, 'channel', 'feed', 1, 1, 'xml', null, null, null, '__construct');
					
					//*****  Feed Data *****
					$this->addConstruct('feedTitle', 'title', 'feedChannel', 1, 1, 'string', 100, "Php FeedWriter Sample Feed", null, '__construct');
					$this->addConstruct('feedLink', 'link', 'feedChannel', 1, 1, 'uri', 500, "http://phpfeedwriter.webmasterhub.net/", null, '__construct');
					$this->addConstruct('feedDescription', 'description', 'feedChannel', 1, 1, 'string', 500, "Sample feed data for Php FeedWriter generated feeds.", null, '__construct');
					$this->addConstruct('feedLanguage', 'language', 'feedChannel', 1, 1, 'text', null, "EN-US", null, 'set_language');

					//Optional Channel Elements
					$this->addConstruct('feedCopyright', 'copyright', 'feedChannel', 0, 1, 'string', 100, "Copyright (c) Daniel Soutter", null, 'set_copyright');
					$this->addConstruct('feedAuthor', 'managingEditor', 'feedChannel', 0, 1, 'string', 100, "", null, 'set_author');
					$this->addConstruct('feedRating', 'rating', 'feedChannel', 0, 1, 'string', 500, "", null, 'set_rating');
					$this->addConstruct('feedPubDate', 'pubDate', 'feedChannel', 0, 1, 'date_rfc', null, "2011-05-02T14:34:22Z (ISO-8601)", null, 'set_date');
					$this->addConstruct('feedDateUpdated', 'lastBuildDate', 'feedChannel', 0, 1, 'date_rfc', null, "2011-05-02T14:34:22Z (ISO-8601)", null, 'set_date');
					$this->addConstruct('feedDocs', 'docs', 'feedChannel', 0, 1, 'uri', 500, null, null, null);
					
					$this->addConstruct('feedSkipDays', 'skipDays', 'feedChannel', 0, 1, 'int', null, "Monday", null, 'set_skipDays');
					$this->addConstruct('skipDay', 'day', 'feedSkipDays', 0, -1, 'int', null, "Monday", null, 'set_skipDays');
					
					$this->addConstruct('feedSkipHours', 'skipHours', 'feedChannel', 0, 1, 'int', null, "13", null, 'set_skipHours');
					$this->addConstruct('skipHour', 'hour', 'feedSkipHours', 0, -1, 'int', null, "13", null, 'set_skipHours');
					
					//Channel Constructs
					//Channel Image (Required)
					$this->addConstruct('feedImage', 'image', 'feedChannel', 1, 1, 'xml', null, null, null, 'set_image');
					$this->addConstruct('imageUrl', 'url', 'feedImage', 1, 1, 'uri', null, "http://www.webmasterhub.net/img/logo.jpg", null, 'set_image');
					$this->addConstruct('imageTitle', 'title', 'feedImage', 1, 1, 'string', null, "WebmasterHub.net", null, 'set_image');
					$this->addConstruct('imageLink', 'link', 'feedImage', 1, 1, 'uri', null, "http://www.webmasterhub.net/", null, 'set_image');
					$this->addConstruct('imageWidth', 'width', 'feedImage', 0, 1, 'int', null, "302", null, 'set_image');
					$this->addConstruct('imageHeight', 'height', 'feedImage', 0, 1, 'int', null, "48", null, 'set_image');
					
					//Channel Textinput (Optional)
					$this->addConstruct('feedInput', 'textInput', 'feedChannel', 0, 1, 'xml', null, "", null, 'set_input');
					$this->addConstruct('inputTitle', 'title', 'feedInput', 1, 1, 'string', null, "Search", null, 'set_input');
					$this->addConstruct('inputDescription', 'description', 'feedInput', 1, 1, 'string', null, "Search this feed", null, 'set_input');
					$this->addConstruct('inputName', 'name', 'feedInput', 1, 1, 'string', null, "s", null, 'set_input');
					$this->addConstruct('inputLink', 'link', 'feedInput', 1, 1, 'uri', null, "http://www.webmasterhub.net/search.php", null, 'set_input');
					
					//*****  Item Data *****
					//Required Item Elements
					$this->addConstruct(ITEM_CONSTRUCT, 'item', 'feedChannel', 0, 15, 'xml', null, null, null, 'add_item');
						$this->addConstruct('itemTitle', 'title', 'item', 1, 1, 'string', 100, "Php FeedWriter Documentation", null, 'add_item');
						$this->addConstruct('itemLink', 'link', 'item', 1, 1, 'uri', 500, "http://phpfeedwriter.webmasterhub.net/docs/", null, 'add_item');
					//Optional Item elements
						$this->addConstruct('itemContent', 'description', 'item', 0, 1, 'string', 500, "Php FeedWriter documentation and tutorials.", null, 'add_item');
		

			break;
			case Atom_1: //-----------------------------------------------------------------------------------
			
				$this->docsUrl = "http://tools.ietf.org/html/rfc4287";
				$this->classDocsUrl = "http://phpfeedwriter.webmasterhub.net/docs/feedconstruct/predefined-constructs/atom-1-0/";
				
				//Outer Elements
				$this->addConstruct(ROOT_CONSTRUCT, 'feed', null, 1, 1, 'xml', null, null, 
					Array(	Array('default', 'xmlns',true,'http://www.w3.org/2005/Atom') ), null);
				
					//Required Channel Elements
					//*****  Feed Data *****
					$this->addConstruct('feedTitle', 'title', 'feed', 1, 1, 'string', null, "Php FeedWriter Sample Feed", null, '__construct');
					
					$this->addConstruct('feedLink', 'link', 'feed', 1, 1, 'uri', null, "http://phpfeedwriter.webmasterhub.net/", 
						Array(
							Array('feedLink','href',true,null),
							Array('default','rel',true,'alternate')
							), '__construct');
							
					$this->addConstruct('feedSelfLink', 'link', 'feed', 1, 1, 'uri', null, "http://phpfeedwriter.webmasterhub.net/sample_feed.php",
						Array(
							Array('feedSelfLink','href',true,null),
							Array('default','rel',true,'self')
							), 'set_selfLink');
					
					$this->addConstruct('feedDescription', 'subtitle', 'feed', 1, 1, 'string', null, "Sample feed data for Php FeedWriter generated feeds.", null, '__construct');
					$this->addConstruct('feedId', 'id', 'feed', 1, 1, 'id', null, "http://phpfeedwriter.webmasterhub.net/sample_feed.php", null, 'set_id');
					$this->addConstruct('feedDateUpdated', 'updated', 'feed', 1, 1, 'date_iso', null, "2011-05-02T14:34:22Z (ISO-8601)", null, 'set_date');
					
					//Channel Author (Required)
					$this->addConstruct('feedAuthor', 'author', 'feed', 1, 1, 'xml', null, "", null, 'set_author');
					$this->addConstruct('feedAuthorName', 'name', 'feedAuthor', 1, 1, 'string', null, "Daniel Soutter", null,'set_author');
					$this->addConstruct('feedAuthorUri', 'uri', 'feedAuthor', 0, 1, 'uri', null, "http://phpfeedwriter.webmasterhub.net/", null,'set_author');
					$this->addConstruct('feedAuthorEmail', 'email', 'feedAuthor', 0, 1, 'email', null, "email@domain.com", null,'set_author');
					
					//Optional Feed Elements
					$this->addConstruct('feedCopyright', 'rights', 'feed', 0, 1, 'string', null, "Copyright (c) Daniel Soutter", null, 'set_copyright');
					$this->addConstruct('feedGenerator', 'generator', 'feed', 0, 1, 'uri', null, null, null, null);
					$this->addConstruct('feedIcon', 'icon', 'feed', 0, 1, 'uri', null, "http://www.webmasterhub.net/img/logo.jpg", null, 'set_icon');
					
						
					$this->addConstruct('feedImage', 'logo', 'feed', 0, 1, 'html', null, "http://www.webmasterhub.net/img/logo.jpg", null, 'set_image'); 
					
					//Channel Contributor (Optional)
					$this->addConstruct('feedContributor', 'contributor', 'feed', 0, -1, 'xml', null, null, null, 'add_contributor');
					$this->addConstruct('feedContributorName', 'name', 'feedContributor', 1, 1, 'string', null, "", null, 'add_contributor');
					$this->addConstruct('feedContributorUri', 'uri', 'feedContributor', 0, 1, 'uri', null, "http://phpfeedwriter.webmasterhub.net/", null, 'add_contributor');
					$this->addConstruct('feedContributorEmail', 'email', 'feedContributor', 0, 1, 'email', null, "email@domain.com", null, 'add_contributor');
					
					//Category (Optional)
					$this->addConstruct('feedCategory', 'category', 'feed', 0, -1, "xml", null, null,
						Array( 
							Array('feedCategoryTerm', 'term', true, null),
							Array('feedCategoryScheme', 'scheme', true, null),
							Array('feedCategoryLabel', 'label', true, null)
						), 'add_category');
					
					//*****  Item Data *****
					//Required Item Elements
					$this->addConstruct(ITEM_CONSTRUCT, 'entry', 'feed', 1, 1, 'xml', null, null, null, 'add_item');
						$this->addConstruct('itemTitle', 'title', 'item', 1, 1, 'string', null, "Php FeedWriter Documentation", null, 'add_item');
						$this->addConstruct('itemId', 'id', 'item', 1, 1, 'id', null, "http://phpfeedwriter.webmasterhub.net/docs/", null, 'set_id');
						$this->addConstruct('itemUpdated', 'updated', 'item', 1, 1, 'date_iso', null, "2011-05-02T14:34:22Z (ISO-8601)", null, 'set_date');
					//Optional Item elements
						$this->addConstruct('itemLink', 'link', 'item', 0, 1, 'uri', null, "http://phpfeedwriter.webmasterhub.net/docs/",
							Array(
								Array('itemLink','href',true,null),
								Array('default','rel',true,'alternate')
								), 'add_item');
						$this->addConstruct('itemCopyright', 'rights', 'item', 0, 1, 'string', null, "Copyright (c) Daniel Soutter", null, 'set_copyright');
						$this->addConstruct('itemContent', 'content', 'item', 0, 1, 'string', null, "Php FeedWriter documentation and tutorials.", 
							Array( Array('default', 'type', false, 'html') ), 'add_item');
						
						$this->addConstruct('itemSummary', 'summary', 'item', 0, 1, 'string', null, "", null, 'set_summary');
						
						$this->addConstruct('itemSource', 'source', 'item', 0, 1, 'string', null, null, null, 'set_source');
							$this->addConstruct('sourceTitle', 'title', 'itemSource', 0, 1, 'string', null, "Php FeedWriter Sample Feed", null, 'set_source');
							$this->addConstruct('sourceUrl', 'id', 'itemSource', 0, 1, 'uri', null, "http://phpfeedwriter.webmasterhub.net/sample_feed.php", null, 'set_source');
							$this->addConstruct('sourceUpdated', 'updated', 'itemSource', 0, 1, 'date_iso', null, "2011-05-02T14:34:22Z (ISO-8601)", null, 'set_source');
						
						$this->addConstruct('itemMedia', 'link', 'item', 0, -1, 'file', null, null, 
							Array(
								Array('default', 'rel', true, 'enclosure'),
								Array('mediaType', 'type', true, null),
								Array('mediaLength', 'length', true, null),
								Array('mediaUrl', 'href', true, null)
								) , 'add_media');
								
						$this->addConstruct('itemLinks', 'link', 'item', 0, -1, 'uri', null, null, 
							Array(
								Array('linkRelType', 'rel', true, null),
								Array('linkType', 'type', true, null),
								Array('linkUri', 'href', true, null)
								) , 'add_link');
						
						$this->addConstruct('itemPubDate', 'published', 'item', 0, 1, 'date_iso', null, "2011-05-02T14:34:22Z (ISO-8601)", null, 'set_date');
						
						//Item Author (Required)
						$this->addConstruct('itemAuthor', 'author', 'item', 0, 1, 'xml', null, null, null, 'set_author');
							$this->addConstruct('itemAuthorName', 'name', 'itemAuthor', 1, 1, 'string', null, "Daniel Soutter", null, 'set_author');
							$this->addConstruct('itemAuthorUri', 'uri', 'itemAuthor', 0, 1, 'uri', null, "http://phpfeedwriter.webmasterhub.net/", null, 'set_author');
							$this->addConstruct('itemAuthorEmail', 'email', 'itemAuthor', 0, 1, 'email', null, "email@domain.com", null, 'set_author');
					
						//Item Contributor (Optional)
						$this->addConstruct('itemContributor', 'contributor', 'feed', 0, -1, 'xml', null, null, null, 'add_contributor');
							$this->addConstruct('itemContributorName', 'name', 'itemContributor', 1, 1, 'string', null, "Daniel Soutter", null, 'add_contributor');
							$this->addConstruct('itemContributorUri', 'uri', 'itemContributor', 0, 1, 'uri', null, "http://phpfeedwriter.webmasterhub.net/", null, 'add_contributor');
							$this->addConstruct('itemContributorEmail', 'email', 'itemContributor', 0, 1, 'email', null, "email@domain.com", null, 'add_contributor');
						
						//Item Category (Optional)
						$this->addConstruct('itemCategory', 'category', 'item', 0, -1, 'xml', null, null,
							Array( 
								Array('itemCategoryTerm', 'term', true, null),
								Array('itemCategoryScheme', 'scheme', true, null),
								Array('itemCategoryLabel', 'label', true, null)
							), 'add_category');

			break;
			default:
				//unknown format
				$this->construct = null;
				return false;
		}
		
		//Feed input functions / documentation url
		$this->functions = Array(
			'__construct' => 'feedwriter/__construct/',
			'add_item' => 'feedwriter/add_item/',
			'set_language' => 'feedwriter/set_language/',
			'set_webmaster' => 'feedwriter/set_webmaster/',
			'set_rating' => 'feedwriter/set_rating/',
			'set_skipDays' => 'feedwriter/set_skipdays/',
			'set_skipHours' => 'feedwriter/set_skiphours/',
			'set_input' => 'feedwriter/set_input/',
			'set_refreshInterval' => 'feedwriter/set_refreshinterval/',
			'set_icon' => 'feedwriter/set_icon/',
			'set_cloud' => 'feedwriter/set_cloud/',
			'set_image' => 'feedwriter/set_image/',
			'set_date' => 'feedwriter/set_date/',
			'set_id' => 'feedwriter/set_id/',
			'set_copyright' => 'feedwriter/set_copyright/',
			'set_author' => 'feedwriter/set_author/',
			'set_selfLink' => 'feedwriter/set_selfLink/',
			'add_contributor' => 'feedwriter/add_contributor/',
			'add_link' => 'feedwriter/add_link/',
			'add_category' => 'feedwriter/add_category/',
			'set_source' => 'feedwriter/set_source/',
			'add_media' => 'feedwriter/add_media/',
			'set_comments' => 'feedwriter/set_comments/',
			'set_summary' => 'feedwriter/set_summary/'
		);
	}
	
	/*********************************************************************************
	* function addConstruct
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedconstruct/addconstruct/
	**********************************************************************************/
	public function addConstruct($commonName, $elementName, $parentConstruct, $min, $max, $type, $limit=null, $example=null, $attributes=null, $function=null )
	{
		if($attributes == null)
			$attributes = Array();
	
		$this->construct[$commonName] = Array(
			'commonName' => $commonName, 
			'elementName' => $elementName, 
			'parentConstruct' => $parentConstruct, 
			'min' => $min, //0 = optional element, 1 = required element
			'max' => $max, //-1 = unlimited
			'limit' => $limit,
			'type' => $type,
			'example' => $example,
			'attributes' => $attributes,
				// Attributes 2D array: 
				//	Array (  
				//		Array(commonName,attributeName,required,value) ... 
				//	); 
				//
				//	'value' is set as default for attribute.
				//	commonName = 'default' if value not supplied by user.
			'function' => $function 
		);
	}
	
	/*********************************************************************************
	* function getChildren
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedconstruct/getchildren/
	**********************************************************************************/
	function getChildren($common_name)
	{
		$parent = $this->construct[$common_name];
		$childArray = Array();
		
		foreach ($this->construct as $current)	
		{
			if(isset($current['parentConstruct']) && $current['parentConstruct'] == $parent['commonName'])
				$childArray[] = $current;
		}
		
		if(count($childArray) > 0)
			return $childArray;
		else
			return false;
	}
	
	/*********************************************************************************
	* function getParent
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedconstruct/getparent/
	**********************************************************************************/
	function getParent($common_name)
	{
		if(isset($this->construct[$this->construct[$common_name]['parentConstruct']]))
			return $this->construct[$this->construct[$common_name]['parentConstruct']];
		else
			return false;
	}
	
	/*********************************************************************************
	* function getConstruct
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedconstruct/getconstruct/
	**********************************************************************************/
	function getConstruct($common_name)
	{
		if(isset($this->construct[$common_name]))
			return $this->construct[$common_name];
		else
			return false;
	}
	
	/*********************************************************************************
	* function setHeaderContentType
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedconstruct/setheadercontenttype/
	**********************************************************************************/
	function setHeaderContentType($format)
	{
		//Top of XML Document
		switch ($format) {
			case Atom_1:
				header('Content-type: application/atom+xml');
			default: //RSS_x_x
				header('Content-type: application/rss+xml');
		}
	}

}
?>
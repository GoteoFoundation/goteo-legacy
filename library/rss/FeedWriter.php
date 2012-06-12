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
	
require_once('constants.php');

class FeedWriter
{
	//variables 
	var $xml;			// Used to store the feed xml
	var $indent;
	var $feedData;
	var $feedSpecs;
	var $itemsArray = Array();
	public $feed_construct = null;
	var $constructArray = Array();
	var $error_details = null;
	private $hasCredit;
	public $debug = true;
	public $feed_Formats;
	
	
	/*********************************************************************************
	* Class Constructor
	*
	* Description:	Creates an instance of the XMLWriter and starts an xml 1.0 
	*				document.  Starts required RSS 2.0 elements (rss, channel)
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/__construct/
	**********************************************************************************/
	function __construct($title, $description, $link, $indent = 6, $useCDATA = false, $encode_as = null, $enable_validation = true)	//Constructor
	{
		//Add available feed format constants to array
		$this->feed_Formats = Array(
			Array('RSS_2_0', RSS_2_0),
			Array('RSS_1_0', RSS_1_0),
			Array('Atom_1', Atom_1),
			Array('RSS_0_91', RSS_0_91),
			Array('RSS_0_92', RSS_0_92)
		);
	
		$this->feedData = Array(
			"feedTitle" => $title, 
			"feedDescription" => $description, 
			"feedLink" => $link,
			"feedId" => $link, //(Atom only)
			"feedLanguage" => null,
			"feedCopyright" => null,
			"feedAuthor" => null,
			"feedWebmaster" => null,
			"feedEditor" => null,
			"feedRating" => null,
			"feedPubDate" => null,
			"feedDateUpdated" => null,
			"feedDocs" => null,
			"feedSkipDays" => null,
			"skipDay" => Array(),
			"feedSkipHours" => null,
			"skipHour" => Array(),
			"feedImage" => null,
			"feedInput" => null,
			"feedGenerator" => null,
			"feedRefreshInterval" => null,
			"feedIcon" => null,
			"feedSelfLink" => null,
			"feedLinks" => Array(),
			"feedContributor" => Array(),
			"feedCategory" => Array(),
			"feedCloud" => null,
			"image_toc" => null,
			"input_toc" => null,
			"items_toc" => null,
			"items_toc_li" => Array(),
			"optionalElements" => Array()
			);

		$this->indent = $indent;
		
		$this->feedSpecs = Array(
			'useCDATA' => $useCDATA,
			'enableValidation' => $enable_validation,
			'xmlEncodeAs' => $encode_as,
			"feedXMLNameSpace" => Array(),
			"feedStylesheet" => Array(),
			);
	}
	
	/*********************************************************************************
	* function add_item
	*
	* Description:	Add an item to the feed. 
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/add_item/
	**********************************************************************************/
	function add_item($title = null, $description = null, $link = null)
	{
		$this->itemsArray[] = Array(
			'itemTitle' => $title, 
			'itemContent' => $description, 
			'itemLink' => $link, 
			'itemSummary' => null, 
			'itemSource' => null, 
			'itemCategory' => Array(), 
			'itemAuthor' => null, 
			'itemContributor' => Array(), 
			'itemMedia' => Array(),
			'itemId' => $link, //Set link as default value for id
			'itemPubDate' => null,
			'itemUpdated' => null,
			'itemCopyright' => null,
			'itemComments' => null,
			'itemSelfLink' => null,
			'itemLinks' => Array(),
			'optionalElements' => Array()
			); 
		
		//initiate the items toc container (RSS 1.0 output)
		if(!isset($this->feedData['items_toc']) || $this->feedData['items_toc'] == null)
			$this->feedData['items_toc'] = Array('items_toc_seq' => 'items_toc_seq');
		
		//Add the new item url to the item_toc_li array
		$this->feedData['items_toc_li'][] = $link;
	}
	
	/*********************************************************************************
	* function set_feedConstruct
	*
	* Description:	Returns the Feed Construct object for the specified format.
	*				Instantiates from the FeedConstruct class if not previously created,
	*				of if the specific format is different to the existing format.
	*
	*				This function can also be called to instantiate and modify the
	*				construct of a specified format prior to output.
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_feedconstruct/
	**********************************************************************************/
	function set_feedConstruct($format = RSS_2_0)
	{
		require_once('FeedConstruct.php');
		$found = false;

		foreach($this->constructArray as $curConstruct)
		{
			if($curConstruct->format == $format)
			{
				$found = true;
				if($curConstruct->format != $this->feed_construct->format)
				{
					//Format has changed
					
					$tmpConstruct = $this->feed_construct;
					$this->feed_construct = $curConstruct;
					
					//Add temp back to construct array
					foreach($this->constructArray as $curConstruct2)
					{
						if($curConstruct2->format == $tmpConstruct->format)
						{
							$curConstruct2 = $tmpConstruct;
							break;
						}
					}
					break;
				}
			}
		}
		
		if(!$found)
		{
			$this->constructArray[] = new FeedConstruct($format);
			$this->feed_construct = $this->constructArray[count($this->constructArray) -1];
		}
	}
	
	/*********************************************************************************
	* function add_feedStylesheet
	*
	* Description:	Add stylesheet details for rendering the feed. 
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/add_feedstylesheet/
	**********************************************************************************/
	function add_feedStylesheet($address, $type = null)
	{
		if($type == null)
			$type = 'text/css';
			
		$this->feedSpecs['feedStylesheet'][] = Array("type" => $type, "address" => $address);
	}

	/*********************************************************************************
	* function add_feedXMLNameSpace (RSS 2.0)
	*
	* Description:	include custom name space referrences. 
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/add_feedxmlnamespace/
	**********************************************************************************/
	function add_feedXMLNameSpace($prefix, $url)
	{
		$this->feedSpecs['feedXMLNameSpace'][] = Array('prefix' => $prefix, 'url' => $url);	
	}
	
	//=============feed only functions===================================================
	/*********************************************************************************
	* function set_language (Optional)
	*
	* Description:	
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_language/
	**********************************************************************************/
	function set_language($lang){
		$this->feedData['feedLanguage'] = $lang;
	}

	/*********************************************************************************
	* function set_webmaster (Optional)
	*
	* Description:	Used for RSS Output
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_webmaster/
	**********************************************************************************/
	function set_webmaster($webmaster){
		$this->feedData['feedWebmaster'] = $webmaster;
	}

	/*********************************************************************************
	* function set_rating (Optional)
	*
	* Description:	Used for RSS Output
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_rating/
	**********************************************************************************/
	function set_rating($rating){
		$this->feedData['feedRating'] = $rating;
	}

	/*********************************************************************************
	* function set_skipDays (Optional)
	*
	* Description:	Used for RSS Output
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_skipdays/
	**********************************************************************************/
	function set_skipDays($skipday){
	
		if($this->feedData['feedSkipDays'] == null)
			$this->feedData['feedSkipDays'] = true;
		
		if(!isset($this->feedData['skipDay']) || $this->feedData['skipDay'] == null)
			$this->feedData['skipDay'] = Array();
		
		if(!is_array($skipday))
			$skipday = Array($skipday);
		
		foreach($skipday as $curDay)
		{
			$found = false;
			foreach($this->feedData['skipDay'] as $curDay_Feed)
			{
				if($curDay_Feed == $curDay)
					$found = true;
			}
			if(!$found)
				$this->feedData['skipDay'][] = $curDay;
		}
	}

	/*********************************************************************************
	* function set_skipHours (Optional)
	*
	* Description:	Used for RSS Output
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_skiphours/
	**********************************************************************************/
	function set_skipHours($hour){
		if($this->feedData['feedSkipHours'] == null)
			$this->feedData['feedSkipHours'] = true;
		
		if(!isset($this->feedData['skipHour']) || $this->feedData['skipHour'] == null)
			$this->feedData['skipHour'] = Array();		
		
		if(!is_array($hour))
			$hour = Array($hour);
		
		foreach($hour as $curHour)
		{
			$found = false;
			foreach($this->feedData['skipHour'] as $curHour_Feed)
			{
				if($curHour_Feed == $curHour)
					$found = true;
			}
			if(!$found)
				$this->feedData['skipHour'][] = $curHour;
		}
	}

	/*********************************************************************************
	* function set_input (Optional)
	*
	* Description:	Used for RSS Output
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_input/
	**********************************************************************************/
	function set_input($inputTitle,$inputDescription,$inputName,$inputLink){
		$this->feedData['feedInput'] = Array(
			'inputTitle' => $inputTitle, 
			'inputDescription' => $inputDescription, 
			'inputName' => $inputName, 
			'inputLink' => $inputLink
			);
			
			$this->feedData['input_toc'] = $inputLink;
	}

	/*********************************************************************************
	* function set_refreshInterval (Optional)
	*
	* Description:	RSS 2.0 output only
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_refreshinterval/
	**********************************************************************************/
	function set_refreshInterval($interval){
		$this->feedData['feedRefreshInterval'] = $interval;
	}

	/*********************************************************************************
	* function set_icon (Optional)
	*
	* Description:	Used for Atom output - associate a small image/icon with the feed
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_icon/
	**********************************************************************************/
	function set_icon($icon_uri){
		$this->feedData['feedIcon'] = $icon_uri;
	}

	/*********************************************************************************
	* function set_cloud (Optional)
	*
	* Description:	Add cloud connectivity settings to the channel
	*				
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_cloud/
	**********************************************************************************/
	function set_cloud($domain, $port = '80', $path, $regProcedure = 'pingMe', $protocol = 'soap'){
		$this->feedData['feedCloud'] = Array(
			'cloudDomain' => $domain, 
			'cloudPort' => $port, 
			'cloudPath' => $path, 
			'cloudRegProcedure' => $regProcedure, 
			'cloudProtocol' => $protocol
			);
	}

	/*********************************************************************************
	* function set_image (Required for RSS 1 Output)
	*
	* Description:	Add an image to the channel.
	*				
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_image/
	**********************************************************************************/
	function set_image($title, $link, $url, $width = null, $height = null, $description = null){
		$this->feedData['feedImage'] = Array(
			'feedImage' => $url,
			'imageUrl' => $url, 
			'imageTitle' => $title, 
			'imageLink' => $link, 
			'imageDescription' => $description, 
			'imageWidth' => $width, 
			'imageHeight' => $height
			);
			
		$this->feedData['image_toc'] = $url;
	}


	//=============Feed/Item Functions===================================================
	/*********************************************************************************
	* function set_date (Required for Atom output)
	*
	* Description:	Include a date value in the feed, or items within the feed.
	*
	*				Call the function before adding items to the feed to associate
	*				the date with the feed.  Call after adding items to add to the 
	*				most recently added item.
	*
	*				Date Type Options (Constants):
	*					DATE_UPDATED (1) 	- Date Feed/Item Updated (Required for Atom output)
	*					DATE_PUBLISHED (2) 	- Item Publish Date
	*
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_date/
	**********************************************************************************/
	function set_date($date_value, $date_type){
		if (count($this->itemsArray) > 0)
		{
			//Add to most recent item
			if($date_type == DATE_UPDATED)
				$this->itemsArray[count($this->itemsArray)-1]['itemUpdated'] = $date_value;
			elseif($date_type == DATE_PUBLISHED)
			{
				$this->itemsArray[count($this->itemsArray)-1]['itemPubDate'] = $date_value;
				
				//Set date updated if not yet supplied
				if($this->itemsArray[count($this->itemsArray)-1]['itemUpdated'] == null)
					$this->itemsArray[count($this->itemsArray)-1]['itemUpdated'] = $date_value;
			}
		}
		else
		{
			//Add to most recent item
			if($date_type == DATE_UPDATED)
				$this->feedData['feedDateUpdated'] = $date_value;
			elseif($date_type == DATE_PUBLISHED)
			{
				$this->feedData['feedPubDate'] = $date_value;
				
				//Set date updated if not yet supplied
				if($this->feedData['feedDateUpdated'] == null)
					$this->feedData['feedDateUpdated'] = $date_value;
			}
		}
	}

	/*********************************************************************************
	* function set_id
	*
	* Description:	Associate an ID with the feed or the most recently added feed item.
	*
	*				If this function is not called, the 'link' value passed to the class 
	*				constructor, or the addItem() function will be used for the unique id.
	*
	*				Calling this function will override the default (link) value with the 
	*				id supplied.  
	*
	*				The following elements will be populated with the unique identifier:
	*				RSS Feed item id: 			<guid>
	*				Atom Feed/Entry id: 		<id>
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_id/
	**********************************************************************************/
	function set_id($id) {
		if (count($this->itemsArray) > 0)
		{
			//Add to most recent item
			$this->itemsArray[count($this->itemsArray)-1]['itemId'] = $id;
		}
		else
		{
			//Add to feed (Atom only)
			$this->feedData['feedId'] = $id;
		}
	}

	/*********************************************************************************
	* function set_copyright (Optional - Overrides default)
	*
	* Description:	
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_copyright/
	**********************************************************************************/
	function set_copyright($copyright){
		if (count($this->itemsArray) > 0)
		{
			//Add to most recent item
			$this->itemsArray[count($this->itemsArray)-1]['itemCopyright'] = $copyright;
		}
		else
		{
			$this->feedData['feedCopyright'] = $copyright;
		}
	}

	/*********************************************************************************
	* function set_author (Required for Atom Feed Output)
	*
	* Description:	Generic function to add author details to the feed or the
	*				most recent item added to the feed.
	*	
	*				For Atom output, the author is required in either the feed, 
	*				or in each item within the feed.  Both is fine.
	*
	*				For RSS output, these details if provided will be added to a <managingEditor>
	*				element within the feed channel, or an <author> element in feed items.
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_author/
	**********************************************************************************/
	function set_author($authorEmail = null, $authorName = null,$authorUri = null){
		
		//Exite if no name/email provided
		if($authorName == null &&
		$authorEmail == null)
			return false;
		elseif($authorName == null)
			$authorString = $authorEmail;
		elseif($authorEmail == null)
			$authorString = $authorName;
		else
			$authorString = $authorEmail . " (" . $authorName . ")";

		
		if (count($this->itemsArray) > 0)
		{
			//Add to most recent item
			$this->itemsArray[count($this->itemsArray)-1]['itemAuthor'] = Array(
				'itemAuthor' => $authorString,
				'itemAuthorEmail' => $authorEmail, 
				'itemAuthorName' => $authorName, 
				'itemAuthorUri' => $authorUri);
		}
		else
		{
			$this->feedData['feedAuthor'] = Array(
				'feedAuthor' => $authorString,
				'feedAuthorName' => $authorName, 
				'feedAuthorUri' => $authorUri, 
				'feedAuthorEmail' => $authorEmail
				);
		}
	}

	/*********************************************************************************
	* function set_selfLink (Required for Atom Feed Data)
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_selflink/
	**********************************************************************************/
	function set_selfLink($uri){
		if (count($this->itemsArray) > 0)
		{
			$this->itemsArray[count($this->itemsArray)-1]['itemSelfLink'] = $uri;
		}
		else
		{
			$this->feedData['feedSelfLink'] = $uri;
		}
	}

	/*********************************************************************************
	* function add_contributor (Optional)
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/add_contributor/
	**********************************************************************************/
	function add_contributor($email = null, $name = null,$uri = null){
		
		if($name == null && $email != null)
			$authorString = $email;
		elseif($email == null && $name != null)
			$authorString = $name;
		elseif($email != null && $name != null)
			$authorString = $email . " (" . $name . ")";
	
		if (count($this->itemsArray) > 0)
		{
			$this->itemsArray[count($this->itemsArray)-1]['itemContributor'][] = Array(
				'itemContributor' => $authorString, 
				'itemContributorName' => $name, 
				'itemContributorUri' => $uri, 
				'itemContributorEmail' => $email);
		}
		else
		{
			$this->feedData['feedContributor'][] = Array(
				'feedContributor' => $authorString, 
				'feedContributorName' => $name, 
				'feedContributorUri' => $uri, 
				'feedContributorEmail' => $email);
		}
	}

	/*********************************************************************************
	* function add_link (Optional)
	*
	* Description:	Add a link to external resource (Atom)
	*				Will return false if rel = self,alternate,enclosure 
	*				(use class functions instead)
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/add_link/
	**********************************************************************************/
	function add_link($uri, $rel, $type){

		//Return false if controlled type
		//Instead use set_selfLink(), or add_media()
		if(	strtolower($rel) == 'self' || 
			strtolower($rel) == 'alternate' || 
			strtolower($rel) == 'enclosure'
			)
			return false;

		if (count($this->itemsArray) > 0)
		{
			$this->itemsArray[count($this->itemsArray)-1]['itemLinks'][] = Array(
				'linkUri' => $uri, 
				'linkRelType' => $rel, 
				'linkType' => $type);
		}
		else
		{
			$this->feedData['feedLinks'][] = Array(
				'linkUri' => $uri, 
				'linkRelType' => $rel, 
				'linkType' => $type);
		}
	}

	/*********************************************************************************
	* function add_category (Optional)
	*
	* Description:	Generic function to add categories to the channel and/or items.
	*				Call this function before adding items to the feed 
	*				to add categories to the channel.  Call after adding an item
	*				to assign categories/tags to individual feed items.
	*
	*				You can add multiple categories to a channel or item.  This
	*				function will need to be called separately to add each category.
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/add_category/
	**********************************************************************************/
	function add_category($categoryName, $domain = null, $categoryScheme = null, $categoryLabel = null){
		if($categoryLabel == null)
			$categoryLabel = $categoryName;
			
		if (count($this->itemsArray) > 0)
		{
			//Add category to most recent item
			$this->itemsArray[count($this->itemsArray)-1]['itemCategory'][] = Array(
				'itemCategory' => $categoryName,
				'itemCategoryTerm' => $categoryName, 
				'itemCategoryScheme' => $categoryScheme, 
				'itemCategoryLabel' => $categoryLabel,
				'itemCategoryDomain' => $domain
				);
		}
		else
		{
			//Add category to feed
			$this->feedData['feedCategory'][] = Array(
				'feedCategory' => $categoryName,
				'feedCategoryTerm' => $categoryName, 
				'feedCategoryScheme' => $categoryScheme, 
				'feedCategoryLabel' => $categoryLabel,
				'feedCategoryDomain' => $domain
				);
		}
	}

	//=============item only functions===================================================
	/*********************************************************************************
	* function set_source (Optional)
	*
	* Description:	Set the source of a feed ited (should be the url of the RSS feed)
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_source/
	**********************************************************************************/
	function set_source($source_title, $source_url, $source_updated){
		//Continue if items have been added to the feed.  
		if (count($this->itemsArray) > 0)
		{
			//Add to most recent item
			$this->itemsArray[count($this->itemsArray)-1]['itemSource'] = Array(
				'itemSource' => $source_title,
				'sourceTitle' => $source_title,
				'sourceUrl' => $source_url,
				'sourceUpdated' => $source_updated
				);
				
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/*********************************************************************************
	* function set_summary (Optional)
	*
	* Description:	Atom Output only
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_summary/
	**********************************************************************************/
	function set_summary($summary){
		//Continue if items have been added to the feed.  
		if (count($this->itemsArray) > 0)
		{
			//Add to most recent item
			$this->itemsArray[count($this->itemsArray)-1]['itemSummary'] = $summary;
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	/*********************************************************************************
	* function addMedia (Optional)
	*
	* Description:	Associate media files to items in the feed.  
	* This function must be called after adding an item to the feed.  The media file(s)
	* will be attached to the item most recently added to the feed using the <enclosure> element.
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/add_media/
	**********************************************************************************/
	function add_media($url, $type, $fileSize){
		//Continue if items have been added to the feed.  
		if (count($this->itemsArray) > 0)
		{
			//Add to most recent item
			$this->itemsArray[count($this->itemsArray)-1]['itemMedia'][] = Array(
				'mediaUrl' => $url, 
				'mediaType' => $type, 
				'mediaLength' => $fileSize);
			return true;
		}
		else
		{
			return false;
		}
	}
	/*********************************************************************************
	* function set_comments (Optional)
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_comments/
	**********************************************************************************/
	function set_comments($uri){
		//Continue if items have been added to the feed.
		if (count($this->itemsArray) > 0)
		{
			$this->itemsArray[count($this->itemsArray)-1]['itemComments'] = $uri;
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/*********************************************************************************
	* function set_docs (Private)
	*
	* Description:	Used for RSS Output
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/set_docs/
	**********************************************************************************/
	private function set_docs($docs_uri){
		$this->feedData['feedDocs'] = $docs_uri;
	}
	
	/*********************************************************************************
	* function add_element (Optional)
	*
	* Description:	Generic function to add any an Optional element to the Chanel 
	*				or most recent item
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/add_element/
	**********************************************************************************/
	function add_element($elementName, $val = null, $attributes = Array()){
		//Override default copyright notice if provided
		if(strtolower($elementName) == 'copyright')
			$this->overrideDefaultCopyright = true;
		
		if (count($this->itemsArray) > 0)
		{
			//Add to most recent item
			$this->itemsArray[count($this->itemsArray)-1]['optionalElements'][] = Array("elementName" => $elementName, "value" => $val, "attributes" => $attributes);
		}
		else
		{
			//Add to channel
			$this->feedData['optionalElements'][] = Array("elementName" => $elementName, "value" => $val, "attributes" => $attributes);
		}
	}

	
	/*********************************************************************************
	* function validate
	*
	* Description:	Validate feed data supplied against the specified format.
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/validate/
	**********************************************************************************/
	function validate($format = RSS_2_0)
	{
		$this->set_feedConstruct($format);
		$valid = true;
		
		$rootConstruct = $this->feed_construct->getConstruct(ROOT_CONSTRUCT);
		
		//Get root construct children
		$feedConstruct = $this->feed_construct->getChildren($rootConstruct['commonName']);
		
		//Check if has feed channel (sub) element
		if(count($feedConstruct) > 0 && $feedConstruct[0]['commonName'] == CHANNEL_DATA_CONSTRUCT)
		{
			//Get get feed channel data constructs
			$feedConstruct = $this->feed_construct->getChildren(CHANNEL_DATA_CONSTRUCT);
		}

		$itemConstruct = $this->feed_construct->getChildren(ITEM_CONSTRUCT);
		
		//Reset Error Details Array
		$this->error_details = Array();
		
		//Loop through Feed Data Elements (stop if reached item construct)
		foreach($feedConstruct as $curConstruct)
		{
			if($curConstruct['commonName'] == ITEM_CONSTRUCT) //Item Data
			{	
				break;
			}
			else //Feed Data
			{	
				if(!isset($this->feedData[$curConstruct['commonName']]))
				{
					if($curConstruct['min'] > 0)
					{
						$this->error_details[] = Array('construct' => $curConstruct, 'data' => null);
						$valid = false;
					}
				}
				elseif(!$this->validateConstruct($this->feedData[$curConstruct['commonName']], $curConstruct, $curConstruct['commonName'],ITEM_CONSTRUCT))
					$valid = false;
			}
		}
		
		//Loop through Feed Items
		foreach($itemConstruct as $curConstruct)
		{
			foreach($this->itemsArray as $curItem)
			{
				if(!$this->validateConstruct($curItem[$curConstruct['commonName']], $curConstruct, $curConstruct['commonName'], null, $curItem))
					$valid = false;
			}
		}
		
		
		
		if($valid)
		{
			$this->error_details = null;
			return true;
		}
		else
			return false;
		
	}
	
	/*********************************************************************************
	* function validateConstruct
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/validateconstruct/
	**********************************************************************************/
	function validateConstruct($feedData, $construct, $feedCommonName, $break_at = null, $item = null)
	{	
		$valid = true;
	
		if($construct['min'] > 0) //Current is required.  Check if data exists
		{
			$valid = false;
			
			if($construct['commonName'] == $break_at)
				break;
			
			//Check that current construct has feed data 
		
			foreach($construct['attributes'] as $curAttribute){
				if(is_array($feedData) && isset($feedData[$curAttribute[0]]) && $feedData[$curAttribute[0]] != null){
					$valid = true;
				}
				elseif($curAttribute[0] == $feedCommonName){
					$valid = true;
				}
			}
		
			if(is_array($feedData) && isset($feedData[$construct['commonName']]) && $feedData[$construct['commonName']] != null){
				$valid = true;
			}
			elseif(!is_array($feedData) && $feedData != null){
				$valid = true;
			}
		
		
			//Get children
			$tmpChildren = $this->feed_construct->getChildren($construct['commonName']);
			
			if($tmpChildren !== false) { //Has Children
				//Call validateConstruct for each child construct (nested calls)
				$validChildren = true;
				foreach($tmpChildren as $curChild)
				{
					if(!$this->validateConstruct($feedData, $curChild, $feedCommonName))
						$validChildren = false;
				}
				$valid = $validChildren;
			}
			
			$parent = $this->feed_construct->getParent($construct['commonName']);
			
			//If child, check if parent element is required.
			if($construct['commonName'] != $feedCommonName)//is child
			{
				if($parent['min'] == 0) //parent not required
				{
					if(!is_array($feedData) && $feedData == null) //no value
						$valid = true;
					elseif(is_array($feedData) && count($feedData) > 0)//is array with values
						$valid = true;
				}
			}
				
			if($valid)
				return true;
			else
			{
				//Not valid.  
				if($item != null)
					$feedData = $item;
				
				//Add construct to error array
				$this->error_details[] = Array('construct' => $construct, 'data' => $feedData);
				return false;
			}
		}
		else
			return true;
	}
	
	/*********************************************************************************
	* function invalidFeed
	*
	* Description:	Generate a feed containing error details, in the same format 
	*				specified for output.
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/invalidfeed/
	**********************************************************************************/
	function invalidFeed($feed_format)
	{
		/* //Debug
		echo "<b>Invalid feed:</b><br/>";
		foreach($this->error_details as $curError)
			echo $curError['construct']['commonName'] . "<br/>";
		exit;
		*/
		
		//Disable validation to prevent endless loop
		$this->feedSpecs['enableValidation'] = false;
		
		//Reset Feed to allow populating with error details
		$this->itemsArray = Array();
		$this->feedData = Array(
			"feedTitle" => 'A valid feed could not be generated in the ' . $feed_format . ' format', 
			"feedDescription" => 'A valid feed could not be generated in the ' . $feed_format . ' format', 
			"feedLink" => 'http://phpfeedwriter.webmasterhub.net/',
			"feedId" => 'http://phpfeedwriter.webmasterhub.net/', 
			"feedLanguage" => null,
			"feedCopyright" => null,
			"feedAuthor" => null,
			"feedWebmaster" => null,
			"feedRating" => null,
			"feedPubDate" => null,
			"feedDateUpdated" => null,
			"feedDocs" => null,
			"feedSkipDays" => null,
			"feedSkipHours" => null,
			"feedImage" => null,
			"feedInput" => null,
			"feedGenerator" => null,
			"feedRefreshInterval" => null,
			"feedIcon" => null,
			"feedSelfLink" => null,
			"feedLinks" => Array(),
			"feedContributor" => Array(),
			"feedCategory" => Array(),
			"feedCloud" => null,
			"optionalElements" => Array()
			);
		
		$this->set_date('2011-04-23T00:00:00Z',DATE_UPDATED);
		$this->set_date('2011-04-23T00:00:00Z',DATE_PUBLISHED);
		$this->set_id('feed_error');
		$this->set_selfLink('http://phpfeedwriter.webmasterhub.net/');
		$this->set_image('WebmasterHub.net', 'http://www.webmasterhub.net/img/logo.jpg','http://www.webmasterhub.net/');
		$this->set_copyright('(c) Daniel Soutter.');
		$this->set_language('EN-US');
		$this->set_webmaster('Daniel Soutter');
		$this->set_author(null, 'Daniel Soutter','http://phpfeedwriter.webmasterhub.net/');
		
		$this->add_item(
			'Troubleshoot Feed Error Details (Php FeedWriter)', 
			'<p>You are seeing this page because there was not enough information available to generate a feed using the ' . $feed_format . ' format.</p>'. 
			'<p>If the problem persists, please notify the owner of the website.</p>
			<hr/>',
			'http://phpfeedwriter.webmasterhub.net/docs/'
			);
		$this->set_date(date('c'),DATE_PUBLISHED);
		$this->set_id('validation_details');
		
		if($this->debug && $this->error_details != null)
		{
			$constructTableHTML = $this->listConstructs($feed_format, true);
		
			$error_details = '<p>Validation errors are generally caused when 
				data that is required by a feed format was not available to add to this feed.
				</p> 
				
				<p>For help with, including enabling/disabling validation when outputting a feed 
				see the <a href="http://phpfeedwriter.webmasterhub.net/docs/">Php FeedWriter Documentation</a>.</p>
				
				<p>The validator failed at the following construct(s) due to invalid or missing feed data:</p>  

					' . $constructTableHTML . '

				<p><strong>Additional Help for:</strong><br/>
				<ul>
				<li><a href="http://phpfeedwriter.webmasterhub.net/docs/feedconstruct/" target="_blank"><b>FeedConstruct</b> class members and functions</a></li>
				<li><a href="http://phpfeedwriter.webmasterhub.net/docs/feedwriter/" target="_blank"><b>FeedWriter</b> class members and functions</a> used to populate data in each construct</li>
				<li><a href="' . $this->feed_construct->docsUrl . '" target="_blank">XML Scema Definition for <b>' . $feed_format . '</b> feeds</a></li>
				</ul></p>
				<hr/>Note: This "debug" item is displayed because debug mode is currently enabled, to assist with configuration of the feed for output in varous formats. It is recommended that debug mode be 
				disabled prior to making the feed available on the internet.
				';
			
			$this->add_item(
				'Debug: Invalid feed construct ' . $this->error_details[0]['construct']['commonName'] . ' (' . $this->error_details[0]['construct']['elementName'] . ')', 
				$error_details, 
				'http://phpfeedwriter.webmasterhub.net/docs/feedconstruct/'
				);
			$this->set_date(date('c'),DATE_PUBLISHED);
			$this->set_id('validation_details');
		}
		
		//update construct data to allow display of debug data table
		$this->feed_construct->construct['itemSummary']['type'] = 'html';
		$this->feed_construct->construct['itemContent']['type'] = 'html';
		$this->feed_construct->construct['itemContent']['limit'] = null;
		echo $this->getXML($feed_format);	
		exit;
	}
	
	/*********************************************************************************
	* function getFeedFormats
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/getfeedformats/
	**********************************************************************************/
	function getFeedFormats()
	{
		//Return an array of all available feed formats.
		return $this->feed_Formats;
	}
	
	/*********************************************************************************
	* function getXML
	*
	* Description:	Generate and return Feed compatible xml (RSS 1.0, RSS 2.0, Atom)
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/getxml/
	**********************************************************************************/
	function getXML($feed_format = RSS_2_0, $category_filter = null)
	{	
		$elementNestLevel = 0;
	
		//Set the default timezone
		@date_default_timezone_set("GMT"); 
		
		//Create the xml write object
		$writer = new XMLWriter(); 
		
		//XMLWriter Output method:
		//------------------------------------------------------------------------------------------
		$writer->openMemory(); 							//	Xml stored in memory (allows set as variable, output 
														//  to file, print/echo	to user, etc.
												
		//$this->$writer->openURI('php://output');  	//	Send xml to directly to browser/user (not implemented in this version)
		//-----------------------------------------------------------------------------------------
		
		//XML Version.  Include Charachter Encoding if supplied
		if($this->feedSpecs['xmlEncodeAs'] != null)
			$writer->startDocument('1.0', $this->feedSpecs['xmlEncodeAs']); 	
		else
			$writer->startDocument('1.0'); 			
		
		//Add stylesheet details if provided
		foreach($this->feedSpecs['feedStylesheet'] as $curStylesheet)
			$writer->writePI("xml-stylesheet", 'type="' . $curStylesheet['type'] . '" href="' . $curStylesheet['address'] . '"');
		//------------------------------------------
		
		//Indent level
		$writer->setIndent($this->indent);
		
		//Validate and display notice if not valid
		if($this->feedSpecs['enableValidation'] && !$this->validate($feed_format) )
		{
			//Validation Enabled.  Feed is not valid for the specified output format.
			//echo $err_message;
			//exit;
			$writer->flush();
			$this->invalidFeed($feed_format);
		}	
		
		//Instantiate the FeedConstruct class
		$this->set_feedConstruct($feed_format);
		
		$this->set_docs($this->feed_construct->docsUrl);
		
		//Set content type for specified output format
		$this->feed_construct->setHeaderContentType($this->feed_construct->format);
		
		//Get root, set current as root construct
		$current = $this->feed_construct->getConstruct(ROOT_CONSTRUCT);
		
		//Start the root element
		$writer->startElement($current['elementName']);
		$elementNestLevel++;
		
		//add attributes if available
		foreach($current['attributes'] as $curAttribute)
			$writer->writeAttribute($curAttribute[1], $curAttribute[3]); 
			
		//add custom namespaces if available
		foreach($this->feedSpecs['feedXMLNameSpace'] as $curNS)
			$writer->writeAttribute('xmlns:' . $curNS['prefix'], $curNS['url']); 
	
		//Get root construct children
		$children = $this->feed_construct->getChildren($current['commonName']);
		
		//Check if has feed channel (sub) element (Channel)
		if(count($children) > 0 && $children[0]['commonName'] == CHANNEL_DATA_CONSTRUCT)
		{
			//Move to CHANNEL_DATA_CONSTRUCT
			$current = $children[0];
			
			//Start the element
			$writer->startElement($current['elementName']);
			$elementNestLevel++;
			
			//add attributes if available
			if($current['attributes'] !== null)
			{
				foreach($current['attributes'] as $curAttribute){
				
					if($curAttribute[0] != 'default' && isset($this->feedData[$curAttribute[0]])){	
						//populated with feed data
						$writer->writeAttribute($curAttribute[1], $this->feedData[$curAttribute[0]]);
					}
					elseif($curAttribute[0] == 'default'){
						//Populated with constant value
						$writer->writeAttribute($curAttribute[1], $curAttribute[3]);
					}
				}
			}
			
			//Get get feed channel data constructs
			$children = $this->feed_construct->getChildren($current['commonName']);
		}
		
		$atItemConstruct = false;
		
		//Loop through Feed Data Elements (stop if reached item construct)
		foreach($children as $curConstruct)
		{
			if($curConstruct['commonName'] == ITEM_CONSTRUCT) //Item Data
			{	
				$atItemConstruct = true;
				break;
			}
			else //Feed Data
			{	
				//Test if feed has data for the current construct.  Skip if not.
				if(isset($this->feedData[$curConstruct['commonName']]) && $this->feedData[$curConstruct['commonName']] != null)
				{
					$iterator = 0;
					$mult = true;
					
					//Proceed single node in feedData array, or loop through set if multiple.
					do
					{
						if($curConstruct['max'] > 1 && $iterator >= $curConstruct['max'])
						{
							//Allws multiple, but has reached limit
							$mult = false;
						}
						elseif($curConstruct['max'] != 1) //Allows multiple instances of current construct
						{
							if(isset($this->feedData[$curConstruct['commonName']][$iterator]))
							{
								$this->writeConstruct(
									$writer, 
									$this->feedData[$curConstruct['commonName']][$iterator], 
									$curConstruct, 
									$curConstruct['commonName']
									);	
								$iterator++;
							}
							else
							{
								//Reached end of feed data array.
								$mult = false;
							}
						}
						else //Single instance of current construct
						{	
							$this->writeConstruct(
								$writer, 
								$this->feedData[$curConstruct['commonName']], 
								$curConstruct, 
								$curConstruct['commonName']);	
							$mult = false;
						}
					}while($mult);
				}
			}
		}
		
		//Close channel element if required
		if($atItemConstruct)
		{
			//Item construct reached when processing feed channel data
			//Items will be added to the channel element
		}
		else
		{
			//Reached end of feed data (channel sub elements), but havent reached items
			//Items are outside of the channel element
			$writer->endElement(); //Close the channel element
			$elementNestLevel--;
			
			//Add non channel elements to feed if available, 
			//exluding items (eg. image, input - RSS 1.0), as they are added 
			//separately
			
			//Move back to parent node if applicable:
			if($current['parentConstruct'] != null){
				$current = $this->feed_construct->getConstruct($current['parentConstruct']);
				
				//Get children.   
				$children = $this->feed_construct->getChildren($current['commonName']);
				
				//Loop through and write element if construct is not item or channel construct (required for RSS 1.0 output)
				foreach($children as $curConstruct){
					if($curConstruct['commonName'] != ITEM_CONSTRUCT && 
						$curConstruct['commonName'] != CHANNEL_DATA_CONSTRUCT){
						
						//Write element if feed data exists for current
						if(isset($this->feedData[$curConstruct['commonName']]) && $this->feedData[$curConstruct['commonName']] != null){
							$this->writeConstruct(
								$writer, 
								$this->feedData[$curConstruct['commonName']], 
								$curConstruct, 
								$curConstruct['commonName']);	
						}
					}
				}
			}
		}
		
		//Add Items to feed xml
		$item_construct = $this->feed_construct->getConstruct(ITEM_CONSTRUCT);
		$item_construct_children = $this->feed_construct->getChildren(ITEM_CONSTRUCT);
		$creditsIncluded = false;
		$itemNumber = 0;
		
		//Loop through items in feed
		foreach($this->itemsArray as $currentItem)
		{
			$itemNumber ++;
			if($item_construct['max'] > 1 && $itemNumber >= $item_construct['max'])
			{
				//has reached limit
				break;
			}
			
			//Start the element
			$writer->startElement($item_construct['elementName']);
			
			//add attributes if available
			if($item_construct['attributes'] !== null)	{
				foreach($item_construct['attributes'] as $curAttribute){
				
					if($curAttribute[0] != 'default' && isset($currentItem[$curAttribute[0]])){	
						//populated with feed data
						$writer->writeAttribute($curAttribute[1], $currentItem[$curAttribute[0]]);
					}
					elseif($curAttribute[0] == 'default'){
						//Populated with constant value
						$writer->writeAttribute($curAttribute[1], $curAttribute[3]);
					}
				}
			}
									
			foreach($item_construct_children as $curItemConstruct)
			{
				//Test if current feed item has data for the current construct.  Skip if not.
				if(isset($currentItem[$curItemConstruct['commonName']]) && $currentItem[$curItemConstruct['commonName']] != null)
				{
					$iterator = 0;
					$mult = true;
					$currentFeedData;
					do
					{
						if($curItemConstruct['max'] > 1 && $iterator >= $curItemConstruct['max'])
						{
							//Allws multiple, but has reached limit
							$mult = false;
						}
						elseif($curItemConstruct['max'] != 1)
						{
							if(isset($currentItem[$curItemConstruct['commonName']][$iterator]))
							{
								$this->writeConstruct(
									$writer, 
									$currentItem[$curItemConstruct['commonName']][$iterator], 
									$curItemConstruct, 
									$curItemConstruct['commonName']
									);	
								$iterator++;
							}
							else
							{
								$mult = false;
							}
						}
						else
						{	
							$this->writeConstruct(
								$writer, 
								$currentItem[$curItemConstruct['commonName']], 
								$curItemConstruct, 
								$curItemConstruct['commonName']);	
							$mult = false;
						}
					}while($mult);
				}
			}
			//Close the current ITEM_CONSTRUCT element
			$writer->endElement();
		}
		
		/***************************************************
		It is a breach of the terms of use to disable, modify or remove the Php FeedWriter footer item if you have not purchased Php FeedWriter for commercial use.
		For full details, see the Php FeedWriter Terms of Use:    http://phpfeedwriter.webmasterhub.net/terms/         */		
		//-Start add footer----------------------
		$this->feed_construct->construct['itemSummary']['type'] = 'html';
		$this->feed_construct->construct['itemContent']['type'] = 'html';
		$item_construct_children = $this->feed_construct->getChildren(ITEM_CONSTRUCT);
		$this->add_credit();
		$writer->startElement($item_construct['elementName']);
		foreach($item_construct['attributes'] as $curAttribute)
			$writer->writeAttribute($curAttribute[1], $curAttribute[3]); 
		$currentItem = $this->itemsArray[count($this->itemsArray)-1];
		$creditsIncluded = true;
		foreach($item_construct_children as $curItemConstruct){
			if(isset($currentItem[$curItemConstruct['commonName']]) && $currentItem[$curItemConstruct['commonName']] != null)
				$this->writeConstruct(
					$writer, 
					$currentItem[$curItemConstruct['commonName']], 
					$curItemConstruct, 
					$curItemConstruct['commonName']);}
					//End add footer-------------

					
		//Close remaining elements
		for($i=$elementNestLevel; $i>0; $i--)
			$writer->endElement();
			
		//End Xml Document
		$writer->endDocument();
		
		//Output memory if no error
		//(!defined(cr))?exit:null;
		$this->xml = $writer->outputMemory(true);

		//Output the Feed XML if footer included
		//($creditsIncluded)?null:$this->xml = null;
		/*
		if($this->hasCredit)		
			return $this->xml;
		else
			return false;
		*/
		return $this->xml;
	}
	
	/*********************************************************************************
	* function writeConstruct
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/writeconstruct/
	**********************************************************************************/
	function writeConstruct($writer, $feedData, $construct, $feedCommonName)
	{
		//Check that current construct has feed data 
		//if current construct is a child (not yet checked for input)
		if($construct['commonName'] != $feedCommonName)
		{
			//Current construct is a child
			$found = false;
			foreach($construct['attributes'] as $curAttribute){
				if(is_array($feedData) && isset($feedData[$curAttribute[0]]) && $feedData[$curAttribute[0]] != null){
					$found = true;
				}
				elseif($curAttribute[0] == $feedCommonName){
					$found = true;
				}
			}
			if(is_array($feedData) && isset($feedData[$construct['commonName']]) && $feedData[$construct['commonName']] != null){
				$found = true;
			}
			elseif(!is_array($feedData) && $feedData != null){
				$found = true;
			}
			elseif(isset($this->feedData[$construct['commonName']]) && $this->feedData[$construct['commonName']] != null)
				$found = true;
			
			if(!$found)
				return;
			
			//Check if current is a child, and repeating. (image_toc_li)
			if($construct['max'] == -1 && isset($this->feedData[$construct['commonName']]))
			{
				foreach($this->feedData[$construct['commonName']] as $tmpFeedData)
				{
					$this->writeConstruct(
						$writer, 
						$tmpFeedData, 
						$construct, 
						$construct['commonName']
						);
				}
				return;
			}
		}

		
		$setAsAttribute = false;
		//Start the element
		$writer->startElement($construct['elementName']);
		
		//add attributes if available
		if($construct['attributes'] !== null)
		{
			foreach($construct['attributes'] as $curAttribute){
				if($curAttribute[0] != 'default'){	
					//populated with feed data
					if(is_array($feedData) && isset($feedData[$curAttribute[0]])){
						$writer->writeAttribute($curAttribute[1], $feedData[$curAttribute[0]]);
						$setAsAttribute = true;
					}
					elseif($curAttribute[0] == $feedCommonName){
						$writer->writeAttribute($curAttribute[1], $feedData);
						$setAsAttribute = true;
					}
				}
				elseif($curAttribute[0] == 'default'){
					//Populated with constant value
					$writer->writeAttribute($curAttribute[1], $curAttribute[3]);
				}
			}
		}
		
		//Get children
		$tmpChildren = $this->feed_construct->getChildren($construct['commonName']);
		if($tmpChildren !== false) { //Has Children
			//Call writeConstruct for each child construct (nested calls)
			foreach($tmpChildren as $curChild)
				$this->writeConstruct($writer, $feedData, $curChild, $feedCommonName);
		}
		else{ 
			//No child constructs.  Write value.
			if(is_array($feedData) && isset($feedData[$construct['commonName']]) && !$setAsAttribute){
			
				if($construct['limit'] != null && $construct['limit'] > 0)
					$data = substr($feedData[$construct['commonName']], 0 , $construct['limit']);
				else
					$data = $feedData[$construct['commonName']];
			}
			elseif(!is_array($feedData) && $construct['commonName'] == $feedCommonName && !$setAsAttribute){
				
				if($construct['limit'] != null && $construct['limit'] > 0)
					$data = substr($feedData, 0 , $construct['limit']);
				else
					$data = $feedData;
			}
			else
				$data = null;
			
			if($data != null)
			{
				//Write value for current element and data type
				switch ($construct['type'])
				{
					case $construct['type'] == 'string' && $this->feedSpecs['useCDATA']:
						$writer->writeCData(htmlentities($data));
						break;
					case $construct['type'] == 'string' && !$this->feedSpecs['useCDATA']:
						$writer->writeRaw(htmlentities($data));
						break;
					case $construct['type'] == 'uri':
						$writer->writeRaw($data);
						break;
					case $construct['type'] == 'email' && $this->feedSpecs['useCDATA']:
						$writer->writeCData($data);
						break;
					case $construct['type'] == 'email' && !$this->feedSpecs['useCDATA']:
						$writer->writeRaw($data);
						break;
					case $construct['type'] == 'xml':
						$writer->writeRaw($data);
						break;
					case $construct['type'] == 'html':
						$writer->writeCData($data);
						break;
					case $construct['type'] == 'date_iso':
						$writer->text( date('c',strtotime($data))); 
						break;
					case $construct['type'] == 'date_rfc':
						$writer->text( date('r',strtotime($data))); 
						break;
					default:
						$writer->text((string)$feedData); 
				}
			}
		}
		//Close the element
		$writer->endElement();
	}
	//function rc(){!defined('cr')?define('cr','cr'):null;}
	
	/*********************************************************************************
	* function writeToFile
	*
	* Description:	Writes the generated rss xml to a file
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/writetofile/
	**********************************************************************************/
	function writeToFile($fileName, $feed_format = RSS_2_0, $categories = null)
	{
		//$this->closeDocument();
		
		$fh = fopen($fileName, 'w') or die("can't open file");
		
		if(!$categories == null)
			fwrite($fh, $this->getXML());
		else
		{
			fwrite($fh, $this->getXML());
			//fwrite($fh, $this->getXMLFiltered($categories));
		}
		
		fclose($fh);
	}
	
	/*********************************************************************************
	* function add_credit
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/add_credit/
	*
	*
	*	It is a breach of the terms of use to disable, modify or remove the Php FeedWriter 
	*   footer item (including this add_credit() function ) if you have not purchased Php FeedWriter for commercial use.
	*	For full details, see the Php FeedWriter Terms of Use: http://phpfeedwriter.webmasterhub.net/terms/
	**********************************************************************************/
	function add_credit()
	{
		$this->add_item(
				'Php Feedwriter', 
				'<font size=1>Powered by <a href=http://phpfeedwriter.webmasterhub.net/><b>Php Feedwriter</b></a>',
				'http://phpfeedwriter.webmasterhub.net/');//$this->rc();
				$this->set_date('2011-06-26T00:00:00Z',DATE_UPDATED); 
				//$this->hasCredit = true;
				$this->set_author(null, 'Daniel Soutter','http://phpfeedwriter.webmasterhub.net/');
	}
	
	/*********************************************************************************
	* function updateFeedData - not implemented
	*
	* Description:	Called by addElement() to update feed data/statistics if the element 
	*				is tracked for validation.
	*	
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/updatefeeddata/
	**********************************************************************************/
	private function updateFeedData($element_name)
	{
		
	}
	
	/*********************************************************************************
	* function listConstructs
	*
	* Description:	Displays a table of data for all constructs for a particular format.
	*				Includes limits, required elements, attributes and links
	*				to online documentation.
	*
	*				If customisations have been made to the construct of a feed format 
	*				during runtime, the changes will be visible in this table, which may 
	*				be useful for debugging.
	*				
	* Details:		http://phpfeedwriter.webmasterhub.net/docs/feedwriter/listconstructs/
	**********************************************************************************/
	function listConstructs($format, $error = false)
	{
		$this->set_feedConstruct($format);
		$constructTableHTML = '';
		
		if($error)
		{
			$constructs = Array();
			if(is_array($this->error_details) && count($this->error_details) > 0)
			{
				foreach($this->error_details as $curError)
				{
					$curError['construct']['data'] = $curError['data'];
					$constructs[] = $curError['construct'];
				}
			}
			else
				return 'No Error';
		}
		else
			$constructs = $this->feed_construct->construct;
		
		foreach($constructs as $currConstruct)
		{
			$attributesTableHTML = '';
			foreach($currConstruct['attributes'] as $curAttribute){
				$attributesTableHTML .= 
				'<tr>' . 
					'<td>' . $curAttribute[0] . '&nbsp;</td>' . 
					'<td>' . $curAttribute[1] . '&nbsp;</td>' . 
					'<td>' . $curAttribute[2] . '&nbsp;</td>' . 
					'<td>' . $curAttribute[3] . '&nbsp;</td>' . 
				'</tr>';
			}
			
			if(count($currConstruct['attributes']) > 0){
				$attributesTableHTML = '
							<table style="border:1px solid #CCCCCC; text-align:left" cellpadding=2 width=100%>
								<tr><th>Common name</th><th>Attribute name</th><th>Required?</th><th>Default Value</th></tr>
								' . $attributesTableHTML . '
							</table>';
			}
			
			if($currConstruct['min'] == 1)
				$min = '<u>' . $currConstruct['min'] . ' (required)</u>';
			else
				$min = $currConstruct['min'] . ' (optional)';
			
			if($currConstruct['max'] == -1)
				$max = 'Unlimited';
			else
				$max = $currConstruct['max'];
			
			$data = '';
			if($error && isset($currConstruct['data']))
			{
				if(is_array($currConstruct['data']))
				{	
					if(isset($currConstruct['data']['itemTitle'])) //Item
					{
						$data = 'Item (Title): "' . $currConstruct['data']['itemTitle'] . "\"<br/>\n";
						if(isset($currConstruct['data'][$currConstruct['commonName']]) && $currConstruct['data'][$currConstruct['commonName']] != null)
							$data .= $currConstruct['commonName'] . ': "' . $currConstruct['data'][$currConstruct['commonName']] . "\"<br/>\n";
						else
							$data .= $currConstruct['commonName'] . ": none<br/>\n";
					}
					else //feed data
					{
						while( $element = each( $currConstruct['data'] ) )
						{
														 $data .= $element[ 'key' ];
								 $data .=  ': ';
								 if($element[ 'value' ] == null)
									$data .= "none";
								 else
									$data .=  $element[ 'value' ];
								 $data .=  "<br />\n";
							
						}
					}
				}
				elseif($currConstruct['data'] != null)
					$data = $currConstruct['data'];
				else
					$data = "none";
			}
				
			$constructTableHTML .= '<tr>';
			
			if($currConstruct['function'] != null)
				$constructTableHTML .= '<td><a href="http://phpfeedwriter.webmasterhub.net/docs/' . $this->feed_construct->functions[$currConstruct['function']] . '" target="_blank"><b>' . $currConstruct['function'] . '()</b></a></td>';
			else
				$constructTableHTML .= '<td>&nbsp;</td>';
				
			$constructTableHTML .= '<td>' . $currConstruct['commonName'] . '</td>
			<td>' . $currConstruct['elementName'] . '&nbsp;</td>
			<td>' . $currConstruct['parentConstruct'] . '&nbsp;</td>
			<td>' . $min . '&nbsp;</td>
			<td>' . $max . '&nbsp;</td>
			<td>' . $currConstruct['limit'] . '&nbsp;</td>
			<td>' . $currConstruct['type'] . '&nbsp;</td>';
			
			if($currConstruct['example'] != null)
				$constructTableHTML .= '<td>' . $currConstruct['example'] . '&nbsp;</td>';
			elseif($currConstruct['example'] == null && $currConstruct['function'] != null)
				$constructTableHTML .= '<td>See <a href="http://phpfeedwriter.webmasterhub.net/docs/' . $this->feed_construct->functions[$currConstruct['function']] . '" target="_blank">' . $currConstruct['function'] . '()</a> documentation.</td>';
			else
				$constructTableHTML .= '<td>&nbsp;</td>';
			
			$constructTableHTML .= '<td>' . $attributesTableHTML . '&nbsp;</td>';

			if($error)
				$constructTableHTML .= '<td>' . $data . '&nbsp;</td>';
			
			$constructTableHTML .= '</tr>';
			
		}
		
		if(!$error)
			$html_output = '<h3><a href="http://phpfeedwriter.webmasterhub.net/">Php FeedWriter</a></h3><hr/>';
		else
			$html_output = '';
		
		$html_output .= '<h3>Output format: ' . $format . '</h3>
				<p>
					<a href="' . $this->feed_construct->classDocsUrl . '">View Online Version of the ' . $format . ' Construct Documentation</a>
					for more information about this construct and feed format.
				</p>
				<br/>
				<table border=1 cellpadding=2>
					<tr>
					<th>Documenation</th>
					<th>Common name</th>
					<th>Element name</th>
					<th>Parent construct</th>
					<th>Min occurances</th>
					<th>Max occurances</th>
					<th>Character limit</th>
					<th>Data type</th>
					<th>Example</th>
					<th>Attributes</th>';
					
					if($error)
						$html_output .= '<th>Input Data</th>';
					
					$html_output .= '</tr>
					' . $constructTableHTML . '
				</table>';
				
		return $html_output;
	}
	
	//  Depreciated Functions:
	//--------------------------------------------------------------------------------
	//  The following functions are no longer in use.  They remain in the class for 
	//  cases where they may be still in use, but will no longer be supported.  
	//
	//  Future versions of the FeedWriter class may not include the functions below.
	//--------------------------------------------------------------------------------
		
	function addImage(){}
	function channelCloud(){}
	function addCategory(){}
	function addItem(){}
	function addElement(){}
	
	/*********************************************************************************
	* function getXMLFiltered: Never implemented
	*
	* Description:	generates and returns the rss feed xml filtered by the categories passed to the function.
	* 				The resulting RSS feed will only contain items which have one or more of the
	*				specified categories.
	*	
	* Paramaters:	$categories: Array("category_name1", "category_name2", "category_nam3")
	*
	* Returns:		Returns the rss feed xml as a String
	**********************************************************************************/
	function getXMLFiltered($categories)
	{	
		//  Use getXML() instead, passing the catgory filter value as the second parameter.
	}
	
	
	/*********************************************************************************
	* function closeItem - No longer in use
	*
	* Description:	Closes an item element only if the current has been left open.
	*	
	* Paramaters:	none
	*
	* Returns:		Void
	**********************************************************************************/
	function closeItem()
	{
		if($this->itemOpen)
		{
			$this->xml .= '</item>
';//end item tag with new line
			$this->itemOpen = false;
		}
	}
	
	/*********************************************************************************
	* private function closeDocument - No longer in use
	*
	* Description:	Closes the Channel and rss elements as well as the document
	*	
	* Paramaters:	none
	*
	* Returns:		Void
	**********************************************************************************/
	private function closeDocument()
	{
		//Create the xml write object
		$writer = new XMLWriter(); 
		$writer->openMemory(); 
		$writer->setIndent(4); 
		
		// Start the xml elements which requiring closing (allow endElement() function to work)
		$writer->startElement('rss');
		$writer->startElement('channel'); 
		$writer->text('.');
		
		//Flush the current xml to remove start tags, but allow correct elements to be closed.
		$writer->flush(); 
		
		$writer->endElement(); 
		//End channel -------------------------------------------------------------------------
			
		// End rss 
		$writer->endElement(); 
		//-----------------------------------------------------------------------------------------
		//*****************************************************************************************

		//End Xml Document
		$writer->endDocument(); 

		//$writer->flush(); 
		$this->xml .= $writer->outputMemory(true);
	}
}
?>
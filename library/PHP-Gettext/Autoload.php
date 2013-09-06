<?php

if( ! extension_loaded('gettext') ) {
	
	require_once( dirname( __FILE__ )."/Gettext.php" );
	require_once( dirname( __FILE__ )."/PHP.php" );
		
	global $__gettext_info__;
	$__gettext_info__ = array();
	
	global $__gettext_object__;
	$__gettext_object__ = NULL;
	
	global $__gettext_object_array__;
	$__gettext_object_array__ = array();
	
	function bind_textdomain_codeset( $domain, $codeset )
	{
		global $__gettext_info__;
		$__gettext_info__[$domain]["codeset"] = $codeset;
	}
	
	function bindtextdomain( $domain, $directory )
	{
		global $__gettext_info__;
		$__gettext_info__[$domain]["directory"] = $directory;
	}
	
	function textdomain( $domain )
	{
		global $__gettext_info__;
				
		if( ! array_key_exists( $domain, $__gettext_info__ ) )
			return;
		
		if( ! array_key_exists( "directory", $__gettext_info__[$domain] ) )
			return;
		
		$directory = $__gettext_info__[$domain]["directory"];
		$codeset = array_key_exists( "codeset", $__gettext_info__[$domain] )
			? $__gettext_info__[$domain]["codeset"]
			: "UTF-8";
		
		$locale = setlocale( LC_ALL, 0 );

		global $__gettext_object__;
		$__gettext_object__ = new Gettext_PHP( $directory, $domain, $locale );
		$__gettext_object_array__[$domain] = $__gettext_object__;
	}
	
	function gettext( $message )
	{
		global $__gettext_object__;
		if( is_null( $__gettext_object__ ) )
			return $message;

		return $__gettext_object__->gettext( $message );
	}
	
	function _( $message )
	{
		return gettext( $message );
	}
	
	function ngettext( $msgid1, $msgid2, $n )
	{
		global $__gettext_object__;
		if( is_null( $__gettext_object__ ) )
			return $msgid1;
		
		return $__gettext_object__->ngettext( $msgid1, $msgid2, $n );
	}
	
	function dgettext( $domain, $message )
	{
		global $__gettext_object_array__;
		if( ! array_key_exists( $domain, $__gettext_object_array__ ) )
			return $message;
		
		return $__gettext_object_array__[$domain]->gettext( $message );
	}
	
	function dngettext( $domain, $msgid1, $msgid2, $n )
	{
		global $__gettext_object_array__;
		if( ! array_key_exists( $domain, $__gettext_object_array__ ) )
			return $msgid1;
		
		return $__gettext_object_array__[$domain]->ngettext( $msgid1, $msgid2, $n );
	}
	
	function dcgettext( $domain, $message, $category )
	{
		throw new Exception( "not implemented" );
	}
	
	function dcngettext( $domain , $msgid1 , $msgid2 , $n , $category )
	{
		throw new Exception( "not implemented" );
	}
	
}

?>
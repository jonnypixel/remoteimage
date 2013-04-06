<?php
/**
 * @package     Windwalker.Framework
 * @subpackage  AKHelper
 *
 * @copyright   Copyright (C) 2012 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Generated by AKHelper - http://asikart.com
 */


// No direct access
defined('_JEXEC') or die;


class AKHelperLang {
	
	const APT_KEY = 'AIzaSyC04nF4KXjfR2VQ0jsFm5vEd9LbyiXqbKw' ;
	
	/*
	 * function translate
	 * @param $text
	 */
	
	public static function translate($text, $SourceLan = null, $ResultLan = null, $separate = 0)
	{
		// if text too big, separate it.
		if($separate) {
			
			if(JString::strlen($text) > $separate) {
				$text = JString::str_split( $text, $separate );
			}else{
				$text = array($text) ;
			}
			
		}else{
			$text = array($text) ;
		}
		
		$result = '' ;
		
		// Do translate by google translate API.
		foreach( $text as $txt):
			$result .= self::gTranslate($txt,$SourceLan,$ResultLan) ;
		endforeach;
		
		return $result ;
	}
	
	
	public static function gTranslate ($text,$SourceLan,$ResultLan) {
		
		$url = new JURI();
		
		// for APIv2
		$url->setHost( 'https://www.googleapis.com/' );
		$url->setPath( 'language/translate/v2' ) ;
		
		$query['key'] 		= self::APT_KEY ;
		$query['q'] 		= urlencode($text) ;
		$query['source'] 	= $SourceLan ;
		$query['target'] 	= $ResultLan ;
		
		if( !$text ) return ;
		
		$url->setQuery( $query );
		$url->toString() ;
		$response =  file_get_contents( $url->toString() );
		
		$json = new JRegistry();
		$json->loadString( $response );
		
		$r =  $json->get( 'data.translations' ) ;
		
		return $r[0]->translatedText ;
	}
	
	
	
	/*
	 * function getLangFiles
	 * @param 
	 */
	
	public static function loadAll($lang = 'en-GB')
	{
		$folder = AKHelper::_('path.getAdmin').'/language/'.$lang ;
		
		if(JFolder::exists($folder)) {
			$files 	= JFolder::files($folder);
		}else{
			return ;
		}
		
		$lang 	= JFactory::getLanguage();
		$langs 	= array();
		
		foreach( $files as $file ):
			$file = explode('.', $file);
			if( array_pop($file) != 'ini' ) continue ;
			
			array_shift($file);
			
			if( count($file) == 1 || $file[1] == 'sys' ) continue ;
			
			$lang->load(implode('.', $file), AKHelper::_('path.getAdmin')) ;
		endforeach;
	}
	
	
	
	/*
	 * function loadLanguage
	 * @param $ext
	 */
	
	public static function loadLanguage($ext = null, $client = 'site')
	{
		if(!$ext) {
			$ext = AKHelper::_('path.getOption');
		}
		$lang = JFactory::getLanguage();
		
		$lang->load($ext, JPATH_BASE, null, false, false)
		|| $lang->load($ext, AKHelper::_('path.get', $client, $ext), null, false, false)
		|| $lang->load($ext, JPATH_BASE, null, true)
		|| $lang->load($ext, AKHelper::_('path.get', $client, $ext), null, true);
	}
}


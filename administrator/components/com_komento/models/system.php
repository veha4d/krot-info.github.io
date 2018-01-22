<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2012 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access');

require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'parent.php' );

class KomentoModelSystem extends KomentoParentModel
{
	public function save( $data )
	{
		$component	= $data['component'];
		$component	= preg_replace('/[^A-Z0-9_\.-]/i', '', $component);
		$component	= JString::strtolower( JString::trim($component) );
		unset($data['component']);

		$config	= Komento::getTable( 'Configs' );
		$config->load( $component );
		$config->component	= $component;

		$registry = Komento::getRegistry( $config->params );

		// $registry = Komento::_( 'loadRegistry', 'komento', $config->params );

		$registry->extend( $data );

		// Get the complete INI string
		$config->params	= $registry->toString( 'INI' );

		// remove environment keys/values
		$config->params = str_replace( "komento_environment=\"production\"", '', $config->params);
		$config->params = str_replace( "komento_environment=\"development\"", '', $config->params);
		$config->params = str_replace( "foundry_environment=\"production\"", '', $config->params);
		$config->params = str_replace( "foundry_environment=\"development\"", '', $config->params);

		// Save it
		if(!$config->store( $component ) )
		{
			return false;
		}

		return true;
	}
}

<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2012 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
*
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access');

require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'parent.php' );

class KomentoModelIpfilter extends KomentoModel
{
	public function __construct()
	{
		$this->db = $this->getDBO();
	}

	public function getRule( $component, $ip )
	{
		$query = 'SELECT ' . $this->db->nameQuote( 'rules' ) . ' FROM ' . $this->db->nameQuote( '#__komento_ipfilter' ) . ' WHERE ' . $this->db->nameQuote( 'component' ) . ' = ' . $this->db->quote( $component ) . ' AND ' . $this->db->nameQuote( 'ip' ) . ' = ' . $this->db->quote( $ip );

		$this->db->setQuery( $query );
		$result = $this->db->loadResult();

		if( !$result )
		{
			return false;
		}

		$result = json_decode( $result );

		return $result;
	}
}

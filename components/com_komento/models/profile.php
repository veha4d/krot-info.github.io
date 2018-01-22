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

class KomentoModelProfile extends KomentoModel
{
	public function __construct()
	{
		$this->db = $this->getDBO();
	}

	public function exists( $id )
	{
		$query	= 'SELECT COUNT(*) FROM ' . $this->db->nameQuote( '#__users' )
				. ' WHERE ' . $this->db->nameQuote( 'id' ) . '=' . $this->db->quote( $id )
				. ' AND ' . $this->db->nameQuote( 'block' ) . '=' . $this->db->quote( 0 );
		$this->db->setQuery( $query );
		return $this->db->loadResult();
	}
}

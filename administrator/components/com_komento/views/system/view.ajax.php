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

require_once( KOMENTO_ADMIN_ROOT . DIRECTORY_SEPARATOR . 'views.php');

class KomentoViewSystem extends KomentoAdminView
{
	public function getArticleStatistics()
	{
		$ajax = Komento::getAjax();

		// check depth column first
		$db = Komento::getDBO();
		if( !$db->isColumnExists( '#__komento_comments', 'depth' ) )
		{
			$query = 'ALTER TABLE  `#__komento_comments` ADD `depth` INT(11) NOT NULL DEFAULT \'0\' AFTER `rgt`';
			$db->setQuery( $query );
			if( !$db->query() )
			{
				$ajax->reject();
				return $ajax->send();
			}
		}

		$query = 'SELECT DISTINCT ' . $db->nameQuote( 'component' ) . ', ' . $db->nameQuote( 'cid' ) . ' FROM ' . $db->nameQuote( '#__komento_comments' );

		$db->setQuery( $query );

		$parents = $db->loadObjectList();

		$ajax->resolve( $parents );
		return $ajax->send();
	}

	public function populateDepth()
	{
		$db = Komento::getDBO();
		$ajax = Komento::getAjax();

		$component = JRequest::getString( 'component' );
		$cid = JRequest::getString( 'cid' );

		$query = 'SELECT ' . $db->nameQuote( 'id' ) . ' FROM ' . $db->nameQuote( '#__komento_comments' );
		$query .= ' WHERE ' . $db->nameQuote( 'component' ) . ' = ' . $db->quote( $component );
		$query .= ' AND ' . $db->nameQuote( 'cid' ) . ' = ' . $db->quote( $cid );
		$query .= ' AND ' . $db->nameQuote( 'parent_id' ) . ' = ' . $db->quote( '0' );

		$db->setQuery( $query );

		$parents = $db->loadResultArray();

		if( !empty( $parents ) )
		{
			foreach( $parents as $parent )
			{
				$this->recurseChildren( $parent, 1 );
			}
		}

		$ajax->resolve();
		return $ajax->send();
	}

	private function recurseChildren( $id, $depth )
	{
		$children = $this->getChildrenId( $id );

		if( !empty( $children ) )
		{
			$ids = implode( ',', $children );

			$this->updateDepth( $ids, $depth );

			foreach( $children as $child )
			{
				$this->recurseChildren( $child, $depth + 1 );
			}
		}
	}

	private function getChildrenId( $id )
	{
		$db = Komento::getDBO();

		$query = 'SELECT ' . $db->nameQuote( 'id' ) . ' FROM ' . $db->nameQuote( '#__komento_comments' );
		$query .= ' WHERE ' . $db->nameQuote( 'parent_id' ) . ' = ' . $db->quote( $id );

		$db->setQuery( $query );

		$children = $db->loadResultArray();

		return $children;
	}

	private function updateDepth( $ids, $depth )
	{
		$db = Komento::getDBO();

		$query = 'UPDATE ' . $db->nameQuote( '#__komento_comments' ) . ' SET ' . $db->nameQuote( 'depth' ) . ' = ' . $db->quote( $depth );
		$query .= ' WHERE ' . $db->nameQuote( 'id' ) . ' IN(' . $db->quote( $ids ) . ')';

		$db->setQuery( $query );
		$db->query();
	}
}

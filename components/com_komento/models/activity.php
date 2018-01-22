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

class KomentoModelActivity extends KomentoModel
{
	public $_total = null;

	public function __construct()
	{
		$this->db = $this->getDBO();
	}

	public function add( $type, $comment_id, $uid )
	{
		$now	= Komento::getDate()->toMySQL();
		$query	= 'INSERT INTO ' . $this->db->nameQuote( '#__komento_activities' )
				. ' ( `type`, `comment_id`, `uid`,  `created`, `published` ) '
				. ' VALUES ( '
				. $this->db->quote( $type ) . ', '
				. $this->db->quote( $comment_id ) . ', '
				. $this->db->quote( $uid ) . ', '
				. $this->db->quote( $now ) . ', '
				. $this->db->quote( 1 ) . ')';
		$this->db->setQuery( $query );

		return $this->db->query();
	}

	public function delete( $comment_id )
	{
		$query = 'DELETE FROM `#__komento_activities` WHERE `comment_id` = ' . $this->db->quote( $comment_id );
		$this->db->setQuery( $query );

		return $this->db->query();
	}

	public function getUserActivities( $id, $options = array() )
	{
		// comments, likes, recommends, articles, forum post, feature... need a hook to get 3rd party content what say you?

		// define default values
		$defaultOptions	= array(
			'type'		=> 'like,comment,reply',
			'sort'		=> 'latest',
			'start'		=> 0,
			'limit'		=> 10,
			// 'search'	=> '', future todo
			'published'	=> 1,
			'component'	=> 'all',
			'cid'		=> 'all'
		);

		// take the input values and clear unexisting keys
		$options	= array_merge($defaultOptions, $options);

		$query = $this->buildQuery( $id, $options );
		$this->db->setQuery( $query );

		return $this->db->loadObjectList();
	}

	public function getTotalUserActivities( $id, $options = array() )
	{
		if (empty($this->_total))
		{
			// define default values
			$defaultOptions	= array(
				'type'		=> 'like,comment,reply',
				'published'	=> 1,
				'component'	=> 'all',
				'cid'		=> 'all'
			);

			$options	= array_merge($defaultOptions, $options);

			$query = $this->buildQueryTotal( $id, $options );
			$this->db->setQuery( $query );
			$this->_total = $this->db->loadResult();
		}

		return $this->_total;
	}

	private function buildQuery( $id, $options )
	{
		$querySelect = $this->buildSelect( $id, $options );
		$queryWhere = $this->buildWhere( $id, $options );
		$queryOrder = $this->buildOrder( $id, $options );
		$queryLimit = $this->buildLimit( $id, $options );

		return $querySelect . $queryWhere . $queryOrder . $queryLimit;
	}

	private function buildQueryTotal( $id, $options )
	{
		$querySelect = $this->buildSelect( $id, $options );
		$queryWhere = $this->buildWhere( $id, $options );

		return 'SELECT COUNT(1) FROM (' . $querySelect . $queryWhere . ') as X';
	}

	private function buildSelect( $id, $options )
	{
		$query  = 'SELECT activities.*, comments.component, comments.cid, comments.comment, comments.name, comments.created_by, comments.parent_id FROM ' . $this->db->nameQuote( '#__komento_activities' ) . ' AS activities';
		$query .= ' LEFT JOIN ' . $this->db->nameQuote( '#__komento_comments' ) . ' AS comments ON activities.comment_id = comments.id';
		return $query;
	}

	private function buildWhere( $id, $options )
	{
		$query = array();

		if( $id !== 'all' )
		{
			$query[] = 'activities.uid = ' . $this->db->quote( $id );
		}

		$query[] = 'activities.published = ' . $this->db->quote( $options['published'] );

		$query[] = 'comments.published = 1';

		if( $options['component'] !== 'all' )
		{
			$query[] = 'comments.component = ' . $this->db->quote( $options['component'] );
		}
		else
		{
			$query[] = 'comments.component IS NOT null';
		}

		if( $options['cid'] !== 'all' )
		{
			if( is_array( $options['cid'] ) )
			{
				$options['cid'] = implode( ',', $options['cid'] );
			}

			if( !empty( $cid ) )
			{
				$query[] = 'comments.cid = 0';
			}
			else
			{
				$query[] = 'comments.cid IN (' . $options['cid'] . ')';
			}
		}
		else
		{
			$query[] = 'comments.cid IS NOT null';
		}

		if( $options['type'] !== 'all' )
		{
			$tmp = $options['type'];

			if( !is_array( $options['type'] ) )
			{
				$tmp = explode( ',', $options['type'] );
			}

			foreach( $tmp as &$t )
			{
				$t = $this->db->quote( $t );
			}

			$tmp = implode( ',', $tmp );

			$query[] = 'activities.type IN (' . $tmp . ')';
		}

		$query = ' WHERE ' . implode(' AND ', $query);
		return $query;
	}

	private function buildOrder( $id, $options )
	{
		$query = '';
		switch( $options['sort'] )
		{
			case 'oldest':
				$query = ' ORDER BY activities.created ASC';
				break;
			case 'latest':
			default:
				$query = ' ORDER BY activities.created DESC';
				break;
		}
		return $query;
	}

	private function buildLimit( $id, $options )
	{
		$query = ' LIMIT ' . $options['start'] . ',' . $options['limit'];
		return $query;
	}
}

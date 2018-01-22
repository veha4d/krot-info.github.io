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

class KomentoModelReports extends KomentoParentModel
{
	public $_data;
	public $_total;
	public $_pagination;

	public $order;
	public $order_dir;
	public $limit;
	public $limitstart;
	public $filter_publish;
	public $filter_component;
	public $search;

	function __construct()
	{
		$db						= Komento::getDBO();
		$mainframe				= JFactory::getApplication();
		$this->limit			= $mainframe->getUserStateFromRequest( 'com_komento.reports.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$this->limitstart		= $mainframe->getUserStateFromRequest( 'com_komento.reports.limitstart', 'limitstart', 0, 'int' );
		$this->filter_publish 	= $mainframe->getUserStateFromRequest( 'com_komento.reports.filter_publish', 'filter_publish', '*', 'string' );
		$this->filter_component	= $mainframe->getUserStateFromRequest( 'com_komento.reports.filter_component', 'filter_component', '*', 'string' );
		$this->order			= $mainframe->getUserStateFromRequest( 'com_komento.reports.filter_order', 'filter_order', 'created', 'cmd' );
		$this->order_dir		= $mainframe->getUserStateFromRequest( 'com_komento.reports.filter_order_Dir',	'filter_order_Dir',	'DESC', 'word' );
		$this->search 			= $mainframe->getUserStateFromRequest( 'com_komento.reports.search', 'search', '', 'string' );
		$this->search 			= $db->getEscaped( trim( JString::strtolower( $this->search ) ) );

		parent::__construct();
	}

	function getData()
	{
		// Lets load the content ifit doesn't already exist
		if( empty( $this->_data ) )
		{
			$db = Komento::getDBO();

			$query = $this->buildQuery();

			$db->setQuery( $query );

			$this->_data = $db->loadObjectList();
		}

		return $this->_data;
	}

	function buildQuery()
	{
		$mainframe	= JFactory::getApplication();
		$db			= Komento::getDBO();

		$querySelect  = 'SELECT a.*, COUNT(`b`.`comment_id`) AS reports FROM ' . $db->nameQuote( '#__komento_comments' ) . ' AS a';
		$querySelect .= ' RIGHT JOIN ' . $db->nameQuote( '#__komento_actions' ) . ' AS b';
		$querySelect .= ' ON a.id = b.comment_id';

		$queryWhere = array();

		$queryWhere[] = 'type = ' . $db->quote( 'report' );

		// filter by component
		if( $this->filter_component != '*' )
		{
			$queryWhere[] = 'component = ' . $db->quote( $this->filter_component );
		}

		// filter by publish state
		if( $this->filter_publish != '*' )
		{
			$queryWhere[] = 'published = ' . $db->quote( $this->filter_publish );
		}

		if( $this->search )
		{
			$queryWhere[] = 'LOWER( comment ) LIKE \'%' . $this->search . '%\' ';
		}

		$queryWhere = count($queryWhere) > 0 ? ' WHERE ' . implode( ' AND ', $queryWhere ) : '';

		$queryGroup = ' GROUP BY comment_id';

		$queryOrder = ' ORDER BY ' . $this->order . ' ' . $this->order_dir;

		$queryLimit = ' LIMIT ' . $this->limitstart . ',' . $this->limit;

		$query = $querySelect . $queryWhere . $queryGroup . $queryOrder . $queryLimit;

		return $query;
	}

	function getPagination()
	{
		// Lets load the content ifit doesn't already exist
		if(empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->limitstart, $this->limit );
		}

		return $this->_pagination;
	}

	function getTotal()
	{
		// Lets load the content if it doesn't already exist
		if(empty($this->_total))
		{
			$db			= Komento::getDBO();

			$query = 'SELECT COUNT(DISTINCT(`a`.`id`)) FROM ' . $db->nameQuote( '#__komento_comments' ) . ' AS a';
			$query .= ' RIGHT JOIN ' . $db->nameQuote( '#__komento_actions' ) . ' AS b';
			$query .= ' ON `a`.`id` = `b`.`comment_id`';
			$query .= ' WHERE `b`.`type` = ' . $db->quote( 'report' );

			$db->setQuery($query);
			$this->_total = $db->loadResult();
		}

		return $this->_total;
	}
}

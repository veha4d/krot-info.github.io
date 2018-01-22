<?php
/**
* @version		1.0.0
* @package		MijoPolls
* @subpackage	MijoPolls
* @copyright	2009-2011 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AcePolls www.joomace.net
*
* Based on Apoll Component
* @copyright (C) 2009 - 2011 Hristo Genev All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.afactory.org
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class MijopollsViewPolls extends MijosoftView {

	function display($tpl = null) {
		$this->mainframe = JFactory::getApplication();
		$this->option = JRequest::getCmd('option');

		$filter_order		= $this->mainframe->getUserStateFromRequest($this->option.'.polls.filter_order',		'filter_order',			'm.title',	'string');
		$filter_order_Dir	= $this->mainframe->getUserStateFromRequest($this->option.'.polls.filter_order_Dir',	'filter_order_Dir',		'',			'word');
		$search				= $this->mainframe->getUserStateFromRequest($this->option.'.polls.search',				'search',				'',			'string');

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		// search filter
		$lists['search'] = $search;
		
		JHTML::_('behavior.tooltip');
		
		if (MijopollsHelper::is15()) {
			$params = $this->mainframe->getParams();
		}
		else {
			$jsite = new JSite();
			$menu = $jsite->getMenu()->getActive();
			
			$menu_params = new JRegistry($menu->params);
			$params = clone($this->mainframe->getParams());
			$params->merge($menu_params);
		}
		
		$this->lists = $lists;
		$this->params = $params;
		$this->items = $this->get('Data');
		$this->pagination = $this->get('Pagination');
		
		if (MijopollsHelper::is30()) {
			$tpl = '30';
		}
		
		parent::display($tpl);
	}
}
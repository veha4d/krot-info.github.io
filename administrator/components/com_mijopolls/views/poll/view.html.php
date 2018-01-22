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
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.pane');

class MijopollsViewPoll extends MijopollsView {

	function display($tpl = null) {
		$document = JFactory::getDocument();
  		$document->addStyleSheet('components/com_mijopolls/assets/css/mijopolls.css');
		
		$cid = JRequest::getVar('cid', array(0), '', 'array');
		$edit = JRequest::getVar('edit', true);
		$text = (($edit) ? JText::_('Edit') : JText::_('New'));

		JToolBarHelper::title(JText::_('COM_MIJOPOLLS_POLL').': <small><small>[ '.$text.' ]</small></small>', 'mijopolls');
		JToolBarHelper::Preview('index.php?option=com_mijopolls&cid[]='.$cid[0]);
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
		
		$this->mainframe = JFactory::getApplication();
		$user = JFactory::getUser();
		
		$row = $this->get('ItemData');

		// fail if checked out not by 'me'
		if ($row->isCheckedOut($user->get('id'))) {
			$msg = JText::sprintf('DESCBEINGEDITTED', JText::_('COM_MIJOPOLLS_THE_POLL'), $row->title);
			$this->setRedirect('index.php?option=com_mijopolls', $msg);
		}

		if ($row->id == 0) {
			$row->published	= 1;
		}

		$options = array();
		$ordering = array();

		if ($edit) {
			$options = $row->getOptions($row->id);
		}
		else {
			$row->lag = 24*60;
		}
		
		//default colors for slices
		$colors = array("ff0000","ffff99","00ccff","66ff99","ffcc00","d7ebff","ccffcc", "cccccc", "ffff00", "006699", "660000", "ffddee");

        $task = JRequest::getCmd('task');

		if (MijopollsHelper::is15()) {
            $tpl .= '15';
			$params = new JParameter($row->params, JPATH_COMPONENT.'/views/poll/poll.xml');
			$this->params = $params;
		}
		else if (MijopollsHelper::is30()) {
            $tpl = '30';
			$this->params = $this->get('Form');
		}
		else if ($task != 'preview') {
			$tpl = '25';
            $this->params = $this->get('Form');
		}
		
		$this->row = $row;
		$this->options = $options;
		$this->color = $colors;
		$this->edit = $edit;
		
		parent::display($tpl);
	}
}
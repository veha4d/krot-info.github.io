<?php
/**
* @package		MijoPolls
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

$controller	= JRequest::getCmd('controller', 'polls');

JHTML::_('behavior.switcher');

$controllers = array('polls' => JText::_('COM_MIJOPOLLS_POLLS'), 'votes' => JText::_('COM_MIJOPOLLS_VOTES'));	

foreach ($controllers as $key => $val) {
	$active	= ($controller == $key);
	JSubMenuHelper::addEntry($val, 'index.php?option=com_mijopolls&controller='.$key, $active);
}
<?php
/**
* @package		MijoPolls
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ADMINISTRATOR.'/components/com_mijopolls/helpers/mijopolls.php');

require_once(JPATH_COMPONENT.'/mvc/model.php');
require_once(JPATH_COMPONENT.'/mvc/view.php');
require_once(JPATH_COMPONENT.'/mvc/controller.php');

require_once(JPATH_COMPONENT.'/toolbar.php');

JTable::addIncludePath(JPATH_COMPONENT.'/tables');

if ($controller = JRequest::getWord('controller')) {
	$path = JPATH_COMPONENT.'/controllers/'.$controller.'.php';

	if (file_exists($path)) {
		require_once $path;
	}
	else {
		$controller = '';
	}
}

$classname = 'MijopollsController'.ucfirst($controller);

// Create the controller
$controller = new $classname();
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
<?php
/**
* @version		1.0.0
* @package		MijoPolls
* @subpackage	MijoPolls
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ADMINISTRATOR.'/components/com_mijopolls/helpers/mijopolls.php');

// Set the table directory
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');

require_once(JPATH_COMPONENT_ADMINISTRATOR.'/mvc/model.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.'/mvc/view.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.'/mvc/controller.php');

// Require specific controller if requested
if ($controller = JRequest::getWord('view')) {
	$path = JPATH_COMPONENT.'/controllers/'.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

// Create the controller
$classname	= 'MijopollsController'.ucfirst($controller);
$controller = new $classname();

$controller->registerTask('results', 'display');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
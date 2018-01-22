<?php
/*
* @package		MijoPolls
* @copyright	2009-2012 Mijosoft LLC, mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.model');

if (!class_exists('MijosoftModel')) {
	if (interface_exists('JModel')) {
		abstract class MijosoftModel extends JModelLegacy {}
	}
	else {
		class MijosoftModel extends JModel {}
	}
}

class MijopollsModel extends MijosoftModel {

	public function __construct() {
		parent::__construct();
	}
}

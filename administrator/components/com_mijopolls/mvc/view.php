<?php
/*
* @package		MijoPolls
* @copyright	2009-2012 Mijosoft LLC, mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.view');

if (!class_exists('MijosoftView')) {
    if (interface_exists('JView')) {
        abstract class MijosoftView extends JViewLegacy {}
    }
    else {
        class MijosoftView extends JView {}
    }
}

class MijopollsView extends MijosoftView {

    public function __construct() {
        parent::__construct();
    }
}

<?php
/**
* @version		1.0.0
* @package		MijoPolls
* @subpackage	MijoPolls
* @copyright	2009-2011 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

abstract class MijopollsHelper {

	public static function is15() {
		static $status;
		
		if (!isset($status)) {
			if (version_compare(JVERSION, '1.6.0', 'ge')) {
				$status = false;
			}
			else {
				$status = true;
			}
		}
		
		return $status;
	}

	public static function is30() {
		static $status;
		
		if (!isset($status)) {
			if (version_compare(JVERSION, '3.0.0', 'ge')) {
				$status = true;
			}
			else {
				$status = false;
			}
		}
		
		return $status;
	}
}
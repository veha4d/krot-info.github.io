<?php
/**
* @version		1.0.0
* @package		MijoPolls
* @subpackage	Mighty Touch
* @copyright	2009-2011 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AcePolls www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgMijopollsMightytouch extends JPlugin {

	function onAfterVote($poll, $option_id) {
		$user = JFactory::getUser();
		$api = JPATH_ROOT.'/components/com_community/api.php';
		
		if ($this->params->get('points', '0') == '1' && file_exists($api)) {
			require_once($api);
			JSCommunityApi::increaseKarma($user->id, $this->params->get('points_value', '0'));
		}
		
		if ($this->params->get('activity', '0') == '1' && file_exists($api)) {
			require_once($api);
			
			$icon = 'media/mijopolls/images/mijopolls.png';
			$link = JRoute::_('index.php?option=com_mijopolls&amp;view=poll&amp;id='. $poll->id . ":" . $poll->alias . self::getItemid($poll->id));
			$text = JText::_('COM_MIJOPOLLS_ACTIVITY_TEXT') . ' <a href="'.$link.'">' . $poll->title . '</a>';
			
			$capi = new JSCommunityApi();
			$capi->registerActivity(0, $text, $user->id, $icon, 'user', null, 'com_mijopolls', '', 'Polls');
		}
	}
	
	function getItemid($poll_id) {
        $component 	= JComponentHelper::getComponent('com_mijopolls');
		$menus		= JApplication::getMenu('site', array());
		$items		= $menus->getItems('component_id', $component->id);
		$match 		= false;
		$item_id	= '';
		
		if (isset($items)) {
			foreach ($items as $item) {
				if ((@$item->query['view'] == 'poll') && (@$item->query['id'] == $poll_id)) {
					$itemid = $item->id;
					$match = true;
					break;
				}			
			}
		}
		
		if ($match) {
			$item_id = '&Itemid='.$itemid;
		}
		
		return $item_id;
	}
}
?>
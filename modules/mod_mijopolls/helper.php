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

class modMijopollsHelper {

	static function getPollOptions($poll_id) {
		$db	= JFactory::getDBO();

		$query = "SELECT o.id, o.text, o.ordering" .
			" FROM #__mijopolls_options AS o " .
			" WHERE o.poll_id = ".(int)$poll_id.
			" AND o.text <> ''" .
			" ORDER BY o.ordering"
			;
		
		$db->setQuery($query);

		if (!($options = $db->loadObjectList())) {
			echo "helper ".$db->stderr();
			return;
		}

		return $options;
	}
	
	// checks if user has voted (if cookie is set)
	static function alreadyVoted($id) {
		$mainframe = JFactory::getApplication();
		
		if (MijopollsHelper::is30()) {
			$cookieName	= JApplication::getHash($mainframe->getName().'poll'.$id);
		}
		else {
			$cookieName	= JUtility::getHash($mainframe->getName().'poll'.$id);
		}
		
		$voted 		= JRequest::getVar($cookieName, '0', 'COOKIE', 'INT');
		
		return $voted;
	}
	
	static function userVoted($user_id, $poll_id) {
		$db	= JFactory::getDBO();
		$query = "SELECT date FROM #__mijopolls_votes WHERE poll_id=".(int) $poll_id." AND user_id=".(int)$user_id; 
		$db->setQuery($query);

		return $userVoted=($db->loadResult()) ? 1 : 0;	
	}
	
	static function ipVoted($poll_id) {
		$db	= JFactory::getDBO();
		$ip = ip2long($_SERVER['REMOTE_ADDR']);
		$query = "SELECT ip FROM #__mijopolls_votes WHERE poll_id=".(int) $poll_id." AND ip = '".$ip."'";
		$db->setQuery($query); 
		
		return $ipVoted=($db->loadResult()) ? 1 : 0;	
	}
	
	static function getResults($poll_id) {
        $db	= JFactory::getDBO();
		$query = "SELECT o.*, COUNT(v.id) AS hits, 
		(SELECT COUNT(id) FROM #__mijopolls_votes WHERE poll_id=".$poll_id.") AS votes 
		FROM #__mijopolls_options AS o 
		LEFT JOIN  #__mijopolls_votes AS v 
		ON (o.id = v.option_id AND v.poll_id = ".(int)$poll_id . ")
		WHERE o.poll_id=".(int)$poll_id ." 
		AND o.text <> '' 
		GROUP BY o.id 
		ORDER BY o.ordering";
		
		$db->setQuery($query);
		
		return $db->loadObjectList();
	}
    
    function getActivePolls() {
        $db    = JFactory::getDBO();
        $query = "SELECT id FROM #__mijopolls_polls WHERE published = 1";
        $db->setQuery($query);
        if ($ids = $db->loadResultArray()) {
            return $ids;
        } else {
            return false;
        }
    }
	
	static function getItemid($poll_id) {
        $component 	= JComponentHelper::getComponent('com_mijopolls');
		$japp		= new JApplication();
		$menus		= $japp->getMenu('site', array());

        if (MijopollsHelper::is15()) {
            $items	= $menus->getItems('componentid', $component->id);
        }
        else {
            $items	= $menus->getItems('component_id', $component->id);
        }

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
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

$lang_file = JFactory::getLanguage();
$lang_file->load('com_mijopolls', JPATH_SITE);

// Include the syndicate functions only once
require_once (dirname(__FILE__).'/helper.php');
require_once(JPATH_ADMINISTRATOR.'/components/com_mijopolls/helpers/mijopolls.php');

$tabclass_arr = array ('sectiontableentry2', 'sectiontableentry1');

$jsite		= new JSite();
$menu 		= $jsite->getMenu();
$items		= $menu->getItems('link', 'index.php?option=com_mijopolls&view=poll');
$itemid 	= isset($items[0]) ? '&Itemid='.$items[0]->id : '';
$details 	= "";

$poll_id 	= $params->get('id', 1);
//if Show random poll is selected
if (!$poll_id) {
    $ids = modMijopollsHelper::getActivePolls();
    
    if (count($ids) > 1) {
        $poll_id = $ids[array_rand($ids)];
    } else {
        $poll_id = $ids[0];
    }
}

$results = modMijopollsHelper::getResults($poll_id);

//print_r($results); exit; 
//get the component params	
JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_mijopolls/tables');
$poll = JTable::getInstance('Poll', 'Table');

if (!$poll->load($poll_id)) {
	return;
}

//merge them with the module params
$pollParams = new JRegistry($poll->params);
$params = clone($params);
$params->merge($pollParams);

$slug = ($poll->alias=='')? $poll->id : $poll->id.":".$poll->alias;

// check if cookie is set showing that the user has voted for this poll
$voted = modMijopollsHelper::alreadyVoted($poll_id);

// check if the registrated user has voted and if user has logged in
$user = JFactory::getUser();
$userVoted = modMijopollsHelper::userVoted($user->id, $poll_id);
$guest = $user->guest;

// check if this ip has been recorder
$ipVoted = modMijopollsHelper::ipVoted($poll_id);

//reset the var display_poll
$display_poll = 0;

//check the start time and the end of poll
$mainframe = JFactory::getApplication();
$date = JFactory::getDate();
//$date->setOffset($mainframe->getCfg('offset'));
$now   = $date->toSql(); 

if (($now > $poll->publish_up) && ($now < $poll->publish_down)) {
	$display_submit = 1;

	// if only registered users can vote
	if ($params->get('only_registered')) {
		//if the user is not a guest
		if(!$guest) {
			//if only one vote is allowed per logged user
			if($params->get('one_vote_per_user')) {
				//check if user has voted
				if ($userVoted) {
				//display the poll with disabled options
					$display_submit = 0;
					$msg = JText::_("MOD_MIJOPOLLS_ALREADY_VOTED");
					$details = JText::_("MOD_MIJOPOLLS_ONLY_ONE_VOTE_PER_USER");
				//user has not voted yet
				} else {
				//display the poll
                    $display_poll = 1;
                    $display_submit = 1;
					$msg = "";
				}
			// if loggedin user are allowed to vote unlimited times
			} else {
				// Check the cookie
				if($voted) {
					$display_poll = 0;
					$display_submit = 0;
					$msg = JText::_("MOD_MIJOPOLLS_ALREADY_VOTED");
					$details = JText::sprintf("MOD_MIJOPOLLS_ONLY_ONE_VOTE_PER_HOUR", $poll->lag / 60);

                //hm check the ip please but only if allowed to do that
                } elseif ($params->get('ip_check')) {
    				if($ipVoted) {
    					//display the poll with disabled options
    					$display_poll = 0;
    					$display_submit = 0;
    					$msg 		= JText::_("MOD_MIJOPOLLS_ALREADY_VOTED");
    					$details 	= JText::_("MOD_MIJOPOLLS_ONLY_ONE_VOTE_PER_IP");
    				//if user's ip has not been logged
    				}
				//if user has not voted
				} else {
					//display the poll
					$display_poll = 1;
					$display_submit = 1;
					$msg = "";
				}
			}
		//if the user has not logged in
		} else {
		//display message please log in
			$display_poll = 1;
			$display_submit = 0;

            $return = JRequest::getURI();
            $return = base64_encode($return);
            $user_option = MijopollsHelper::is15() ? 'com_user' : 'com_users';
            $link = 'index.php?option='.$user_option.'&view=login&return='.$return;

            $msg = JText::sprintf('MOD_MIJOPOLLS_PLEASE_REGISTER_TO_VOTE', '<a href="'.$link.'">', '</a>');
		}
	// if anybody can vote
	} else {
		//if user has voted, according to the cookie check
		if($voted) {
			//display the poll with disabled options or the results
			$display_poll = 0;
			$display_submit = 0;
			$msg = JText::_("MOD_MIJOPOLLS_ALREADY_VOTED");
			$details = JText::sprintf("MOD_MIJOPOLLS_ONLY_ONE_VOTE_PER_HOUR", $poll->lag/60);
		//if user has not voted according to the cookie
		} else {
			//hm check the ip please but only if allowed to do that
			if ($params->get('ip_check')) {
				if($ipVoted) {
					//display the poll with disabled options
					$display_poll = 0;
					$display_submit = 0;
					$msg 		= JText::_("MOD_MIJOPOLLS_ALREADY_VOTED");
					$details 	= JText::_("MOD_MIJOPOLLS_ONLY_ONE_VOTE_PER_IP");
				//if user's ip has not been logged
				} else {
					//display the poll
					$display_poll = 1;
					$display_submit = 1;
					$msg = "";
				}
			//if we are not allowed to do the ip check
			} else {
				//display the poll
				$display_poll = 1;
				$display_submit = 1;
				$msg = "";
			}
		}
	}

} else {
	$display_submit = 0;
	$msg = JText::_("MOD_MIJOPOLLS_VOTING_HAS_NOT_STARTED");
	$publish_up = JFactory::getDate($poll->publish_up);
	$details = JText::_("MOD_MIJOPOLLS_IT_WILL_START_ON").": ".$publish_up->format($params->get('msg_date_format'));
}

//if deadline has passed change the msg
if($now > $poll->publish_down) { 
	$display_poll = 0;
	$msg = JText::_("MOD_MIJOPOLLS_VOTING_HAS_ENDED");
	$publish_down = JFactory::getDate($poll->publish_down);
	$details = JText::_("MOD_MIJOPOLLS_ON").": ".$publish_down->format($params->get('msg_date_format'));
}

$disabled = ($display_submit)? '':'disabled="disabled"';
//if show messages is set to no, reset all messages
//if(!$params->get('show_msg')) {$msg ='<br />';}

if ($poll && $poll->id) {
	$layout = JModuleHelper::getLayoutPath('mod_mijopolls');
	$tabcnt = 0;
	$options = modMijopollsHelper::getPollOptions($poll_id);
	$itemid = modMijopollsHelper::getItemid($poll_id);
	require($layout);
}
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

jimport('joomla.utilities.simplexml');

class MijopollsViewPoll extends MijosoftView {

	function display($tpl = null) {
		// Get data from the model
		require_once(JPATH_COMPONENT.'/models/ajaxvote.php');
		
		$model = new MijopollsModelAjaxvote();

		$vote		=  $model->getVoted();
		$data		=  $model->getData();
		$total		=  $model->getTotal();

		$poll_id = JRequest::getInt('id', 0, 'post');
		
		if (MijopollsHelper::is15()) {
			// create root node
			$xml = new JSimpleXMLElement('poll', array('id' => $poll_id));
			
			//get total votes 
			$sum = 0;
			foreach ($data as $row) {
				$sum += $row->votes;
			}
			
			$number_voters = 0;

			$options = $xml->addChild('options');
			
			for ($i=0; $i<$total; $i++) {
				$option = $options->addChild('option', array('id'=>$data[$i]->id, 'percentage' => self::_toPercent($data[$i]->votes, $sum), 'votes'=>$data[$i]->votes, 'color'=>$data[$i]->color));
				$text = $option->addChild('text');
				$text->setData($data[$i]->text);		
				$number_voters += $data[$i]->votes;
			}
			
			$voters = $xml->addChild('voters');
			$voters->setData($number_voters);
			
			$this->assign('xml', $xml->toString());
		}
		else {
			// create root node
			$xml = new JXMLElement('<poll></poll>');
			$xml->addAttribute('id', $poll_id);
			
			//get total votes
			$sum = 0;
			foreach ($data as $row) {
				$sum += $row->votes;
			}
			
			$number_voters = 0;
			$options = $xml->addChild('options');
			
			for ($i = 0; $i < $total; $i++) {
				$option = $options->addChild('option');
				
				$option->addAttribute('id', $data[$i]->id);
				$option->addAttribute('percentage', self::_toPercent($data[$i]->votes, $sum));
				$option->addAttribute('color', $data[$i]->color);
				$option->addChild('text', $data[$i]->text);
				
				$number_voters += $data[$i]->votes;
			}
			
			$xml->addChild('voters', $number_voters);

			$this->assign('xml', $xml->asFormattedXML());
		}
		
		$this->setLayout('raw');
		
		parent::display($tpl);
	}
	
	function _toPercent($val, $sum) { 
		return round($val*100/$sum, 1);
	}
}
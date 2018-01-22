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
defined('_JEXEC') or die('Restricted access'); ?>

<table class="pollstableborder" cellspacing="0" cellpadding="0" border="0" style="martin-top:10px;">
	<?php 
	$k=0; $i=0;
	foreach($this->options as $poll_option){ ?>
	<tr class="sectiontableentry2">
		<td width="100%" colspan="2" style="font-size:<?php echo $this->params->get('optionsFontSize'); ?>px;">
			<?php echo $poll_option->text; ?>
		</td>
	</tr>
	<tr class="sectiontableentry1">
		<td>
			<?php if ($this->params->get('show_hits')) { ?> 
				<strong><?php echo JText::_('COM_MIJOPOLLS_VOTES'); echo ":&nbsp;".$poll_option->hits; ?></strong>
			<?php } ?>
		</td>
		<td width="<?php echo$this->params->get('chartX'); ?>" >
			<div style="width:100%; border:<?php echo $this->params->get('bgBarBorder'); ?>; background:#<?php echo$this->params->get('bgBarColor'); ?>; height:<?php echo$this->params->get('barHeight'); ?>px;">
			<div id="poll_bar<?php echo $i; ?>" style="height:<?php echo$this->params->get('barHeight')-2; ?>px; width:<?php echo $poll_option->percent; ?>%; float:left; background-color:#<?php echo $poll_option->color ?>; border:<?php echo $this->params->get('barBorder'); ?> ">
			</div>
				<span style="float:left; padding-left:3px;">
					<?php echo $poll_option->percent; ?>%
				</span>&nbsp;
			</div>
		</td>
	</tr> 
	<? $k= 1-$k; $i++; ?>
	<?php } ?>
</table>
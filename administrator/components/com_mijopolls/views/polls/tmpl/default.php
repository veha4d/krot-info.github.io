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

defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php?option=com_mijopolls&amp;controller=polls&amp;task=view" method="post" name="adminForm" id="adminForm">
	<table>
		<tr>
			<td align="left" width="100%">
				<?php echo JText::_('COM_MIJOPOLLS_FILTER'); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_('COM_MIJOPOLLS_GO'); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_('COM_MIJOPOLLS_RESET'); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php echo $this->lists['state']; ?>
			</td>
		</tr>
	</table>
	
	<table class="adminlist">
		<thead>
			<tr>
				<th width="5">
					<?php echo JText::_('#'); ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
				</th>
				<th  class="title">
					<?php echo JHTML::_('grid.sort', JText::_('COM_MIJOPOLLS_TITLE'), 'm.title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th width="5%" align="center">
					<?php echo JHTML::_('grid.sort', JText::_('COM_MIJOPOLLS_PUBLISHED'), 'm.published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th width="10%" align="center">
					<?php echo JHTML::_('grid.sort', JText::_('COM_MIJOPOLLS_START_DATE'), 'm.publish_up', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th width="10%" align="center">
					<?php echo JHTML::_('grid.sort', JText::_('COM_MIJOPOLLS_END_DATE'), 'publish_down', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th width="5%" align="center">
					<?php echo JHTML::_('grid.sort', JText::_('COM_MIJOPOLLS_VOTES'), 'votes', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th width="5%" align="center">
					<?php echo JHTML::_('grid.sort', JText::_('COM_MIJOPOLLS_OPTIONS'), 'numoptions', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th width="5%" align="center">
					<?php echo JHTML::_('grid.sort', JText::_('COM_MIJOPOLLS_LAG'), 'm.lag', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th width="1%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', JText::_('ID'), 'm.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$k = 0;
		$n=count($this->items);
		
		for ($i=0; $i < $n; $i++) {
			$row =$this->items[$i];

			$link 		= JRoute::_('index.php?option=com_mijopolls&controller=poll&task=edit&cid[]='.$row->id);

			$checked 	= JHTML::_('grid.checkedout', $row, $i);
			$published 	= JHTML::_('grid.published', $row, $i);
		?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $this->pagination->getRowOffset($i); ?>
				</td>
				<td>
					<?php echo $checked; ?>
				</td>
				<td>
				<?php if (JTable::isCheckedOut($this->user->get('id'), $row->checked_out)) {
					echo $row->title;
				} else {
					?>
					<span class="editlinktip hasTip" title="<?php echo JText::_('COM_MIJOPOLLS_EDIT_POLL');?>::<?php echo $row->title; ?>">
					<a href="<?php echo $link; ?>">
						<?php echo $row->title; ?></a></span>
					<?php
				}
				?>
				</td>
				<td align="center">
					<?php echo $published;?>
				</td>			
				<td align="center">
					<?php echo $row->publish_up;?>
				</td>			
				<td align="center">
					<?php echo $row->publish_down;?>
				</td>
				<td align="center">
					<a href="index.php?option=com_mijopolls&controller=votes&task=view&total=<?php echo $row->votes; ?>&id=<?php echo $row->id; ?>"><?php echo $row->votes; ?></a>
				</td>
				<td align="center">
					<?php echo $row->options; ?>
				</td>
				<td align="center">
					<?php echo $row->lag/60; ?>
				</td>
				<td align="center">
					<?php echo $row->id; ?>
				</td>
			</tr>
			<?php
				$k = 1 - $k;
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>

	<input type="hidden" name="option" value="com_mijopolls" />
	<input type="hidden" name="task" value="view" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
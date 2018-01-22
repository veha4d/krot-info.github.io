<?php 
/**
* @package		MijoPolls
* @copyright	2009-2011 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access'); ?>

<script language="javascript" type="text/javascript">
	function tableOrdering(order, dir, task) {
	var form = document.adminForm;

	form.filter_order.value 	= order;
	form.filter_order_Dir.value	= dir;
	document.adminForm.submit(task);
}
</script>

<h1><?php echo $this->params->get('page_title'); ?></h1>
<form action="<?php echo JRoute::_(JFactory::getURI()->toString()); ?>" method="post" name="adminForm">
	<div id="filter-bar" class="btn-toolbar">
		<div class="filter-search btn-group pull-left">
			<label for="filter_search" class="element-invisible"><?php echo JText::_('Search in title');?></label>
			<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_(' '); ?>" value="<?php echo $this->lists['search'];?>" title="<?php echo JText::_('Search in title'); ?>" />
		</div>
		<div class="btn-group pull-left">
			<button type="submit" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
			<button type="button" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
		</div>
		<div class="btn-group pull-right">
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
	</div>

	<br/>
	<table class="table table-striped" width="100%" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<td width="5" height="20" style="" class="sectiontableheader">
					<?php echo JText::_('Num'); ?>
				</td>
				<td class="sectiontableheader">
					<?php echo JHTML::_('grid.sort', JText::_('Title'), 'm.title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</td>
					<?php if($this->params->get('show_start_date')) { ?>
				<td width="10%" class="sectiontableheader" style="text-align:center;" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', JText::_('COM_MIJOPOLLS_START'), 'm.publish_up', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</td><?php } ?>						
				<?php if($this->params->get('show_end_date')) { ?>
				<td width="10%" class="sectiontableheader" style="text-align:center;" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', JText::_('COM_MIJOPOLLS_END'), 'm.publish_down', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</td><?php } ?>					
				<?php if($this->params->get('show_status')) { ?>
				<td width="5%" class="sectiontableheader" style="text-align:center;" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', JText::_('COM_MIJOPOLLS_STATUS'), 'status', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</td><?php } ?>				
				<?php if($this->params->get('show_num_voters')) { ?>
				<td width="5%" class="sectiontableheader" style="text-align:center;" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', JText::_('COM_MIJOPOLLS_VOTES'), 'voters', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</td><?php } ?>
					<?php if($this->params->get('show_num_options')) { ?>
				<td width="5%" class="sectiontableheader" style="text-align:center;" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', JText::_('COM_MIJOPOLLS_OPTIONS'), 'numoptions', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</td><?php } ?>
			</tr>
		</thead>

		<tbody>
		<?php
		$k = 0;
		$n = count($this->items);
		for ($i=0; $i < $n; $i++) {
			$row =$this->items[$i];
			
			//find the Itemid that correspondents to the link if any.
			$component 	= JComponentHelper::getComponent('com_mijopolls');
			$japp		= new JApplication();
			$menus		= $japp->getMenu('site', array());
			
			if (MijopollsHelper::is15()) {
				$menu_items	= $menus->getItems('componentid', $component->id);
			}
			else {
				$menu_items	= $menus->getItems('component_id', $component->id);
			}
			
			$itemid		= null;
			
			if (isset($menu_items)) {
				foreach ($menu_items as $item) {
					if ((@$item->query['view'] == 'poll') && (@$item->query['id'] == $row->id)) {
						$itemid = '&Itemid='.$item->id;
						break;
					}			
				}
			}
			
			$link = JRoute::_('index.php?option=com_mijopolls&view=poll&id='.$row->slug.$itemid); 
		
		?>
			<tr class="sectiontableentry<?php echo $k + 1; ?>">
				<td>
					<?php echo $this->pagination->getRowOffset($i); ?>
				</td>
				<td>
					<span class="hasTip" title="<?php echo JText::_('COM_MIJOPOLLS_VIEW');?>">
						<a href="<?php echo $link;?>"><?php echo $row->title; ?></a>
					</span>
				</td>
				<?php if ($this->params->get('show_start_date')) { ?>
				<td align="center">
					<?php echo JFactory::getDate($row->publish_up)->format($this->params->get('date_format')); ?>
				</td>
				<?php } ?>	
				<?php if($this->params->get('show_end_date')) { ?>
				<td>
					<?php echo JFactory::getDate($row->publish_down)->format($this->params->get('date_format')); ?>
				</td>
				<?php } ?>				
				<?php if ($this->params->get('show_status')) { ?>
				<td align="center" style="text-transform:capitalize;">
					<?php 
					if ($this->params->get('show_status_as')) { ?>
						<img src="<?php echo JURI::base(); ?>media/mijopolls/images/poll-<?php echo $row->status; ?>.gif" />
					<?php 
					} else {
						echo JText::_('COM_MIJOPOLLS_'.$row->status);
					} ?>
				</td>			
				<?php 
				}
				if ($this->params->get('show_num_voters')) { ?>
				<td align="center">
					<?php echo $row->voters; ?>
				</td>
				<?php } ?>
				<?php if($this->params->get('show_num_options')) { ?>
				<td align="center">
					<?php echo $row->numoptions; ?>
				</td>
				<?php } ?>
			</tr>
			<?php $k = 1 - $k;
		}	?>
		</tbody>
	</table>
	<div>
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	<div class="pagecounter">
		<?php echo $this->pagination->getPagesCounter(); ?>
	</div>

	<input type="hidden" name="option" value="com_mijopolls" />
	<input type="hidden" name="view" value="polls" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<?php echo JHTML::_('form.token'); ?>
</form>
<br />
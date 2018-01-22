<?php
/**
 * @package		Komento
 * @copyright	Copyright (C) 2012 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *
 * Komento is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die('Restricted access'); ?>
<div class="row-fluid">
	<div class="span2">
		<div id="sidebar">
			<h4 class="page-header"><?php echo JText::_( 'COM_KOMENTO_FILTER' ); ?>:</h4>

			<div class="filter-select hidden-phone">

				<?php echo $this->state; ?>
				<hr class="hr-condensed" />

				<?php echo $this->component; ?>
				<hr class="hr-condensed" />
			</div>
		</div>
	</div>

	<div class="span10">
		<table class="table table-striped" cellspacing="1">
		<thead>

			<!--

			Row
				Number
				Checkbox
				Comment
				Component
				Article Id
				Action
				Date
				Author
				Action
				Id

			-->

			<tr>
				<th width="1%"><?php echo JText::_( 'COM_KOMENTO_COLUMN_NUM' ); ?></th>
				<th width="1%"><input type="checkbox" name="toggle" value="" onClick="Joomla.checkAll(this);" /></th>
				<th width="30%"><?php echo JText::_( 'COM_KOMENTO_COLUMN_COMMENT' ); ?></th>
				<th width="5%"><?php echo JText::_( 'COM_KOMENTO_COLUMN_STATUS' ); ?></th>
				<th width="5%"><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_KOMENTO_COLUMN_REPORT_COUNT' ) , 'reports', $this->orderDirection, $this->order ); ?></th>
				<th width="10%"><?php echo JHTML::_( 'grid.sort', JText::_('COM_KOMENTO_COLUMN_COMPONENT'), 'component', $this->orderDirection, $this->order ); ?></th>
				<th width="10%"><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_KOMENTO_COLUMN_ARTICLE' ), 'cid', $this->orderDirection, $this->order ); ?></th>
				<th width="10%"><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_KOMENTO_COLUMN_DATE' ), 'created', $this->orderDirection, $this->order ); ?></th>
				<th width="10%"><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_KOMENTO_COLUMN_AUTHOR' ) , 'created_by', $this->orderDirection, $this->order ); ?></th>
				<th width="1%"><?php echo JHTML::_( 'grid.sort', JText::_( 'COM_KOMENTO_COLUMN_ID' ) , 'id', $this->orderDirection, $this->order ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		if($this->comments)
		{
			$k = 0;
			$x = 0;
			$i = 0;
			$n = count($this->comments);
			$config = JFactory::getConfig();

			foreach($this->comments as $row)
			{
				$row = Komento::getHelper( 'comment' )->process( $row, 1 ); ?>
			<!--

			Row
				Number
				Checkbox
				Comment
				Component
				Article Id
				Action
				Date
				Author
				Action
				Id

			-->

				<tr id="<?php echo 'kmt-' . $row->id; ?>" class="<?php echo "row$k"; ?>" childs="<?php echo $row->childs; ?>" parentid="<?php echo $row->parent_id; ?>">
					<!-- Number -->
					<td class="center">
						<?php echo $this->pagination->getRowOffset( $i ); ?>
					</td>

					<!-- Checkbox -->
					<td class="center">
						<?php echo JHTML::_('grid.id', $x++, $row->id); ?>
					</td>

					<!-- Comment -->
					<td>
						<span>
							<?php echo $row->comment; ?>
						</span>
					</td>

					<!-- Publish/Unpublish -->
					<!-- <td align="center">
						<ul class="kmt-actions">
							<li class="kmt-clear"><a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i; ?>', 'clear')"><span class="icon-32-kmt-clear-reports"></span><?php echo JText::_( 'COM_KOMENTO_CLEAR' ); ?></a></li>
							<?php if( $row->published == 1 ) { ?>
							<li class="kmt-unpublish"><a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i; ?>', 'unpublish')"><span class="icon-32-unpublish"></span><?php echo JText::_( 'COM_KOMENTO_UNPUBLISH' ); ?></a></li>
							<?php } ?>
							<li class="kmt-remove"><a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i; ?>', 'remove')"><span class="icon-32-delete"></span><?php echo JText::_( 'COM_KOMENTO_DELETE' ); ?></a></li>
						</ul>
					</td> -->
					<td class="published-cell center">
						<?php if( $row->published == 2 ) { ?>
							<a title="<?php echo JText::_('COM_KOMENTO_PUBLISH_ITEM'); ?>" onclick="return listItemTask('cb<?php echo $i; ?>', 'publish')" href="javascript:void(0);">
								<img alt="<?php echo JText::_('COM_KOMENTO_MODERATE'); ?>" src="components/com_komento/assets/images/pending-favicon.png" />
							</a>
						<?php } else {
							if( $row->published != 1 ) {
								$row->published = 0;
							}
							echo JHTML::_('jgrid.published', $row->published, $i );
						} ?>
					</td>

					<!-- Report counts -->
					<td class="center">
						<?php echo $row->reports; ?>
					</td>

					<!-- Component -->
					<td class="center">
						<?php echo $row->componenttitle; ?>
					</td>

					<!-- Article Title -->
					<td class="center">
						<?php if( $row->extension ) { ?>
						<a href="<?php echo $row->pagelink; ?>"><?php echo $row->contenttitle; ?></a>
						<?php } else { ?>
						<span class="error"><?php echo $row->contenttitle; ?></span>
						<?php } ?>
					</td>

					<!-- Date -->
					<td class="center">
						<?php echo $row->unformattedDate; ?>
					</td>

					<!-- Author -->
					<td class="center">
						<?php echo $row->name; ?>
					</td>

					<!-- ID -->
					<td class="center">
						<?php if( $row->extension ) { ?>
						<a href="<?php echo $row->permalink; ?>"><?php echo $row->id; ?></a>
						<?php } else { ?>
						<span class="error"><?php echo $row->id; ?></span>
						<?php } ?>
					</td>
				</tr>
		<?php
			$k = 1 - $k;
			$i++;
			}

		} else { ?>
			<tr>
				<td colspan="12" class="center">
					<?php echo JText::_('COM_KOMENTO_COMMENTS_NO_COMMENT');?>
				</td>
			</tr>
		<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="12">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		</table>
	</div>
</div>

<?php
/*
* @package		MijoPolls
* @copyright	2009-2012 Mijosoft LLC, mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.installer.installer');

class com_MijopollsInstallerScript {

    private $_current_version = null;
    private $_is_new_installation = true;

    public function preflight($type, $parent) {
        $db = JFactory::getDBO();
        $db->setQuery('SELECT params FROM #__extensions WHERE element = "com_mijopolls" AND type = "component"');
        $config = $db->loadResult();

        if (!empty($config)) {
            $this->_is_new_installation = false;

            $mijopolls_xml = JPATH_ADMINISTRATOR.'/components/com_mijopolls/mijopolls.xml';
            $a_mijopolls_xml = JPATH_ADMINISTRATOR.'/components/com_mijopolls/a_mijopolls.xml';

            if (JFile::exists($mijopolls_xml)) {
                $xml = JFactory::getXML($mijopolls_xml);
                $this->_current_version = (string)$xml->version;
            }
			else if (JFile::exists($a_mijopolls_xml)) {
                $xml = JFactory::getXML($a_mijopolls_xml);
                $this->_current_version = (string)$xml->version;
            }
        }
    }
	
	public function postflight($type, $parent) {
		$status = new JObject();
		
        $db = JFactory::getDBO();
        $src = $parent->getParent()->getPath('source');
		
		require_once(JPATH_ADMINISTRATOR.'/components/com_mijopolls/helpers/mijopolls.php');
		
		/***********************************************************************************************
		* ---------------------------------------------------------------------------------------------
		* DATABASE INSTALLATION SECTION
		* ---------------------------------------------------------------------------------------------
		***********************************************************************************************/
		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__mijopolls_polls` (
		  `id` int(11) unsigned NOT NULL auto_increment,
		  `title` varchar(255) NOT NULL default '',
		  `alias` varchar(255) NOT NULL default '',
		  `checked_out` int(11) NOT NULL default '0',
		  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
		  `published` tinyint(1) NOT NULL default '0',
		  `publish_up` datetime NOT NULL default '0000-00-00 00:00:00',
		  `publish_down` datetime default '0000-00-00 00:00:00',
		  `params` text NOT NULL,
		  `access` int(11) NOT NULL default '0',
		  `lag` int(11) NOT NULL default '0',
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;");
		$db->query();

		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__mijopolls_options` (
		  `id` int(11) NOT NULL auto_increment,
		  `poll_id` int(11) NOT NULL default '0',
		  `text` text NOT NULL,
		  `link` varchar(255) DEFAULT NULL,
		  `color` varchar(6)  NOT NULL,
		  `ordering` int(11) NOT NULL,
		  PRIMARY KEY  (`id`),
		  KEY `poll_id` (`poll_id`,`text`(1))
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;");
		$db->query();

		$db->setQuery("CREATE TABLE IF NOT EXISTS `#__mijopolls_votes` (
		  `id` bigint(20) NOT NULL auto_increment,
		  `date` datetime NOT NULL default '0000-00-00 00:00:00',
		  `option_id` int(11) NOT NULL default '0',
		  `poll_id` int(11) NOT NULL default '0',
		  `ip` int(10) unsigned NOT NULL,
		  `user_id` int(11) DEFAULT NULL,
		  PRIMARY KEY  (`id`),
		  KEY `poll_id` (`poll_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;");
		$db->query();
		
		/***********************************************************************************************
		* ---------------------------------------------------------------------------------------------
		* MODULE INSTALLATION SECTION
		* ---------------------------------------------------------------------------------------------
		***********************************************************************************************/
		$installer = new JInstaller();
		$installer->install($src.'/extensions/mod_mijopolls');
		
		/***********************************************************************************************
		* ---------------------------------------------------------------------------------------------
		* PLUGIN INSTALLATION SECTION
		* ---------------------------------------------------------------------------------------------
		***********************************************************************************************/
		$installer = new JInstaller();
		$installer->install($src.'/extensions/plg_mijopolls');

		$installer = new JInstaller();
		$installer->install($src.'/extensions/plg_mijopollssearch');

		$installer = new JInstaller();
		$installer->install($src.'/extensions/plg_alphauserpoints');

		$installer = new JInstaller();
		$installer->install($src.'/extensions/plg_jomsocial');

		$installer = new JInstaller();
		$installer->install($src.'/extensions/plg_mightytouch');

        if ($this->_is_new_installation == true) {
			$this->_installMijopolls();
		}
        else {
            $this->_updateMijopolls();
        }

        $this->_installationOutput($status);
	}

    protected function _installMijopolls() {
        if (empty($this->_current_version)) {
            return;
        }

        if ($this->_current_version = '1.0.0') {
            return;
        }
    }

    protected function _updateMijopolls() {
        if (empty($this->_current_version)) {
            return;
        }

        if ($this->_current_version = '1.0.0') {
            return;
        }
    }
	
	public function uninstall($parent) {
		$status = new JObject();
	
        $db = JFactory::getDBO();
        $src = $parent->getParent()->getPath('source');

		$db->setQuery("SELECT extension_id FROM #__extensions WHERE type = 'module' AND element = 'mod_mijopolls' LIMIT 1");
		$id = $db->loadResult();
		if ($id) {
			$installer = new JInstaller();
			$installer->uninstall('module', $id);
		}

		$db->setQuery("SELECT extension_id FROM #__extensions WHERE type = 'plugin' AND element = 'mijopolls' AND folder = 'content' LIMIT 1");
		$id = $db->loadResult();
		if ($id) {
			$installer = new JInstaller();
			$installer->uninstall('plugin', $id);
		}

		$db->setQuery("SELECT extension_id FROM #__extensions WHERE type = 'plugin' AND element = 'mijopollssearch' AND folder = 'search' LIMIT 1");
		$id = $db->loadResult();
		if ($id) {
			$installer = new JInstaller();
			$installer->uninstall('plugin', $id);
		}

		$db->setQuery("SELECT extension_id FROM #__extensions WHERE type = 'plugin' AND element = 'alphauserpoints' AND folder = 'mijopolls' LIMIT 1");
		$id = $db->loadResult();
		if ($id) {
			$installer = new JInstaller();
			$installer->uninstall('plugin', $id);
		}

		$db->setQuery("SELECT extension_id FROM #__extensions WHERE type = 'plugin' AND element = 'jomsocial' AND folder = 'mijopolls' LIMIT 1");
		$id = $db->loadResult();
		if ($id) {
			$installer = new JInstaller();
			$installer->uninstall('plugin', $id);
		}

		$db->setQuery("SELECT extension_id FROM #__extensions WHERE type = 'plugin' AND element = 'mightytouch' AND folder = 'mijopolls' LIMIT 1");
		$id = $db->loadResult();
		if ($id) {
			$installer = new JInstaller();
			$installer->uninstall('plugin', $id);
		}
		
        $this->_uninstallationOutput($status);
	}
	
	protected function _installationOutput($status) {
?>
<img src="components/com_mijopolls/assets/images/logo.png" alt="Joomla! Polls" style="width:80px; height:80px; float: left; padding-right:15px;" />

<h2>MijoPolls Installation</h2>
<h2><a href="index.php?option=com_mijopolls">Go to MijoPolls</a></h2>
<table class="adminlist table table-striped">
	<thead>
		<tr>
			<th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
			<th width="30%"><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3"></td>
		</tr>
	</tfoot>
	<tbody>
		<tr>
			<th colspan="3"><?php echo JText::_('Core'); ?></th>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'MijoPolls '.JText::_('Component'); ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row1">
			<td class="key" colspan="2"><?php echo 'MijoPolls '.JText::_('Module'); ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr>
			<th colspan="3"><?php echo JText::_('Plugins'); ?></th>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'MijoPolls - AlphaUserPoints'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row1">
			<td class="key" colspan="2"><?php echo 'MijoPolls - JomSocial'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'MijoPolls - Mighty Touch'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row1">
			<td class="key" colspan="2"><?php echo 'Content - Load MijoPolls'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'Search - MijoPolls'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
	</tbody>
</table>
	<?php
    }

	private function _uninstallationOutput($status) {
?>
<h2>MijoPolls Removal</h2>
<table class="adminlist table table-striped">
	<thead>
		<tr>
			<th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
			<th width="30%"><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3"></td>
		</tr>
	</tfoot>
	<tbody>
		<tr>
			<th colspan="3"><?php echo JText::_('Core'); ?></th>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo JText::_('Component'); ?></td>
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
		<tr class="row1">
			<td class="key" colspan="2"><?php echo 'MijoPolls '.JText::_('Module'); ?></td>
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
		<tr>
			<th colspan="3"><?php echo JText::_('Plugins'); ?></th>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'MijoPolls - AlphaUserPoints'; ?></td>
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'MijoPolls - JomSocial'; ?></td>
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'MijoPolls - Mighty Touch'; ?></td>
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'Content - Load MijoPolls'; ?></td>
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'Search - MijoPolls'; ?></td>
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
	</tbody>
</table>
	<?php
    }
}
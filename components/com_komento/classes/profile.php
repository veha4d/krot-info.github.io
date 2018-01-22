<?php
/**
 * @package		Komento
 * @copyright	Copyright (C) 2012 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Komento is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.user.user');

/*
Using KomentoProfile

$profile	= Komento::getProfile();
$profile->load(42);
echo $profile->getAvatar();
echo $profile->getLink();

*/

class KomentoProfile extends JUser
{
	protected $profileName		= null;
	protected $profileAvatar	= null;
	protected $profileLink		= null;
	protected $profileUsername	= null;

	public function __construct($id = null)
	{
		if (empty($id))
		{
			$this->set( 'name',		JText::_( 'COM_KOMENTO_GUEST' ) );
			$this->set( 'username',	JText::_( 'COM_KOMENTO_GUEST' ) );
		}

		parent::__construct($id);
	}

	public static function getUser($id = null)
	{
		static $profiles = array();

		$juser		= JFactory::getUser($id);
		$newid		= $juser->id;

		if( empty( $newid ) )
		{
			$newid = 0;
		}

		if( empty( $profiles[$newid] ) )
		{
			$profiles[$newid]	= new KomentoProfile($newid);
			if ($newid != 0)
			{
				$profiles[$newid]->load($newid);
			}
		}

		return $profiles[$newid];
	}

	// an overwrite of JUser
	public function load($id = null)
	{
		$result = parent::load($id);
		return $result;
	}

	public function isAdmin()
	{
		$isAdmin	= false;

		if(Komento::joomlaVersion() >= '1.6')
		{
			$isAdmin	= $this->authorise('core.admin');
		}
		else
		{
			$isAdmin	= $this->usertype == 'Super Administrator' || $this->usertype == 'Administrator' ? true : false;
		}

		return $isAdmin;
	}

	public function getName()
	{
		$config = Komento::getConfig();

		if( $config->get( 'name_type' ) == 'username' )
		{
			return $this->getUsername();
		}

		if (!$this->profileName)
		{
			$this->profileName	= $this->name;
		}

		return $this->profileName;
	}

	public function getUsername()
	{
		if (!$this->profileUsername)
		{
			$this->profileUsername	= $this->username;
		}

		return $this->profileUsername;
	}

	public function getAvatar( $email = '' )
	{
		static $avatar = array();

		$config = Komento::getConfig();
		$vendorName	= $config->get( 'layout_avatar_integration' );

		if( $vendorName == 'gravatar' && $email != '' )
		{
			if( !isset( $avatar[$email] ) )
			{
				$avatar[$email] = $this->getVendor()->getAvatar( $email );
			}

			$this->profileAvatar = $avatar[$email];
		}
		else
		{
			if (!$this->profileAvatar)
			{
				$this->profileAvatar	= $this->getVendor()->getAvatar( $email );
			}
		}

		$app = JFactory::getApplication();

		if ( $app->isAdmin() )
		{
			$this->profileAvatar = str_ireplace( '/administrator/', '/', $this->profileAvatar );
		}

		return $this->profileAvatar;
	}

	public function getProfileLink()
	{
		if (!$this->profileLink)
		{
			$this->profileLink	= $this->getVendor()->getLink();
		}

		return $this->profileLink;
	}

	public function getVendor( $name = '' )
	{
		static $vendors	= array();

		$config		= Komento::getConfig();
		$preferred	= $config->get( 'layout_avatar_integration' );
		$vendorName	= $name !== '' ? $name : $preferred;

		if (empty($vendors[$vendorName][$this->id]))
		{
			require_once( KOMENTO_CLASSES . DIRECTORY_SEPARATOR . 'profileVendors.php' );
			$classname	= 'KomentoProfile' . ucfirst($vendorName);
			$vendor		= new $classname($this);

			if ( !$vendor->state )
			{
				$vendor	= $this->getVendor('default');
			}

			$vendors[$vendorName][$this->id]	= $vendor;
		}

		return $vendors[$vendorName][$this->id];
	}

	public function allow( $action = '', $component = '' )
	{
		static $loaded = null;

		$component	= $component ? $component : Komento::getCurrentComponent();

		if (!$loaded)
		{
			require_once( KOMENTO_HELPERS . DIRECTORY_SEPARATOR . 'acl.php' );
			$loaded = true;
		}

		return KomentoAclHelper::check( $action, $component, $this->id );
	}

	public function manualset( $action, $value, $component = '' )
	{
		static $loaded = null;

		$component	= $component ? $component : Komento::getCurrentComponent();

		if (!$loaded)
		{
			require_once( KOMENTO_HELPERS . DIRECTORY_SEPARATOR . 'acl.php' );
			$loaded = true;
		}

		return KomentoAclHelper::manualset( $action, $component, $this->id, $value );
	}
}

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
defined('_JEXEC') or die('Restricted access');

if( !defined( 'DS' ) )
{
	define( 'DS' , DIRECTORY_SEPARATOR );
}

// Root path
define( 'KOMENTO_ROOT', JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_komento' );

// Backend path
define( 'KOMENTO_ADMIN_ROOT', JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_komento' );

// Assets path
define( 'KOMENTO_ASSETS', KOMENTO_ROOT . DIRECTORY_SEPARATOR . 'assets' );

// Helper path
define( 'KOMENTO_HELPERS', KOMENTO_ROOT . DIRECTORY_SEPARATOR . 'helpers' );

// Controllers path
define( 'KOMENTO_CONTROLLERS', KOMENTO_ROOT . DIRECTORY_SEPARATOR . 'controllers' );

// Models path
define( 'KOMENTO_MODELS', KOMENTO_ROOT . DIRECTORY_SEPARATOR . 'models' );

// Libraries path
define( 'KOMENTO_CLASSES', KOMENTO_ROOT . DIRECTORY_SEPARATOR . 'classes' );

// Tables path
define( 'KOMENTO_TABLES', KOMENTO_ADMIN_ROOT . DIRECTORY_SEPARATOR . 'tables' );

// Themes path
define( 'KOMENTO_THEMES', KOMENTO_ROOT . DIRECTORY_SEPARATOR . 'themes' );

// Media path
define( 'KOMENTO_MEDIA_ROOT', JPATH_ROOT . DIRECTORY_SEPARATOR . 'media' );

// Komento media path
define( 'KOMENTO_MEDIA', KOMENTO_MEDIA_ROOT . DIRECTORY_SEPARATOR . 'com_komento' );

// Foundry path
define( 'KOMENTO_FOUNDRY_ROOT', KOMENTO_MEDIA_ROOT . DIRECTORY_SEPARATOR . 'foundry' . DIRECTORY_SEPARATOR . '2.1' );

// Foundry bootstrap
define( 'KOMENTO_FOUNDRY_BOOTSTRAP', KOMENTO_FOUNDRY_ROOT . DIRECTORY_SEPARATOR . 'joomla' . DIRECTORY_SEPARATOR . 'bootstrap.php' );

// JavaScripts path
define( 'KOMENTO_JS_ROOT', KOMENTO_MEDIA . DIRECTORY_SEPARATOR . 'js' );

// Scripts path
define( 'KOMENTO_SCRIPTS_ROOT', KOMENTO_MEDIA . DIRECTORY_SEPARATOR . 'scripts' );

// Scripts_ path
define( 'KOMENTO_SCRIPTS__ROOT', KOMENTO_MEDIA . DIRECTORY_SEPARATOR . 'scripts_' );

// Admistrator path
define( 'KOMENTO_ADMIN', JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_komento' );

// Spinner path
define( 'KOMENTO_SPINNER', KOMENTO_MEDIA . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'loader.gif' );

// Uploads root
define( 'KOMENTO_UPLOADS_ROOT', KOMENTO_MEDIA . DIRECTORY_SEPARATOR . 'uploads' );

// Plugins path
define( 'KOMENTO_PLUGINS' , KOMENTO_ROOT . DIRECTORY_SEPARATOR . 'komento_plugins' );

// Comment statuses
define( 'KOMENTO_COMMENT_UNPUBLISHED', 0 );
define( 'KOMENTO_COMMENT_PUBLISHED', 1 );
define( 'KOMENTO_COMMENT_MODERATE', 2 );

// Comment flags
define( 'KOMENTO_COMMENT_NOFLAG', 0 );
define( 'KOMENTO_COMMENT_SPAM', 1 );
define( 'KOMENTO_COMMENT_OFFENSIVE', 2 );
define( 'KOMENTO_COMMENT_OFFTOPIC', 3 );

// bbcode emoticons path
define( 'KOMENTO_EMOTICONS_DIR', rtrim( JURI::root() , '/' ) . '/components/com_komento/classes/markitup/sets/bbcode/images/');

// Supported components, comma separated
define( 'KOMENTO_SUPPORTED_COMPONENTS', 'com_aceshop,com_content,com_contentbuilder,com_easyblog,com_flexicontent,com_hwdmediashare,com_jevents,com_k2,com_mtree,com_ohanah,com_redshop,com_sobipro,com_virtuemart,com_zoo' );

// Updates server
define( 'KOMENTO_UPDATES_SERVER', 'stackideas.com' );

// core important files
define( 'KOMENTO_HELPER', KOMENTO_HELPERS . DIRECTORY_SEPARATOR . 'helper.php' );

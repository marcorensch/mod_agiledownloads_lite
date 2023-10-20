<?php
/**
 * @package    AgileDownloads
 *
 * @author     nx-desgins <support@nx-designs.ch>
 * @copyright  Copyright© 2021 by nx-designs
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://nx-designs.ch
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;



require_once 'helper.php';

$debug = $params->get('debug',0);

$document = Factory::getDocument();
if($params->get('load_uikit',1)){
    $document->addStyleSheet(JUri::base() . 'modules/mod_agiledownloads_lite/tmpl/assets/uikit/css/uikit.min.css');
    $document->addScript(JUri::base() . 'modules/mod_agiledownloads_lite/tmpl/assets/uikit/js/uikit.min.js');
    $document->addScript(JUri::base() . 'modules/mod_agiledownloads_lite/tmpl/assets/uikit/js/uikit-icons.min.js');
}
if($params->get('load_fa',0)) {
    $document->addStyleSheet(JUri::base() . 'modules/mod_agiledownloads_lite/tmpl/assets/fontawesome/fa-all.min.css');
}
$document->addStyleSheet(JUri::base() . 'modules/mod_agiledownloads_lite/tmpl/assets/css/main.css?ver=1.3');

// Get files from defined folder
$files = ModAgileDownloadsLiteHelper::getFolderFiles($params);

// The below line is no longer used in Joomla 4
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx',''));

// Define defaults
$loggedIn = Factory::getUser()->id;
$hasRestrictedFiles = false;

// Group title configuration
$gtt = $params->get('group-title-tag','h3');
$gtc = htmlspecialchars($params->get('group-title-class',''));

require ModuleHelper::getLayoutPath('mod_agiledownloads_lite', $params->get('design', 'default'));

if($debug) {
    echo '<div class="uk-scope agiledownloads-debug">';
        echo '<div class="uk-margin-top uk-margin-bottom uk-card uk-background-muted uk-card-small uk-card-body uk-border-rounded">';
            echo '<h4>Debug</h4>';
            echo '<ul uk-accordion>';
                echo '<li><a class="uk-accordion-title" href="#">Watched Folders</a>';
                    echo '<div class="uk-accordion-content"><pre>' . var_export($params->get('watched-folders'), 1) . '</pre></div>';
                echo '</li>';
                echo '<li><a class="uk-accordion-title" href="#">Files</a>';
                    echo '<div class="uk-accordion-content"><pre>' . var_export($files, 1) . '</pre></div>';
                echo '</li>';
            echo '</ul>';
        echo '</div>';
    echo '</div>';
}


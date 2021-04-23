<?php
/**
 * @package    TUBEFLIX Module
 *
 * @author     nx-designs | Marco Rensch <support@nx-designs.ch>
 * @copyright  Copyright© 2021 by nx-designs
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://www.nx-designs.ch
 */

use Joomla\CMS\Language\Text;

defined('JPATH_PLATFORM') or die;

/**
 * Form Field class for the Joomla Platform.
 * Provides a list to select customfields of this page
 *
 * @link   http://www.w3.org/TR/html-markup/input.text.html#input.text
 * @since  3.2
 */
class JFormFieldBackendhelper extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  3.2
     */
    protected $type = 'backendhelper';

    /**
     * Method to get the field input markup.
     *
     * @return  string  The field input markup.
     *
     * @since   3.2
     */

    public function getInput() {
        $doc = \Joomla\CMS\Factory::getDocument();
        $doc->addStyleSheet(JUri::root().'modules/mod_agiledownloads_lite/models/fields/assets/helper.css?v=1.1');
        $doc->addScript(JUri::root() . 'modules/mod_agiledownloads_lite/models/fields/assets/helper.js?v=1.1');

        $rateUs = '<div class="rate-us"><a href="https://extensions.joomla.org/write-review/review/add/?extension_id=15297" target="_blank" title="Rate agile Downloads lite on JED">Do you like this module? Support us and rate agile Downloads lite on JED!</a></div>';

        $html = '<div id="rate-us-container" class="nx-rate-us-container nx-margin nx-padding-small nx-background-colorful">'.$rateUs.'</div>';
        return $html;
    }
}

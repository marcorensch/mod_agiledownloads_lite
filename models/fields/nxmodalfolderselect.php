<?php

/**
 * @package    AgileDownloads
 *
 * @author     nx-desgins <support@nx-designs.ch>
 * @copyright  Copyright© 2021 by nx-designs
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://nx-designs.ch
 */

defined('JPATH_BASE') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * Supports a modal for selecting a helloworld record
 *
 */
class JFormFieldNxModalFolderSelect extends JFormField
{

    protected $type = 'nxmodalfolderselect';

    /**
     * Method to get the html for the input field.
     *
     * @return  string  The field input html.
     */
    protected function getInput()
    {
        // Load language
        Factory::getApplication()->getLanguage()->load('mod_agiledownloads_lite');

        // $this->value is set if there's a default id specified in the xml file
        $value = strlen($this->value) ? $this->value : '';

        // $this->id will be jform_request_xxx where xxx is the name of the field in the xml file
        $modalId = 'nxmodal_' . $this->id;

        // Add the modal field script to the document head.
        JHtml::_('jquery.framework');

        $document = Factory::getApplication()->getDocument();

        if(JVERSION < 4)
        {
            $document->addStyleSheet(JUri::root() . 'modules/mod_agiledownloads_lite/tmpl/assets/uikit/3.6.16/css/uikit.min.css');
            $document->addScript(JUri::root() . 'modules/mod_agiledownloads_lite/tmpl/assets/uikit/3.6.16/js/uikit.min.js');
            $document->addScript(JUri::root() . 'modules/mod_agiledownloads_lite/tmpl/assets/uikit/3.6.16/js/uikit-icons.min.js');

            $document->addScript(JUri::root() . 'modules/mod_agiledownloads_lite/models/fields/assets/nxd_modal_helper.js');
            $document->addScript(JUri::root() . 'modules/mod_agiledownloads_lite/models/fields/assets/nxd_modal.js');
            $document->addStyleSheet(JUri::root() . 'modules/mod_agiledownloads_lite/models/fields/assets/nxmodalfolderselect.css?ver=1.4');
        }
        else
        {
            $wa = $document->getWebAssetManager();
            $wa->registerAndUseStyle('uikitCss', JUri::root() . 'modules/mod_agiledownloads_lite/tmpl/assets/uikit/3.6.16/css/uikit.min.css');
            $wa->registerAndUseScript('uikitJs', JUri::root() . 'modules/mod_agiledownloads_lite/tmpl/assets/uikit/3.6.16/js/uikit.min.js');
            $wa->registerAndUseScript('uikitIconsJs', JUri::root() . 'modules/mod_agiledownloads_lite/tmpl/assets/uikit/3.6.16/js/uikit-icons.min.js');

            $wa->registerAndUseScript('nxModalHelper', JUri::root() . 'modules/mod_agiledownloads_lite/models/fields/assets/nxd_modal_helper.js?ver=1.3.2');
            $wa->registerAndUseScript('nxModalJs', JUri::root() . 'modules/mod_agiledownloads_lite/models/fields/assets/nxd_modal.js?ver=1.3.2');
            $wa->registerAndUseScript('nxBackend4Js', JUri::root() . 'modules/mod_agiledownloads_lite/models/fields/assets/backend_j4.js');
            $wa->registerAndUseStyle('nxmodalFolderselectCss', JUri::root() . 'modules/mod_agiledownloads_lite/models/fields/assets/nxmodalfolderselect.css');
            $wa->registerAndUseStyle('nxmodalFolderselectCssJ4', JUri::root() . 'modules/mod_agiledownloads_lite/models/fields/assets/nxmodalfolderselect_j4.css');
        }

        // display the default greeting or "Select" if no default specified
        $title = empty($value) ? '' : htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        $html = '<div class="input-append">';
        $html .= '<input class="form-control input-large folder-input-field" data-target="' . $this->id . '" id="' . $this->id . '_name" type="text" value="' . $title . '" size="35" placeholder="' . JText::_('MOD_NXAD_SELECTDIR_TXT') . '" style="display:inline; width:auto;" />';

        // html for the Select button
        $html .= '<button'
            . ' class="btn hasTooltip open-folderselect-modal"'
            . ' id="' . $this->id . '_select"'
            . ' data-for="' . $this->id . '"'
            . ' role="button"'
            . ' href="#' . $modalId . '"'
            . ' title="' . JHtml::tooltipText('MOD_NXAD_SELECTDIR_TXT') . '">'
            . '<span class="icon-folder-2" aria-hidden="true"></span> ' . JText::_('JSELECT')
            . '</button>';
        $html .= '</div>';


        $modalBody = '<div class="nx-modal nx-padding">
                        <div class="folder-container"></div>
                        </div>';

        // html to set up the modal iframe
        $html .= '
                <div id="' . $modalId . '" class="uk-flex-top nx-modal-container" data-for="' . $this->id . '" uk-modal>
                    <div class="uk-modal-dialog uk-margin-auto-vertical">
                        <div class="uk-modal-header">
                            <h2 class="uk-modal-title">' . JText::_('MOD_NXAD_SELECTDIR_TXT') . '</h2>
                        </div>
                        <div class="uk-modal-body uk-padding-remove-horizontal uk-padding-remove-vertical">
                            ' . $modalBody . '
                        </div>
                        <div class="uk-modal-footer">
                        	<div class="selected-path-info uk-text-meta uk-width-1-1 uk-text-truncate"><span>Path:</span><span class="selected-path uk-margin-small-left"></span></div>
                        	<div class="uk-margin-small-top">
                            	<div class="uk-grid uk-grid-small uk-child-width-1-1 uk-child-width-auto@m uk-flex-right@m" uk-margin>
                            		<div>
                            			<button class="uk-width-1-1 uk-modal-close uk-button uk-button-default" type="button">Cancel</button>
									</div>
                            		<div>
                            			<button class="uk-width-1-1 uk-modal-close uk-button uk-button-success nx-button-success set-folder" style="display:none" type="button">' . Text::_('MOD_NXAD_SELECTFOLDER_TXT') . '</button>
                                    </div>
                            	</div>
                            </div>
                        </div>
                    </div>
                </div>';

        // class='required' for client side validation.
        $class = $this->required ? ' class="required modal-value watched-folder-value"' : ' class="watched-folder-value"';

        // hidden input field to store the record id
        $html .= '<input type="hidden" id="' . $this->id . '_id" ' . $class
            . ' data-required="' . (int)$this->required . '" name="' . $this->name
            . '" data-text="' . htmlspecialchars(JText::_('MOD_NXAD_SELECTDIR_TXT', true), ENT_COMPAT, 'UTF-8')
            . '" value="' . $value . '" />';

        return $html;
    }

    /**
     * Method to get the html for the label field.
     *
     * @return  string  The field label html.
     */
    protected function getLabel()
    {
        return str_replace($this->id, $this->id . '_id', parent::getLabel());
    }
}

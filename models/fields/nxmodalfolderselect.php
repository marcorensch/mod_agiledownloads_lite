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
        Factory::getLanguage()->load('mod_agiledownloads');

        // $this->value is set if there's a default id specified in the xml file
        $value = strlen($this->value) ? $this->value : '';

        // $this->id will be jform_request_xxx where xxx is the name of the field in the xml file
        $modalId = 'agiledownloads_' . $this->id;

        // Add the modal field script to the document head.
        JHtml::_('jquery.framework');
        //JHtml::_('script', 'system/modal-fields.js', array('version' => 'auto', 'relative' => true));

        $document = Factory::getDocument();
        $document->addStyleSheet(JUri::root() . 'modules/mod_agiledownloads_lite/tmpl/assets/uikit/3.6.16/css/uikit.min.css');
        $document->addScript(JUri::root() . 'modules/mod_agiledownloads_lite/tmpl/assets/uikit/3.6.16/js/uikit.min.js');
        $document->addScript(JUri::root() . 'modules/mod_agiledownloads_lite/tmpl/assets/uikit/3.6.16/js/uikit-icons.min.js');

        $document->addScript(JUri::root() . 'modules/mod_agiledownloads_lite/models/fields/assets/nxmodalfolderselect.js');
        $document->addStyleSheet(JUri::root() . 'modules/mod_agiledownloads_lite/models/fields/assets/nxmodalfolderselect.css?ver=1.3');

        // display the default greeting or "Select" if no default specified
        $title = empty($value) ? '' : htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        $html = '<span class="input-append">';
        $html .= '<input class="input-large folder-input-field" data-target="' . $this->id . '" id="' . $this->id . '_name" type="text" value="' . $title . '" size="35" placeholder="' . JText::_('MOD_NXAD_SELECTDIR_TXT') . '" />';

        // html for the Select button
        $html .= '<a'
            . ' class="btn hasTooltip open-folderselect-modal"'
            . ' uk-toggle="target: #ModalSelectagiledownloads_' . $this->id . '_modal"'
            . ' id="' . $this->id . '_select"'
            . ' data-for="' . $this->id . '"'
            . ' data-toggle="modal"'
            . ' role="button"'
            . ' href="#ModalSelect' . $modalId . '"'
            . ' title="' . JHtml::tooltipText('MOD_NXAD_SELECTDIR_TT') . '">'
            . '<span class="icon-folder-2" aria-hidden="true"></span> ' . JText::_('JSELECT')
            . '</a>';

        // html for the Clear button
        /* NOT USED
        $html .= '<a'
            . ' class="btn' . ($value ? '' : ' hidden') . '"'
            . ' id="' . $this->id . '_clear"'
            . ' href="#"'
            . ' onclick="window.processModalParent(\'' . $this->id . '\'); return false;">'
            . '<span class="icon-remove" aria-hidden="true"></span>' . JText::_('JCLEAR')
            . '</a>';
        */
        $html .= '</span>';

        // title to go in the modal header
        $modalTitle = JText::_('MOD_NXAD_SELECTDIR_TXT');


        $modalBody = '<div class="nx-modal nx-padding">
                        <div class="loadFoldersBtn nx-button nx-button-default" style="display: none">Load Filesystem</div>
                        <div class="folder-container"></div>
                        </div>';

        // html to set up the modal iframe
        $html .= '
                <div id="ModalSelectagiledownloads_' . $this->id . '_modal" class="nx-modal-container" data-for="' . $this->id . '" uk-modal>
                    <div class="uk-modal-dialog">
                        <div class="uk-modal-header">
                            <h2 class="uk-modal-title">'.JText::_('MOD_NXAD_SELECTDIR_TXT').'</h2>
                        </div>
                        <div class="uk-modal-body uk-padding-remove-horizontal uk-padding-remove-vertical">
                            '.$modalBody.'
                        </div>
                        <div class="uk-modal-footer uk-flex uk-flex-right">
                            <div>
                            <button class="uk-modal-close uk-button uk-button-default" type="button">Cancel</button>
                            <button class="uk-modal-close uk-button uk-button-success nx-button-success set-folder" style="display:none" type="button">'.JText::_('MOD_NXAD_SELECTFOLDER_TXT').'</button>
                            </div>
                        </div>
                    </div>
                </div>';

        // class='required' for client side validation.
        $class = $this->required ? ' class="required modal-value watched-folder-value"' : ' class="watched-folder-value"';

        // hidden input field to store the helloworld record id
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

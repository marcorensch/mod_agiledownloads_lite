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

$contentSize = 'uk-text-'.$params->get('content-size','default');
?>

<li class="nx-list-item">
    <div class="<?php echo $contentSize;?> nx-list-item-content">
        <div class="uk-flex uk-flex-middle uk-grid-small">
            <div class="uk-width-expand nx-list-item-content-details">
                <div class="uk-position-relative uk-margin-left">
                    <div class="uk-flex uk-flex-middle uk-grid-small">
                        <?php if($file->iconCls):?><div class=" list-item-icon"><i class="fas fa-file-<?php echo $file->iconCls;?>"></i></div><?php endif;?>
                        <div class="uk-width-expand"><span class="list-item-label"><?php echo $file->label;?></span></div>
                    </div>
                </div>
            </div>
            <?php if($params->get('show-filetype-label',1)):?>
            <div><div class="uk-label nx-filetype-label"><?php echo $file->ext;?></div></div>
            <?php endif;?>
        </div>
        <a class="uk-margin-small-left uk-position-cover uk-flex uk-flex-middle" target="_self" href="<?php echo $file->url;?>" title="Download <?php echo $file->name;?>" download>

        </a>
    </div>
</li>


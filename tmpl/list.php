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
use Joomla\CMS\Factory;

$ul = '<ul class="uk-list uk-list-'.$params->get('list-style','default').' uk-list-'.$params->get('list-size','default').'">';
?>

<div class="nx-extensions uk-scope agiledownloads nx-list-layout <?php echo htmlspecialchars($params->get('moduleclass_sfx',''));?>">
        <?php
            if($params->get('group-watched',1)):
                foreach($files as $group):
                    $hasRestrictedFiles = false;
                    if($group->isRestricted && $loggedIn || !$group->isRestricted):
                        if(strlen($group->title)) echo '<'.$gtt.' class="'.$gtc.'">'.$group->title.'</'.$gtt.'>';
                        echo $ul;
                            foreach($group->processed as $file):
                                include 'list-item.php';
                            endforeach;
                        echo '</ul>';
                    else:
                        $hasRestrictedFiles = true;
                    endif;
                endforeach;
            else:
                echo $ul;
                    foreach($files['merged'] as $file):
                        if($file->isRestricted && $loggedIn || !$file->isRestricted):
                            include 'list-item.php';
                        endif;
                    endforeach;
                echo '</ul>';
            endif;
        ?>
</div>

<?php

?>



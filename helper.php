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

use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Factory;



class ModAgileDownloadsHelper {

    private static function removePath($string){
        $pathArray = explode('/',$string);
        $name = $pathArray[count($pathArray)-1];
        return $name;
    }
    private static function defineIcon($ext){
        $classname = '';
        if(strlen($ext)){
            switch(strtolower($ext)){
                case 'pdf':
                    $classname = 'fas fa-file-'.$ext;
                    break;
                case 'zip':
                case '7zip':
                case 'rar':
                case 'tar':
                    $classname = 'fas fa-file-archive';
                    break;
                case 'iso':
                    $classname = 'fas fa-file-compact-disc';
                    break;
                case 'doc':
                case 'docx':
                    $classname = 'fas fa-file-word';
                    break;
                case 'xls':
                case 'xlsx':
                    $classname = 'fas fa-file-excel';
                    break;
                case 'pptm':
                case 'pptx':
                case 'ppt':
                    $classname = 'fas fa-file-powerpoint';
                    break;
                case 'mp4':
                case 'ogv':
                case 'mpeg':
                case 'wmv':
                case 'avi':
                case 'mov':
                    $classname = 'fas fa-file-video';
                    break;
                case 'mp3':
                case 'ogg':
                case 'wav':
                case 'aac':
                case 'aiff':
                case 'flac':
                case 'alac':
                case 'pcm':
                    $classname = 'fas fa-file-audio';
                    break;
                case 'jpg':
                case 'jpeg':
                case 'bmp':
                case 'png':
                case 'gif':
                    $classname = 'fas fa-file-image';
                    break;
                case 'html':
                case 'htm':
                case 'php':
                case 'js':
                case 'vue':
                case 'xml':
                case 'md':
                    $classname = 'fas fa-file-code';
                    break;
                case 'svg':
                    $classname = 'fas fa-bezier-curve';
                    break;
                case 'csv':
                    $classname = 'fas fa-file-csv';
                    break;
                default:
                    $classname = 'fas fa-file';
            }

            return $classname;
        }else{
            return false;
        }
    }

    public static function customRulesLight($params, $string){
        // This function is the preflight to manipulate the string if setted up
        // It appends strings in before or after the string (example: 21 --> #21)
        // @params  $string String  String with the fieldvalue
        // @params  $target String  Determine the target / source of this string / where this string should be used later
        // @params  $rules Array    Array of objects for each rule {'customfield_for_rule': 'name-of-field', 'rule_string_to_find':'string', 'rule_string_replace_with':'Type: String|Linebreak', 'rule_string_to_replace':'orig_stringpart' 'rule_type': ['before', 'after'], 'value': '#'}
        // @return  string          #new String

        $rules = $params->get('label-overrides',array());

        if(!$rules){
            return $string;
        }
        $modified = $string;

        // Get through all rules one by one
        foreach ($rules as $rule)
        {
            switch ($rule->rule_type){
                case 'replace':
                    switch ($rule->rule_string_replace_with){

                        case 'string':
                            $modified = str_replace($rule->rule_string_to_find, $rule->rule_string_to_replace, $modified);
                            break;
                        case 'nbspace':
                            $modified = str_replace($rule->rule_string_to_find, '&nbsp;', $modified);
                            break;
                        case 'space':
                            $modified = str_replace($rule->rule_string_to_find, ' ', $modified);
                            break;
                        case 'break':
                            $modified = str_replace($rule->rule_string_to_find, '<br>', $modified);
                            break;
                        default:
                    };
                    break;
                default:
            }
        }

        return trim($modified);
    }

    private static function buildFileManifest($file, $restricted, $params){
        $item = new stdClass();
        $item->url = JURI::base() . $file;
        $item->relpath = $file;
        $item->relfolder = pathinfo($file,PATHINFO_DIRNAME);
        $item->isRestricted = $restricted;
        $item->name = $params->get('show-ext',0) ? self::removePath($file) : self::removePath(File::stripExt($file));
        $item->ext = File::getExt($file);
        $item->iconCls = $params->get('show-icon',1) ? self::defineIcon($item->ext) : '';
        // Pro feature str Replacer here
        $item->label = self::customRulesLight($params, $item->name);

        return $item;
    }

    public static function getFolderFiles($params){
        // Filename exclude
        if(strlen($params->get('ignore',''))){
            $exclude = explode(',', preg_replace('/\s+/', '', $params->get('ignore','')));
        }else{
            $exclude = array();
        }
        $exclude = array_merge($exclude, array('.svn', 'CVS', '.DS_Store', '__MACOSX'));

        // File extension filter
        if(strlen($params->get('filetype_filter',''))){
            $filter = explode(',',preg_replace('/\s+/', '', $params->get('filetype_filter','')));
        }else{
            $filter = array();
        }
        $filter = array_merge($filter,array('^\..*', '.*~'));

        $filesArray = array();

        $folder = $params->get('watched-folders',array());

        $foldersArr = array();

        $path = $folder->watched_folder;

        $group = new stdClass();
        $group->title = $folder->grouping_title;
        $group->path = $path;
        $group->isRestricted = file_exists(JPATH_ROOT . '/' . $path .'/.htaccess');
        $group->files = array();
        if(Folder::exists($path)){
            $group->files = Folder::files($path, '.', false, true , $exclude, $filter);
        }
        $foldersArr[] = $group;


        $orderBy = $params->get('ordering-by','name');

        if($foldersArr){
            // Add merged array if group-watched is disabled
            if(!$params->get('group-watched',1)) {
                $filesArray['merged'] = array();
            }
            foreach ($foldersArr as $group){
                $group->processed = array();
                foreach ($group->files as &$file){
                    $group->processed[] = self::buildFileManifest($file, $group->isRestricted, $params);
                }

                if(!$params->get('group-watched',1)){
                    $filesArray['merged'] = array_merge($filesArray['merged'], $group->processed);
                }else{
                    // Do the sort for each group before adding them to the array (grouped):
                    if (in_array($params->get('ordering', 'asc'), array('asc', 'desc'))) {
                        if ($params->get('ordering', 'asc') == 'asc') {
                            usort($group->processed, function ($a, $b) use ($orderBy) {
                                return strcmp(strtolower($a->$orderBy), strtolower($b->$orderBy));
                            });
                        } else {
                            usort($group->processed, function ($b, $a) use ($orderBy) {
                                return strcmp(strtolower($a->$orderBy), strtolower($b->$orderBy));
                            });
                        }
                    }
                    $filesArray[] = $group;
                }

            }
            // do a sort after all files are inside the array (when not grouped):
            if(!$params->get('group-watched',1)) {
                if (in_array($params->get('ordering', 'asc'), array('asc', 'desc'))) {
                    if ($params->get('ordering', 'asc') == 'asc') {
                        usort($filesArray['merged'], function ($a, $b) use ($orderBy) {
                            return strcmp(strtolower($a->$orderBy), strtolower($b->$orderBy));
                        });
                    } else {
                        usort($filesArray['merged'], function ($b, $a) use ($orderBy) {
                            return strcmp(strtolower($a->$orderBy), strtolower($b->$orderBy));
                        });
                    }
                }
            }else{
                // moved inside loop row 307
            }
        }

        return $filesArray;
    }
}

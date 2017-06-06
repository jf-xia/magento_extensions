<?php
/**
 * Magpleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE-CE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE-CE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Magpleasure does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Magpleasure does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   Magpleasure
 * @package    Magpleasure_Guestbook
 * @version    1.1
 * @copyright  Copyright (c) 2012-2013 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */

class Magpleasure_Guestbook_Helper_Comment_Render extends Mage_Core_Helper_Data
{

    public function render($content, $limit = false)
    {
        if ($limit){
            if (strlen($content) > $limit){
                $content = substr($content, 0, $limit);
                if (strpos($content, " ") !== false){
                    $cuts = explode(" ", $content);
                    if (count($cuts) && count($cuts) > 1){
                        unset($cuts[count($cuts) - 1]);
                        $content = implode(" ", $cuts)."...";
                    }
                }
            }
        }

        preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $content, $match);
        foreach ($match[0] as $url){
            $content = str_replace($url, "<a href=\"{$url}\" target=\"_blank\">{$url}</a>", $content);
        }
        return nl2br($content);
    }



}
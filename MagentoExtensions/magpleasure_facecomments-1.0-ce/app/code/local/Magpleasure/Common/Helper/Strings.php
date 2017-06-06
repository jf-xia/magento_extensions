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
 * @package    Magpleasure_Common
 * @version    0.6.11
 * @copyright  Copyright (c) 2012-2013 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */
class Magpleasure_Common_Helper_Strings extends Mage_Core_Helper_Abstract
{
    protected $_defaultEncoding = "UTF-8";

    /**
     * Common Helper
     *
     * @return Magpleasure_Common_Helper_Data
     */
    protected function _commonHelper()
    {
        return Mage::helper('magpleasure');
    }

    protected function _cutBadSuffix($content)
    {
        $contentPieces = explode(" ", $content);
        if (count($contentPieces) > 1){
            unset($contentPieces[count($contentPieces) - 1]);
        }
        $content = implode(" ", $contentPieces);
        return $content;
    }

    public function strtoupper($value)
    {
        return function_exists("mb_strtoupper") ? mb_strtoupper($value, $this->_defaultEncoding) : strtoupper($value);
    }

    public function strtolower($value)
    {
        return function_exists("mb_strtolower") ? mb_strtolower($value, $this->_defaultEncoding) : strtolower($value);
    }

    public function strlen($value)
    {
        return function_exists("mb_strlen") ? mb_strlen($value, $this->_defaultEncoding) : strlen($value);
    }

    public function substr($string, $start, $length = null)
    {
        return function_exists("mb_substr") ? mb_substr($string, $start, $length, $this->_defaultEncoding) : substr($string, $start, $length);
    }

    public function ereg_replace($pattern, $replacement, $string)
    {
        return function_exists("mb_ereg_replace") ?
            mb_ereg_replace($pattern, $replacement, $string, $this->_defaultEncoding) :
            preg_replace("/".$pattern."/", $replacement, $string);
    }

    /**
     * Extract keywords from text
     *
     * @param string $text
     * @param int $limit
     * @return array
     */
    public function getKeywords($text, $limit = 5)
    {
        Varien_Profiler::start("mp::common::strings::get_keywords");

        /** @var $words Magpleasure_Common_Model_Type_Dictionary_Keywords */
        $keywords = Mage::getSingleton('magpleasure/type_dictionary_keywords');
        $text = $this->htmlToText($text);
        $resultArray = $keywords->extractKeywords($text, 3);

        Varien_Profiler::stop("mp::common::strings::get_keywords");

        return $resultArray;
    }

    /**
     * Cut long text
     *
     * @param $content
     * @param $limit
     * @param bool $htmlToText
     * @return string
     */
    public function strLimit($content, $limit, $htmlToText = true)
    {
        if ($htmlToText){
            $content = $this->htmlToText($content);
        }

        if (function_exists('mb_strlen')){
            if (mb_strlen($content, 'UTF-8') > $limit){
                $content = $this->_cutBadSuffix(mb_substr($content, 0, $limit - 1, 'UTF-8'));
            }
        } else {
            if (strlen($content) > $limit){
                $content = $this->_cutBadSuffix(substr($content, 0, $limit - 1));
            }
        }
        return $content;
    }

    /**
     * HTML to text
     *
     * @param string $content
     * @return string
     */
    public function htmlToText($content)
    {
        return $this->stripTags($content);
    }

    /**
     * HTML to text without new lines
     *
     * @param string $content
     * @return string
     */
    public function htmlToPlainText($content)
    {
        $content = $this->htmlToText($content);
        $content = str_replace(array("\n","\r"), ' ', $content);
        return $content;
    }

    /**
     * Wrapper for standard strip_tags() function with extra functionality for html entities
     *
     * @param string $data
     * @param string $allowableTags
     * @param bool $escape
     * @return string
     */
    public function stripTags($data, $allowableTags = null, $escape = false)
    {
        $result = strip_tags($data, $allowableTags);
        return $escape ? $this->escapeHtml($result, $allowableTags) : $result;
    }

    /**
     * Escape html entities
     *
     * @param   mixed $data
     * @param   array $allowedTags
     * @return  mixed
     */
    public function escapeHtml($data, $allowedTags = null)
    {
        if (is_array($data)) {
            $result = array();
            foreach ($data as $item) {
                $result[] = $this->escapeHtml($item);
            }
        } else {
            // process single item
            if (strlen($data)) {
                if (is_array($allowedTags) and !empty($allowedTags)) {
                    $allowed = implode('|', $allowedTags);
                    $result = preg_replace('/<([\/\s\r\n]*)(' . $allowed . ')([\/\s\r\n]*)>/si', '##$1$2$3##', $data);
                    $result = htmlspecialchars($result, ENT_COMPAT, 'UTF-8', false);
                    $result = preg_replace('/##([\/\s\r\n]*)(' . $allowed . ')([\/\s\r\n]*)##/si', '<$1$2$3>', $result);
                } else {
                    $result = htmlspecialchars($data, ENT_COMPAT, 'UTF-8', false);
                }
            } else {
                $result = $data;
            }
        }
        return $result;
    }


    /**
     * Cut Line into pieces
     *
     * @param $line
     * @param $limit
     * @return array
     */
    public function cutToPieces($line, $limit)
    {
        $lines = array();
        $strLen = $this->strlen($line);
        $count = 0;
        if ($strLen > $limit){
            for ($i = 0; ($count * $limit) < $strLen; $i += $limit){
                $lines[] = $this->substr($line, $i, $limit);
                $count++;
            }

            if ($count * $limit != $strLen){
                $i += $limit;
                $lines[] = $this->substr($line, $i, ($strLen - ($limit * $count)));
            }
        } else {
            $lines[] = $line;
        }
        return $lines;
    }

    /**
     * Escape html entities in url
     *
     * @param string $data
     * @return string
     */
    public function escapeUrl($data)
    {
        return htmlspecialchars($data);
    }

    /**
     * Generate Slug
     *
     * @param string $title
     * @return string
     */
    public function generateSlug($title)
    {
        $title = urldecode($title);
        $title = $this->_commonHelper()->getTransliteration()->transliterate($title);
        $title = strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/','/[ -]+/','/^-|-$/'),array('','-',''),$title));

        return $title;
    }
}
<?php

/**
 * Lifestyle Model Favorite
 *
 * @category	Brisign
 * @package		Brisign_Lifestyle
 * @author		Drew Hunter <drewdhunter@gmail.com>
 * @version		0.1.0
 */
class Brisign_Lifestyle_Model_Favorite extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('lifestyle/favorite');
    }
}
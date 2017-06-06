<?php
/**
 * @category    Mana
 * @package     ManaPro_FilterColors
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
/* BASED ON SNIPPET: New Module/Helper/Data.php */
/**
 * Generic helper functions for ManaPro_FilterColors module. This class is a must for any module even if empty.
 * @author Mana Team
 */
class ManaPro_FilterColors_Helper_Data extends Mage_Core_Helper_Abstract {
	public function getCssRelativeUrl($filterOptions) {
	    return 'm-filter-'.$filterOptions->getStoreId().'-'.$filterOptions->getGlobalId().'.css';
	}
    public function getFilterClass($filterOptions) {
        return 'mf-'.$filterOptions->getStoreId().'-'.$filterOptions->getGlobalId();
    }
    public function getFilterValueClass($filterOptions, $optionId) {
        return 'mfv-'.$optionId;
    }
    protected function _renderBackgrounds() {
        $backgrounds = func_get_args();
        /* @var $files Mana_Core_Helper_Files */ $files = Mage::helper(strtolower('Mana_Core/Files'));
        $result = '';
        foreach ($backgrounds as $background) {
            if ($background) {
                if ($result) {
                    $result .= ", \n        ";
                }
                $result .= 'url('.$files->getUrl($background, 'image').')';
            }
        }
        return $result;
    }
    public function generateCss($filterOptions) {
        /* @var $files Mana_Core_Helper_Files */ $files = Mage::helper(strtolower('Mana_Core/Files'));
        $values = Mage::getResourceModel('mana_filters/filter2_value_store_collection');
        $values->addFieldToFilter('filter_id', $filterOptions->getId())->setEditFilter(true);
        ob_start();
?>
<?php foreach ($values as $value) : ?>
.<?php echo $this->getFilterValueClass($filterOptions, $value->getOptionId())?> {
    width: <?php echo $filterOptions->getImageWidth() ?>px;
    height: <?php echo $filterOptions->getImageHeight() ?>px;
    -webkit-border-radius: <?php echo $filterOptions->getImageBorderRadius() ?>px;
    -moz-border-radius: <?php echo $filterOptions->getImageBorderRadius() ?>px;
    border-radius: <?php echo $filterOptions->getImageBorderRadius() ?>px;
<?php if ($color = $value->getColor()) : ?>
    background-color: <?php echo $color ?>;
<?php endif; ?>
<?php if ($image = $this->_renderBackgrounds($filterOptions->getImageNormal(), $value->getNormalImage())) : ?>
    background-image: <?php echo $image ?>;
<?php endif; ?>
}
<?php if ($image = $this->_renderBackgrounds($filterOptions->getImageNormalHovered(), $value->getNormalHoveredImage())) : ?>
.<?php echo $this->getFilterValueClass($filterOptions, $value->getOptionId())?>.hovered {
    background-image: <?php echo $image ?>;
}
<?php endif; ?>
<?php if ($image = $this->_renderBackgrounds($filterOptions->getImageSelected(), $value->getSelectedImage())) : ?>
/* .<?php echo $this->getFilterClass($filterOptions)?> */
.<?php echo $this->getFilterValueClass($filterOptions, $value->getOptionId())?>.selected {
    background-image: <?php echo $image ?>;
}
<?php endif; ?>
<?php if ($image = $this->_renderBackgrounds($filterOptions->getImageSelectedHovered(), $value->getSelectedHoveredImage())) : ?>
.<?php echo $this->getFilterValueClass($filterOptions, $value->getOptionId())?>.selected.hovered {
    background-image: <?php echo $image ?>;
}
<?php endif; ?>
.<?php echo $this->getFilterValueClass($filterOptions, $value->getOptionId())?>-state {
    width: <?php echo $filterOptions->getStateWidth() ?>px;
    height: <?php echo $filterOptions->getStateHeight() ?>px;
    -webkit-border-radius: <?php echo $filterOptions->getStateBorderRadius() ?>px;
    -moz-border-radius: <?php echo $filterOptions->getStateBorderRadius() ?>px;
    border-radius: <?php echo $filterOptions->getStateBorderRadius() ?>px;
<?php if ($color = $value->getColor()) : ?>
    background-color: <?php echo $color ?>;
<?php endif; ?>
<?php if ($image = $this->_renderBackgrounds($filterOptions->getStateImage(), $value->getStateImage())) : ?>
    background-image: <?php echo $image ?>;
<?php endif; ?>
}
<?php endforeach; ?>
<?php
        $css = ob_get_clean();
        $filename = $files->getFilename($this->getCssRelativeUrl($filterOptions), 'css', true);
        $fh = fopen($filename, 'w');
        fwrite($fh, $css);
        fclose($fh);
    }
}
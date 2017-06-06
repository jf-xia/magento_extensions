<?php
/**
 * Adminhtml base helper
 *
 * @category   Santhosh
 * @package    Santhosh_Core
 * @author     santhosh kumar <santhoshnsscoe@gmail.com>
 */
class Santhosh_Core_Helper_Category extends Mage_Core_Helper_Abstract
{

	public function getAttributes() {
		$data = array();
		$data[] = array ('field' => 'name', 'label' => 'Name','required' => 'yes');
		$data[] = array ('field' => 'path', 'label' => 'Path', 'function' => 'getFullPath','required' => 'yes' );
		$data[] = array ('field' => 'position', 'label' => 'Position');
		$data[] = array ('field' => 'is_active', 'label' => 'Is Active', 'function' => 'getYesNo');
		$data[] = array ('field' => 'url_key', 'label' => 'Url Key');
		$data[] = array ('field' => 'description', 'label' => 'Description');
		$data[] = array ('field' => 'image', 'label' => 'Image');
		$data[] = array ('field' => 'meta_title', 'label' => 'Page Title');
		$data[] = array ('field' => 'meta_keywords', 'label' => 'Meta Keywords');
		$data[] = array ('field' => 'meta_description', 'label' => 'Meta Description');
		$data[] = array ('field' => 'include_in_menu', 'label' => 'Include In Menu', 'function' => 'getYesNo');
		$data[] = array ('field' => 'display_mode', 'label' => 'Display Mode', 'function' => 'getDisplayMode');
		$data[] = array ('field' => 'landing_page', 'label' => 'CMS Block', 'function' => 'getStaticBlock');
		$data[] = array ('field' => 'is_anchor', 'label' => 'Is Anchor', 'function' => 'getYesNo');
		$data[] = array ('field' => 'available_sort_by', 'label' => 'Availabe Sort By', 'function' => 'getProductSortBy');
		$data[] = array ('field' => 'default_sort_by', 'label' => 'Default Sort By', 'function' => 'getProductSortBy');
		//$data[] = array ('field' => 'custom_design', 'label' => 'Custom Design');
		//$data[] = array ('field' => 'custom_design_apply', 'label' => 'Apply To');
		//$data[] = array ('field' => 'custom_design_from', 'label' => 'Active From');
		//$data[] = array ('field' => 'custom_design_to', 'label' => 'Active To');
		$data[] = array ('field' => 'page_layout', 'label' => 'Page Layout', 'function' => 'getPageLayout');
		$data[] = array ('field' => 'custom_layout_update', 'label' => 'Custom Layout Update');
		return $data;
	}
}
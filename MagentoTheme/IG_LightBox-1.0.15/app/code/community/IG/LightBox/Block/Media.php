<?php
/**
 * IDEALIAGroup srl
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@idealiagroup.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category   IG
 * @package    IG_LightBox
 * @copyright  Copyright (c) 2010-2011 IDEALIAGroup srl (http://www.idealiagroup.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Riccardo Tempesta <tempesta@idealiagroup.com>
*/
 
class IG_LightBox_Block_Media extends Mage_Catalog_Block_Product_View_Media
{
	protected $_igLightBoxConfigPath = "ig_lightbox";
	
	public function getIGLightBoxConfig($key)
	{
		return Mage::getStoreConfig($this->_igLightBoxConfigPath.'/'.$key);
	}
	
	public function getIgLightBoxMainImageSize()
	{
		list($main_width, $main_height) = explode('x', $this->getIGLightBoxConfig('general/mainImageSize'));
		
		$main_width = intval($main_width) > 0 ? intval($main_width) : 800;
		$main_height = intval($main_height) > 0 ? intval($main_height) : 600;
		
		return array($main_width, $main_height);
	}
	
	public function getIgLightBoxThumbnailImageSize()
	{
		list($thu_width, $thu_height) = explode('x', $this->getIGLightBoxConfig('general/thumbnailImageSize'));
		
		$thu_width = intval($thu_width) > 0 ? intval($thu_width) : 256;
		$thu_height = intval($thu_height) > 0 ? intval($thu_height) : 256;
		
		return array($thu_width, $thu_height);
	}
	
	public function getIgLightBoxBigImageSize()
	{
		list($big_width, $big_height) = explode('x', $this->getIGLightBoxConfig('general/bigImageSize'));
		
		$big_width = intval($big_width) > 0 ? intval($big_width) : 60;
		$big_height = intval($big_height) > 0 ? intval($big_height) : 60;
		
		return array($big_width, $big_height);
	}
	
	public function getIgLightBoxJsConfig()
	{
		$out = '';
		
		/* Read configuration */
		$background_opactiy = floatval($this->getIGLightBoxConfig('display/backgroundOpacity'));
		$imagebox_opactiy = floatval($this->getIGLightBoxConfig('display/imageboxOpacity'));
		$toolbar_opactiy = floatval($this->getIGLightBoxConfig('display/toolbarOpacity'));
		
		$background_color = $this->getIGLightBoxConfig('display/backgroundColor');
		$imagebox_color = $this->getIGLightBoxConfig('display/imageboxColor');
		$toolbar_color = $this->getIGLightBoxConfig('display/toolbarColor');
		$toolbar_text_color = $this->getIGLightBoxConfig('display/toolbarTextColor');
		$toolbar_text_font = $this->getIGLightBoxConfig('display/toolbarTextFont');
		$toolbar_text_size = intval($this->getIGLightBoxConfig('display/toolbarTextSize'));
		
		$border_color = $this->getIGLightBoxConfig('display/borderColor');
		$border_size = intval($this->getIGLightBoxConfig('display/borderSize'));
		
		$fade_in_duration = floatval($this->getIGLightBoxConfig('effects/fadeIn'));
		$fade_out_duration = floatval($this->getIGLightBoxConfig('effects/fadeOut'));
		$image_swap_duration = floatval($this->getIGLightBoxConfig('effects/imageSwap'));
		$image_resize_duration = floatval($this->getIGLightBoxConfig('effects/imageResize'));
		
		/* Default values and ranges */	
		$background_color = $background_color ? $background_color : '#000000';
		$imagebox_color = $imagebox_color ? $imagebox_color : '#000000';
		$toolbar_color = $toolbar_color ? $toolbar_color : '#000000';
		$toolbar_text_color = $toolbar_text_color ? $toolbar_text_color : '#000000';
		$toolbar_text_font = $toolbar_text_font ? $toolbar_text_font : 'Verdana';
		$border_color = $border_color ? $border_color : '#000000';
		
		$toolbar_text_size = intval($toolbar_text_size) > 0 ? intval($toolbar_text_size) : 10;
		$border_size = intval($border_size) >= 0 ? intval($border_size) : 0;
		
		$background_opactiy = min(1.0, max(0.0, $background_opactiy));
		$imagebox_opactiy = min(1.0, max(0.0, $imagebox_opactiy));
		$toolbar_opactiy = min(1.0, max(0.0, $toolbar_opactiy));
		
		$values = array(
			"ig_lightbox_background_opactiy"	=> $background_opactiy,
			"ig_lightbox_imagebox_opactiy"		=> $imagebox_opactiy,
			"ig_lightbox_toolbar_opactiy"		=> $toolbar_opactiy,
			"ig_lightbox_background_color"		=> $background_color,
			"ig_lightbox_imagebox_color"		=> $imagebox_color,
			"ig_lightbox_toolbar_color"			=> $toolbar_color,
			"ig_lightbox_toolbar_text_color"	=> $toolbar_text_color,
			"ig_lightbox_toolbar_text_font"		=> $toolbar_text_font,
			"ig_lightbox_toolbar_text_size"		=> $toolbar_text_size,
			"ig_lightbox_border_size"			=> $border_size,
			"ig_lightbox_border_color"			=> $border_color,
			"ig_lightbox_fade_in_duration"		=> $fade_in_duration,
			"ig_lightbox_fade_out_duration"		=> $fade_out_duration,
			"ig_lightbox_image_swap_duration"	=> $image_swap_duration,
			"ig_lightbox_image_resize_duration"	=> $image_resize_duration,
			"ig_lightbox_wrap_images"			=> $this->getIGLightBoxConfig('general/wrapImages')
		);
		
		$out.="<script type=\"text/javascript\">\n";
		foreach ($values as $k => $v)
			$out.="var $k='$v'\n";	
		$out.="</script>\n";
		
		return $out;
	}
}

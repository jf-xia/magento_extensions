<?php
class EM_Megamenupro_Block_Megamenupro extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
    public function getMegamenupro()
    {
        $id     = $this->getData('menu');
		$model  = Mage::getModel('megamenupro/megamenupro')->load($id)->getData();

		// check menu is disabled
		if ($model['status']==0)
			return array('type' => 1, 'content' => ''); 
		
		$data['type']		=	$model['type'];
		$content	=	unserialize($model['content']);
		$helper = Mage::helper('cms');
		$processor = $helper->getPageTemplateProcessor();
		if(is_array($content)){
			foreach($content as $k=>$v){
				if($v['type']	== 'text'){
					$tmp	=	"";
					$tmp	=	base64_decode($v['text']);
					$content[$k]['text'] = $processor->filter($tmp);
				}
			}
		}
		$data['content']	=	$content;
		return $data;
    }
	
	public function _toHtml(){
		$this->setTemplate('em_megamenupro/showmenu.phtml');
		return parent::_toHtml();
	}
	
	public function close_tags(&$close_tags, $item_depth) {
		ksort($close_tags);
		$close_tags = array_reverse($close_tags, true);
		$html ="";
		foreach ($close_tags as $depth => $tag) {
			if ($item_depth <= $depth) {
				$html .= $tag."<!-- $depth -->\n";
				unset($close_tags[$depth]);
			}
			
			if ($item_depth < $depth)
				$html .= "</ul><!-- $depth -->\n";
				
			if ($item_depth > $depth)
				break;
		}
		return $html;
	}
	
	public function open_tag($close_tags, $item_depth, $container_css = '') {
		$html = "";
		if (!empty($close_tags) && max(array_keys($close_tags)) < $item_depth)
			$html .= '<ul class="menu-container"' . ($container_css ? " style=\"$container_css\"" : '') . '>';
		return $html;
	}
}
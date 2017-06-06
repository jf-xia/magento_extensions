<?php

class PSystem_CatalogAjax_Block_Config extends Mage_Core_Block_Abstract {
/**
 * Render block config
 * 
 * @return string
 *
 */
	public function _toHtml() {
		$content = "";
		
		$configs = array(
			'selector'	=> array(
				'paginator'			=> 'pscatalogajax/selector/paginator',
				'limiter'			=> 'pscatalogajax/selector/limiter',
				'mode'				=> 'pscatalogajax/selector/mode',
				'sortby'			=> 'pscatalogajax/selector/sortby',
				'sortdir'			=> 'pscatalogajax/selector/sortdir',
				'navfilter'			=> 'pscatalogajax/selector/navfilter',
				'navclear'			=> 'pscatalogajax/selector/navclear',
				'navremove'			=> 'pscatalogajax/selector/navremove'
			),
			'content'	=> array(
				'name'				=> 'pscatalogajax/blockcontent/name',
				'selector'			=> 'pscatalogajax/blockcontent/selector',
				'replace'			=> 'pscatalogajax/blockcontent/replace'
			),
			'layer'		=> array(
				'name'				=> 'pscatalogajax/blocklayer/name',
				'selector'			=> 'pscatalogajax/blocklayer/selector',
				'replace'			=> 'pscatalogajax/blocklayer/replace'
			)
		);
		
		$newConfig = array();
		foreach ($configs as $confKey => $_data) {
			foreach ($_data as $dataKey => $dataVal) {
				$tempVal = Mage::getStoreConfig($dataVal);
				if (!empty($tempVal)) {
					if (!isset($newConfig[$confKey]))
						$newConfig[$confKey] = array();
					$newConfig[$confKey][$dataKey] = $tempVal;
				}
			}
		}
		
		if (count($newConfig)) {
			foreach ($newConfig as $key => $data) {
				switch ($key) {
					case 'selector' :
						foreach ($data as $_key => $_val) {
							$content.= "PS.catalogajax.config.".$_key."='".$_val."';\n";
						}
						break;
					case 'content' :
						foreach ($data as $_key => $_val) {
							$content.= "PS.catalogajax.config.blocks.content.".$_key."='".$_val."';\n";
						}
						break;
					case 'layer' :
						foreach ($data as $_key => $_val) {
							$content.= "PS.catalogajax.config.blocks.layer.".$_key."='".$_val."';\n";
						}
						break;
					default:
						continue;
				} 
			}
		}
		if (Mage::app()->getFrontController()->getAction()->getFullActionName() == 'catalogsearch_result_index') {
			$searchBlockName = Mage::getStoreConfig('pscatalogajax/blocksearchlayer/name');
			$content.= 'PS.catalogajax.config.blocks.layer.name = \''.(($searchBlockName)?$searchBlockName:'catalogsearch.leftnav').'\';'."\n";
			$temp = Mage::getStoreConfig('pscatalogajax/blocksearchlayer/selector');
			if ($temp) {
				$content.= 'PS.catalogajax.config.blocks.layer.selector = \''.$temp.'\''."\n";
			}
			$temp = Mage::getStoreConfig('pscatalogajax/blocksearchlayer/replace');
			if ($temp) {
				$content.= 'PS.catalogajax.config.blocks.layer.replace = \''.$temp.'\''."\n";
			}
		}
		if ($content) {
			$content = "<script type=\"text/javascript\">\n".$content."\n</script>\n";
		}
		
		return $content;
	}
}
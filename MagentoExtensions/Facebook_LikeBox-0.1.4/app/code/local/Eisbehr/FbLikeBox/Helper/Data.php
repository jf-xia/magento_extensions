<?php
class Eisbehr_FbLikeBox_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function configValue($path, $name)
	{
		$basePath  = 'fblikebox/';
		$basePath .= $path;
		
		if( substr($basePath, 0, -1) != '/' )
		{
			$basePath .= '/';
		}
		
		return Mage::getStoreConfig( $basePath . $name );
	}
	
	public function getBoxActive()
	{
		$value = $this->configValue('fblikebox', 'active');
		return ( $value ) ? true : false ;
	}
	
	public function getBoxMode()
	{
		$value = $this->configValue('fblikebox', 'xfbml');
		return ( $value ) ? true : false ;
	}
	
	public function getInitStatus()
	{
		$value = $this->configValue('fblikebox', 'status');
		return ( $value ) ? 'true' : 'false' ;
	}
	
	public function getInitCookie()
	{
		$value = $this->configValue('fblikebox', 'cookie');
		return ( $value ) ? 'true' : 'false' ;
	}
	
	public function getAppId()
	{
		$value = $this->configValue('ids', 'appid');
		
		if( empty($value) )
		{
			return 'null';
		}
		
		return $value;
	}

	public function getPageId()
	{
		$value = $this->configValue('ids', 'pageid');
		
		if( empty($value) )
		{
			return 'null';
		}
		
		return $value;
	}
	
	public function getShowHeader()
	{
		$value = $this->configValue('options', 'header');
		return ( $value ) ? 'true' : 'false' ;
	}
	
	public function getShowStream()
	{
		$value = $this->configValue('options', 'stream');
		return ( $value ) ? 'true' : 'false' ;
	}
	
	public function getConnections()
	{
		$value = $this->configValue('options', 'connections');
		
		if( empty($value) || !is_numeric($value) )
		{
			$value = '10';
		}
		
		return $value;
	}
	
	public function getLanguage()
	{
		$value = $this->configValue('options', 'language');
		
		if( empty($value) )
		{
			return 'en_US';
		}
		
		return $value;
	}
	
	public function getBoxWidth()
	{
		$value = $this->configValue('size', 'width');

		if( substr($value, 0, -2) == 'px' )
		{
			$value = substr($value, 0, strlen($value) - 2);
		}

		if( empty($value) || !is_numeric($value) ) 
		{
			$value = 292;
		}
		
		return $value;
	}

	public function getBoxHeight()
	{
		$value = $this->configValue('size', 'height');

		if( substr($value, 0, -2) == 'px' )
		{
			$value = substr($value, 0, strlen($value) - 2);
		}

		if( empty($value) || !is_numeric($value) ) 
		{
			$value = 587;
		}
		
		return $value;
	}
	
	private function getCssUse()
	{
		$value = $this->configValue('style', 'use');
		return ( $value ) ? true : false ;
	}
	
	private function getCssFileName()
	{
		$value = $this->configValue('style', 'file');
		
		if( empty($value) )
		{
			return NULL;
		}
		
		return $value;
	}
	
	private function getCssDeveloperMode()
	{
		$value = $this->configValue('style', 'developer');
		return ( $value ) ? true : false ;
	}
	
	public function getCss()
	{
		$file = $this->getCssFileName();
		
		if( $this->getCssUse() && $file != NULL )
		{
			$baseDir = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
			
			$buffer  = ( $this->getBoxMode() ) ? ' css="' : '&amp;css=';
			$buffer .= $baseDir . 'fblikebox/' . $file;
			
			if( $this->getCssDeveloperMode() )
			{
				$r = rand(1, 9999);
				$buffer .= "?" . $r;
			}
			
			$buffer .= ( $this->getBoxMode() ) ? '"' : '';
			
			return $buffer;
		}
		
		return NULL;
	}
}

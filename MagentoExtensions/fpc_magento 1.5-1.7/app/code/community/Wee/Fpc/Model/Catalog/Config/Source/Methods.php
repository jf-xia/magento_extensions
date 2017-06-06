<?php

class Wee_Fpc_Model_Catalog_Config_Source_Methods
{
    protected $_methods = array(
        'file'     => 'File system',
        'apc'      => 'APC',
        'memcached' => 'Memcached'
    );
    
    public function toOptionArray()
    {
        $options = array();
        $methods = $this->getMethods();
        foreach ($methods as $code => $label) {
            $options[] = array(
                'value' => $code,
                'label' => $label
            );
        }
        return $options;
    }
    
    protected function getMethods()
    {
        $methods = $this->_methods;
        if (!extension_loaded('apc')) {
            unset($methods['apc']);
        }
        return $methods;
    }
}
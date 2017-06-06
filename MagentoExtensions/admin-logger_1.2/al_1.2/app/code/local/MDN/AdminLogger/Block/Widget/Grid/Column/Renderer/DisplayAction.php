<?php

class MDN_AdminLogger_Block_Widget_Grid_Column_Renderer_DisplayAction
	extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        // get current message
        $action = $row->getal_action_type();
        
        switch ($action){
            
            case 'delete':
                    $html = '<span style="color:red;">'.$action.'</span> ';
                break;
            
            case 'insert':
                    $html = '<span style="color:green;">'.$action.'</span> ';
                break;
            
            case 'update':
                    $html = '<span style="color:blue;">'.$action.'</span> ';
                break;
            
            case 'login':
                    $html = '<span style="color:purple;">'.$action.'</span> ';
                break;
            
            default :
                $html = '<span style="color:grey;">'.$action.'</span> ';
                break;
            
        }
        
    	
    	return $html;
    }

}
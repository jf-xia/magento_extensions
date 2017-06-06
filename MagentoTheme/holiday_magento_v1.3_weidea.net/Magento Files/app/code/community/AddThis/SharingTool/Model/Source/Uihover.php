<?php

?>
<?php
class AddThis_SharingTool_Model_Source_Uihover
{

    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label'=>'Normal'),
            array('value' => 1, 'label'=>'Top'),
            array('value' => -1, 'label'=>'Bottom'),
        );
    }

}
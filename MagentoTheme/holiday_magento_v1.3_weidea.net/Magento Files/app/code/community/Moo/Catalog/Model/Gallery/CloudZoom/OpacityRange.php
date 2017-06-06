<?php


class Moo_Catalog_Model_Gallery_CloudZoom_OpacityRange
{

    public function toOptionArray()
    {
        $result = array();
        for ($i = 0; $i <= 1; $i+=0.1) {
            $result[] = array(
                'value' => ($i*100),
                'label' => $i
            );
        }
        return $result;
    }
}
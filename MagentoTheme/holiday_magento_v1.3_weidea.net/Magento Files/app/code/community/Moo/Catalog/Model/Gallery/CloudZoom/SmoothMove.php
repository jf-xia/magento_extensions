<?php


class Moo_Catalog_Model_Gallery_CloudZoom_SmoothMove
{

    public function toOptionArray()
    {
        $result = array();
        for ($i = 1; $i <= 10; $i++) {
            $result[] = array(
                'value' => $i,
                'label' => $i
            );
        }
        return $result;
    }
}

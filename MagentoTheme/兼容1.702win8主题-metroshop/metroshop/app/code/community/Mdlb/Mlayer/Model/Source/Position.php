<?php
class Mdlb_Mlayer_Model_Source_Position {
	public function toOptionArray() {
		$options = array();
		$options[] = array('value'=>'topleft', 'label'=>'Top Left');
		$options[] = array('value'=>'topright', 'label'=>'Top Right');
		$options[] = array('value'=>'bottomleft', 'label'=>'Bottom Left');
		$options[] = array('value'=>'bottomright', 'label'=>'Bottom Right');
		return $options;
	}
}
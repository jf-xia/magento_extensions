<?php
    class Click2Customer_QSRecs_Block_Recs extends Mage_Core_Block_Text {
        protected function _loadCache() {
            return false;
        }

        protected function _saveCache($data) {
            return $this;
        }
        
        function _toHtml() {
            $resp = Mage::helper('analytics/data')->getResponse();
            $html = '';
            if ( is_array( $resp ) ) {
                if ( isset( $resp['content'] ) ) {
                    if ( isset( $resp['content']['QUICKSTART'] ) ) {
                        if ( !empty( $resp['content']['QUICKSTART'] ) ) {
                            $html .= '<div id="c2c_recs">';
                            $html .= $resp['content']['QUICKSTART'];
                            $html .= '</div>';
                        }
                    }
                }
            }
            
            return $html;
        }
    }

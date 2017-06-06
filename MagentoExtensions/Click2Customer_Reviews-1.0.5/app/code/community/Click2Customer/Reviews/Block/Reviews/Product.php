<?php
    class Click2Customer_Reviews_Block_Reviews_Product extends Mage_Core_Block_Text {
        protected function _loadCache() {
            return false;
        }

        protected function _saveCache($data) {
            return $this;
        }
        
        function _toHtml() {
            $config = Mage::getSingleton('analytics/config');
            $accountId = $config->getAccountId();
            $html = '<script type="text/javascript">';
            $html .='
                c2cQuery(document).ready(function() {
                    c2cQuery(\'#c2c_prod_review\').c2cReviews({
                        show: 10,
                        type: \'product\',
                        key: _ss_item_code,
                        template: \'reviews\'
                    });
                    c2cQuery(\'#c2c_review_form\').c2cReviewForm({
                        prod_code: _ss_item_code
                    })
                });
            ';
            $html .= '</script>';
            return $html;
        }
    }

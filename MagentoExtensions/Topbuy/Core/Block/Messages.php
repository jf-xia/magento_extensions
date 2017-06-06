<?php 
/**
 * Messages block
 *
 * @category   Mage
 * @package    Mage_Core
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Topbuy_Core_Block_Messages extends Mage_Core_Block_Messages
{ 
    /**
     * Retrieve messages in HTML format grouped by type
     *
     * @param   string $type
     * @return  string
     */
    public function getGroupedHtml()
    {
        $types = array(
            Mage_Core_Model_Message::ERROR,
            Mage_Core_Model_Message::WARNING,
            Mage_Core_Model_Message::NOTICE,
            Mage_Core_Model_Message::SUCCESS
        );
		 
        $html = '';
        foreach ($types as $type) {
            if ( $messages = $this->getMessages($type) ) {
				if ( !$html ) {
                    $html .= '<' . $this->_messagesFirstLevelTagName . ' class="messages">';
                }
                $html .= '<' . $this->_messagesSecondLevelTagName . ' class="' . $type . '-msg">';
                $html .= '<' . $this->_messagesFirstLevelTagName . '>';
				$sampleText = '';
                foreach ( $messages as $message ) {
					if (strpos($sampleText, $message->getText()))
					{
						//found duplicate error message
						#echo 'I am working now';
					}
					else
					{
						//else display, and string add to sample text
						$sampleText = $sampleText.";". $message->getText();
						$html.= '<' . $this->_messagesSecondLevelTagName . '>';
						$html.= '<' . $this->_messagesContentWrapperTagName . '>';
						$html.= ($this->_escapeMessageFlag) ? $this->htmlEscape($message->getText()) : $message->getText();
						$html.= '</' . $this->_messagesContentWrapperTagName . '>';
						$html.= '</' . $this->_messagesSecondLevelTagName . '>';
					}
                }
                $html .= '</' . $this->_messagesFirstLevelTagName . '>';
                $html .= '</' . $this->_messagesSecondLevelTagName . '>';
            }
        }
        if ( $html) {
            $html .= '</' . $this->_messagesFirstLevelTagName . '>';
        }
        return $html;
    }
 
}

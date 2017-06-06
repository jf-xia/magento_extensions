<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Varien
 * @package    Varien_Data
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Category form input image element
 *
 * @category   Varien
 * @package    Varien_Data
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Varien_Data_Form_Element_Gallery2 extends Varien_Data_Form_Element_Abstract
{
    public function __construct($data)
    {
        parent::__construct($data);
        $this->setType('file');
    }

    public function getElementHtml()
    {
        $gallery = $this->getValues();

        $html = '<table id="gallery" class="gallery" border="0" cellspacing="3" cellpadding="0">';
        $html .= '<thead id="gallery_thead" class="gallery"><tr class="gallery"><td class="gallery"></td><td class="gallery">Image</td><td class="gallery" valign="middle" align="center" width="100px">Sort Order</td><td class="gallery" valign="middle" align="center" width="100px">Delete</td></tr></thead>';
        $widgetButton = $this->getForm()->getParent()->getLayout();
        $buttonHtml = $widgetButton->createBlock('adminhtml/widget_button')
                ->setData(
                    array(
					    'label'     => 'Add New Image',
                        'onclick'   => 'addNewImg()',
                        'class'     => 'add'))
                ->toHtml();

        $html .= '<tfoot class="gallery">';
        $html .= '<tr class="gallery">';
        $html .= '<td class="gallery" valign="middle" align="left" colspan="5">'.$buttonHtml.'</td>';
        $html .= '</tr>';
        $html .= '</tfoot>';

        $html .= '<tbody class="gallery">';

        $i = 0;
		
		
        if(count($gallery)) {
            foreach ($gallery as $image) {
				$i++;
                $html .= '<tr class="gallery">';
               
				$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $image->getImageUrl();
				$html .= '<td class="gallery" align="center" style="vertical-align:bottom;">';
				$html .= '<a href="'.$url.'" target="_blank" onclick="imagePreview(\'image_'.$image->getId().'\');return false;">
				<img id="image_'.$image->getId().'" src="'.$url.'" height="23" align="absmiddle" class="small-image-preview"></a></td>';
				$html .= '<td><input type="file" name="'.$this->getName().'_0['.$image->getId().']" size="30"></td>';

                $html .= '<td class="gallery" align="center" style="vertical-align:bottom;"><input type="input" name="'.parent::getName().'[position]['.$image->getId().']" value="'.$image->getSortOrder().'" size="3"/></td>';
                $html .= '<td class="gallery" align="center" style="vertical-align:bottom;"><input type="checkbox" name="'.parent::getName().'[delete]['.$image->getId().']" value="'.$image->getId().'"/></td>';
                $html .= '</tr>';
            }
        }
        if ($i==0) {
            $html .= '<script type="text/javascript">document.getElementById("gallery_thead").style.visibility="hidden";</script>';
        }

        $html .= '</tbody></table>';

/*
        $html .= '<script language="javascript">
                    var multi_selector = new MultiSelector( document.getElementById( "gallery" ),
                    "'.$this->getName().'",
                    -1,
                        \'<a href="file:///%file%" target="_blank" onclick="imagePreview(\\\''.$this->getHtmlId().'_image_new_%id%\\\');return false;"><img src="file:///%file%" width="50" align="absmiddle" class="small-image-preview" style="padding-bottom:3px; width:"></a> <div id="'.$this->getHtmlId().'_image_new_%id%" style="display:none" class="image-preview"><img src="file:///%file%"></div>\',
                        "",
                        \'<input type="file" name="'.parent::getName().'[new_image][%id%][%j%]" size="1" />\'
                    );
                    multi_selector.addElement( document.getElementById( "'.$this->getHtmlId().'" ) );
                    </script>';
*/

        $name = $this->getName();
        $parentName = parent::getName();

        $html .= <<<EndSCRIPT

        <script language="javascript">
        id = 0;

        function addNewImg(){

            document.getElementById("gallery_thead").style.visibility="visible";

            id--;
            new_file_input = '<input type="file" name="{$name}_0[%id%]" size="30" />';

		    // Sort order input
		    var new_row_input = document.createElement( 'input' );
		    new_row_input.type = 'text';
		    new_row_input.name = '{$parentName}[position]['+id+']';
		    new_row_input.size = '3';
		    new_row_input.value = '0';

		    // Delete button
		    var new_row_button = document.createElement( 'input' );
		    new_row_button.type = 'checkbox';
		    new_row_button.value = 'Delete';

            table = document.getElementById( "gallery" );

            // no of rows in the table:
            noOfRows = table.rows.length;

            // no of columns in the pre-last row:
            noOfCols = table.rows[noOfRows-2].cells.length;

            // insert row at pre-last:
            var x=table.insertRow(noOfRows-1);

            // insert cells in row.
            for (var j = 0; j < noOfCols; j++) {

                newCell = x.insertCell(j);
                newCell.align = "center";
                newCell.valign = "middle";
				
				if (j==0) {
		            newCell.innerHTML = ('&nbsp');
                }
                else if (j==2) {
		            newCell.appendChild( new_row_input );
                }
                else if (j==3) {
		            newCell.appendChild( new_row_button );
                }
                else {
                    newCell.innerHTML = new_file_input.replace(/%id%/g, id);
                }

            }

		    // Delete function
		    new_row_button.onclick= function(){

                this.parentNode.parentNode.parentNode.removeChild( this.parentNode.parentNode );

			    // Appease Safari
			    //    without it Safari wants to reload the browser window
			    //    which nixes your already queued uploads
			    return false;
		    };

	    }
        </script>

EndSCRIPT;
        $html.= $this->getAfterElementHtml();
        return $html;
    }

    public function getName()
    {
        return $this->getData('name');
    }

    public function getParentName()
    {
        return parent::getName();
    }
}

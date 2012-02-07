<?php
// +--------------------------------------------------------------------------+
// | Image_Graph aka GraPHPite                                                |
// +--------------------------------------------------------------------------+
// | Copyright (C) 2003, 2004 Jesper Veggerby Hansen                          |
// | Email         pear.nosey@veggerby.dk                                |
// | Web           http://graphpite.sourceforge.net                           |
// | PEAR          http://pear.php.net/pepr/pepr-proposal-show.php?id=145     |
// +--------------------------------------------------------------------------+
// | This library is free software; you can redistribute it and/or            |
// | modify it under the terms of the GNU Lesser General Public               |
// | License as published by the Free Software Foundation; either             |
// | version 2.1 of the License, or (at your option) any later version.       |
// |                                                                          |
// | This library is distributed in the hope that it will be useful,          |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU        |
// | Lesser General Public License for more details.                          |
// |                                                                          |
// | You should have received a copy of the GNU Lesser General Public         |
// | License along with this library; if not, write to the Free Software      |
// | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA |
// +--------------------------------------------------------------------------+

/**
 * Image_Graph aka GraPHPite - PEAR PHP OO Graph Rendering Utility.
 * @package linestyle
 * @copyright Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license http://www.gnu.org/licenses/lgpl.txt GNU Lesser General Public License
 * @author Jesper Veggerby Hansen <pear.nosey@veggerby.dk>
 * @version $Id: Array.php,v 1.6 2004/11/05 19:13:30 nosey Exp $
 */ 

/**
 * Include file Graph/Common.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/Common.php");

/**
 * A sequential array of linestyles.
 * This is used for multiple objects within the same element with different line styles.
 * This is done by adding multiple line styles to a LineArrray structure. The linearray 
 * will then when requested return the "next" linestyle in sequential order. It is possible
 * to specify ID tags to each linestyle, which is used to make sure some data uses a 
 * specific linestyle (i.e. in a multiple-/stackedbarchart you name the {@see Image_Graph_Dataset}s and
 * uses this name as ID tag when adding the dataset's associated linestyle to the linearray.
 */
class Image_Graph_Line_Array extends Image_Graph_Common 
{

    /**
     * The fill array
     * @var array
     * @access private
     */
    var $_lineStyles = array ();

    /**
     * Add a line style to the array
     * @param Image_Graph_Line $style The style to add
     */
    function add(& $style)
    {
        if (is_a($style, "Image_Graph_Element")) {
            parent::add($style);
        } else {
            $this->addColor($style);
        }
        $this->_lineStyles[] = & $style;
        reset($this->_lineStyles);

    }

    /**
     * Return the linestyle
     * @return int A GD Linestyle 
     * @access private
     */
    function _getLineStyle($ID = false)
    {
        if (($ID === false) and (!(list ($ID, $lineStyle) = each($this->_lineStyles)))) {
            reset($this->_lineStyles);
            list ($ID, $lineStyle) = each($this->_lineStyles);
        }
        $lineStyle = & $this->_lineStyles[$ID];

        if (is_numeric($lineStyle)) {
            if (isset($GLOBALS['_Image_Graph_gd2'])) {
                ImageSetThickness($this->_canvas(), 1);
            }
            return $this->_color($lineStyle);
        }
        elseif ($lineStyle != null) {
            if (is_a($lineStyle, "Image_Graph_Color")) {
                return $lineStyle->_index;
            } else {
                return $lineStyle->_getLineStyle();
            }
        } else {
            ImageSetThickness($this->_canvas(), 1);
            return $this->_color(IMAGE_GRAPH_BLACK);
        }
    }

}

?>
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
 * @package marker
 * @copyright Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license http://www.gnu.org/licenses/lgpl.txt GNU Lesser General Public License
 * @author Jesper Veggerby Hansen <pear.nosey@veggerby.dk>
 * @version $Id: Diamond.php,v 1.5 2004/11/05 19:13:28 nosey Exp $
 */ 

/**
 * Include file Graph/Marker.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/Marker.php");

/**
 * Data marker as a diamond
 */
class Image_Graph_Marker_Diamond extends Image_Graph_Marker 
{

    /**
     * Draw the marker on the canvas
     * @param int $x The X (horizontal) position (in pixels) of the marker on the canvas 
     * @param int $y The Y (vertical) position (in pixels) of the marker on the canvas 
     * @param array $values The values representing the data the marker "points" to 
     * @access private
     */
    function _drawMarker($x, $y, $values = false)
    {
        $diamond[] = $x - $this->_size;
        $diamond[] = $y;
        $diamond[] = $x;
        $diamond[] = $y - $this->_size;
        $diamond[] = $x + $this->_size;
        $diamond[] = $y;
        $diamond[] = $x;
        $diamond[] = $y + $this->_size;
        ImageFilledPolygon($this->_canvas(), $diamond, 4, $this->_getFillStyle());
        ImagePolygon($this->_canvas(), $diamond, 4, $this->_getLineStyle());
        parent::_drawMarker($x, $y, $values);
    }

}

?>
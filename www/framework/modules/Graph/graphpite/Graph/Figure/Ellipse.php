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
 * @package figure
 * @copyright Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license http://www.gnu.org/licenses/lgpl.txt GNU Lesser General Public License
 * @author Jesper Veggerby Hansen <pear.nosey@veggerby.dk>
 * @version $Id: Ellipse.php,v 1.5 2004/11/05 19:13:30 nosey Exp $
 */ 

/**
 * Include file Graph/Element.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/Element.php");

/**
 * Ellipse to draw on the canvas
 */
class Image_Graph_Figure_Ellipse extends Image_Graph_Element 
{

    /**
     * Ellipse [Constructor]
     * @param int $x The center pixel of the ellipse on the canvas 
     * @param int $y The center pixel of the ellipse on the canvas 
     * @param int $radiusX The width in pixels of the box on the canvas 
     * @param int $radiusY The height in pixels of the box on the canvas 
     */
    function &Image_Graph_Figure_Ellipse($x, $y, $radiusX, $radiusY)
    {
        parent::Image_Graph_Element();
        $this->_setCoords($x - $radiusX, $y - $radiusY, $x + $radiusX, $y + $radiusY);
    }

    /**
     * Output the ellipse
     * @access private
     */     
    function _done()
    {
        parent::_done();

        ImageFilledEllipse($this->_canvas(), ($this->_left + $this->_right) / 2, ($this->_top + $this->_bottom) / 2, $this->width(), $this->height(), $this->_getFillStyle());
        ImageEllipse($this->_canvas(), ($this->_left + $this->_right) / 2, ($this->_top + $this->_bottom) / 2, $this->width(), $this->height(), $this->_getLineStyle());
    }

}

?>
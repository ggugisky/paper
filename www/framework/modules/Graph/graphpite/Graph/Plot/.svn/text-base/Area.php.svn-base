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
 * @package plottype
 * @copyright Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license http://www.gnu.org/licenses/lgpl.txt GNU Lesser General Public License
 * @author Jesper Veggerby Hansen <pear.nosey@veggerby.dk>
 * @version $Id: Area.php,v 1.5 2004/11/05 19:13:29 nosey Exp $
 */ 

/**
 * Include file Graph/Plot.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/Plot.php");

/**
 * Area Chart plot.
 * An area chart plots all data points similar to a {@see Image_Graph_Plot_Line}, but the area beneath the
 * line is filled and the whole area "the-line", "the right edge", "the x-axis" and 
 * "the left edge" is bounded.
 */
class Image_Graph_Plot_Area extends Image_Graph_Plot 
{

    /**
     * Output the plot
     * @access private
     */
    function _done()
    {
        parent::_done();

        $plotarea[] = $this->_parent->_pointX(array ('X' => 0, 'Y' => 0));
        $plotarea[] = $this->_parent->_pointY(array ('X' => 0, 'Y' => 0));

        $this->_dataset->_reset();
        while ($point = $this->_dataset->_next()) {
            $plotarea[] = $this->_parent->_pointX($point);
            $plotarea[] = $this->_parent->_pointY($point);
            $lastPoint = $point;
        }

        $endPoint['X'] = $lastPoint['X'];
        $endPoint['Y'] = 0;
        $plotarea[] = $this->_parent->_pointX($endPoint);
        $plotarea[] = $this->_parent->_pointY($endPoint);

        ImageFilledPolygon($this->_canvas(), $plotarea, count($plotarea) / 2, $this->_getFillStyle());
        ImagePolygon($this->_canvas(), $plotarea, count($plotarea) / 2, $this->_getLineStyle());
        $this->_drawMarker();
    }

}

?>
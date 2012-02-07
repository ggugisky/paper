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
 * @version $Id: Area.php,v 1.5 2004/11/05 19:13:30 nosey Exp $
 */ 

/**
 * Include file Graph/Plot/Smoothed/Bezier.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/Plot/Smoothed/Bezier.php");

/**
 * Bezier smoothed area chart
 * Similar to an {@see Image_Graph_Plot_Area}, but the interconnecting lines between two datapoints are
 * smoothed using a Bezier curve, which enables the chart to appear as a nice curved plot
 * instead of the sharp edges of a conventional {@see Image_Graph_Plot_Area}. 
 */
class Image_Graph_Plot_Smoothed_Area extends Image_Graph_Plot_Smoothed_Bezier 
{

    /**
     * Output the Bezier smoothed plot as an Area Chart
     * @access private
     */
    function _done()
    {
        parent::_done();
        $plotarea = $this->_getPoints(true);
        if (count($plotarea) >= 6) {
            ImageFilledPolygon($this->_canvas(), $plotarea, count($plotarea) / 2, $this->_getFillStyle());
            ImagePolygon($this->_canvas(), $plotarea, count($plotarea) / 2, $this->_getLineStyle());
        }
        $this->_drawMarker();
    }

}

?>
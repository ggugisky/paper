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
 * @version $Id: Radar.php,v 1.5 2004/11/05 19:13:29 nosey Exp $
 */ 

/**
 * Include file Graph/Plot.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/Plot.php");

/**
 * Radar chart
 */
class Image_Graph_Plot_Radar extends Image_Graph_Plot 
{

    /**
     * Get the minimum X value from the dataset
     * @return double The minimum X value
     * @access private
     */
    function _minimumX()
    {
        return 0;
    }

    /**
     * Get the maximum X value from the dataset
     * @return double The maximum X value
     * @access private
     */
    function _maximumX()
    {
        if ($this->_dataset) {
            return $this->_dataset->count();
        }
    }

    /**
     * Output the plot
     * @access private
     */
    function _done()
    {
        if (is_a($this->_parent, "Image_Graph_Plotarea_Radar")) {
            $centerX = (int) (($this->_left + $this->_right) / 2);
            $centerY = (int) (($this->_top + $this->_bottom) / 2);
            $radius = min($this->height(), $this->width()) * 0.40;
            $maxY = $this->_dataset->maximumY();
            $count = $this->_dataset->count();

            $this->_dataset->_reset();
            while ($point = $this->_dataset->_next()) {
                $radarPolygon[] = $this->_parent->_pointX($point);
                $radarPolygon[] = $this->_parent->_pointY($point);
            }
            ImageFilledPolygon($this->_canvas(), $radarPolygon, count($radarPolygon) / 2, $this->_getFillStyle());
            ImagePolygon($this->_canvas(), $radarPolygon, count($radarPolygon) / 2, $this->_getLineStyle());
        }
        $this->_drawMarker();
        parent::_done();
    }

}

?>
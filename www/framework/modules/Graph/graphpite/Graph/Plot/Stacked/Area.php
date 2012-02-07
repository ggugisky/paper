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
 * @version $Id: Area.php,v 1.5 2004/11/05 19:13:31 nosey Exp $
 */ 

/**
 * Include file Graph/Plot/Bar.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/Plot/Bar.php");

/**
 * Stacked barchart.
 * The dataset Y-values are stacked on top of eachother, i.e. the first point with X0 = 0 is
 * displayed from Y = 0 to Y = Y0, the next point with X1 = 0 are displayed from Y = Y0 to Y = Y0+Y1, etc.
 */
class Image_Graph_Plot_Stacked_Area extends Image_Graph_Plot_Stacked_Bar 
{

    /**
     * Get the maximum X value from the dataset
     * @return double The maximum X value
     * @access private
     */
    function _maximumX()
    {
        return parent::_maximumX() - 1;
    }

    /**
     * Output the plot
     * @access private
     */
    function _done()
    {
        Image_Graph_Plot::_done();

        if (is_array($this->_datasets)) {
            reset($this->_datasets);

            $keys = array_keys($this->_datasets);
            list ($ID, $key) = each($keys);
            $dataset = & $this->_datasets[$key];

            $point = array ('X' => $dataset->minimumX(), 'Y' => 0);
            $base[] = $this->_parent->_pointY($point);
            $base[] = $this->_parent->_pointX($point);

            $point = array ('X' => $dataset->maximumX(), 'Y' => 0);
            $base[] = $this->_parent->_pointY($point);
            $base[] = $this->_parent->_pointX($point);
            reset($keys);

            $current = array();
            while (list ($ID, $key) = each($keys)) {
                $dataset = & $this->_datasets[$key];
                $dataset->_reset();
                $plotarea = array_reverse($base);
                unset ($base);
                while ($point = $dataset->_next()) {
                    $x = $point['X'];
                    $p = $point;
                    if (isset($current[$x])) {
                        $p['Y'] += $current[$x];
                    } else {
                        $current[$x] = 0;
                    }
                    $x1 = $this->_parent->_pointX($p);
                    $y1 = $this->_parent->_pointY($p);
                    $plotarea[] = $x1;
                    $plotarea[] = $y1;
                    $base[] = $y1;
                    $base[] = $x1;
                    $current[$x] += $point['Y'];
                }
                ImageFilledPolygon($this->_canvas(), $plotarea, count($plotarea) / 2, $this->_getFillStyle());
                ImagePolygon($this->_canvas(), $plotarea, count($plotarea) / 2, $this->_getLineStyle());
            }
            $this->_drawMarker();
        }
    }
}

?>
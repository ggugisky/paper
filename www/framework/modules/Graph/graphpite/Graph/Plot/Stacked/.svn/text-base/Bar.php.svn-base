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
 * @version $Id: Bar.php,v 1.5 2004/11/05 19:13:31 nosey Exp $
 */ 

/**
 * Include file Graph/Plot/Bar/Multiple.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/Plot/Bar/Multiple.php");

/**
 * Stacked barchart
 * The dataset Y-values are stacked on top of eachother, i.e. the first point with X0 = 0 is
 * displayed from Y = 0 to Y = Y0, the next point with X1 = 0 are displayed from Y = Y0 to Y = Y0+Y1, etc.
 */
class Image_Graph_Plot_Stacked_Bar extends Image_Graph_Plot_Bar_Multiple 
{

    /**
     * Get the maximum Y value from the dataset
     * @return double The maximum Y value
     * @access private
     */
    function _maximumY()
    {
        $maxY = 0;
        if (is_array($this->_datasets)) {
            reset($this->_datasets);            

            $keys = array_keys($this->_datasets);
            while (list ($ID, $key) = each($keys)) {
                $dataset = & $this->_datasets[$key];

                $dataset->_reset();
                while ($point = $dataset->_next()) {
                    $x = $point['X'];
                    if ((!isset($total)) or (!isset($total[$x]))) {
                        $maxY = ($total[$x] = $point['Y']);
                    } else {
                        $maxY = max($maxY, $total[$x] += $point['Y']);
                    }
                }
            }
        }
        return $maxY;
    }

    /**
     * Calculate marker point data
     * @param Array Point The point to calculate data for
     * @param Array NextPoint The next point
     * @param Array PrevPoint The previous point
     * @param Array Totals The pre-calculated totals, if needed
     * @return Array An array containing marker point data
     * @access private
     */
    function _getMarkerData($point, $nextPoint, $prevPoint, & $totals)
    {
        if (!isset($totals['SUM_Y'])) {
            $totals['SUM_Y'] = array();
        }
        $x = $point['X'];
        if (!isset($totals['SUM_Y'][$x])) {
            $totals['SUM_Y'][$x] = 0;
        }
        $point = parent::_getMarkerData($point, $nextPoint, $prevPoint, $totals);
        $point['MARKER_X'] = ($point['MARKER_X1'] + $point['MARKER_X2']) / 2;
        $point['MARKER_Y'] = ($this->_parent->_pointY($totals['SUM_Y'][$x]) + $this->_parent->_pointY($totals['SUM_Y'][$x] + $point['Y'])) / 2;
        return $point;
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

            if (!$this->_xValueWidth) {
                $width = $this->width() / ($this->_maximumX() + 2) / 2;
            }

            $keys = array_keys($this->_datasets);
            while (list ($ID, $key) = each($keys)) {
                $dataset = & $this->_datasets[$key];
                $dataset->_reset();
                while ($point = $dataset->_next()) {
                    if (!$this->_xValueWidth) {
                        $x1 = $this->_parent->_pointX($point) - $width + $this->_space;
                        $x2 = $this->_parent->_pointX($point) + $width - $this->_space;
                    } else {
                        $x1 = $this->_parent->_pointX($point['X'] - $this->_xValueWidth / 2) + $this->_space;
                        $x2 = $this->_parent->_pointX($point['X'] + $this->_xValueWidth / 2) - $this->_space;
                    }
                    $x = $point['X'];
                    if (!isset($current[$x])) {
                        $current[$x] = 0;
                    }
                    $y1 = $this->_parent->_pointY($current[$x]);
                    $y2 = $this->_parent->_pointY($current[$x] + $point['Y']);
                    $current[$x] += $point['Y'];
                    if ($y1 != $y2) {
                        ImageFilledRectangle($this->_canvas(), min($x1, $x2), min($y1, $y2), max($x1, $x2), max($y1, $y2), $this->_getFillStyle($key));
                        ImageRectangle($this->_canvas(), min($x1, $x2), min($y1, $y2), max($x1, $x2), max($y1, $y2), $this->_getLineStyle());
                    }
                }
            }
            $this->_drawMarker();
        }
    }
}

?>
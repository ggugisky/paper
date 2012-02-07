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
 * @package plotarea
 * @copyright Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license http://www.gnu.org/licenses/lgpl.txt GNU Lesser General Public License
 * @author Jesper Veggerby Hansen <pear.nosey@veggerby.dk>
 * @version $Id: Radar.php,v 1.5 2004/11/05 19:13:30 nosey Exp $
 */ 

/**
 * Include file Graph/Plotarea.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/Plotarea.php");

/**
 * Plot area used for radar plots.
 */
class Image_Graph_Plotarea_Radar extends Image_Graph_Plotarea 
{

    /**
     * Create the plotarea, implicitely creates 2 normal axis
     */
    function &Image_Graph_Plotarea_Radar()
    {
        parent::Image_Graph_Element();
        $this->_padding = 10;
        $this->_axisX = & new Image_Graph_Axis_Radar();
        $this->_axisX->_setParent($this);
        $this->_axisY = & new Image_Graph_Axis(IMAGE_GRAPH_AXIS_Y);
        $this->_axisY->_setParent($this);
        $this->_axisY->_setMinimum(0);
    }

    /**
     * Add a X-axis grid to the plotarea	 
     * @param Grid $grid The grid to add
     * @see Image_Graph_Common::add() 
     */
    function &addGridX(& $grid)
    {
        return $this->addGridY($grid);
    }

    /**
     * Get the width of the "real" plotarea	 
     * @return int The width of the "real" plotarea, ie not including space occupied by padding and axis 
     * @access private
     */
    function _plotWidth()
    {
        return (min($this->height(), $this->width())) * 0.80;
    }

    /**
     * Get the height of the "real" plotarea	 
     * @return int The height of the "real" plotarea, ie not including space occupied by padding and axis 
     * @access private
     */
    function _plotHeight()
    {
        return (min($this->height(), $this->width())) * 0.80;
    }

    /**
     * Left boundary of the background fill area 
     * @return int Leftmost position on the canvas
     * @access private
     */
    function _fillLeft()
    {
        return (int) (($this->_left + $this->_right - $this->_plotWidth()) / 2);
    }

    /**
     * Top boundary of the background fill area 
     * @return int Topmost position on the canvas
     * @access private
     */
    function _fillTop()
    {
        return (int) (($this->_top + $this->_bottom - $this->_plotHeight()) / 2);
    }

    /**
     * Right boundary of the background fill area 
     * @return int Rightmost position on the canvas
     * @access private
     */
    function _fillRight()
    {
        return (int) (($this->_left + $this->_right + $this->_plotWidth()) / 2);
    }

    /**
     * Bottom boundary of the background fill area 
     * @return int Bottommost position on the canvas
     * @access private
     */
    function _fillBottom()
    {
        return (int) (($this->_top + $this->_bottom + $this->_plotHeight()) / 2);
    }

    /**
     * Get the X pixel position represented by a value
     * @param double Value the value to get the pixel-point for	 
     * @return double The pixel position along the axis
     * @access private
     */
    function _pointX($value)
    {
        if (is_array($value)) {
            $radius = (($value['Y'] === false) ? 1 : ($value['Y'] - $this->_axisY->_getMinimum()) / ($this->_axisY->_getMaximum() - $this->_axisY->_getMinimum()));
            $x = ($this->_left + $this->_right) / 2 - $radius * ($this->_plotWidth() / 2) * cos(deg2rad($this->_axisX->_point($value['X'])));
        }
        return max($this->_plotLeft, min($this->_plotRight, $x));
    }

    /**
     * Get the Y pixel position represented by a value
     * @param double Value the value to get the pixel-point for	 
     * @return double The pixel position along the axis
     * @access private
     */
    function _pointY($value)
    {
        if (is_array($value)) {
            $radius = (($value['Y'] === false) ? 1 : ($value['Y'] - $this->_axisY->_getMinimum()) / ($this->_axisY->_getMaximum() - $this->_axisY->_getMinimum()));
            $y = ($this->_top + $this->_bottom) / 2 + $radius * ($this->_plotHeight() / 2) * sin(deg2rad($this->_axisX->_point($value['X'])));
        }
        return max($this->_plotTop, min($this->_plotBottom, $y));
    }

    /**
     * Update coordinates
     * @access private
     */
    function _updateCoords()
    {
        $this->_debug("Calculating and setting edges");
        $this->_calcEdges();

        $centerX = (int) (($this->_left + $this->_right) / 2);
        $centerY = (int) (($this->_top + $this->_bottom) / 2);
        $radius = min($this->_plotHeight(), $this->_plotWidth()) / 2;

        if (is_object($this->_axisX)) {
            $this->_axisX->_setCoords($centerX - $radius, $centerY - $radius, $centerX + $radius, $centerY + $radius);
        }

        if (is_object($this->_axisY)) {
            $this->_axisY->_setCoords($centerX, $centerY, $centerX - $radius, $centerY - $radius);
        }

        $this->_plotLeft = $this->_fillLeft();
        $this->_plotTop = $this->_fillTop();
        $this->_plotRight = $this->_fillRight();
        $this->_plotBottom = $this->_fillBottom();

        $this->_debug("Updating child elements");
        Image_Graph_Element::_updateCoords();
    }

}

?>
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
 * @package axis
 * @copyright Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license http://www.gnu.org/licenses/lgpl.txt GNU Lesser General Public License
 * @author Jesper Veggerby Hansen <pear.nosey@veggerby.dk>
 * @version $Id: Radar.php,v 1.6 2004/11/05 19:13:30 nosey Exp $
 */

/**
 * Include file Graph/Axis.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/Axis.php");

// TODO Make it possible to display values on all axis (for mgeary)

/**
 * Displays an "X"-axis in a radar plot chart.
 * This axis maps the number of elements in the dataset to a angle (from 0-360 degrees).
 * Displaying the axis consist of drawing a number of lines from center to the edge of
 * the "circle" than encloses the radar plot. The labels are drawn on the "ends" of these
 * radial lines.
 */
class Image_Graph_Axis_Radar extends Image_Graph_Axis_Multidimensional
{

    /**
     * Specifies the number of pixels, the labels is offsetted from the end of
     * the axis
     * @var int
     * @access private
     */
    var $_distanceFromEnd = 5;

    /**
     * Gets the minimum value the axis will show
     * @return double The minumum value
     * @access private
     */
    function _getMinimum()
    {
        return $this->_minimum;
    }

    /**
     * Gets the maximum value the axis will show
     * @return double The maximum value
     * @access private
     */
    function _getMaximum()
    {
        return $this->_maximum;
    }

    /**
     * Get the step each pixel on the canvas will represent on the axis.
     * @return double The step a pixel represents
     * @access private
     */
    function _delta()
    {
        $this->_debug("Calculating delta");
        if (abs($this->_getMaximum() - $this->_getMinimum()) == 0) {
            return 0;
        }

        return 360 / ($this->_getMaximum() - $this->_getMinimum());
    }

    /**
     * Get the pixel position represented by a value on the canvas
     * @param double $value the value to get the pixel-point for
     * @return double The pixel position along the axis
     * @access private
     */
    function _point($value)
    {
        return (90 + (int) ($this->_value($value) * $this->_delta())) % 360;
    }

    /**
     * Get the interval with which labels are shown on the axis.
     * For a radar plot this is always 1
     * @return double The label interval always 1
     * @access private
     */
    function _labelInterval()
    {
        return 1;
    }

    /**
     * Get the minor label interval with which axis label ticks are drawn.
     * For a radar plot this is always disabled (i.e false)
     * @return double The minor label interval, always false
     * @access private
     */
    function _minorLabelInterval()
    {
        return false;
    }

    /**
     * Get the size in pixels of the axis.
     * For a radar plot this is always 0
     * @return int The size of the axis
     * @access private
     */
    function _size()
    {
        return 0;
    }

    /**
     * Sets the distance from the end of the category lines to the label.
     * @param int $distance The distance in pixels
     */
    function setDistanceFromEnd($distance = 5) {
        $this->_distanceFromEnd = $distance;
    }

    /**
     * Output the axis
     * @access private
     */
    function _done()
    {
        Image_Graph_Element::_done();

        if (!$this->_font) {
            $this->_font = $GLOBALS['_Image_Graph_font'];
        }

        $labelInterval = $this->_labelInterval();
        $value = $this->_getMinimum();

        $centerX = (int) (($this->_left + $this->_right) / 2);
        $centerY = (int) (($this->_top + $this->_bottom) / 2);

        $radius = min($this->height(), $this->width()) / 2;

        $this->_debug("Enumerating values from $value to ".$this->_getMaximum()." in steps of $labelInterval");
        while ($value < $this->_getMaximum()) {
            $endPoint = array ('X' => $value, 'Y' => false);
            $dX = $this->_parent->_pointX($endPoint);
            $dY = $this->_parent->_pointY($endPoint);

            if (!$this->_font) {
                $this->_font = $GLOBALS['_Image_Graph_font'];
            }

            if (is_object($this->_dataPreProcessor)) {
                $labelText = $this->_dataPreProcessor->_process($value);
            } else {
                $labelText = $value;
            }

            $offX = ($dX - $centerX);
            $offY = ($dY - $centerY);

            $hyp = sqrt($offX*$offX + $offY*$offY);
            if ($hyp != 0) {
                $scale = $this->_distanceFromEnd / $hyp;
            } else {
                $scale = 1;
            }

            $adX = $dX + $offX * $scale;
            $adY = $dY + $offY * $scale;

            $text = & new Image_Graph_Text($adX, $adY, $labelText, $this->_font);
            if ((abs($dX - $centerX) < 1.5) and ($dY < $centerY)) {
                $text->setAlignment(IMAGE_GRAPH_ALIGN_BOTTOM + IMAGE_GRAPH_ALIGN_CENTER_X);
            }
            elseif ((abs($dX - $centerX) < 1.5) and ($dY > $centerY)) {
                $text->setAlignment(IMAGE_GRAPH_ALIGN_TOP + IMAGE_GRAPH_ALIGN_CENTER_X);
            }
            elseif ($dX < $centerX) {
                $text->setAlignment(IMAGE_GRAPH_ALIGN_RIGHT + IMAGE_GRAPH_ALIGN_CENTER_Y);
            } else {
                $text->setAlignment(IMAGE_GRAPH_ALIGN_LEFT + IMAGE_GRAPH_ALIGN_CENTER_Y);
            }
            $this->add($text);
            $text->_done();

            ImageLine($this->_canvas(), $centerX, $centerY, $dX, $dY, $this->_getLineStyle());
            $value += $labelInterval;
        }
    }

}

?>
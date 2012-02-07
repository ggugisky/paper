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
 * @version $Id: Sequential.php,v 1.5 2004/11/05 19:13:30 nosey Exp $
 */ 

/**
 * Include file Graph/Axis.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/Axis.php");

/**
 * A normal axis thats displays labels with a "interval" of 1.
 * This is basically a normal axis where the range is
 * the number of labels defined, that is the range is explicitly defined
 * when constructing the axis. 
 */
class Image_Graph_Axis_Sequential extends Image_Graph_Axis 
{
    
    /** The labels shown on the axis
     * @var array
     * @access private
     */
    var $_labels = false;

    /**
     * Image_Graph_AxisSequential [Constructor].
     * @param array $labels The labels shown on the Axis
     * @param int $type The type (direction) of the Axis
     */
    function &Image_Graph_Axis_Sequential($labels, $type = IMAGE_GRAPH_AXIS_X)
    {
        parent::Image_Graph_Axis($type);
        $this->_labels = $labels;
    }

    /**
     * Gets the minimum value the axis will show.
     * This is always 0
     * @return double The minumum value
     * @access private
     */
    function _getMinimum()
    {
        return 0;
    }

    /**
     * Gets the maximum value the axis will show.
     * This is always the number of labels passed to the constructor.
     * @return double The maximum value
     * @access private
     */
    function _getMaximum()
    {
        return count($this->_labels);
    }

    /**
     * Sets the minimum value the axis will show.
     * A minimum cannot be set on a SequentialAxis, it is always 0.
     * @param double Minimum The minumum value to use on the axis
     * @access private
     */
    function _setMinimum($minimum)
    {
    }

    /**
     * Sets the maximum value the axis will show
     * A maximum cannot be set on a SequentialAxis, it is always the number
     * of labels passed to the constructor.
     * @param double Maximum The maximum value to use on the axis
     * @access private
     */
    function _setMaximum($maximum)
    {
    }
    
    /**
     * Forces the minimum value of the axis
     * A minimum cannot be set on a SequentialAxis, it is always 0.
     * @param double $minimum The minumum value to use on the axis
     */
    function forceMinimum($minimum)
    {
    }

    /**
     * Forces the maximum value of the axis
     * A maximum cannot be set on a SequentialAxis, it is always the number
     * of labels passed to the constructor.
     * @param double $maximum The maximum value to use on the axis
     */
    function forceMaximum($maximum)
    {
    }

    /**
     * Sets an interval for when labels are shown on the axis.
     * The label interval cannot be set for a SequentialAxis
     * @param double $labelInterval The interval with which labels are shown
     */
    function setLabelInterval($labelInterval = IMAGE_GRAPH_AXIS_INTERVAL_AUTO)
    {
    }

    /**
     * Preprocessor for values, ie for using logarithmic axis
     * @param double $value The value to preprocess
     * @return double The preprocessed value
     * @access private
     */
    function _value($value)
    {
        return $value + 0.5;
    }

    /**
     * Get the interval with which labels are shown on the axis.
     * This is always 1.
     * @return double The label interval
     * @access private
     */
    function _labelInterval()
    {
        return 1;
    }

    /**
     * Get the minor label interval with which axis label ticks are drawn.
     * For a sequential axis this is always disabled (i.e false)
     * @return double The minor label interval, always false
     * @access private
     */
    function _minorLabelInterval()
    {
        return false;
    }
    
    /** 
     * Get the size in pixels of the axis.
     * For an x-axis this is the width of the axis including labels, and for an
     * y-axis it is the corrresponding height
     * @return int The size of the axis
     * @access private 
     */
     function _size()
     {
        $this->_debug("Getting axis 'size'");
        if (!$this->_font) {
            $this->_debug("Defaulting font");
            $this->_font = $GLOBALS['_Image_Graph_font'];
        }

        $maxSize = 0;

        $value = $this->_getMinimum();

        reset($this->_labels);

        $this->_debug("Enumerating values from $value to ".$this->_getMaximum());
        while ($value < $this->_getMaximum()) {
	       $labelPosition = $this->_point($value);
                
            list($index, $realValue) = each($this->_labels);

            if (is_object($this->_dataPreProcessor)) {
                $labelText = $this->_dataPreProcessor->_process($realValue);
            } else {
                $labelText = $realValue;
            }

            if ($this->_type == IMAGE_GRAPH_AXIS_Y) {
                $maxSize = max($maxSize, $this->_font->width($labelText));
            } else {
                $maxSize = max($maxSize, $this->_font->height($labelText));
            }

            $value++;
        }
        $this->_debug("Done getting size = ".$maxSize +3);
        return $maxSize +3;
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

        $value = $this->_getMinimum();
        
        $this->_debug("Enumerating axis labels");
        while ($value < $this->_getMaximum()) {
            $labelPosition = $this->_point($value);
                
            $realValue = $this->_labels[$value];

            if (is_object($this->_dataPreProcessor)) {
                $labelText = $this->_dataPreProcessor->_process($realValue);
            } else {
                $labelText = $realValue;
            }

            if ($this->_type == IMAGE_GRAPH_AXIS_Y) {
                $text = new Image_Graph_Text($this->_right - 3, $labelPosition, $labelText, $this->_font);
                $text->setAlignment(IMAGE_GRAPH_ALIGN_CENTER_Y | IMAGE_GRAPH_ALIGN_RIGHT);
            } else {
                $text = new Image_Graph_Text($labelPosition, $this->_top + 3, $labelText, $this->_font);
                $text->setAlignment(IMAGE_GRAPH_ALIGN_CENTER_X | IMAGE_GRAPH_ALIGN_TOP);
            }

            $this->add($text);
            $text->_done();

            if ($this->_type == IMAGE_GRAPH_AXIS_Y) {
                ImageLine($this->_canvas(), $this->_right, $labelPosition, $this->_right + 6, $labelPosition, $this->_getLineStyle());
            } else {
                ImageLine($this->_canvas(), $labelPosition, $this->_top, $labelPosition, $this->_top - 6, $this->_getLineStyle());
            }

            $value++;
        }

        if ($this->_type == IMAGE_GRAPH_AXIS_Y) {
            ImageLine($this->_canvas(), $this->_right, $this->_top, $this->_right, $this->_bottom, $this->_getLineStyle());
            if ($this->_showArrow) {
                $arrow[] = $this->_right - 5;
                $arrow[] = $this->_top + 8;
                $arrow[] = $this->_right;
                $arrow[] = $this->_top;
                $arrow[] = $this->_right + 5;
                $arrow[] = $this->_top + 8;
                ImageFilledPolygon($this->_canvas(), $arrow, count($arrow) / 2, $this->_getFillStyle());
                ImagePolygon($this->_canvas(), $arrow, count($arrow) / 2, $this->_getLineStyle());
            }
        } else {
            ImageLine($this->_canvas(), $this->_left, $this->_top, $this->_right, $this->_top, $this->_getLineStyle());
            if ($this->_showArrow) {
                $arrow[] = $this->_right - 8;
                $arrow[] = $this->_top + 5;
                $arrow[] = $this->_right;
                $arrow[] = $this->_top;
                $arrow[] = $this->_right - 8;
                $arrow[] = $this->_top - 5;
                ImageFilledPolygon($this->_canvas(), $arrow, count($arrow) / 2, $this->_getFillStyle());
                ImagePolygon($this->_canvas(), $arrow, count($arrow) / 2, $this->_getLineStyle());
            }
        }
    }

}

?>
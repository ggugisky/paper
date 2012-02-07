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
 * @version $Id: Logarithmic.php,v 1.5 2004/11/05 19:13:30 nosey Exp $
 */ 

/**
 * Include file Graph/Axis.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/Axis.php");

/**
 * Diplays a logarithmic axis (either X- or Y-axis). 
 */
class Image_Graph_Axis_Logarithmic extends Image_Graph_Axis 
{
   
    /**
     * Image_Graph_AxisLogarithmic [Constructor].
     * Normally a manual creation should not be necessary, axis are created automatically
     * by the {@see Image_Graph_Plotarea} constructor unless explicitly defined otherwise
     * @param int $type The type (direction) of the Axis, use IMAGE_GRAPH_AXIS_X for an X-axis (default, may be omitted) and IMAGE_GRAPH_AXIS_Y for Y-axis)
     */
    function &Image_Graph_Axis_Logarithmic($type = IMAGE_GRAPH_AXIS_X)
    {
        parent::Image_Graph_Axis($type);
        $this->showLabel(IMAGE_GRAPH_LABEL_MINIMUM+IMAGE_GRAPH_LABEL_MAXIMUM);               
    }
    
    /**
     * Axis value span     
     * @return double The span of the axis (i.e. Max-Min)
     * @access private
     */
    function _axisValueSpan()
    {
        return $this->_value($this->_axisSpan());
    }

    /**
     * Axis span     
     * @return double The span of the axis (i.e. Max-Min)
     * @access private
     */
    function _axisSpan()
    {
        return $this->_getMaximum();
    }

    /**
     * Forces the minimum value of the axis.
     * For an logarithimc axis this is always 0     
     * @param double $minimum The minumum value to use on the axis
     */
    function forceMinimum($minimum)
    {
        $this->_debug("Forcing minimum = 0 (overriding $minimum)");
        $this->_minimum = 0;
        $this->setLabelInterval($this->_calcLabelInterval());        
    }
    
    /**
     * Gets the minimum value the axis will show.
     * For an logarithimc axis this is always 0     
     * @return double The minumum value
     * @access private
     */
    function _getMinimum()
    {
        return 1;
    }

    /**
     * Preprocessor for values, ie for using logarithmic axis
     * @param double $value The value to preprocess
     * @return double The preprocessed value
     * @access private
     */
    function _value($value)
    {
        return log10($value);
    }

    /**
     * Get next label point
     * @param doubt $point The current point, if omitted or false, the first is returned
     * @return double The next label point
     * @access private
     */
    function _getNextLabel($currentLabel = false)
    {
        if ($currentLabel === false) {            
            return 1;
        } else {
            $value = log10($currentLabel);
            $base = floor($value);
            $frac = $value - $base;        
            //print "$currentLabel - $value - $base - $frac - " . pow(10, $base) . "<br>";
            for ($i = 2; $i<10; $i++) {
                if ($frac <= (log10($i)-0.01)) {
                    return pow(10, $base)*$i;
                }
            }
/*            if ($frac < 0.30) {
                return pow(10, $base)*2;
            }
            elseif ($frac < 0.47) {
                return pow(10, $base)*3;
            }
            elseif ($frac < 0.60) {
                return pow(10, $base)*4;
            }
            elseif ($frac < 0.69) {
                return pow(10, $base)*5;
            }
            elseif ($frac < 0.84) {
                return pow(10, $base)*7;
            }
            elseif ($frac < 0.90) {
                return pow(10, $base)*8;
            }
            elseif ($frac < 0.95) {
                return pow(10, $base)*9;
            } else {
                return pow(10, $base+1);
            }*/            
            return pow(10, $base+1);
        }
    }
     
}

?>
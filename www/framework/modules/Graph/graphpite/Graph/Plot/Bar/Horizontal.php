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
 * @version $Id: Horizontal.php,v 1.5 2004/11/05 19:13:29 nosey Exp $
 */ 

/**
 * Include file Graph/Plot/Bar.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/Plot/Bar.php");

/**
 * Horizontal barchart.
 * This is done by switching X and Y values!
 */
class Image_Graph_Plot_Bar_Horizontal extends Image_Graph_Plot_Bar 
{

    /**
     * Get the minimum X value from the dataset - this value is basically the minimum Y-value
     * @return double The minimum X value
     * @access private
     */
    function _minimumX()
    {
        return parent::_minimumY();
    }

    /**
     * Get the maximum X value from the dataset - this value is basically the maximum Y-value
     * @return double The maximum X value
     * @access private
     */
    function _maximumX()
    {
        return parent::_maximumY();
    }

    /**
     * Get the minimum Y value from the dataset - this value is basically the minimum X-value
     * @return double The minimum Y value
     * @access private
     */
    function _minimumY()
    {
        return parent::_minimumX();
    }

    /**
     * Get the maximum Y value from the dataset - this value is basically the maximum X-value
     * @return double The maximum Y value
     * @access private
     */
    function _maximumY()
    {
        return parent::_maximumX();
    }

    /**
     * Output the plot
     * @access private
     */
    function _done()
    {
        Image_Graph_Plot::_done();
        if ($this->_dataset) {
            if (!$this->_xValueWidth) {
                $height = ($this->height() / ($this->_dataset->count() + 2)) / 2;
            }

            $this->_dataset->_reset();
            while ($point = $this->_dataset->_next()) {
                $pX = $point['Y'];
                $pY = $point['X'];
                $point['X'] = $pX;
                $point['Y'] = $pY;
                if (!$this->_xValueWidth) {
                    $y1 = $this->_parent->_pointY($point) - $height + $this->_space;
                    $y2 = $this->_parent->_pointY($point) + $height - $this->_space;
                } else {
                    $y1 = $this->_parent->_pointY($point['Y'] - $this->_xValueWidth / 2) + $this->_space;
                    $y2 = $this->_parent->_pointY($point['Y'] + $this->_xValueWidth / 2) - $this->_space;
                }
                $x1 = $this->_parent->_pointX(0);
                $x2 = $this->_parent->_pointX($point);
                ImageFilledRectangle($this->_canvas(), min($x1, $x2), min($y1, $y2), max($x1, $x2), max($y1, $y2), $this->_getFillStyle());
                ImageRectangle($this->_canvas(), min($x1, $x2), min($y1, $y2), max($x1, $x2), max($y1, $y2), $this->_getLineStyle());
            }
            $this->_drawMarker();
        }
    }
}

?>
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
 * @version $Id: Step.php,v 1.6 2004/11/05 19:13:28 nosey Exp $
 */ 

/**
 * Include file Graph/Plot/Bar.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/Plot/Bar.php");

/**
 * Stepchart
 */
class Image_Graph_Plot_Step extends Image_Graph_Plot_Bar 
{

    /**
     * Output the plot
     * @access private
     */
    function _done()
    {
        Image_Graph_Plot::_done();
        if ($this->_dataset) {
            if (!$this->_xValueWidth) {
                $width = ($this->width() / ($this->_dataset->count() + 2)) / 2;
            }

            $point['X'] = $this->_maximumX();
            $point['Y'] = 0;

            $polygon[] = $this->_parent->_pointX($point) - $width;
            $polygon[] = $this->_parent->_pointY($point);

            $point['X'] = $this->_minimumX();
            $point['Y'] = 0;
            
//            $polygon[] = ($x = $this->_parent->_pointX($point) - $width);
//            $polygon[] = ($y = $this->_parent->_pointY($point));            
            $y = $this->_parent->_pointY($point);

            $this->_dataset->_reset();
            while ($point = $this->_dataset->_next()) {
                $polygon[] = ($x = $this->_parent->_pointX($point) - $width);
                $polygon[] = $y;
                $polygon[] = ($x = $this->_parent->_pointX($point) - $width);
                $polygon[] = ($y = $this->_parent->_pointY($point));
                $lastY = $point['Y'];
            }

            $point['X'] = $this->_maximumX();
            $point['Y'] = $lastY;
            $polygon[] = $this->_parent->_pointX($point)-$width;
            $polygon[] = $this->_parent->_pointY($point);

            ImageFilledPolygon($this->_canvas(), $polygon, count($polygon)/2, $this->_getFillStyle());
            ImagePolygon($this->_canvas(), $polygon, count($polygon)/2, $this->_getLineStyle());
            $this->_drawMarker();
        }
    }
}

?>
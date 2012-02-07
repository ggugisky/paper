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
 * @package grid
 * @copyright Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license http://www.gnu.org/licenses/lgpl.txt GNU Lesser General Public License
 * @author Jesper Veggerby Hansen <pear.nosey@veggerby.dk>
 * @version $Id: Bars.php,v 1.6 2004/11/05 19:13:30 nosey Exp $
 */ 

/**
 * Include file Graph/Grid.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/Grid.php");

/**
 * Display alternating bars on the plotarea.
 * {@see Image_Graph_Grid} 
 */
class Image_Graph_Grid_Bars extends Image_Graph_Grid 
{

    /**
     * Output the grid
     * @access private      
     */
    function _done()
    {
        parent::_done();

        if (!$this->_primaryAxis) {
            return false;
        }

        $i = 0;
        $value = $this->_primaryAxis->_getNextLabel();

        $secondaryPoints = $this->_getSecondaryAxisPoints();

        while ($value <= $this->_primaryAxis->_getMaximum()) {
            if (($value > $this->_primaryAxis->_getMinimum()) and ($i == 1)) {
                reset($secondaryPoints);
                list ($id, $previousSecondaryValue) = each($secondaryPoints);
                while (list ($id, $secondaryValue) = each($secondaryPoints)) {
                    if ($this->_primaryAxis->_type == IMAGE_GRAPH_AXIS_Y) {
                        $p1 = array ('X' => $secondaryValue, 'Y' => $value);
                        $p2 = array ('X' => $previousSecondaryValue, 'Y' => $value);
                        $p3 = array ('X' => $previousSecondaryValue, 'Y' => $previousValue);
                        $p4 = array ('X' => $secondaryValue, 'Y' => $previousValue);
                    } else {
                        $p1 = array ('Y' => $secondaryValue, 'X' => $value);
                        $p2 = array ('Y' => $previousSecondaryValue, 'X' => $value);
                        $p3 = array ('Y' => $previousSecondaryValue, 'X' => $previousValue);
                        $p4 = array ('Y' => $secondaryValue, 'X' => $previousValue);
                    }

                    $polygon[] = $this->_parent->_pointX($p1);
                    $polygon[] = $this->_parent->_pointY($p1);
                    $polygon[] = $this->_parent->_pointX($p2);
                    $polygon[] = $this->_parent->_pointY($p2);
                    $polygon[] = $this->_parent->_pointX($p3);
                    $polygon[] = $this->_parent->_pointY($p3);
                    $polygon[] = $this->_parent->_pointX($p4);
                    $polygon[] = $this->_parent->_pointY($p4);

                    $previousSecondaryValue = $secondaryValue;

                    ImageFilledPolygon($this->_canvas(), $polygon, 4, $this->_getFillStyle());
                    unset ($polygon);
                }
            }
            $i = 1 - $i;
            $previousValue = $value;
            $value = $this->_primaryAxis->_getNextLabel($value);
        }
    }

}

?>
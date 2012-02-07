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
 * @version $Id: Impulse.php,v 1.5 2004/11/05 19:13:29 nosey Exp $
 */ 

/**
 * Include file Graph/Plot/Line.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/Plot/Line.php");

/**
 * Impulse chart
 */
class Image_Graph_Plot_Impulse extends Image_Graph_Plot_Line 
{

    /**
     * Output the plot
     * @access private
     */
    function _done()
    {
        Image_Graph_Plot::_done();

        $this->_dataset->_reset();
        while ($point = $this->_dataset->_next()) {
            $p2['X'] = $this->_parent->_pointX($point);
            $p2['Y'] = $this->_parent->_pointY($point);
            
            $point['Y'] = 0;
            $p1['X'] = $this->_parent->_pointX($point);
            $p1['Y'] = $this->_parent->_pointY($point);

            ImageLine($this->_canvas(), $p1['X'], $p1['Y'], $p2['X'], $p2['Y'], $this->_getLineStyle());
        }
        $this->_drawMarker();
    }

}

?>
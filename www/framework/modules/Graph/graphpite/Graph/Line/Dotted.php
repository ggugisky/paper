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
 * @package linestyle
 * @copyright Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license http://www.gnu.org/licenses/lgpl.txt GNU Lesser General Public License
 * @author Jesper Veggerby Hansen <pear.nosey@veggerby.dk>
 * @version $Id: Dotted.php,v 1.6 2004/11/05 19:13:30 nosey Exp $
 */ 

/**
 * Include file Graph/Line/Formatted.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/Line/Formatted.php");

/**
 * Dotted line style.
 * This style displays as a short line with a shorter space afterwards, i.e 
 * 1px color1, 1px color2, 1px color1, etc. 
 */
class Image_Graph_Line_Dotted extends Image_Graph_Line_Formatted 
{

    /**
     * DottedLine [Constructor]
     * @param int $color1 The color representing the dots 
     * @param int $color2 The color representing the spaces 
     */
    function &Image_Graph_Line_Dotted($color1, $color2)
    {
        parent::Image_Graph_Line_Formatted(array ($color1, $color2));
    }

}

?>
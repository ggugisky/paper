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
 * @package datapreprocessor
 * @copyright Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license http://www.gnu.org/licenses/lgpl.txt GNU Lesser General Public License
 * @author Jesper Veggerby Hansen <pear.nosey@veggerby.dk>
 * @version $Id: RomanNumerals.php,v 1.5 2004/11/05 19:13:28 nosey Exp $
 */ 

/**
 * Include file Graph/DataPreprocessor.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/DataPreprocessor.php");

/**
 * Formatting a value as a roman numerals.
 * Values are formatted as roman numeral, i.e. 1 = I, 2 = II, 9 = IX, 2004 = MMIV.
 */
class Image_Graph_DataPreprocessor_RomanNumerals extends Image_Graph_DataPreprocessor 
{

    /** Create a RomanNumerals preprocessor
     */
    function &Image_Graph_DataPreprocessor_RomanNumerals()
    {
        parent::Image_Graph_DataPreprocessor();
    }

    /**
     * Process the value
     * @param var $value The value to process/format
     * @return string The processed value
     * @access private
	 */
    function _process($value)
    {
        $result = "";
        $numbers = array (1, 4, 5, 9, 10, 40, 50, 90, 100, 400, 500, 900, 1000);
        $romans = array ("I", "IV", "V", "IX", "X", "XL", "L", "XC", "C", "CD", "D", "CM", "M");
        for ($i = 12; $i >= 0; $i --) {
            while ($value >= $numbers[$i]) {
                $value -= $numbers[$i];
                $result .= $romans[$i];
            }
        }
        return trim($result);
    }

}

?>
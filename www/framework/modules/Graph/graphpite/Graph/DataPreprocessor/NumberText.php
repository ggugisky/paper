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
 * @version $Id: NumberText.php,v 1.5 2004/11/05 19:13:28 nosey Exp $
 */ 

/**
 * Include file Graph/DataPreprocessor.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/DataPreprocessor.php");

/**
 * Formatting a number as its written in english.
 * Used to display values as text, i.e. 123 is displayed as one hundred and twenty three.
 */
class Image_Graph_DataPreprocessor_NumberText extends Image_Graph_DataPreprocessor 
{

    /**
     * Image_Graph_NumberText [Constructor]
     */
    function &Image_Graph_DataPreprocessor_NumberText()
    {
        parent::Image_Graph_DataPreprocessor();
    }

    /**
     * Get the "name" of the number
     * @param int $i The number to return the name of
     * @return string Name of the number
     * @access private
     */
    function _simpleName($i)
    {
        switch ($i) {
            case 1 :
                return "one";

            case 2 :
                return "two";

            case 3 :
                return "three";

            case 4 :
                return "four";

            case 5 :
                return "five";

            case 6 :
                return "six";

            case 7 :
                return "seven";

            case 8 :
                return "eight";

            case 9 :
                return "nine";

            case 10 :
                return "ten";

            case 11 :
                return "eleven";

            case 12 :
                return "twelve";

            case 13 :
                return "thirteen";

            case 14 :
                return "fourteen";

            case 15 :
                return "fifteen";

            case 16 :
                return "sixteen";

            case 17 :
                return "seventeen";

            case 18 :
                return "eighteen";

            case 19 :
                return "nineteen";

            case 20 :
                return "twenty";

            case 30 :
                return "thirty";

            case 40 :
                return "fourty";

            case 50 :
                return "fifty";

            case 60 :
                return "sixty";

            case 70 :
                return "seventy";

            case 80 :
                return "eighty";

            case 90 :
                return "ninety";
        }
        return false;
    }

    /**
     * Get the combined "name" of the number
     * @param int $number The number to return the name of
     * @param bool $original Specidies whether the number is the "original" number or if it is parts there of
     * @return string Name of the number
     * @access private
     */
     function _complexName($number, $original = false)
     {
        $and = "";
        
        if (!$original) {
            $original = $number;
        }

        if ($original == 0) {
            return "zero";
        }

        if ($number >= 1000000000) {
            return $this->_complexName(floor($number / 1000000000))." billion ".$this->_complexName($number % 1000000000, $original);
        }

        if ($number >= 1000000) {
            return $this->_complexName(floor($number / 1000000))." million ".$this->_complexName($number % 1000000, $original);
        }

        if ($number >= 1000) {
            return $this->_complexName(floor($number / 1000))." thousand ".$this->_complexName($number % 1000, $original);
        }

        if ($number >= 100) {
            return $this->_simpleName(floor($number / 100))." hundred ".$this->_complexName($number % 100, $original);
        }

        if ($original > 100) {
            $and = "and ";
        }

        if ($number >= 10) {
            if ($num = $this->_simpleName($number)) {
                return $and.$num;
            } else {
                return $and.$this->_simpleName(floor($number / 10) * 10)." ".$this->_simpleName($number % 10);
            }
        }

        if ($num = $this->_simpleName($number)) {
            return "$and$num";
        } else {
            return "";
        }
    }

    /**
     * Process the value
     * @param var $value The value to process/format
     * @return string The processed value
     * @access private
	 */
    function _process($value)
    {
        return trim($this->_complexName($value));
    }

}

?>
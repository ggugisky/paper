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
 * @package layout
 * @copyright Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license http://www.gnu.org/licenses/lgpl.txt GNU Lesser General Public License
 * @author Jesper Veggerby Hansen <pear.nosey@veggerby.dk>
 * @version $Id: Matrix.php,v 1.1 2004/11/05 19:18:16 nosey Exp $
 */ 

/**
 * Include file Graph/Layout.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/Layout.php");

/**
 * Layout for displaying elements in a matix. 
 */
class Image_Graph_Layout_Matrix extends Image_Graph_Layout 
{

    /**
     * Layout matrix
     * @var array
     * @access private
     */
    var $_matrix = false;
    
    /**
     * The number of rows
     * @var int
     * @access private
     */
    var $_rows = false;
    
    /**
     * The number of columns
     * @var int
     * @access private
     */
    var $_cols = false;
    
    /**
     * Image_Graph_Layout_Matrix [Constructor]
     * @param int $rows The number of rows
     * @param int $cols The number of cols
     * @param bool $autoCreate Specifies whether the matrix should automatically
     * be filled with newly created Image_Graph_Plotares objects, or they will
     * be added manually
     */
    function &Image_Graph_Layout_Matrix($rows, $cols, $autoCreate = true)
    {
        parent::Image_Graph_Layout();
        
        $this->_rows = $rows;
        $this->_cols = $cols;
        if (($this->_rows > 0) and ($this->_cols > 0)) {                   
            $this->_matrix = array(array());
            for ($i = 0; $i < $this->_rows; $i++) {
                for ($j = 0; $j < $this->_cols; $j++) {
                    if ($autoCreate) {
                        $this->_matrix[$i][$j] =& $this->add(new Image_Graph_Plotarea());
                        $this->_pushEdges($i, $j);
                    } else {
                        $this->_matrix[$i][$j] = false;
                    }
                }
            }
        }        
    }
    
    /**
     * Pushes the edges on the specified position in the matrix
     * @param int $row The row
     * @param int $col The column
     */
    function _pushEdges($row, $col) {
        if ((isset($this->_matrix[$row])) and (isset($this->_matrix[$row][$col]))) {
            $height = 100/$this->_rows;
            $width = 100/$this->_cols;

            if ($col > 0) {                
                $this->_matrix[$row][$col]->_push(IMAGE_GRAPH_AREA_LEFT, round($col*$width) . '%');
            }
            if ($col+1 < $this->_cols) {                       
                $this->_matrix[$row][$col]->_push(IMAGE_GRAPH_AREA_RIGHT, round(100-($col+1)*$width) . '%');
            }
            if ($row > 0) {                       
                $this->_matrix[$row][$col]->_push(IMAGE_GRAPH_AREA_TOP, round($row*$height) . '%');
            }
            if ($row+1 < $this->_rows) {                       
                $this->_matrix[$row][$col]->_push(IMAGE_GRAPH_AREA_BOTTOM, round(100-($row+1)*$height) . '%');
            }
        }
    }
    
    /**
     * Get the area on the specified position in the matrix
     * @param int $row The row
     * @param int $col The column
     * @return Image_Graph_Layout The element of position ($row, $col) in the
     * matrix
     */
    function &getEntry($row, $col) {
        if ((isset($this->_matrix[$row])) and (isset($this->_matrix[$row][$col]))) {
            return $this->_matrix[$row][$col];
        } else {
            return false;
        }
    }

    /**
     * Get the area on the specified position in the matrix
     * @param int $row The row
     * @param int $col The column
     * @param Image_Graph_Layout $element The element to set in the position
     * ($row, $col) in the matrix
     */
    function setEntry($row, $col, $element) {
        $this->_matrix[$row][$col] =& $element;
        $this->_pushEdges($row, $col);
    }

    /**
     * Update coordinates
     * @access private
     */
    function _updateCoords()
    {
        for ($i = 0; $i < $this->_rows; $i++) {
            for ($j = 0; $j < $this->_cols; $j++) {
                $element =& $this->getEntry($i, $j);
                $this->add($element);
            }
        }        
        parent::_updateCoords();
    }

    /**
     * Output the layout to the canvas
     * @access private
     */
    function _done()
    {
        for ($i = 0; $i < $this->_rows; $i++) {
            for ($j = 0; $j < $this->_cols; $j++) {
                $element =& $this->getEntry($i, $j);
                if ($element) {
                    $element->_done();
                }
            }
        }
    }

}

?>
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
 * @package plotarea
 * @copyright Copyright (C) 2003, 2004 Jesper Veggerby Hansen
 * @license http://www.gnu.org/licenses/lgpl.txt GNU Lesser General Public License
 * @author Jesper Veggerby Hansen <pear.nosey@veggerby.dk>
 * @version $Id: Map.php,v 1.5 2004/11/05 19:13:30 nosey Exp $
 */ 

/**
 * Include file Graph/Plotarea.php
 */
require_once(IMAGE_GRAPH_PATH . "/Graph/Plotarea.php");

/**
 * Plot area used for map plots.
 * A map plot is a chart that displays a map (fx. a world map) in the form of .png file.
 * The maps must be located in the /Images/Maps folder and a corresponding .txt files mush
 * also exist in this location where named locations are mapped to an (x, y) coordinate of
 * the map picture (this text file is tab separated with "Name" "X" "Y" values, fx 
 * "Denmark 378 223"). The x-values in the dataset are then the named locations (fx "Denmark")
 * and the y-values are then the data to plot. Currently the best (if not only) use is to
 * combine a map plot area with a {@see Image_Graph_Plot_Dot} using {@see Image_Graph_Marker_PercentageCircle} as
 * marker.
 */
class Image_Graph_Plotarea_Map extends Image_Graph_Plotarea 
{
    
    /**
     * The GD image for the map
     * @var resource
     * @access private
     */
    var $_imageMap;

    /**
     * The value for scaling the width and height to fit into the layout boundaries
     * @var int
     * @access private
     */
    var $_scale;

    /**
     * The (x,y)-points for the named point 
     * @var array
     * @access private
     */
    var $_mapPoints;
    
    /**
     * The original size of the image map
     * @var array
     * @access private
     */    
    var $_mapSize;

    /**
     * PlotareaMap [Constructor]
     * @param string $map The name of the map, i.e. the [name].png and [name].txt files located in the Images/maps folder     
     */
    function &Image_Graph_Plotarea_Map($map)
    {
        parent::Image_Graph_Plotarea(null, null);
        
        $this->_imageMap = ImageCreateFromPNG(dirname(__FILE__)."/../Images/maps/$map.png");
        $points = file(dirname(__FILE__)."/../Images/maps/$map.txt");
        
        if (is_array($points)) {
            unset($this->_mapPoints);
            while (list($id, $line) = each($points)) {
                list($country, $x, $y) = explode("\t", $line);                
                $this->_mapPoints[$country] = array('X' => $x, 'Y' => $y);
            }
        }                
        
        $this->_mapSize['X'] = ImageSX($this->_imageMap);
        $this->_mapSize['Y'] = ImageSY($this->_imageMap);
    }

    /**
     * Add a X-axis grid to the plotarea	 
     * @param Grid $grid The grid to add
     * @see Image_Graph_Common::add() 
     */
    function &addGridX(& $grid)
    {
        return $grid;
    }

    /**
     * Add a Y-axis grid to the plotarea	 
     * @param Grid $grid The grid to add
     * @see Image_Graph_Common::add() 
     */
    function &addGridY(& $grid)
    {
        return $grid;
    }        

    /**
     * Left boundary of the background fill area 
     * @return int Leftmost position on the canvas
     * @access private
     */
    function _fillLeft()
    {
        return $this->_left + $this->_padding; 
    }

    /**
     * Top boundary of the background fill area 
     * @return int Topmost position on the canvas
     * @access private
     */
    function _fillTop()
    {
        return $this->_top + $this->_padding; 
    }

    /**
     * Right boundary of the background fill area 
     * @return int Rightmost position on the canvas
     * @access private
     */
    function _fillRight()
    {
        return $this->_right - $this->_padding; 
    }

    /**
     * Bottom boundary of the background fill area 
     * @return int Bottommost position on the canvas
     * @access private
     */
    function _fillBottom()
    {
        return $this->_bottom - $this->_padding; 
    }

    /**
     * Set the extrema of the axis	 
     * @param double MinimumX The minimum X value 
     * @param double MaximumX The maximum X value 
     * @param double MinimumY The minimum Y value 
     * @param double MaximumY The maximum Y value 
     * @access private
     */
    function _setExtrema($minimumX, $maximumX, $minimumY = 0, $maximumY = 0)
    {
    }
    
    /**
     * Get the X pixel position represented by a value
     * @param double Value the value to get the pixel-point for	 
     * @return double The pixel position along the axis
     * @access private
     */
    function _pointX($value)
    {
        $country = $value['X'];        
        return $this->_plotLeft+$this->_mapPoints[$country]['X']*$this->_scale;
    }

    /**
     * Get the Y pixel position represented by a value
     * @param double Value the value to get the pixel-point for	 
     * @return double The pixel position along the axis
     * @access private
     */
    function _pointY($value)
    {
        $country = $value['X'];
        return $this->_plotTop+$this->_mapPoints[$country]['Y']*$this->_scale;        
    }

    /** 
     * Hides the axis
     */
    function hideAxis()
    {
    }
    
    /**
     * Add a point to the maps
     * @param int $latitude The latitude of the point
     * @param int $longiude The longitude of the point
     * @param string $name The name of the plot
     */
    function addMappoint($latitude, $longitude, $name)
    {
        $x = (($longitude + 180) * ($this->_mapSize['X'] / 360));
        $y = ((($latitude * -1) + 90) * ($this->_mapSize['Y'] / 180));
        $this->_mapPoints[$name] = array('X' => $x, 'Y' => $y);
    }    
    
    /**
     * Add a point to the maps
     * @param int $x The latitude of the point
     * @param int $y The longitude of the point
     * @param string $name The name of the plot
     */
    function addPoint($x, $y, $name)
    {
        $this->_mapPoints[$name] = array('X' => $x, 'Y' => $y);
    }
    
    /**
     * Update coordinates
     * @access private
     */
    function _updateCoords()
    {
        parent::_updateCoords();

        $mapAspectRatio = $this->_mapSize['X']/$this->_mapSize['Y'];
        $plotAspectRatio = ($width = $this->_fillWidth())/($height = $this->_fillHeight());
        
        $scaleFactorX = ($mapAspectRatio > $plotAspectRatio);
        
        if ((($this->_mapSize['X'] <= $width) and ($this->_mapSize['Y'] <= $height)) or
            (($this->_mapSize['X'] >= $width) and ($this->_mapSize['Y'] >= $height))) {
            if ($scaleFactorX) {
                $this->_scale = $width / $this->_mapSize['X'];
            } else {
                $this->_scale = $height / $this->_mapSize['Y'];
            }
        } 
        elseif ($this->_mapSize['X'] < $width) {
            $this->_scale = $height / $this->_mapSize['Y'];
        }
        elseif ($this->_mapSize['Y'] < $height) {
            $this->_scale = $width / $this->_mapSize['X'];
        }        
    
        $this->_plotLeft = ($this->_fillLeft() + $this->_fillRight() - $this->_mapSize['X']*$this->_scale)/2;
        $this->_plotTop = ($this->_fillTop() + $this->_fillBottom() - $this->_mapSize['Y']*$this->_scale)/2;
        $this->_plotRight = ($this->_fillLeft() + $this->_fillRight() + $this->_mapSize['X']*$this->_scale)/2;
        $this->_plotBottom = ($this->_fillTop() + $this->_fillBottom() + $this->_mapSize['Y']*$this->_scale)/2;
    }
    
    /**
     * Output the plotarea to the canvas
     * @access private
     */
    function _done()
    {
        if ($this->_fillStyle) {
            ImageFilledRectangle($this->_canvas(), $this->_fillLeft(), $this->_fillTop(), $this->_fillRight(), $this->_fillBottom(), $this->_getFillStyle());
        }

        $scaledWidth = $this->_mapSize['X']*$this->_scale;
        $scaledHeight = $this->_mapSize['Y']*$this->_scale;               
        
        if (isset($GLOBALS['_Image_Graph_gd2'])) {            
            ImageCopyResampled($this->_canvas(), $this->_imageMap, $this->_plotLeft, $this->_plotTop, 0, 0, $scaledWidth, $scaledHeight, $this->_mapSize['X'], $this->_mapSize['Y']);
        } else {
            ImageCopyResized($this->_canvas(), $this->_imageMap, $this->_plotLeft, $this->_plotTop, 0, 0, $scaledWidth, $scaledHeight, $this->_mapSize['X'], $this->_mapSize['Y']);
        }               

        Image_Graph_Layout::_done();

        if ($this->_plotBorderStyle) {
            ImageRectangle($this->_canvas(), $this->_fillLeft(), $this->_fillTop(), $this->_fillRight(), $this->_fillBottom(), $this->_plotBorderStyle->_getLineStyle());
        }
    }

}

?>
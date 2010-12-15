<?php
/**
* Table object provides table-functionality
*
* @author	Alexander Wirtz <wirtz@web-active.com>
* @package	pc4p
*/
class pc4p_table extends pc4p_object
{
	/**
	* Array for accessing the cells of the table
	*
	* @var		array	$cell
	* @access	private
	*/
	var $cell;

	/**
	* Array containing the sizes of each column
	*
	* @var		array	$colsize
	* @access	private
	*/
	var $colsize;

	/**
	* Array containing the sizes of each row
	*
	* @var		array	$rowsize
	* @access	private
	*/
	var $rowsize;

	/**
	* Array containing the names of each column
	*
	* @var		array	$colnames
	* @access	private
	*/
	var $colnames = array();

	/**
	* Array containing the names of each row
	*
	* @var		array	$rownames
	* @access	private
	*/
	var $rownames = array();

	/**
	* x-Pos used when calculating the positions of subobjects
	*
	* @var		integer	$draw_width
	* @access	private
	*/
	var $draw_width;

	/**
	* Constructor
	*
	* @param	object	pc4p_page	&$parent
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_table( &$parent )
	{
		pc4p_object::pc4p_object( $parent );
	}

	/**
	* Creates the table and sets the names for columns and rows
	* if provided, otherwise an index is used as name for each.
	*
	* @param	integer	$colsize
	* @param	integer	$rowsize
	* @param	array	$colnames
	* @param	array	$rownames
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_create_tablematrix( $colsize, $rowsize = 1, $colnames = array(), $rownames = array() )
	{
		// Check if columnnames are provided
		if( !empty( $colnames ) ) {
			// if yes, are there enough for all cols?
			if( sizeof( $colnames ) != $colsize )
				die("Error: ".get_class( $this )." - size of columnnames is not correct.");
		}
		else {
			// there are no names, so use an index
			for( $i = 0; $i < $colsize; $i++ )
				$colnames[] = $i;
		}
		// set the colnames for this object
		$this->colnames = $colnames;

		// Check if rownames are provided
		if( !empty( $rownames ) ) {
			// if yes, are there enough for all rows?
			if( sizeof( $rownames ) != $rowsize )
				die("Error: ".get_class( $this )." - size of rownames is not correct.");
		}
		else {
			// there are no names, so use an index
			for( $i = 0; $i < $rowsize; $i++ )
				$rownames[] = $i;
		}
		// set the colnames for this object
		$this->rownames = $rownames;

		// use this dummy as object for anchoring the cellobjects
		$dummy = new pc4p_object( $this );
		for( $c = 0; $c < $colsize; $c++ ) {
			// initialise the col as an array...
			$this->cell[ $colnames[ $c ] ] = array();
			for( $r = 0; $r < $rowsize; $r++ )
				// ...and create the cellobjects
				$this->cell[ $colnames[ $c ] ][ $rownames[ $r ] ] = &pc4p_create_object( $dummy );
		}
	}

	/**
	* Adds a row to the table
	*
	* @param	string	$rowname
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_add_tablerow( $rowname = "" ) {
		// check if rowname is provided
		if( empty( $rowname ) ) {
			// no, so use index
			$rowname = sizeof( $this->rownames );
		}
		// add rowname to object-rownames
		$this->rownames[] = $rowname;

		// use this dummy as object for anchoring the cellobject...
		$dummy = new pc4p_object( $this );
		for( $c = 0; $c < sizeof( $this->colnames ); $c++ ) {
			// ...and create new row using the dummy
			$this->cell[ $this->colnames[ $c ] ][ $rowname ] = &pc4p_create_object( $dummy );
		}
	}

	/**
	* Sets the width for the columns
	*
	* @param	array	$colsize
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_set_tablecolsize( $colsize )
	{
		$this->colsize = array();
		for( $i = 0; $i < sizeof( $colsize ); $i++ ) {
			if( ereg( "%", $colsize[ $i ] ) ) {
				// colsize is in percent
				$this->colsize[] = $colsize[ $i ];
			}
			else {
				//colsize is absolute
				$this->colsize[] = $colsize[ $i ]."#";
			}
		}
	}

	/**
	* Set the style of the tableborder
	*
	* @param	string	$style
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_set_tableborder( $style ) {
		$this->table_border = $style;
	}

	/**
	* Walks through the rows and cols and calls pc4p_draw in each cell;
	* draws the boxes around the cells
	*
	* @access	private
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_draw_children()
	{
		// get top coordinate for row
		$draw_y = $this->act_height;
		for( $r = 0; $r < sizeof( $this->rownames ); $r++ ) {
			// get left coordinate for box
			$draw_x = $this->act_width;
			for( $c = 0; $c < sizeof( $this->colnames ); $c++ ) {
				if( $this->table_border == "single" ) {
					// draw the box around the cell
					pdf_moveto( $this->pdfp, $draw_x, -$draw_y );
					pdf_lineto( $this->pdfp, $draw_x + $this->colsize[ $c ], -$draw_y );
					pdf_lineto( $this->pdfp, $draw_x + $this->colsize[ $c ], -( $draw_y + $this->rowsize[ $r ] ) );
					pdf_lineto( $this->pdfp, $draw_x, -( $draw_y + $this->rowsize[ $r ] ) );
					pdf_lineto( $this->pdfp, $draw_x, -$draw_y );
					pdf_closepath_stroke( $this-> pdfp );
				}
				// call pc4p_draw in the cell
				$this->cell[ $this->colnames[ $c ] ][ $this->rownames[ $r ] ]->pc4p_draw();
				// increase coordinate to move to left edge of the next cell
				$draw_x += $this->colsize[ $c ];
			}
			// increase coordinate to move to top edge of the next row
			$draw_y += $this->rowsize[ $r ];
		}
	}

	
	/**
	* Calculates the offsets in all cells, especially the x-coordinates!!!
	*
	* @param	object	pc4p_page	&$parent
	* @return	integer	$this->height
	* @access	private
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_calc_offset( &$parent )
	{
		// Check, if someone set the width externally
		// Absolute?
		if( ereg( "#", $this->width ) )
			// Yes, someone did. So erase the #
			$this->width = (int) ereg_replace( "#", "", $this->width );
		// Relative?
		elseif( ereg( "%", $this->width ) ) {
			// Yes, someone did. So erase the % and calculate the width
			$this->width = ereg_replace( "%", "", $this->width );
			$this->width = round( ( $parent->width - ( $parent->margin[ "left" ] + $parent->margin[ "right" ] ) ) * ( $this->width / 100.0 ) );
		}
		// Check, if width is set, and if yes, if the width exceeds max width defined by parent
		if( $this->width <= 0 || $this->width > $parent->width - ( $parent->margin[ "left" ] + $parent->margin[ "right" ] ) ) {
			// If-Clause is true, so set width to max width
			$this->width = $parent->width - ( $parent->margin[ "left" ] + $parent->margin[ "right" ] );
		}

		if( is_array( $this->colsize ) ) {
			// Calculate the widths accordingly to percentage if used, else
			// just use the absolute value
			for( $i = 0; $i < sizeof( $this->colsize ); $i++ ) {
				if( ereg( "#", $this->colsize[ $i ] ) ) {
					$val = (int) ereg_replace( "#", "", $this->colsize[ $i ] );
					$this->colsize[ $i ] = $val;
				} else {
					// percent-calculation still missing!!!
				}
			}
		}
		else {
			// No colsizes set, so divide the space evenly between all cols
			$this->colsize = array();
			$numcols = sizeof( $this->colnames );
			$cize = (int) ($this->width / $numcols);
			$cmod = $this->width % $numcols;
			for( $n = 0; $n < $numcols; $n++) {
				$this->colsize[ $n ] = $cize;
				if( $cmod > 0 )
					$this->colsize[ $n ]++;
				$cmod--;
			}
		}

		$this->height = $this->margin[ "top" ];

		// Calculate act_width accordingly to alignment
		if( $this->alignment == "center" )
			$this->act_width = $parent->act_width + $parent->margin[ "left" ] + ceil( ( $parent->width - ( $parent->margin[ "left" ] + $parent->margin[ "right" ] ) - $this->width ) / 2 );
		elseif( $this->alignment == "right" )
			$this->act_width = $parent->act_width + $parent->margin[ "left" ] + floor( $parent->width - ( $parent->margin[ "left" ] + $parent->margin[ "right" ] ) - $this->width );
		else
			$this->act_width = $parent->act_width + $parent->margin[ "left" ];

		$this->act_height = $parent->draw_height;
		$this->draw_height = $parent->draw_height + $this->height;
		// Calculate the heights in the cells and add them to our height
		// Set the widths for the cells
		for( $r = 0; $r < sizeof( $this->rownames ); $r++ ) {
			$row_height = 0;
			$this->draw_width = $this->act_width;
			for( $c = 0; $c < sizeof( $this->colnames ); $c++ ) {
				$this->cell[ $this->colnames[ $c ] ][ $this->rownames[ $r ] ]->pc4p_set_width( $this->colsize[ $c ] );
				$child_height = $this->cell[ $this->colnames[ $c ] ][ $this->rownames[ $r ] ]->pc4p_calc_offset( $this );
				$this->draw_width += $this->colsize[ $c ];
				if( $child_height > $row_height )
					$row_height = $child_height;
			}
			$this->rowsize[] = $row_height;
			$this->height += $row_height;
			$this->draw_height += $row_height;
		}
		$this->height += $this->margin[ "bottom" ];
		return $this->height;
	}
}
?>

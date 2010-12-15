<?php
/**
* The master object for content-objects
*
* @author	Alexander Wirtz <wirtz@web-active.com>
* @package	pc4p
*/
class pc4p_object
{
	/**
	* Array of all children of this object
	*
	* @var		array	$children
	* @access	private
	*/
	var $children = array();

	/**
	* PDF-Pointer used for all pdf_*
	*
	* @var		integer	$pdfp
	* @access	private
	*/
	var $pdfp;

	/**
	* Width of the object
	*
	* @var		integer	$width
	* @access	private
	*/
	var $width;

	/**
	* Height of the object
	*
	* @var		integer	$height
	* @access	private
	*/
	var $height;

	/**
	* Margins for the object
	*
	* @var		array	$margin
	* @access	private
	*/
	var $margin = array( "top" => 0, "bottom" => 0, "left" => 0, "right" => 0 );

	/**
	* x-Pos where the object is placed
	*
	* @var		integer	$act_width
	* @access	private
	*/
	var $act_width;

	/**
	* y-Pos where the object is placed
	*
	* @var		integer	$act_height
	* @access	private
	*/
	var $act_height;

	/**
	* y-Pos used when calculating the positions of subobjects
	*
	* @var		integer	$draw_height
	* @access	private
	*/
	var $draw_height;

	/**
	* Alignment for the object
	*
	* @var		string	$alignment
	* @access	private
	*/
	var $alignment = "left";

	/**
	* Text-Leading
	* @var		integer	$leading
	* @access	private
	*/
	var $leading;

	/**
	* Constructor
	*
	* @param	object	pc4p_page	&$parent
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_object( &$parent )
	{
		$accepted_classes = array( "pc4p_page", "pc4p_object", "pc4p_box" );

		// Checks, if the class has an allowed parent-Class
		if( in_array( get_class( $parent ), $accepted_classes ) ) {
			// If yes, get PDF-Pointer
			$this->pdfp = &$parent->pdfp;
		}
		elseif( get_class( $parent ) == "pc4p_table" && get_class( $this ) == "pc4p_object" ) {
			// Okay, we're called in a table as pc4p_object
			$this->pdfp = &$parent->pdfp;
		}
		else {
			die("Error: ".get_class( $this )." - ".get_class( $parent )." not allowed as parent");
		}
	}

	/**
	* Sets the margins for the object
	*
	* @param	array	$margin
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_set_margin( $margin )
	{
		$this->margin[ "top" ] = $margin[ "top" ];
		$this->margin[ "bottom" ] = $margin[ "bottom" ];
		$this->margin[ "left" ] = $margin[ "left" ];
		$this->margin[ "right" ] = $margin[ "right" ];
	}

	/**
	* Sets the width for the object
	*
	* @param	mixed	$width
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_set_width( $width )
	{
		if( ereg( "%", $width ) )
			$this->width = $width;
		else
			$this->width = $width."#";
	}

	/**
	* Sets the alignment for this object.
	*
	* @param	string	$alignment
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_set_alignment( $alignment = "left" )
	{
		$accepted_values = array( "left", "center", "right" );
		if( in_array( strtolower( $alignment ), $accepted_values ) )
			$this->alignment = $alignment;
		else
			die("Error: ".get_class( $this )." - no such alignment: ".$alignment);
	}

	/**
	* Sets the textleading for text-subobjects.
	*
	* @param	integer	$leading
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_set_textleading( $leading )
	{
		if( !empty( $leading ) );
			$this->leading = $leading;
	}

	/**
	* Calls the pc4p_draw_children
	*
	* @access	private
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_draw()
	{
		$this->pc4p_draw_children();
	}

	/**
	* Calls the draw function for each child in the children-array
	*
	* @access	private
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_draw_children()
	{
		for( $i = 0; $i < sizeof( $this->children ); $i++) {
			$this->children[$i]->pc4p_draw();
		}
	}

	/**
	* Calls the calc_offset function in all children, sets its own offsets
	*
	* @param	object	pc4p_page	&$parent
	* @return	integer		$this->height
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

		$this->height = $this->margin[ "top" ];

		// Calculate act_width accordingly to alignment
		// First check, if we are the member of a table
		if( get_class( $parent ) == "pc4p_table" )
			$draw_width = $parent->draw_width;
		else
			$draw_width = $parent->act_width;

		if( $this->alignment == "center" )
			$this->act_width = $draw_width + $parent->margin[ "left" ] + ceil( ( $parent->width - ( $parent->margin[ "left" ] + $parent->margin[ "right" ] ) - $this->width ) / 2 );
		elseif( $this->alignment == "right" )
			$this->act_width = $draw_width + $parent->margin[ "left" ] + floor( $parent->width - ( $parent->margin[ "left" ] + $parent->margin[ "right" ] ) - $this->width );
		else
			$this->act_width = $draw_width + $parent->margin[ "left" ];

		$this->act_height = $parent->draw_height;
		$this->draw_height = $parent->draw_height + $this->height;
		// Calculate the heights in the children and add them to our height
		for( $i = 0; $i < sizeof( $this->children ); $i++) {
			$child_height = $this->children[$i]->pc4p_calc_offset( $this );
			$this->height += $child_height;
			$this->draw_height += $child_height;
		}
		$this->height += $this->margin[ "bottom" ];
		return $this->height;
	}
}
?>

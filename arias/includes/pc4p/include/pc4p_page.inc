<?php
/**
* Supplies pagehandling ability
*
* @author	Alexander Wirtz <wirtz@web-active.com>
* @package	pc4p
*/
class pc4p_page
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
	* Width of the page
	*
	* @var		integer	$width
	* @access	private
	*/
	var $width;

	/**
	* Height of the page
	*
	* @var		integer	$height
	* @access	private
	*/
	var $height;

	/**
	* Margins for the page
	*
	* @var		array	$margin
	* @access	private
	*/
	var $margin = array( "top" => 5, "bottom" => 5, "left" => 5, "right" => 5 );

	/**
	* x-Pos where the page really starts placing objects
	*
	* @var		integer	$act_width
	* @access	private
	*/
	var $act_width;

	/**
	* y-Pos where the page really starts placing objects
	*
	* @var		integer	$act_height
	* @access	private
	*/
	var $act_height;

	/**
	* y-Pos used when calculating the positions for the objects
	*
	* @var		integer	$draw_height
	* @access	private
	*/
	var $draw_height;

	/**
	* Name of the background image
	*
	* @var		string	$bg_image
	* @access	private
	*/
	var $bg_image;

	/**
	* Type of the background image
	*
	* @var		string	$bgi_type
	* @access	private
	*/
	var $bgi_type;

	/**
	* x-Pos for the background image
	*
	* @var		integer	$bgi_xoff
	* @access	private
	*/
	var $bgi_xoff;

	/**
	* y-Pos for the background image
	*
	* @var		integer	$bgi_yoff
	* @access	private
	*/
	var $bgi_yoff;

	/**
	* Scale for the background image
	*
	* @var		float	$bgi_scale
	* @access	private
	*/
	var $bgi_scale;

	/**
	* Constructor
	*
	* @param	object	pc4p_page	&$parent
	* @param	string	$format
	* @param	string	$dimensions
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_page( &$parent, $format, $dimensions )
	{
		$accepted_formats = array( "a0" => "2380x3368", "a1" => "1684x2380", "a2" => "1190x1684", "a3" => "842x1190", "a4" => "595x842", "a5" => "421x595", "a6" => "297x421", "b5" => "501x709", "letter" => "612x792", "legal" => "612x1008", "ledger" => "1224x792", "p11x17" => "792x1224");
		$accepted_classes = array( "pc4p_main" );

		// Checks, if the class has an allowed parent-Class
		if( in_array( get_class( $parent ), $accepted_classes ) ) {
			// If yes, get PDF-Pointer
			$this->pdfp = &$parent->pdfp;
		}
		else
			die("Error: ".get_class( $this )." - ".get_class( $parent )." not allowed as parent");

		// Set page format
		if( in_array( strtolower( $format ), array_keys( $accepted_formats ) ) ) {
			// Ok, we have a standard format
			$xy = explode( "x", $accepted_formats[ $format ] );
			$this->width = $xy[0];
			$this->height = $xy[1];
		}
		elseif( $format == strtolower( "user" ) ) {
			// Homemade page. Set dimensions accordingly
			if( strlen( $dimensions ) > 0 ) {
				$xy = explode( "x", $dimensions );
				$this->width = $xy[0];
				$this->height = $xy[1];
			}
			else
				die("Error: ".get_class( $this )." - dimensions not set");
			
		}
		else {
			die("Error: ".get_class( $this )." - unknown format: ".$format);
		}
	}

	/**
	* Sets the margins for the page
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
	* Sets a background image for this page
	*
	* @param	string	$imgfile
	* @param	string	$imgtype
	* @param	integer	$xoffset
	* @param	integer $yoffset
	* @param	float	$scale
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_set_bgimage( $imgfile, $imgtype, $xoffset, $yoffset, $scale = 1.0 ) {
		$this->bg_image = $imgfile;
		$this->bgi_type = $imgtype;
		$this->bgi_xoff = $xoffset;
		$this->bgi_yoff = $yoffset;
		$this->bgi_scale = $scale;
	}

	/**
	* Begins the page with the dimensions, calls the draw for each child
	* and closes the page.
	* Called as last function in the class.
	*
	* @param	object	pc4p_main	&$parent
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_draw( &$parent )
	{
		if( !empty( $this->bg_image ) ) {
			$pim = pdf_open_image_file( $this->pdfp, $this->bgi_type, $this->bg_image );
			$this->bgi_yoff += pdf_get_value( $this->pdfp, "imageheight", $pim ) * $this->bgi_scale;
			pdf_place_image( $this->pdfp, $pim, $this->bgi_xoff, -$this->bgi_yoff, $this->bgi_scale );
			pdf_close_image( $this->pdfp, $pim );
		}

		// Draw the children
		$this->pc4p_draw_children();
		// ...and close page
		pdf_end_page( $this->pdfp );
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
	* Calls the calc_offset function in all children. Checks, if the current object
	* still fits onto the page. If not, it checks if there's a following page; if
	* not, it creates one, if yes it uses the existing. Moves the overlapping
	* objects to the new page. In one word: Here's the pagebreak function!!!
	*
	* @param	object	pc4p_page	&$parent
	* @access	private
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_calc_offset( &$parent )
	{
		// Initialize page and set (0,0) to the upper left corner
		pdf_begin_page( $this->pdfp, $this->width, $this->height );
		pdf_translate( $this->pdfp, 0, $this->height );
		$this->draw_height = $this->margin[ "top" ];
		for( $i = 0; $i < sizeof( $this->children); $i++ ) {
			// Get the height for the current child and...
			$child_height = $this->children[ $i ]->pc4p_calc_offset( $this );
			// ...check if it still fits onto the current page
			if( ( $this->draw_height + $child_height ) > ( $this->height - ( $this->margin[ "top" ] + $this->margin[ "bottom" ] ) ) ) {
				// No, it doesn't so get the overlapping objects, reverse them and
				// check if there's a next page
				$move_arr = array_reverse( array_splice( $this->children, $i ) );
				if( !is_object( $parent->next_page ) ) {
					// No, there isn't. Create a new one!
					// Check if object fits into page
					if( $child_height > ( $this->height - ( $this->margin[ "top" ] + $this->margin[ "bottom" ] ) ) )
						die("Error: ".get_class( $this->children[ $i ] )." - Object is too large for current margins/dimensions");
					$next_page = &pc4p_create_page( $parent, "user", $this->width."x".$this->height );
					$next_page->pc4p_set_margin( $this->margin );
					$next_page->pc4p_set_bgimage( $this->bg_image, $this->bgi_type, $this->bgi_xoff, $this->bgi_yoff, $this->bgi_scale );
					// Move the remaining objects to the next page.
					foreach( $move_arr as $obj ) {
						array_unshift( $next_page->children, $obj );
					}
				}
				else {
					// Yes, there is.
					// Check if object fits into page
					if( $child_height > ( $parent->next_page->height - ( $parent->next_page->margin[ "top" ] + $parent->next_page->margin[ "bottom" ] ) ) )
						die("Error: ".get_class( $this->children[ $i ] )." - Object is too large for margins/dimensions of the next page");
					// Move the remaining objects in front of the objects on the next page.
					foreach( $move_arr as $obj ) {
						array_unshift( $parent->next_page->children, $obj );
					}
				}
			}
			else  {
				// It fits! So increase the used height by the height of the object.
				$this->draw_height += $child_height;
			}
		}
		$this->draw_height += $this->margin[ "bottom" ];
	}
}
?>

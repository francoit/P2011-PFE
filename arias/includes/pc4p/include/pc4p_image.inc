<?php
/**
* Image-Object provides functionality for adding Images to the page
*
* @author	Alexander Wirtz <wirtz@web-active.com>
* @package	pc4p
*/
class pc4p_image extends pc4p_object
{
	/**
	* Margins for this image
	*
	* @var		array	$margin
	* @access	private
	*/
	var $margin = array( "top" => 0, "bottom" => 0, "left" => 0, "right" => 0 );

	/**
	* Pointer to the image
	*
	* @var		integer	$pim
	* @access	private
	*/
	var $pim;

	/**
	* Name of the image
	*
	* @var		string	$imgfile
	* @access	private
	*/
	var $imgfile;

	/**
	* Type of the image
	*
	* @var		string	$imgtype
	* @access	private
	*/
	var $imgtype;

	/**
	* Scale of the image
	*
	* @var		float	$imgscale
	* @access	private
	*/
	var $imgscale = 1.0;

	/**
	* Constructor
	*
	* @param	object	pc4p_page	&$parent
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_image( &$parent )
	{
		pc4p_object::pc4p_object( $parent );
	}

	/**
	* Sets the imagescale for this object.
	*
	* @param	float	$scale
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_set_imagescale( $scale )
	{
		$this->imgscale = $scale;
	}

	/**
	* Sets the image for this object.
	*
	* @param	string	$image
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_set_image( $image )
	{
		$imgtype = strtolower( substr( strrchr( $image, "." ), 1 ) );
		switch( $imgtype ) {
			case "png" :	$this->imgtype = "png";
							break;
			case "gif" :	$this->imgtype = "gif";
							break;
			case "tiff" :
			case "tif" :	$this->imgtype = "tiff";
							break;
			case "jpeg" :
			case "jpg" :	$this->imgtype = "jpeg";
							break;
			default:		die("Error: ".get_class( $this )." - this imagefiletype is not supported: ".$imgtype);
		}
		if( is_file( $image ) )
			$this->imgfile = $image;
		else
			die( "Error: ".get_class( $this )." - image not found: ".$image );
	}

	/**
	* Calls the pc4p_draw_children
	*
	* @access	private
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_draw()
	{
		$this->pim = pdf_open_image_file( $this->pdfp, $this->imgtype, $this->imgfile );
		pdf_place_image( $this->pdfp, $this->pim, $this->act_width, -( $this->act_height + $this->height ), $this->imgscale );
		pdf_close_image( $this->pdfp, $this->pim );
	}

	/**
	* Calculates the stringwidth for the current text and font in the
	* object and applies linefeed where necessary. Returns the size
	* for the complete object.
	*
	* @param	object	pc4p_page	&$parent
	* @return	integer	$this->height
	* @access	private
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_calc_offset( &$parent )
	{
		$this->pim = pdf_open_image_file( $this->pdfp, $this->imgtype, $this->imgfile );
		$this->act_width = $parent->act_width + $parent->margin[ "left" ];
		$this->act_height = $parent->draw_height;
		$this->width = $parent->width - ( $parent->margin[ "left" ] + $parent->margin[ "right" ] );
		// Check, if the image * scale is bigger than the width of the parent
		// If yes, calculate new scale
		$imgwidth = pdf_get_value( $this->pdfp, "imagewidth", $this->pim );
		if( ( $imgwidth * $this->imgscale ) > $this->width )
			$this->imgscale *= round( $this->width / ( $imgwidth * $this->imgscale ), 4 );
		// Set Alignment accordingly
		$imgwidth = round( $imgwidth * $this->imgscale );
		if( $this->alignment == "center" )
			$this->act_width += ceil( ( $this->width - $imgwidth ) / 2 );
		elseif( $this->alignment == "right" )
			$this->act_width += floor( $this->width - $imgwidth );
		// Check, if the image * scale is bigger than the height of the parent
		// If yes, calculate new scale
		$imgheight = pdf_get_value( $this->pdfp, "imageheight", $this->pim );
		$this->height = round( $imgheight * $this->imgscale );
		pdf_close_image( $this->pdfp, $this->pim );
		return $this->height;
	}
}
?>

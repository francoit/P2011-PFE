<?php
/**
* Box-Object provides functionality for drawing boxes around
* other objects
*
* @author	Alexander Wirtz <wirtz@web-active.com>
* @package	pc4p
*/
class pc4p_box extends pc4p_object
{
	/**
	* Margins for the box
	*
	* @var		array	$margin
	* @access	public
	*/
	var $margin = array( "top" => 5, "bottom" => 5, "left" => 5, "right" => 5 );

	/**
	* Constructor
	*
	* @param	object	pc4p_page	&$parent
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_box( &$parent )
	{
		pc4p_object::pc4p_object( $parent );
	}

	/**
	* Calls the pc4p_draw in its children, then draws the box
	*
	* @access	private
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_draw()
	{
		$this->pc4p_draw_children();
		pdf_rect( $this->pdfp, $this->act_width, -$this->act_height, $this->width, -$this->height );
//		pdf_rect( $this->pdfp, $this->act_width-1, -($this->act_height-1), $this->width+2, -($this->height+2) );
//		pdf_rect( $this->pdfp, $this->act_width+1, -($this->act_height+1), $this->width-2, -($this->height-2) );
		pdf_closepath_stroke( $this->pdfp );
	}
}
?>

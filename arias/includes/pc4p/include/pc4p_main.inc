<?php
/**
* PC4P's main class
*
* @author	Alexander Wirtz <wirtz@web-active.com>
* @package	pc4p
*/
class pc4p_main
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
	* File-Pointer for creating a PDF
	*
	* @var		integer	$fp
	* @access	private
	*/
	var $fp;

	/**
	* Pointer to the current page, used when walking through $children
	*
	* @var		object	pc4p_page	$curr_page
	* @access private
	*/
	var $curr_page;

	/**
	* Pointer to the next page, used when walking through $children
	*
	* @var		object	pc4p_page	$next_page
	* @access private
	*/
	var $next_page;

	/**
	* Constructor - Initializes the filepointer required for apprehending
	* the PDF-Pointer, which is initialized also
	*
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_main()
	{
		// acquire file in tmp-dir
		$this->fp = tmpfile();
		// acquire PDF-Pointer
		$this->pdfp = pdf_open( $this->fp );
	}

	/**
	* Calls the draw for each child, closes PDF Document, then makes
	* a passthrough for the temp-filepointer. The document is handed
	* to the Browser, then the filepointer is closed as well.
	* Called as last function in the class.
	*
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_draw()
	{
		$this->pc4p_draw_children();
		pdf_close( $this->pdfp );
		if( !defined( "PC4PDEBUG" ) ) {
			rewind( $this->fp );
			header( "Content-type: application/pdf" );
			header( "Content-Disposition: attachment; filename=acrobat.pdf" );
			fpassthru( $this->fp );
		}
		fclose( $this->fp );
	}

	/**
	* Calls the draw function for each child in the children-array
	*
	* @access	private
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_draw_children()
	{
		for( $i = 0; $i < sizeof( $this->children ); $i++ ) {
			unset( $this->next_page );
			$this->curr_page = &$this->children[ $i ];
			if( is_object( $this->children[ $i + 1 ] ) )
				$this->next_page = &$this->children[ $i + 1 ];
			$this->curr_page->pc4p_calc_offset( $this );
			if( defined( "PC4PDEBUG" ) ) {
				echo "<PRE>\n";
				var_dump( $this );
				echo "</PRE>\n";
			}
			$this->curr_page->pc4p_draw( $this );
		}
		unset( $this->curr_page );
		unset( $this->next_page );
	}

	/**
	* Sets the info values for the PDF
	*
	* @param	array	$info_array
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_set_info( $info_array )
	{
		$accepted_values = array( "subject", "title", "creator", "author", "keywords" );
		// Walk through the array and apply the set_info for each pair
		foreach( $info_array as $fieldname => $value ) {
			// Check for possible Values and set value if valid
			if( in_array( strtolower( $fieldname ), $accepted_values ) ) {
				pdf_set_info( $this->pdfp, $fieldname, $value );
			}
		}
	}

	/**
	* Sets the compression-parameter
	*
	* @param	integer	$compress
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_set_compression( $compress )
	{
		if( $compress >= 0 && $compress <= 9 )
			set_value( $this->pdfp, "compress", $compress );
	}
}
?>

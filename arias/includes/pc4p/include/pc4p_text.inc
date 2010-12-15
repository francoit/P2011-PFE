<?php
/**
* Text object provides text-funktionality
*
* @author	Alexander Wirtz <wirtz@web-active.com>
*/
class pc4p_text extends pc4p_object
{
	/**
	* Margins for this text
	*
	* @var		array	$margin
	* @access	private
	*/
	var $margin = array( "top" => 0, "bottom" => 0, "left" => 0, "right" => 0 );

	/**
	* Fontname for this text
	*
	* @var		string	$fontname
	* @access	private
	*/
	var $fontname = "Times-Roman";

	/**
	* Fontsize for this text
	*
	* @var		integer	$fontsize
	* @access	private
	*/
	var $fontsize = 10;

	/**
	* Encoding used for this text
	*
	* @var		string	$encoding
	* @access	private
	*/
	var $encoding = "host";

	/**
	* Underline for this text
	*
	* @var		integer	$underline
	* @access	private
	*/
	var $underline = 0;

	/**
	* Text for this text...haha
	*
	* @var		string	$text
	* @access	private
	*/
	var $text;

	/**
	* Constructor
	*
	* @param	object	pc4p_page	&$parent
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_text( &$parent )
	{
		pc4p_object::pc4p_object( $parent );

		if( !empty( $parent->leading ) )
			$this->leading = $parent->leading;
	}

	/**
	* Sets the font for this object.
	*
	* @param	string	$fontname
	* @param	integer	$fontsize
	* @param	string	$encoding
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_set_font( $fontname, $fontsize = 10, $encoding = "host" )
	{
		$this->fontname = $fontname;
		$this->fontsize = $fontsize;
		$this->encoding = $encoding;
	}

	/**
	* Sets the text which the object shall print later on
	*
	* @param	string	$text
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_set_text( $text )
	{
		$this->text = $text;
	}

	/**
	* Shall the text have an underline?
	*
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_set_underline()
	{
		if( $this->underline == 0 )
			$this->underline = 1;
		else
			$this->underline = 0;
	}

	/**
	* Formats the text for the actual width and inserts | where a newline
	* shall occur during print out. Returns the rows which the text needs.
	*
	* @param	string	&$text
	* @return	integer	$rowcount
	* @access	private
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_linefeed( &$text )
	{
		$rowcount = 1;
		$text = str_replace( "|", " ", $text );
		if( ceil( pdf_stringwidth( $this->pdfp, $text ) ) >= $this->width ) {
			$newtext = "";
			$tok = strtok( $text, " " );
			while( $tok ) {
				if( strrchr( $newtext, "|" ) != FALSE ) {
					if( ceil( pdf_stringwidth( $this->pdfp, strrchr( $newtext, "|")." ".$tok ) ) >= $this->width ) {
						$newtext = $newtext."|".$tok;
						$rowcount++;
					} else {
						$newtext = $newtext." ".$tok;
					}
				} else {
					if( ceil( pdf_stringwidth( $this->pdfp, $newtext." ".$tok ) ) >= $this->width ) {
						$newtext = $newtext."|".$tok;
						$rowcount++;
					} else {
						$newtext = $newtext." ".$tok;
					}
				}
				$tok = strtok(" ");
			}
			$text = trim($newtext);
		}
		return $rowcount;
	}

	/**
	* Draws the text for the set fontoptions.
	*
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_draw()
	{
		pdf_set_font( $this->pdfp, $this->fontname, $this->fontsize, $this->encoding );
		if( $this->underline )
			pdf_set_parameter( $this->pdfp, "underline", "true" );

		$this->draw_height = $this->fontsize + $this->act_height - 2;
		if( strstr( $this->text, "|" ) == FALSE ) {
			$act_width = $this->pc4p_calc_alignment( $this->text );
			pdf_show_xy( $this->pdfp, $this->text, $act_width, -$this->draw_height );
		} else {
			$tok = strtok( $this->text, "|" );
			while( $tok ) {
				$act_width = $this->pc4p_calc_alignment( $tok );
				pdf_show_xy( $this->pdfp, $tok, $act_width, -$this->draw_height );
				$tok = strtok( "|" );
				$this->draw_height += $this->fontsize + ( $this->leading - $this->fontsize );
			}
		}
		pdf_set_parameter( $this->pdfp, "underline", "false" );
	}

	/**
	* Calculates the position of the text according to the set alignment
	*
	* @param	string	$text
	* @return	integer	$act_width
	* @access	private
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_calc_alignment( $text )
	{
		$strwidth = pdf_stringwidth( $this->pdfp, $text );
		if( $this->alignment == "center" )
			$act_width = $this->act_width + ceil( ( $this->width - $strwidth ) / 2 );
		elseif( $this->alignment == "right" )
			$act_width = $this->act_width + floor( $this->width - $strwidth );
		else
			$act_width = $this->act_width;
		return $act_width;
	}

	/**
	* Calculates the stringwidth for the current text and font in the
	* object and applies linefeed where necessary. Returns the size
	* for the complete object.
	*
	* @param	object	pc4p_page	&$parent
	* @return	int		$this->height
	* @access	private
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function pc4p_calc_offset( &$parent )
	{
		if( empty( $this->leading ) || $this->leading < $this->fontsize )
			$this->leading = $this->fontsize;
		pdf_set_font( $this->pdfp, $this->fontname, $this->fontsize, $this->encoding );
		$this->width = $parent->width - ( $this->margin[ "left" ] + $this->margin[ "right" ] + $parent->margin[ "left" ] + $parent->margin[ "right" ] );
		$lines = $this->pc4p_linefeed( $this->text );
		$this->height = $this->margin[ "top" ] + ( ( $this->fontsize + ( $this->leading - $this->fontsize ) ) * $lines ) + $this->margin[ "bottom" ];
		$this->act_width = $this->margin[ "left" ] + $parent->act_width + $parent->margin[ "left" ];
		$this->act_height = $this->margin[ "top" ] + $parent->draw_height;
		return $this->height;
	}
}
?>

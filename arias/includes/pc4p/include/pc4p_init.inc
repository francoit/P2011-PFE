<?php
/**
* PC4P's init module, sets the needed include path and provides wrapper functions
* for the objects
*
* @module	pc4p_init
* @modulegroup	pc4p
* @author	Alexander Wirtz <wirtz@web-active.com>
* @package	pc4p
*/

// Set the include path
$include_path = dirname( __FILE__ );
$ini_include_path = ini_get( "include_path" );
$ini_include_path .= !strpos( $ini_include_path, ";" ) ? ":" . $include_path : ";" . $include_path;
ini_set( "include_path", $ini_include_path );

// Include the main files
/**
* Include the main object
*
* @include	pc4p_main.inc
*/
include_once( "pc4p_main.inc" );

/**
* Include the page object
*
* @include	pc4p_page.inc
*/
include_once( "pc4p_page.inc" );

/**
* Include the generic object
*
* @include	pc4p_object.inc
*/
include_once( "pc4p_object.inc" );

	/**
	* pc4p_create_pdf - Main wrapper function to create the PDF
	*
	* This is the first function which should be called, as it
	* acquires the PDF-Pointer for all other operations.
	*
	* @param	array	$info					here go the vars for the infos of the PDF
	* @return	object	pc4p_main	$pdf		the pdf-object, used by pc4p_create_page
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function &pc4p_create_pdf( $info = array() )
	{
		$pdf = &new pc4p_main();
		if( !empty( $info ) )
			$pdf->pc4p_set_info( $info );
		return $pdf;
	}

	/**
	* pc4p_create_page - Wrapper function to create a page
	*
	* This function is used to create new pages. Can take
	* a standard pagesize or user defines.
	*
	* @param	object	pc4p_main	&$parent	parent of the page, can only be a PDF-Object
	* @param	string	$format					name of a standard pagesize or "user"
	* @param	string	$dimensions				or the dimensions if page is "user"-defined <width>x<height>
	* @return	object	pc4p_page	$page		a page-object is returned, used by pc4p_create_object
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function &pc4p_create_page( &$parent, $format, $dimensions = "" )
	{
		$page = &new pc4p_page( $parent, $format, $dimensions );
		$parent->children[] = &$page;
		return $page;
	}

	/**
	* pc4p_create_object - Wrapper function to create objects
	*
	* Here we have the wrapper for all the other objects: Text, tables, boxes,
	* images and a object, which is used as container.
	*
	* @param	object	pc4p_page	&$parent	parent of the object, usually a page, or some container-object such as boxes and tables
	* @param	string	$object_type			type of object to be created, if empty a container-object is created
	* @return	object	pc4p_object	$object		the object, which was created by this function
	* @access	public
	* @author	Alexander Wirtz <wirtz@web-active.com>
	*/
	function &pc4p_create_object( &$parent, $object_type = "" )
	{
		switch( $object_type ) {
			// Is it a text?
			case "text" :
							/**
							* Include the object for using text
							*
							* @include	pc4p_text.inc
							*/
							include_once( "pc4p_text.inc" );
							$object = &new pc4p_text( $parent );
							break;
			// Is it a box?
			case "box" :
							/**
							* Include the object for drawing boxes
							*
							* @include	pc4p_box.inc
							*/
							include_once( "pc4p_box.inc" );
							$object = &new pc4p_box( $parent );
							break;
			// Maybe it's a table...
			case "table" :
							/**
							* Include the object for creating tables
							*
							* @include	pc4p_table.inc
							*/
							include_once( "pc4p_table.inc" );
							$object = &new pc4p_table( $parent );
							break;
			// ...or is it an image??
			case "image" :
							/**
							* Include the object for images
							*
							* @include pc4p_image
							*/
							include_once( "pc4p_image.inc" );
							$object = &new pc4p_image( $parent );
							break;
			// NO! It is SUPERobject!!!
			default		:	$object = &new pc4p_object( $parent );
		}
		$parent->children[] = &$object;
		return $object;
	}
?>

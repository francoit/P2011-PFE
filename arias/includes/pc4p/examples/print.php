<?
	include( "../include/pc4p_init.inc" );

//	define( "PC4PDEBUG", 1 );

	$PDF = &pc4p_create_pdf( array( "Author" => "Alexander Wirtz", "Title" => "PC4P-Test", "Creator" => "Alexander Wirtz" ) );
	$Page1 = &pc4p_create_page( $PDF, "a4" );

	$table1 = &pc4p_create_object( $Page1, "table");
	$table1->pc4p_create_tablematrix( 5, 2, array( "col1", "col2", "col3", "col4", "col5" ), array( "row1", "row2" ) );
		$text1 = &pc4p_create_object( $table1->cell[ "col1" ][ "row1" ] , "text" );
		$text1->pc4p_set_text( "bla1" );
		$text2 = &pc4p_create_object( $table1->cell[ "col2" ][ "row1" ] , "text" );
		$text2->pc4p_set_text( "This is an example for wordwrapping" );
		$text3 = &pc4p_create_object( $table1->cell[ "col3" ][ "row1" ] , "text" );
		$text3->pc4p_set_text( "bla3" );
		$text4 = &pc4p_create_object( $table1->cell[ "col4" ][ "row1" ] , "text" );
		$text4->pc4p_set_text( "bla4" );
		$image1 = &pc4p_create_object( $table1->cell[ "col5" ][ "row1" ], "image" );
		$image1->pc4p_set_image( "test.jpg" );
		$text5 = &pc4p_create_object( $table1->cell[ "col1" ][ "row2" ] , "text" );
		$text5->pc4p_set_text( "bla5" );
		$text6 = &pc4p_create_object( $table1->cell[ "col2" ][ "row2" ] , "text" );
		$text6->pc4p_set_text( "bla6" );
		$table2 = &pc4p_create_object( $table1->cell[ "col3" ][ "row2" ] , "table" );
		$table2->pc4p_set_tableborder( "single" );
		$table2->pc4p_create_tablematrix( 2 );
			$text7 = &pc4p_create_object( $table2->cell[ 0 ][ 0 ] , "text" );
			$text7->pc4p_set_text( "bla7" );
			$text8 = &pc4p_create_object( $table2->cell[ 1 ][ 0 ] , "text" );
			$text8->pc4p_set_alignment( "center" );
			$text8->pc4p_set_text( "bla8" );
		$table2->pc4p_add_tablerow();
			$text9 = &pc4p_create_object( $table2->cell[ 0 ][ 1 ] , "text" );
			$text9->pc4p_set_text( "bla9" );
			$text10 = &pc4p_create_object( $table2->cell[ 1 ][ 1 ] , "text" );
			$text10->pc4p_set_alignment( "center" );
			$text10->pc4p_set_text( "bla10" );
		$text11 = &pc4p_create_object( $table1->cell[ "col4" ][ "row2" ] , "text" );
		$text11->pc4p_set_alignment( "right");
		$text11->pc4p_set_text( "bla11" );
		$text12 = &pc4p_create_object( $table1->cell[ "col5" ][ "row2" ] , "text" );
		$text12->pc4p_set_text( "bla12" );
	$box1 = &pc4p_create_object( $Page1, "box" );
	$box1->pc4p_set_margin( array( "top" => 370, "bottom" => 370 ) );
	$box1->pc4p_set_width( 300 );
	$box1->pc4p_set_alignment( "center" );
		$text13 = &pc4p_create_object( $box1, "text" );
		$text13->pc4p_set_font( "Times-Bold", 15 );
		$text13->pc4p_set_alignment( "center" );
		$text13->pc4p_set_text( "Okay, here we have some text in a big box to proove, that pagebreak functions properly" );

	$PDF->pc4p_draw();
?>

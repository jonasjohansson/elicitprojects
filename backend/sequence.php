<?php
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
	header('Access-Control-Allow-Origin: *');

	$dir = dirname(__FILE__) . '/../assets/seq/';

	$sfiles = scandir( $dir );
	$files = array();

	foreach ( $sfiles as $f ) {
		if ( ( strcmp( $f, "." ) !== 0 ) && ( strcmp( $f, "." ) !== 0 ) && ( strpos( $f, ".json" ) !== FALSE ) ) {
			array_push( $files, $f );
		}
	}

	$k = rand( 0, count( $files )-1 );
	
	$contents = file_get_contents( $dir . $files[$k] );

	print( $contents );

?>
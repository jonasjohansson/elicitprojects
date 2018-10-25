<?php 
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
	header('Access-Control-Allow-Origin: *');

	function isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}

	$URL = "http://api.openweathermap.org/data/2.5/weather?q=Shanghai,cn&units=metric&appid=4cddfa85698d6bbf602e7fd142e0f8bd";
	$STATIC = dirname(__FILE__) . "/static.json";
	$TIMEOUT = 25; # time in minutes for new slice of data

	# check static file date
	date_default_timezone_set( 'Asia/Shanghai' );
	$lastmod = stat( $STATIC );
	$lastmod = $lastmod[9];
	$now = date( "U" );

	$age = ($now - $lastmod)/60;

	if ( $age < $TIMEOUT ) {
		# deliver data if less than 20 mins old
		print( file_get_contents( $STATIC ) );
	}
	else {
		# store new current data
		$data = @file_get_contents( $URL );
		if ( $data === FALSE ) {
			# oops went wrong!
			print( file_get_contents( $STATIC ) );
		}
		else {
			$json = json_decode( $data );
			if ( json_last_error() == JSON_ERROR_NONE ) {
				# it is JSON
				if ( !$json->{'wind'} ) {
					# deliver static (BAD JSON)
					print( file_get_contents( $STATIC ) );
					return;
				}
				# good data!
				# store data into static file
				file_put_contents( $STATIC, $data );
				# print data
				print( $data );
				return;		
			}
			
			# deliver static (IS NOT A JSON RESPONSE)
			print( file_get_contents( $STATIC ) );
				
		}
	}
?>
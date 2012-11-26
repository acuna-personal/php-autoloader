<?php
/**
 * Script for generation array-map of php-classes for autoload
 *
 * Use:
 * php map.php --file=/path/to/class_map.php --dir=/path/to/dir/where/php/files
 *
 * @author Dmitry Kuznetsov 2012
 * @url https://github.com/dmkuznetsov/php-class-map
 */
require_once dirname( __FILE__ ) . '/ClassMap.php';
require_once dirname( __FILE__ ) . '/Log/Console.php';
require_once dirname( __FILE__ ) . '/Progress/Console.php';

$options = getopt( '', array( 'dir:', 'file:', 'verbose', 'help' ) );
if ( array_key_exists( 'help', $options ) )
{
	help();
}
checkOptions( $options );

$verbose = false;
if ( array_key_exists( 'verbose', $options ) )
{
	$verbose = true;
}

$classMap = new ClassMap( $options[ 'file' ], $options[ 'dir' ], new Log_Console( $verbose ), new Progress_Console() );
$classMap->run();

exit( "\n" );

/**
 * @param array $options
 */
function checkOptions( array $options )
{
	$messages = array();
	if ( !array_key_exists( 'file', $options ) )
	{
		$messages[ ] = 'Please specify file for input data.' . "\n";
	}
	if ( !array_key_exists( 'dir', $options ) )
	{
		$messages[ ] = 'Please specify dir for analyze.' . "\n";
	}
	if ( !empty( $messages ) )
	{
		array_unshift( $messages, 'ERROR!' );
		showMessage( $messages, false );
		help();
	}
}

function help()
{
	$messages = array();
	$messages[ ] = 'CLASS-MAP HELP';
	$messages[ ] = '';
	$messages[ ] = 'Example:';
	$messages[ ] = 'php map.php --file=/www/project/class_map.php --dir=/www/project/';
	$messages[ ] = 'Script will create file class_map.php (if it possible) with array of all classes in dir /www/project';
	$messages[ ] = '';
	$messages[ ] = 'For verbose mode use option --verbose';

	showMessage( $messages );
}

function showMessage( $message, $stop = true )
{
	if ( is_array( $message ) )
	{
		$message = implode( "\n", $message );
	}
	echo $message . "\n";
	if ( $stop )
	{
		exit;
	}
}
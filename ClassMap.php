<?php
/**
 * @author Dmitry Kuznetsov 2012
 * @url https://github.com/dmkuznetsov/php-class-map
 */
class ClassMap
{
	/**
	 * @var string
	 */
	protected $_file;
	/**
	 * @var string
	 */
	protected $_dir;
	/**
	 * @var bool
	 */
	protected $_verbose = false;

	/**
	 * @var array
	 */
	private $_messages = array();
	/**
	 * @var array
	 */
	private $_files = array();
	/**
	 * @var int
	 */
	private $_filesCount = 0;
	/**
	 * @var array
	 */
	private $_classMap = array();
	/**
	 * @var int
	 */
	private $_classMapCount = 0;

	/**
	 * @param string $file
	 * @param string $dir
	 * @param bool $verbose
	 */
	public function __construct( $file, $dir, $verbose = false )
	{
		$this->_file = $file;
		$this->_dir = rtrim( $dir, '/' );
		$this->_verbose = $verbose ? true : false;
	}

	public function run()
	{
		$this->_log( "ClassMap: init with params:\nFILE: %s\nDIR: %s"
			, $this->_getFileWithStatus(), $this->_getDirWithStatus() );

		$this->_log( "Start searching php files..." );
		$this->_searchFiles();
		$this->_log( "Found %d php-files", $this->_filesCount );

		$this->_log( "Start analyzing files for classes..." );
		$this->_buildClassMap();
		$this->_log( "Found %d classes", $this->_classMapCount );

		$this->_log( "Start writing class map to file..." );
		$success = $this->_save();
		if ( $success )
		{
			$this->_log( "Success! Please, check file %s", $this->_file );
		}
		else
		{
			$this->_log( "Error! Can't write to file %s", $this->_file );
		}
	}

	/**
	 * @return array
	 */
	public function getLog()
	{
		return $this->_messages;
	}

	protected function _searchFiles()
	{
		$this->_files = $this->_getFileList( $this->_dir . '/*.php' );
		$this->_filesCount = count( $this->_files );
	}

	protected function _buildClassMap()
	{
		for ( $i = 0; $i < $this->_filesCount; $i++ )
		{
			$list = $this->_getClasses( $this->_files[ $i ] );
			foreach ( $list as $className )
			{
				$this->_classMap[ $className ] = $this->_files[ $i ];
			}
			$this->_classMapCount += count( $list );
		}
//		ksort( $this->_classMap );
	}

	/**
	 * @return int
	 */
	protected function _save()
	{
		$content = '<?php return ' . var_export( $this->_classMap, true ) . ';';
		return file_put_contents( $this->_file, $content );
	}

	/**
	 * @param $pattern
	 * @param int $flags
	 * @return array
	 */
	protected function _getFileList( $pattern, $flags = 0 )
	{
		$files = glob( $pattern, $flags );
		foreach ( glob( dirname( $pattern ) . '/*', GLOB_ONLYDIR | GLOB_NOSORT ) as $dir )
		{
			$files = array_merge( $files, $this->_getFileList( $dir . '/' . basename( $pattern ), $flags ) );
		}
		return $files;
	}

	/**
	 * @param $fileName
	 * @return array
	 */
	protected function _getClasses( $fileName )
	{
		$result = array();
		$content = file_get_contents( $fileName );
		$tokens = token_get_all( $content );
		$waitingClassName = false;
		for ( $i = 0, $c = count( $tokens ); $i < $c; $i++ )
		{
			if ( is_array( $tokens[ $i ] ) )
			{
				list( $type, $value ) = $tokens[ $i ];
				switch ( $type )
				{
					case T_CLASS:
					case T_INTERFACE:
						$waitingClassName = true;
						break;
					case T_STRING:
						if ( $waitingClassName )
						{
							$result[ ] = $value;
							$waitingClassName = false;
						}
						break;
				}
			}
		}
		return $result;
	}

	protected function _log()
	{
		$date = date( 'H:i:s' );
		$message = $date . ' ' . call_user_func_array( 'sprintf', func_get_args() );
		$this->_messages[] = $message;
		if ( $this->_verbose )
		{
			echo $message . "\n";
		}
	}

	private function _getFileWithStatus()
	{
		$status = 'not found';
		if ( file_exists( $this->_file ) )
		{
			$status = 'found';
			if ( is_writable( $this->_file ) )
			{
				$status .= ', writable';
			}
		}
		return $this->_file . ' (' . $status . ')';
	}

	private function _getDirWithStatus()
	{
		$status = 'not found';
		if ( file_exists( $this->_dir ) )
		{
			$status = 'found';
			if ( is_readable( $this->_dir ) )
			{
				$status .= ', readable';
			}
		}
		return $this->_dir . ' (' . $status . ')';
	}
}
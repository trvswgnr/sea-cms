<?php
/**
 * Class: Setup
 *
 * @package quark
 */

/**
 * Initialize Site
 */
class Setup {
	/**
	 * Database connection
	 *
	 * @var PDO
	 */
	private $conn;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->create_config_file();
		// import $conn variable.
		require_once 'connection.php';
		$this->conn = $conn;
		$this->create_tables();
	}

	/**
	 * Create Database Tables
	 */
	private function create_tables() {
		try {
			$conn  = $this->conn;
			$table = 'posts';
			$sql   = "CREATE TABLE IF NOT EXISTS $table(
				id bigint(20) AUTO_INCREMENT PRIMARY KEY,
				date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				title text NOT NULL,
				content longtext NOT NULL,
				slug text NOT NULL,
				type varchar(20) NOT NULL DEFAULT 'post');";

			$conn->exec( $sql );

			$table = 'users';
			$sql   = "CREATE TABLE IF NOT EXISTS $table(
				id bigint(20) AUTO_INCREMENT PRIMARY KEY,
				created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				user varchar(100) NOT NULL,
				email varchar(100) NOT NULL,
				password varchar(100) NOT NULL,
				role varchar(100) NOT NULL);";

			$conn->exec( $sql );

			echo 'Tables created successfully.';
		} catch ( PDOException $e ) {
			echo 'Error Creating Tables: ' . $e->getMessage();
		}
	}

	/**
	 * Create 'config.php' File
	 */
	public function create_config_file() {
		$host   = secure_input( 'host' ) ?: 'localhost';
		$user   = secure_input( 'user' ) ?: 'root';
		$pass   = filter_input( INPUT_POST, 'pass' ) ?: 'root';
		$dbname = secure_input( 'dbname' ) ?: 'quark_cms';

		$filename = 'config.php';
		$contents = "<?php\ndefine( 'DB_HOST', '$host' );\ndefine( 'DB_USER', '$user' );\ndefine( 'DB_PASSWORD', '$pass' );\ndefine( 'DB_NAME', '$dbname' );\ndefine( 'DEBUG', false );\ndefine( 'TIMEZONE', 'America/New_York' );\n";
		file_put_contents( $filename, $contents );
	}
}

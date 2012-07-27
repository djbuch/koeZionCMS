<?php
/**
 * Classe de gestion de fichiers locaux et distants.
 * http://www.developpez.net/forums/d811333/php/langage/contribuez/classe-gestion-fichier/
 */
class File
{

	public $filename;

	public function __construct( $filename )
	{
		$this->filename = $filename;
	}

	/**
	 * Renvoie le contenu du fichier s'il existe, un message d'erreur sinon.
	 * @author  Josselin Willette
	 * @param   string    $type     Type de contenu à retourner (string (valeur par défaut), array) (facultatif).
	 * @param   string    $retour   Langage de retour dans le cas d'une erreur (facultatif).
	 * @param   int       $offset   Position à laquelle on commence à lire (facultatif).
	 * @param   int       $maxlen   Taille maximale d'octets (facultatif).
	 * @return  mixed               Contenu sous la forme du type passé en paramètre.
	 */
	public function get( $type = 'string', $retour = '', $offset = null, $maxlen = null )
	{
		try {
			$this->testURI();

			switch ( $type )
			{
				case 'array'  :
					if ( is_null( $maxlen ) ) {
						$contents = explode( "\n", file_get_contents( $this->filename, null, null, $offset ) );
					}
					else {
						$contents = explode( "\n", file_get_contents( $this->filename, null, null, $offset, $maxlen ) );
					}
					break;

				case 'string' :
				default       :
					if ( is_null( $maxlen ) ) {
						$contents = file_get_contents( $this->filename, null, null, $offset );
					}
					else {
						$contents = file_get_contents( $this->filename, null, null, $offset, $maxlen );
					}
					break;
			}
		}
		catch ( Exception $e ) {
			switch ( strtolower( $retour ) )
			{
				case 'css'    :
				case 'js'     :
					$contents = '/* ' . $e->getMessage() . ' */';
					break;

				case 'html'   :
				case 'xml'    :
					$contents = '<!-- ' . $e->getMessage() . ' -->';
					break;

				default       :
					$contents = $e->getMessage();
				break;
			}
		}

		return $contents;
	}

	/**
	 * Ecrit le contenu passé en paramètre dans un fichier.
	 * @author  Josselin Willette
	 * @param   string    $content    Contenu à écrire.
	 * @param   int       $append     Précise si on écrase le fichier ou si on écrit à la fin (0 par défaut : écrase) (facultatif).
	 * @return  bool                  Retourne true en cas de succès et false en cas d'échec.
	 */
	public function put( $content, $append = 0 )
	{
		$this->mkpath();

		try {
			if ( !file_put_contents( $this->filename, $content, $append ) )
			{
				$message = error_get_last();
				$message = $message['message'];
				throw new Exception( 'Impossible d\'écrire dans ' . $this->filename . "\n" . $message );
			}

			return true;
		}
		catch ( Exception $e ) {
			return false;
		}
	}

	/**
	 * Crée une arborescence (vérifie son existence) en fonction du chemin du fichier (qui peut contenir son nom mais sera ignoré).
	 * @author  Josselin Willette
	 */
	public function mkpath()
	{
		$path_pieces = explode( '/', dirname( $this->filename ) );

		$path = '';

		while ( !is_null( $piece = array_shift( $path_pieces ) ) ) {
			$path .= $piece . '/';

			if ( !is_dir( $path ) ) {
				$this->createDirectory( 0777, $path );
			}
		}
	}

	/**
	 * Teste si une URI est bonne et lance une exception dans le cas contraire.
	 * @author  Josselin Willette
	 */
	public function testURI()
	{
		if ( !@fopen( $this->filename, 'r' ) )
		{
			$message = error_get_last();
			$message = $message['message'];
			throw new Exception( 'Impossible d\'ouvrir ' . $this->filename . "\n" . $message );
		}
	}

	/**
	 * Modifie les droits d'un fichier.
	 * @author  Josselin Willette
	 * @param   int     $mod     Nouveaux droits du fichier (en octal). Exemple : 0777
	 */
	public function chProperties( $mod )
	{
		umask( 0 );
		chmod( $this->filename, $mod );
	}

	/**
	 * Crée un répertoire à l'endroit spécifié.
	 * @author  Josselin Willette
	 * @param   int     $mod     Nouveaux droits du fichier (en octal). Exemple : 0777
	 * @param   string  $path    Dossier à créer.
	 */
	public function createDirectory( $mod, $path )
	{
		umask( 0 );
		mkdir( $path, $mod );
	}

	/**
	 * Vérifie l'existence du fichier.
	 * @author  Josselin Willette
	 * @return  bool              Retourne true si le fichier existe, false le cas contraire.
	 */
	public function exists()
	{
		clearstatcache();
		return file_exists( $this->filename );
	}

	/**
	 * Supprime le fichier.
	 * @author  Josselin Willette
	 * @return  bool              Retourne true si le fichier a pu être supprimé, false sinon.
	 */
	public function remove()
	{
		return unlink( $this->filename );
	}

	/**
	 * Vérifie la validité d'un fichier en fonction d'une durée passée en paramètre.
	 * @author  Josselin Willette
	 * @param   int   $dureeValidite    Durée de validité du fichier, en secondes.
	 * @return  bool                    Retourne true si le fichier est encore valide, false sinon.
	 */
	public function valid( $dureeValidite )
	{
		clearstatcache();
		return time() - filemtime( $this->filename ) < $dureeValidite;
	}

	/**
	 * Renvoie la taille du fichier.
	 * @author  Josselin Willette
	 * @return  int                    Retourne la taille du fichier.
	 */
	public function size()
	{
		return filesize( $this->filename );
	}

	/**
	 * Renomme le fichier.
	 * @author  Josselin Willette
	 * @param   string    $newName  Nouveau nom du fichier.
	 * @param   bool      $change   Change le nom du fichier de l'objet courant.
	 * @return  bool                Retourne true si le fichier a pu être renommé, false sinon.
	 */
	public function rename( $newName, $change = false )
	{
		$o = rename( $this->filename, $newName );

		if ( $change == true ) {
			$this->filename = $newName;
		}

		return $o;
	}

}
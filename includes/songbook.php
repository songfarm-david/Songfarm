<?php require_once('initialize.php');

class songbook{

	/**
	*	Static variables
	*
	* @var $table_name private holds name of
	* table user_songs
	*/
	private static $table_name = 'user_songs';

	/**
	* Public variables
	*
	* @var $user_id int contains unique user id
	*	@var $song_name string name of song
	* @var $original_artist string name of original artist if song is cover
	* @var $lyric text lyrics for the song
	*/
	public $user_id;
	public $song_name;
	public $cover_artist;
	public $lyric;

	/**
	* Private variables
	*
	* @var $song_id int unique id for song
	* @var $type int defines whether original or cover song
	* @var $permission int defines the permission of the song
	*/
	private $song_id;
	private $type;
	private $permission;

	/**
	* Public function - collects values
	* from submitted songtag and inserts
	* them into database
	*
	* @param int $user_id the unique user id
	*/
	public function insert_song($user_id){
		global $db;
		// collect into variables form submissions
		$this->user_id = $user_id;
		$this->song_id = uniqid('', true);
		$this->type = $_POST['type'];
		$this->song_name = $db->escape_value($_POST['song_name']);
		$this->cover_artist = $db->escape_value($_POST['cover_artist']);
		$this->lyric = $db->escape_value($_POST['lyric']);
		$this->permission = $_POST['permission'];
		// create query string for insertion
		$sql = "INSERT INTO ".self::$table_name." (";
		$sql.= "user_id, song_id, song_name, type, permission, lyric, cover_artist";
		$sql.= ") VALUES (";
		$sql.= "$this->user_id, '$this->song_id', '$this->song_name', $this->type, $this->permission, '$this->lyric', '$this->cover_artist')";
		if(!$result = $db->query($sql)){
			echo "Error uploading song";
		}
	}

	/**
	*
	*
	*/
	public function delete_song(){
		global $db;

		$sql = "DELETE FROM ".self::$table_name;
		$sql.= "WHERE song_id = {$song_id}";
	}

	/**
	* Private function the takes an int from
	* a database and converts it friendly string
	* if type is cover
	*
	* @param int $type the value of $_POST['type']
	* @return string the type of song
	*/
	private function convert_type($type, $cover_artist){
		if($type == 0){
			return false;
		} else {
			$output = "<span class=\"cover\">({$cover_artist} cover)</span>";
			return $output;
		}
	}

	/**
	*	Private function - takes an integer description
	* of a permission and converts it to a string
	*
	* @param $perm int description of permission
	* @return string description of permission
	*/
	private function convert_permission($perm){
		if($perm == 0){
			$output = "<span class=\"permission private\">Private</span>";
		} else {
			$output = "<span class=\"permission public\">Public</span>";
		}
		return $output;
	}

	/**
	* Public function that uses the user_id
	* to check if there are songs uploaded
	*
	* If so, it returns the song list
	* if not, it returns a default message
	*
	* @access Public
	* @param int $user_id the user_id of the current user
	* @return string the song list or a default message
	*/
	public function list_songs($user_id){
		global $db;

		$sql = "SELECT * FROM user_songs WHERE user_id = $user_id";
		if($result = $db->query($sql)){
			if($db->has_rows($result) < 1){
				$output = "<p>No songs currently listed.</p>";
			} else {
				$output = "<ul>";
				while ($arr = $db->fetch_array($result)) {
					$output.= "<li>";
					$output.= "<input type=\"hidden\" name=\"song_id\" value=\"".$arr['song_id']."\">";
					$output.= $this->convert_permission($arr['permission']);
					$output.= $arr['song_name'];
					$output.= $this->convert_type($arr['type'],$arr['cover_artist']);
					$output.= "<ul>";
					$output.= "<li>sT</li>";
					$output.= "<li>Dx</li>";
					$output.= "</ul>";
					$output.= "</li>";
				}
				$output.= "</ul>";
			}
			echo $output;
		}
	}

	/**
	*	Public function - outputs video
	* and video info per song upon request
	*
	* @param varchar a unique string to identify the song
	*/
	public function output_video($song_id){
		global $db;

		$sql = "SELECT * FROM ".self::$table_name;
		$sql.= " WHERE song_id = '{$song_id}'";
		if($result = $db->query($sql)){
			$arr = $db->fetch_array($result);
			$output = "<div id=\"video-container\">";
			$output.= "<video width=\"100\" height=\"100\" controls>";
			$output.= "</video>";
			$output.= "<span class=\"display_perm\">".$this->convert_permission($arr['permission'])."</span>";
			$output.= "<h2>".$arr['song_name'];
			$output.= "<span class=\"sub_name\">".$this->convert_type($arr['type'],$arr['cover_artist'])."</span></h2>";
			$output.= "</div>";
			echo $output;
		}

	}

}

$songbook = new songbook();

?>

<?php require_once('initialize.php');

class Image extends MySQLDatabase{

	/**
	*	Static variables
	*
	* @var $table_name user_photo table on database
	* @var $photo_dir path to photo directory on server
	*/
	protected static $table_name = 'user_photo';
	protected static $photo_dir = 'uploaded_images';
	/**
	* Public variables
	*
	* @var $user_id int contains unique user id
	*	@var $song_name string name of song
	* @var $original_artist string name of original artist if song is cover
	* @var $lyric text lyrics for the song
	*/
	public $image_name = "";
	public $filename;
	public $type;
	protected $size;
	private $tmp_name;
	// private $dir = "uploaded_images";
	private $existing_filename;
	private $existing_filetype;

	public $message;
	public $photo_errors = [];
	public $php_upload_errors = array(
			0 => 'There is no error, the file uploaded with success',
			1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
			2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
			3 => 'The uploaded file was only partially uploaded',
			4 => 'No file was uploaded',
			6 => 'Missing a temporary folder',
			7 => 'Failed to write file to disk.',
			8 => 'A PHP extension stopped the file upload.',
	);

	public function upload_image($image){
		if($this->image_has_presence($image)){
			if($this->is_valid_filename($this->filename)){
				if($this->is_valid_extension($this->type)){
					if($this->user_has_existing_photo($this->user_id)){
						if($this->delete_existing_image($this->existing_filename, $this->existing_filetype)){
							$this->update_image(); }
					}
					else
					{
						$this->insert_image();
					}
				}
			}
		}
	}

	/**
	*	Private function - Takes uploaded image array
	* from user andchecks for presence.
	*
	* If present, then checks array for errors
	*
	* @param array $_FILES['image']
	* @return mixed variables containing uploaded image data
	* else
	* @return string $photo_error returns an error
	*/
	private function image_has_presence($uploaded_image){
		if($uploaded_image && !empty($uploaded_image) && is_array($uploaded_image)) {
			if($this->has_not_photo_errors($uploaded_image)){
				$this->filename = uniqid('', true);
				$this->type = str_replace('image/','.',$uploaded_image['type']);
				$this->size = $uploaded_image['size'];
				$this->tmp_name = $uploaded_image['tmp_name'];
				return true;
			}
		} else {
			$this->photo_errors[] = "No File Uploaded";
			return false;
		}
	}

	private function has_not_photo_errors($image){
		if($image['error'] != 0){
			foreach($this->php_upload_errors as $key => $value) {
				if($image['error'] == $key) {
					$this->photo_errors[] = $value;
				}
			}
			return false;
		} else {
			return true;
		}
	}

	protected function is_valid_filename($filename) {
		if(preg_match('/^[a-zA-Z0-9_.-]+$/',$filename)){
			return true;
		} else {
			$this->photo_errors[] = "Invalid characters in file name. Only characters, numbers and (_-.) allowed.";
			return false;
		}
	}

	protected function is_valid_extension($type) {
		$ext_type = array('gif','jpg','jpe','jpeg','png');
		$file_ext = substr(strrchr($type,'.'),1);
		if(in_array($file_ext, $ext_type)){
			return true;
		} else {
			return $this->photo_errors[] = "Files must be of either type jpeg, jpg, jpe, gif or png.";
		}
	}

	protected function user_has_existing_photo($user_id){
		global $db;
		$sql = "SELECT * FROM user_photo WHERE user_id = $user_id";
		if($result = $db->query($sql)){
			if($db->has_rows($result)){
				$res_array = $db->fetch_array($result);
				$this->existing_filename = $res_array['filename'];
				$this->existing_filetype = $res_array['type'];
				return true;
			}
		}
	}

	protected function delete_existing_image($filename, $type){
		if(file_exists(SITE_ROOT.DS.self::$photo_dir.DS.$filename.$type)){ // if file exists, delete it
			if(unlink(SITE_ROOT.DS.self::$photo_dir.DS.$filename.$type)){
				return true;
			} else {
				$this->photo_errors[] = "Could not delete existing file in file system. Update failed.";
			}
		}
	}

	protected function update_image(){
		global $db;

		$sql = "UPDATE user_photo ";
		$sql.= "SET filename = '$this->filename', type = '$this->type', size = $this->size ";
		$sql.= "WHERE user_id = $this->user_id";
		if($result = $db->query($sql)) {
			if($this->moved_uploaded_file()){
				$this->message = "Update Successful";
				$this->image_name = $this->filename.$this->type;
			}
		} else {
			$this->photo_errors[] = "There was an error updating your profile picture. Please try again at a later time.";
		}
	}

	protected function insert_image(){
		global $db;

		$sql = "INSERT INTO user_photo (";
		$sql.= "user_id, filename, type, size ";
		$sql.= ") VALUES (";
		$sql.= "$this->user_id, '$this->filename', '$this->type', $this->size)";
		if($result = $db->query($sql)){
			if(mysqli_affected_rows($db->connection) == 0){
				$photo_errors[] = "There was an error inserting your file into our records. Please try again at a later time.";
				return false;
			} elseif($this->moved_uploaded_file()) {
				// success
				$this->message = "Insert Successful";
				$this->image_name = $this->filename.$this->type;
			}
		}
	}

	protected function moved_uploaded_file(){
		if(move_uploaded_file($this->tmp_name, SITE_ROOT.DS.self::$photo_dir.'/'.$this->filename.$this->type)){
			return true;
		} else {
			$this->photo_errors[] = "Error moving file to file system";
			return false;
		}
	}

	/*
	*	Public function - retrieves user image
	* information from database based on the users's
	* user id.
	* Creates image path and checks server file system
	* for matching image
	*
	*	@return string path to file image if exists
	*	else
	* @return string path to default image 'Upload Photo'
	*/
	public function retrieve_user_photo(){
		global $db;
		$sql = "SELECT filename, type FROM ".self::$table_name." WHERE ";
		$sql.= "user_id = $this->user_id";
		if($result = $db->query($sql)){
			if($db->has_rows($result)){
				$result_array = $db->fetch_array($result);
				$image_name = $result_array['filename'].$result_array['type'];
				$image_path = SITE_ROOT.DS.self::$photo_dir.DS.$image_name;
				if(file_exists($image_path)){
					$this->image_name = $image_name;
				} else {
					/* display image of 'Please Upload a Photo' */
					$this->photo_errors[] = "Please upload a photo";
				}
			}
		}
	}
}

$image = new Image();

?>

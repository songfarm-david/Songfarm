<?php require_once(LIB_PATH.DS.'initialize.php');

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
	* @var $image_name string user image filename/type
	* @var $photo_errors array photo errors
	* @var $message string success message
	*/
	public $image_name = "";
	public $photo_errors = [];
	public $message;

	/**
	* Protected variables
	*
	* @var $filename string name of user image
	* @var $type string extension of image
	* @var $size int size of image file
	* @var $php_upload_errors array array of possible photo errors
	*/
	protected $filename;
	protected $type;
	protected $size;
	protected $php_upload_errors = array(
			0 => 'There is no error, the file uploaded with success',
			1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
			2 => 'The uploaded file exceeds the MAX_FILE_SIZE of 2MB',
			3 => 'The uploaded file was only partially uploaded',
			4 => 'No file was uploaded',
			6 => 'Missing a temporary folder',
			7 => 'Failed to write file to disk.',
			8 => 'A PHP extension stopped the file upload.',
	);

	/**
	* Private variables
	*
	* @var $tmp_name string temporary image path
	* @var $existing_filename string existing path to user image
	* @var $existing_filetype string file extension of existing user image
	*/
	private $tmp_name;
	private $existing_filename;
	private $existing_filetype;


	/**
	* Public function - Performs
	* various checks on a file upload
	* before either updating or inserting
	* image into database
	*
	* @param array upload image array
	* @param int user id
	*
	* @return function update_image()
	* or
	* @return function insert_image()
	*/
	public function upload_image($image, $id){
		$this->user_id = $id;
		if($this->image_has_presence($image)){
			if($this->has_not_photo_errors($image)){
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
	*	Public function - retrieves user image
	* from database based on user id.
	*
	*	@return string path to file image if exists
	*	else
	* @return string path to default image 'Upload Photo'
	*/
	public function retrieve_user_photo($id){

		global $db;

		$sql = "SELECT filename, type FROM ".self::$table_name." WHERE ";
		$sql.= "user_id = $id";
		if($result = $db->query($sql)){
			if($db->hasRows($result) > 0){
				$image_array = $db->fetchArray($result);
				$image_name = $image_array['filename'].$image_array['type'];
				$image_path = SITE_ROOT.DS.self::$photo_dir.DS.$image_name;
				if(file_exists($image_path)){
					$this->image_name = $image_name;
				} else {
					$this->photo_errors[] = "Sorry, we couldn't find your photo in the filesystem. Please, try uploading it again";
				}
			} else {
				// if there is no existing user photo, use default
				$this->image_name = 'avatar_default.png';
			}
		}
	}


	/**
	*	Protected function - Checks for presence
	* of an image array
	*
	* @param array $_FILES['image']
	* @return mixed variables containing uploaded image data
	* else
	* @return string $photo_error returns an error
	*/
	protected function image_has_presence($uploaded_image){
		if($uploaded_image && !empty($uploaded_image) && is_array($uploaded_image)) {
			// if($this->has_not_photo_errors($uploaded_image)){
			// 	$this->filename = uniqid('', true);
			// 	$this->type = str_replace('image/','.',$uploaded_image['type']);
			// 	$this->size = $uploaded_image['size'];
			// 	$this->tmp_name = $uploaded_image['tmp_name'];
			// }
			return true;
		} else {
			$this->photo_errors[] = "No File Uploaded";
			return false;
		}
	}

	/**
	* Protected function - Takes $_FILES superglobal
	* array and checks for photo errors
	*
	* @param array $_FILES superglobal array
	* @return true if no errors, false if errors
	*/
	protected function has_not_photo_errors($image){
		if($image['error'] != 0){
			foreach($this->php_upload_errors as $key => $value) {
				if($image['error'] == $key) {
					$this->photo_errors[] = $value;
				}
			}
			return false;
		} else {
			$this->filename = uniqid('', true);
			$this->type = str_replace('image/','.',$image['type']);
			$this->size = $image['size'];
			$this->tmp_name = $image['tmp_name'];
			return true;
		}
	}

	/**
	* Protected function - checks file type
	* is a valid extension
	*
	* @param string a file extension
	*/
	protected function is_valid_extension($type) {
		$ext_type = array('gif','jpg','jpe','jpeg','png');
		$file_ext = substr(strrchr($type,'.'),1);
		if(in_array($file_ext, $ext_type)){
			return true;
		} else {
			$this->photo_errors[] = "Files must be of either type jpeg, jpg, jpe, gif or png.";
		}
	}

	/**
	* Protected function - Checks database
	* for existing user image
	*
	* @param int user id
	* @return bool true if user has existing photo
	* @var string $image_name default image if no existing photo
	*/
	protected function user_has_existing_photo($id){
		global $db;
		$sql = "SELECT * FROM user_photo WHERE user_id = $id";
		if($result = $db->query($sql)){
			if($db->hasRows($result)){
				$res_array = $db->fetchArray($result);
				$this->existing_filename = $res_array['filename'];
				$this->existing_filetype = $res_array['type'];
				return true;
			} else {
				$this->image_name = 'avatar_default.png';
			}
		}
	}

	/**
	* Protected function - Moves uploaded
	* file to the filesystem
	*
	* @return bool true on success, false on failure
	*/
	protected function moved_uploaded_file(){
		if(move_uploaded_file($this->tmp_name, SITE_ROOT.DS.self::$photo_dir.'/'.$this->filename.$this->type)){
			return true;
		} else {
			$this->photo_errors[] = "Error moving file to file system. Please try again later.";
			return false;
		}
	}


	/**
	*	Private function - Deletes a image file
	* from the server filesystem
	*
	* @param string filename name of the image file
	* @param string type extension of file
	* @return bool true if delete successful
	*/
	private function delete_existing_image($filename, $type){
		if(file_exists(SITE_ROOT.DS.self::$photo_dir.DS.$filename.$type)){ // if file exists, delete it
			if(unlink(SITE_ROOT.DS.self::$photo_dir.DS.$filename.$type)){
				return true;
			} else {
				$this->photo_errors[] = "Could not delete existing file in file system. Update failed.";
			}
		}
	}

	/**
	* Private function - Updates user photo
	*/
	private function update_image(){
		global $db;

		$sql = "UPDATE ".self::$table_name." ";
		$sql.= "SET filename = '$this->filename', type = '$this->type', size = $this->size ";
		$sql.= "WHERE user_id = $this->user_id";
		if($result = $db->query($sql)) {
			if($this->moved_uploaded_file()){
				Message::$message = "Image update successful!";
				$this->image_name = $this->filename.$this->type;
				return false;
			}
		} else {
			$this->photo_errors[] = "There was an error updating your profile picture. Please try again at a later time.";
		}
	}

	/**
	* Private function - Insert new image
	* into database
	*/
	private function insert_image(){
		global $db;

		$sql = "INSERT INTO ".self::$table_name." (";
		$sql.= "user_id, filename, type, size ";
		$sql.= ") VALUES (";
		$sql.= "$this->user_id, '$this->filename', '$this->type', $this->size)";
		if($result = $db->query($sql)){
			if(mysqli_affected_rows($db->connection) == 0){
				$photo_errors[] = "There was an error inserting your file into our records. Please try again at a later time.";
				return false;
			} elseif($this->moved_uploaded_file()) {
				// success
				Message::$message = "Image upload successful!";
				$this->image_name = $this->filename.$this->type;
				return false;
			}
		}
	}



	/**
	* Protected function - Validates a
	* string for acceptable (safe) characters
	*
	* @param string image file
	*/
	// protected function is_valid_filename($filename) {
	// 	if(preg_match('/^[a-zA-Z0-9_.-]+$/',$filename)){
	// 		return true;
	// 	} else {
	// 		$this->photo_errors[] = "Invalid characters in file name. Only characters, numbers and (_-.) allowed.";
	// 		return false;
	// 	}
	// }


}

$image = new Image();

?>

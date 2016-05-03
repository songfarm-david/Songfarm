<?php require_once('initialize.php');
require_once(EMAIL_PATH.DS.'email_data.php');
$errors = []; // <-- initialize empty errors array
if(isset($_POST['submit'])) {
  // check for presence of 'name'
  if($db->hasPresence($_POST['name'])) {
    // make sure it's at least two characters
    if($db->hasMinLength($_POST['name'],2)) {
      // assign clean, valid 'name' variable
      $name = htmlspecialchars($_POST['name']);
    } else {
      $name = "";
      $errors[] = "A name must be at least 2 characters";
    }
  } else {
    $name = "";
    $errors[] = "Please enter a name";
  }
  // check for presence of email
  if($db->hasPresence($_POST['email'])) {
    // make sure email is valid
    if($db->isValidEmail($_POST['email'])) {
      // assign clean, valid 'email' variable
      $email = htmlspecialchars($_POST['email']);
    } else {
      $email = htmlspecialchars($_POST['email']);
      $errors[] = "Please enter a valid email address";
    }
  } else {
    $email = "";
    $errors[] = "Please enter an email address";
  }
  // if there's a subject, clean it up
  if($db->hasPresence($_POST['subject'])) {
    $subject = htmlspecialchars($_POST['subject']);
  } else {
    $subject = "";
  }
  // check for presence of a message
  if($db->hasPresence($_POST['message'])) {
    // if it's at least 3 characters long
    if($db->hasMinLength($_POST['message'], 3)) {
      $message = wordwrap(htmlspecialchars($_POST['message']),70);
    } else {
      $message = htmlspecialchars($_POST['message']);
      $errors[] = "Please write more than 2 words";
    }
  } else {
    $message = "";
    $errors[] = "Please write a comment before trying to send the form.";
  }
  // if no errors, then assemble the email
  if(empty($errors)) {
    $to = 'Contact Songfarm <contact@songfarm.ca>'; // this may cause a bug on Windows systems
    $subject.= " sent ".strftime("%a, %B %d at %I:%M %p", time());
    $from = "{$name} <{$email}>"; // this may cause a bug on Windows systems
    $message = "New message from: {$name}\r\n\r\n{$message}";
    $headers = "From: {$from}\r\n";
    $headers.= "Reply-to: {$email}\r\n";
    $headers.= "MIME-Version: 1.0\r\n";
    $headers.= "Content-Type: text/plain; charset=utf-8";
    $result = mail($to, $subject, $message, $headers, '-fsongfarm'); // 5th arg. possible bug
    if($result){
      echo "Thank you, {$name}, for getting in touch!";
      $user_data = [ "name" => $name ];

      if( !$message = initiateEmail($email_data['contact_us'], $user_data) )
      {
        // NOTE: log error
        $err_msg = " -- ERROR: ".$_SERVER['PHP_SELF']." (line ".__LINE__.") -- initiateEmail failed";
  			file_put_contents(SITE_ROOT.'/logs/error_'.date("m-d-Y").'.txt',date("G:i:s").$err_msg.PHP_EOL,FILE_APPEND);

        notifyAdminByEmail("Contact Form autoresponder failed to send -- {$_SERVER['PHP_SELF']}");

      }
      else
      {
        $to   = "{$name} <{$email}>";
        $from = "Songfarm <contact@songfarm.ca>";
        $subject = "Message Received!";
        $headers = "From: {$from}\r\n";
        $headers.= "Content-Type: text/html; charset=utf-8";
        if( !$result = mail($to,$subject,$message,$headers,'-fsongfarm') )
        {
          // NOTE: write to error log
          $err_msg = " -- ERROR: ".$_SERVER['PHP_SELF']." (line ".__LINE__.") -- PHP mail method failed. Unable to send autoresponse";
          file_put_contents(SITE_ROOT.'/logs/error_'.date("m-d-Y").'.txt',date("G:i:s").$err_msg.PHP_EOL,FILE_APPEND);

          notifyAdminByEmail($err_msg);

        }
      }
    } else {
      echo "We were unable to send your message at this time. Our team has been notified of this error. Please try again later.";
      // NOTE: log error here: failed to send contact form comment via mail method PHP
      notifyAdminByEmail("Failed to send contact form comment via mail method -- {$_SERVER['PHP_SELF']}");
    }
  }
} else { $name = $email = $subject = $message = ""; }
?>

<?php require_once('initialize.php');
$errors = []; // <-- initialize empty errors array
if(isset($_POST['submit'])) {
  // check for presence of 'name'
  if(has_presence($_POST['name'])) {
    // make sure it's at least two characters
    if(has_min_length($_POST['name'])) {
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
  if(has_presence($_POST['email'])) {
    // make sure email is valid
    if(is_valid_email($_POST['email'])) {
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
  if(has_presence($_POST['subject'])) {
    $subject = htmlspecialchars($_POST['subject']);
  } else {
    $subject = "";
  }
  // check for presence of a message
  if(has_presence($_POST['message'])) {
    // if it's at least 3 characters long
    if(has_min_length($_POST['message'], 3)) {
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
    $to = 'David Gaskin <davidburkegaskin@gmail.com>'; // this may cause a bug on Windows systems
    $subject.= " sent ".strftime("%a, %B %d at %I:%M %p", time());
    $from = "{$name} <contactform@songfarm.ca>"; // this may cause a bug on Windows systems
    $message = "New message from: {$name}\r\n\r\n{$message}";
    $headers = "From: {$from}\r\n";
    $headers.= "Reply-to: {$email}\r\n";
    $headers.= "Bcc: David Gaskin <ste_llar@hotmail.com>\r\n";
    $headers.= "MIME-Version: 1.0\r\n";
    $headers.= "Content-Type: text/plain; charset=utf-8";
    /* use 'X-' ... in your headers to append non-standard headers */
    $result = mail($to, $subject, $message, $headers, '-fsongfarm'); // 5th arg. possible bug
    if($result){
      echo "Thank you, {$name}, for getting in touch!";
    }else{
      echo "We were unable to send your message at this time. Please try again later.";
    }
  }
} else { $name = $email = $subject = $message = ""; }
?>

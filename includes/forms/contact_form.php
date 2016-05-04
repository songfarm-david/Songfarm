<?php
$errors = []; // <-- initialize empty errors array
if(isset($_POST['contact_submit'])) {
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
    $to = 'David Gaskin <davidburkegaskin@gmail.com>'; // this may cause a bug on Windows systems
    $subject.= " at ".strftime("%Y", time());
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
      echo "Thank you {$name} for getting in touch!";
    }else{
      echo "We were unable to send your message at this time. Please try again later.";
    }
  }
} else { $name = $email = $subject = $message = ""; }
?>
<!-- Contact Form starts here -->
<?php if($errors) { ?>
  <div class="errors_php">
    <span>Please fix the errors below before submitting:</span>
    <ul>
      <?php foreach ($errors as $error) {
        echo "<li>{$error}</li>";
      } ?>
    </ul>
  </div>
<?php } ?>
<form id="contact-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
  <div>
    <label for="name">
    <input type="text" id="name" name="name" value="<?php echo $name ?>" placeholder="Your name" autocomplete="off" minlength="2" required><div class="errorContainer error"></div>
    </label>
  </div>
  <div>
    <label for="email">
    <input type="email" id="email" name="email" value="<?php echo $email ?>" placeholder="Contact email" required>
    <div class="errorContainer"></div>
    </label>
  </div>
  <div>
    <label for="subject">
    <input type="text" id="subject" name="subject" value="<?php echo $subject ?>" placeholder="Subject of message (optional)"/>
    </label>
  </div>
  <div>
    <label for="msg">
    <textarea id="msg" name="message" placeholder="Comments, feedback, suggestions..." minlength="3" required><?php echo $message ?></textarea>
    <div class="errorContainer"></div>
    </label>
  </div>
  <div class="button">
    <button type="submit" class="contact" name="contact_submit">Send Your Message</button> <!-- value="submit"-->
  </div>
</form>
<div id="thank-you_message" class="hide"><p></p></div>
<!-- End of Contact Form -->

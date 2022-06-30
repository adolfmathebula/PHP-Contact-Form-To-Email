<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <title>PHP Contact Form to Email</title>
<link rel="stylesheet" href="pagestyle.css" />
</head>
<body class="form-page">
  

<div class="form-container">
<?php 
if (isset($_POST["op"]) && ($_POST["op"]=="send")) { 

/*.........START OF CONFIG SECTION .........*/
  $sendto  = "adolf.mathebula@gmail.com";
  $subject = "Website Contact Enquiry";
// Select if you want to check form for standard spam text
  $SpamCheck = "Y"; // Y or N
  $SpamReplaceText = "*content removed*";
// Error message prited if spam form attack found
$SpamErrorMessage = "<p align=\"center\"><font color=\"red\">Malicious code content detected.
</font><br><b>Your IP Number of <b>".getenv("REMOTE_ADDR")."</b> has been logged.</b></p>";
/*.........END OF CONFIG SECTION .........*/

  $name = $_POST['name']; 
  $email = $_POST['email']; 
  $number = $_POST['number']; 
  $services = $_POST['services']; 
  $message = $_POST['message']; 
  
  $headers = "From: $email\n";
  $headers . "MIME-Version: 1.0\n"
		   . "Content-Transfer-Encoding: 7bit\n"
		   . "Content-type: text/html;  charset = \"iso-8859-1\";\n\n";
if ($SpamCheck == "Y") {		   
// Check for Website URL's in the form input boxes as if we block website URLs from the form,
// then this will stop the spammers wastignt ime sending emails
if (preg_match("/http/i", "$name")) {echo "$SpamErrorMessage"; exit();} 
if (preg_match("/http/i", "$email")) {echo "$SpamErrorMessage"; exit();}
if (preg_match("/http/i", "$number")) {echo "$SpamErrorMessage"; exit();}
if (preg_match("/http/i", "$services")) {echo "$SpamErrorMessage"; exit();}
if (preg_match("/http/i", "$message")) {echo "$SpamErrorMessage"; exit();} 

// Patterm match search to strip out the invalid charcaters, this prevents the mail injection spammer 
  $pattern = '/(;|\||`|>|<|&|^|"|'."\n|\r|'".'|{|}|[|]|\)|\()/i'; // build the pattern match string 
                            
  $name = preg_replace($pattern, "", $name); 
  $email = preg_replace($pattern, "", $email);
  $number = preg_replace($pattern, "", $number);
  $servives = preg_replace($pattern, "", $services); 
  $message = preg_replace($pattern, "", $message); 

// Check for the injected headers from the spammer attempt 
// This will replace the injection attempt text with the string you have set in the above config section
  $find = array("/bcc\:/i","/Content\-Type\:/i","/cc\:/i","/to\:/i"); 
  $email = preg_replace($find, "$SpamReplaceText", $email); 
  $number = preg_replace($find, "$SpamReplaceText", $number); 
  $name = preg_replace($find, "$SpamReplaceText", $name); 
  $services = preg_replace($find, "$SpamReplaceText", $services);
  $message = preg_replace($find, "$SpamReplaceText", $message); 
  
// Check to see if the fields contain any content we want to ban
 if(stristr($name, $SpamReplaceText) !== FALSE) {echo "$SpamErrorMessage"; exit();} 
 if(stristr($message, $SpamReplaceText) !== FALSE) {echo "$SpamErrorMessage"; exit();} 
  if(stristr($number, $SpamReplaceText) !== FALSE) {echo "$SpamErrorMessage"; exit();} 
  if(stristr($services, $SpamReplaceText) !== FALSE) {echo "$SpamErrorMessage"; exit();} 
 
 // Do a check on the send email and subject text
 if(stristr($sendto, $SpamReplaceText) !== FALSE) {echo "$SpamErrorMessage"; exit();} 
 if(stristr($subject, $SpamReplaceText) !== FALSE) {echo "$SpamErrorMessage"; exit();} 
}
// Build the email body text
  $emailcontent = " 

   WEBSITE CONTACT ENQUIRY
_______________________________________

Name: $name 
Email: $email 
Number: $number 
Services of Interest: $services
Message: $message 

_______________________________________ 
Contact as soon as possible 
"; 
// Check the email address enmtered matches the standard email address format
 if (!preg_match("^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$^", $email)) { 
  echo "<p>It appears you entered an invalid email address</p><p><a href='javascript: history.go(-1)'>Click here to go back</a>.</p>"; 
} 

 elseif (!trim($name)) { 
  echo "<p>Please go back and enter a Name</p><p><a href='javascript: history.go(-1)'>Click here to go back</a>.</p>"; 
} 


 elseif (!trim($message)) { 
  echo "<p>Please go back and type a Message</p><p><a href='javascript: history.go(-1)'>Click here to go back</a>.</p>"; 
}  

 elseif (!trim($email)) { 
  echo "<p>Please go back and enter an Email</p><p><a href='javascript: history.go(-1)'>Click here to go back</a>.</p>"; 
} 

elseif (!trim($number)) { 
  echo "<p>Please go back and type a your number</p><p><a href='javascript: history.go(-1)'>Click here to go back</a>.</p>"; 
} 

// Sends out the email or will output the error message 
 elseif (mail($sendto, $subject, $emailcontent, $headers)) {  
  echo "<br><br><p><b>Thank You  $name For Contacting</b></p><p>We will be in touch as soon as possible.</p>"; 

} 
} 
else { 
?> 

<div class="container">
  
  <p class="form-heading">Please complete all details of your enquiry/order<br>and we will get back to you shortly.</p>
  <form class="form-horizontal"  method="post"><INPUT NAME="op" TYPE="hidden" VALUE="send">
    
    <div class="form-group">

      <label class="control-label col-sm-5" for="email">Name:</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" id="email" placeholder="Enter Your Name or Company Name" name="name">
      </div>
    </div>
   
   <div class="form-group">
      <label class="control-label col-sm-5" for="email">Email:</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" id="email" placeholder="Enter Your Email" name="email">
      </div>
    </div>
    
    <div class="form-group">
      <label class="control-label col-sm-5" for="email">Contact Number:</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" id="email" placeholder="Enter Your Contact Number" name="number">
      </div>
    </div>
    
    <div class="form-group">
      <label class="control-label col-sm-5" for="email">What Services Are You Interested In:</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" id="email" placeholder="Service of Interest" name="services">
      </div>
    </div>
    
    <div class="form-group">
      <label class="control-label col-sm-5" for="email">Details About  Your Services:</label>
      <div class="col-sm-5">
        <textarea type="text" class="form-control" id="email" placeholder="Tell Us More About Your Enquries" name="message" rows="5"></textarea>
      </div>
    </div>
    
    
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-8 submit-button">
        <input name="submit" type="submit" class="btn btn-primary" value="Send Message">
      </div>
    </div>
  </form>
</div>

<?php } ?>

</div>

</body>
</html>
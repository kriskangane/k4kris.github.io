<?php

// Base config settings
$siteOwnersEmail = 'krishna.kangane@gmail.com';
$servername = "localhost";
$username = "254575";
$password = "34VIo5YoxHpG";
$dbname = "254575";

date_default_timezone_get();
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if($_POST) 
{
    $client_name = trim(mysqli_real_escape_string($conn, $_POST['contactName']));
    $client_email = trim(mysqli_real_escape_string($conn, $_POST['contactEmail']));
    $client_subject = trim(mysqli_real_escape_string($conn, $_POST['contactSubject']));
    $client_message = trim(mysqli_real_escape_string($conn, $_POST['contactMessage']));
    $submit_time = date("Y-m-d H:i:s");
    
    // Check Name
	if (strlen($client_name) < 2) 
	{
		$error['client_name'] = "Please enter your name.";
	}
	// Check Email
	if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $client_email)) 
	{
		$error['client_email'] = "Please enter a valid email address.";
	}
    // Check Subject
	if ($client_subject == '') 
	{ 
	    $client_subject = "Contact Form Submission"; 
	}
	// Check Message
	if (strlen($client_message) < 15) 
	{
		$error['client_message'] = "Please enter your message. It should have at least 15 characters.";
	}
   

    // Check connection
    if(!$conn) 
    {
        die("Connection failed. PLease email the below code to : <br>krishna.kangane@gmail.com<br> Connection failed: " . mysqli_connect_error());
    }
    else
    {
        $sql = "INSERT INTO contact_form(client_name, client_email, client_subject, client_message, posted_at) VALUE ('$client_name', '$client_email', '$client_subject', '$client_message', '$submit_time')";
        if($query = mysqli_query($conn, $sql))
        {
            echo "Your Data saved in Database Successfully.";
            
            // Set Message
            $message .= "Email from: " . $client_name . "<br />";
            $message .= "Email address: " . $client_email . "<br />";
            $message .= "Message: <br />";
            $message .= $client_message;
            $message .= "<br /> ----- <br /> This email was sent from your site's contact form. <br />";
            
            // Set From: header
            $from =  $client_name . " <" . $client_email . ">";
            
            // Email Headers
            $headers = "From: " . $from . "\r\n";
            $headers .= "Reply-To: ". $siteOwnersEmail . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            
            
            if (!$error) 
            {
                ini_set("sendmail_from", $siteOwnersEmail); // for windows server
                @$mail = mail($siteOwnersEmail, $client_subject, $client_message, $headers);
            
                if ($mail) 
                { 
                    echo "OK"; 
                }
                else 
                { 
                    echo nl2br("\nDue to server limitations Email service has been put on hold. \nThanks for your message, I'll contact you soon.");
                }
            } # end if - no validation error
            else 
            {
                $response = (isset($error['client_name'])) ? $error['client_name'] . "<br /> \n" : null;
                $response .= (isset($error['client_email'])) ? $error['client_email'] . "<br /> \n" : null;
                $response .= (isset($error['client_message'])) ? $error['client_message'] . "<br />" : null;

                echo $response;
            } # end if - there was a validation error
        }
        else
        {
            echo "Something went wrong. Please try again after some time or please Email me at : krishna.kangane@gmail.com"; 
        }
    }
}
?>				
<?php

require_once __DIR__ . '/vendor/autoload.php';
use mikehaertl\wkhtmlto\Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception; 
use PHPMailer\PHPMailer\SMTP;

function printCertificate($Teacher_Name, $Teacher_Email, $Account_Name, $Workshop_Name, $Workshop_Date)
{
	//echo "Teacher Name: $Teacher_Name <br> $Teacher_Email <br> school: $Account_Name <br> Workshop: $Workshop_Name ($Workshop_Date) <br>";
	$Teacher_Name = "اسم المعلم"; 
	$Teacher_Email= "diana@edutechnoz.com"; 
	$Account_Name = "English School";
	$Workshop_Name = "Innovate Reading Workshop ورشة ترميم القراءة" ;
	$Workshop_Date = "2021-09-14";
	
	$html = '
		<html>
		<body>
		<div style="position: relative;"> <img src="https://edutechnoz.com/images/Certificates/Certificate.png" style="width:700px; height:630px;">
		<div style="position: absolute; top: 60px; left: 85px;">
		<table align="center"><tbody><tr><td align="center">
		<p align="center"> <font size="6" color="#333333"><b>Certificate of Attendance <br>شهادة حضور</b></font><b> </b></p><b> <p align="center"><font size="3" color="#333333">Presented To - مقدمة لـ </font><br>
		</p><p align="center"><font size="4" color="#4a508f">'.$Teacher_Name.'<br> '.$Account_Name.'</font></p>
		<p align="center"> <font size="3" color="#333333"> For Attending -  لحضور  <br> '.$Workshop_Name.' </font></p> <p align="center"> <table><tbody><tr><td valign="bottom"> <div align="center"><img src="https://edutechnoz.com/images/page/logoSmall.png" style="width:200px; height:35px;"><br><font size="3" color="#333333"> Signature-التوقيع </font><p></p> 
		</div></td><td valign="middle" width="197px" height="250">
		<p align="center"><img src="https://edutechnoz.com/images/Certificates/PerfectScore.png" alt="Perfect Score Badge" style="width:197px; height:250px;">  </p></td><td valign="bottom"> <font size="3" color="#333333"> Date-التاريخ<br> </font> <font size="2" color="#333333">'.$Workshop_Date.' </font> <p></p> </td></tr> </tbody></table> </p></b></td></tr> </tbody></table></div></div>
		</body>
		</html>	
	';
	
	$pdf = new Pdf(array(
		'binary' => 'wkhtmltox\bin\wkhtmltopdf.exe',
		'ignoreWarnings' => true,
		'page-width' => '175',
		'page-height' => '156',
		'encoding' => 'UTF-8', 
		'commandOptions' => array(
			'useExec' => true,      // Can help on Windows systems
			'procEnv' => array(
				'LANG' => 'UTF-8',
			),
		),
	));

	$pdf->addPage($html);

	if (!$pdf->saveAs('page.pdf')) {
		echo $pdf->getError();
	}
	$mail = new PHPMailer(true); 
	
	$mail->SMTPDebug = SMTP::DEBUG_SERVER;                     
    $mail->isSMTP();                                            
    $mail->Host       = "ssl://vps.edutechnoz.com";                    
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = "web@edutechnoz.com";                     
    $mail->Password   = 'HK}(u]6t.Wz;';                               
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;           
    $mail->Port       = 465; 
	$mail->CharSet = 'UTF-8';

	$mail->From = "web@edutechnoz.com";
	$mail->FromName = "Email Master";

	$mail->addAddress($Teacher_Email);
	$mail->addCC("sales@edutechnoz.com");

	//Provide file path and name of the attachments
	$mail->addAttachment("page.pdf", "Certificate.pdf");        

	$mail->isHTML(true);

	$mail->Subject = "Certificate for $Teacher_Name";
	$mail->Body = "<i>The certificate has been provided for you!</i>";

	try {
		$mail->send();
		echo "Message has been sent successfully";
	} catch (Exception $e) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	}
	return 1; 
}
echo printCertificate(); 

?>
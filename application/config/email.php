<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['protocol'] = 'smtp';
$config['charset'] = 'iso-8859-1';
$config['smtp_host'] = "smtp.gmail.com";
$config['smtp_user'] = "kasoprecede47@gmail.com";
$config['smtp_pass'] = "bullJohn";
$config['smtp_port'] = 587;
$config['mailtype'] = "html";
$config['validate'] = true;
/*
 *
 * address: smtp.gmail.com
port: 587
authentication: login
domain: gmail.com
user_name: kasoprecede47@gmail.com
password: *your password*
 *
 *
mailpath	/usr/sbin/sendmail	None	The server path to Sendmail.
smtp_host	No Default	None	SMTP Server Address.
smtp_user	No Default	None	SMTP Username.
smtp_pass	No Default	None	SMTP Password.
smtp_port	25	None	SMTP Port.
smtp_timeout	5	None	SMTP Timeout (in seconds).
wordwrap	TRUE	TRUE or FALSE (boolean)	Enable word-wrap.
wrapchars	76		Character count to wrap at.
mailtype	text	text or html	Type of mail. If you send HTML email you must send it as a complete web page. Make sure you don't have any relative links or relative image paths otherwise they will not work.
charset	utf-8		Character set (utf-8, iso-8859-1, etc.).
validate	FALSE	TRUE or FALSE (boolean)	Whether to validate the email address.
priority	3	1, 2, 3, 4, 5	Email Priority. 1 = highest. 5 = lowest. 3 = normal.
crlf	\n	"\r\n" or "\n" or "\r"	Newline character. (Use "\r\n" to comply with RFC 822).
newline	\n	"\r\n" or "\n" or "\r"	Newline character. (Use "\r\n" to comply with RFC 822).
bcc_batch_mode	FALSE	TRUE or FALSE (boolean)	Enable BCC Batch Mode.
bcc_batch_size	200	None	Number of emails in each BCC batch.
*/
# mailers
a collection of mailing tools

# usage
for py-smtpauth or py-gmail
	
	you will need to edit the file to include valid 	information for your environment.

	python py-smtpauth -f from@addr -t to@addr -s 	"subject" -b "the body of the message" -a 	an_optional_attachment.txt

for php-mailTo
	
	you will first need to edit mail.php to reflect a 	username, password, and server information for your 	environment
	
	you will need to include mail.php on the pages you 	wish to have access to the mailer. 
	
	Then you can call mailTo($to, $subject, $body)

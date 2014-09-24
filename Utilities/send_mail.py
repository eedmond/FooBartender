#!/usr/bin/python
 
# Import smtplib for the actual sending function
import smtplib
 
# For guessing MIME type
import mimetypes
 
# Import the email modules we'll need
import email
import email.mime.application
 
#Import sys to deal with command line arguments
import sys
 
# Create a text/plain message
msg = email.mime.Multipart.MIMEMultipart()
msg['Subject'] = sys.argv[1]
msg['From'] = 'triomproductions@gmail.com'
msg['To'] = 'triomproductions@gmail.com'
 
# The main body is just another attachment

body = email.mime.Text.MIMEText(sys.argv[2])
msg.attach(body)
 
# PDF attachment block code

add_attachment = 1;
if (len(sys.argv) < 4):
	add_attachment = 0;

if (add_attachment == 1):
	directory=sys.argv[2]
	 
	# Split de directory into fields separated by / to substract filename
	 
	spl_dir=directory.split('/')
	 
	# We attach the name of the file to filename by taking the last
	# position of the fragmented string, which is, indeed, the name
	# of the file we've selected
	 
	filename=spl_dir[len(spl_dir)-1]
	 
	# We'll do the same but this time to extract the file format (pdf, epub, docx...)
	 
	spl_type=directory.split('.')
	 
	type=spl_type[len(spl_type)-1]
	 
	fp=open(directory,'rb')
	att = email.mime.application.MIMEApplication(fp.read(),_subtype=type)
	fp.close()
	att.add_header('Content-Disposition','attachment',filename=filename)
	msg.attach(att)
 
# send via Gmail server
# NOTE: my ISP, Centurylink, seems to be automatically rewriting
# port 25 packets to be port 587 and it is trashing port 587 packets.
# So, I use the default port 25, but I authenticate.
s = smtplib.SMTP('smtp.gmail.com:587')
s.starttls()
s.login('triomproductions@gmail.com','pi@1335e')
s.sendmail('triomproductions@gmail.com',['triomproductions@gmail.com'], msg.as_string())
s.quit()
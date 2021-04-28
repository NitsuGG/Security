# Security
PHP class use to secure DB and html form :

Class DataSanitize :
  
  sanitize_var :
     Sanitize data by removing all html characters and EOL.
     
  sanitize_array :
     Sanitize array by removing all html characters and EOL.
      
  generate_csrf_token : 
     Generate a CSRF token into a session.
      
  generate_csrf_input : 
     Generate hidden input with csrf_token stored from session.
   
  csrf_verification : 
     Verify if the session token and the csrf token are the same.
     
Class Uploader :
  __construct : 
     Define object properties by file given and its path.
     
  checkMaxSize :
     Check if the file don't exceed the max size.
  
  check_extension : 
     Return True if the file have an authorized extension.
  
  check_empty : 
     Check if the file is empty.
     
  rename_file :
     Rename the file with a random string chain (10 characters by default) or with a name given.
     
  move_file :
     Move the final file from /tmp to the path given with name and extension.
  

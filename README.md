PHP Authentication
===================

This is a proceedural PHP authentication application that is intended to give developers a starting point for their web security.  The application supports a standard username/password combination and makes use of multiple security measures to prevent intrusion.

Requirements
-------------------

The application was developed using PHP 5.4.12 and MySQL 5.6.12.  Apache 2.4.4 was also used, but the application isn't server dependant.

A typical installation requires two MySQL tables;
- Users table
 - A column for the user ID number, which must be a unique INTEGER
 - A column for the username, which can be a variable length VARCHAR or other String data type
 - A column for the password, which is hashed using SHA256 and so must support at least 64 characters of String data
 - A binary column is required with a value of 1 for enabled or 0 for disabled.
- Sessions table
 - SID column for 64 character SHA256 hash
 - UID column for corresponding user ID INT
 - A last activity to track the last session use
 - An ID address column for session hijack prevention

Configuration
-------------------

Configure the application from within the configuration.php file.

Security
-------------------

There are multiple security measures used by this application to prevent unauthorized access into the protected area of the website.  In addition, there are measures in place to reduce the impact of a database compromise, in the event that access is gained through alternative means.

### Database Security

Only non-critical information in stored in the database in plain-text form.  Passwords and session IDs (both of which can be used to gain access) are both kept in the database in hashed form.  They are hashed using PHP's `hash()` function and the SHA256 algorithm, rendering them virtually useless in the event of a data compromise.

Additionally, the use of hashing goes a long way towards preventing SQL injection.  Submitted data is hashed prior to any database request, resulting in a 'safe' to use value that won't contain any potentially dangerous characters.

### Session Security

#### Session ID Generation

Session ID generation is probably needlessly elaborate in this application, but results in a virtually impossible to reverse ID that will never be the same as another user.  The session generation begins with the user ID, the username, and a randomly generated 3 digit number (the key).  The application salts these three pieces of information and hashes it using the MD5 algorithm.  The ID is then appended with the key again and salted once more, before the SHA256 `hash()` function is used.

#### Session ID Storage

Session IDs are stored in two different formats.  In the databse they are simply stored as an SHA256 hashed string (`'[a-zA-Z0-9]+'`).  However, a cookie on the user's computer is also required.  Cookies are high risk because the methods of obtaining them are vast and ever-changing.  For this reason, the session information in the cookie is kept in the MD5 format with a copy of the user ID and the key (`'[a-zA-Z0-9]+:[0-9]+[R]?'`).  This method makes it harder for information from the database or cookies to be used to generate or steal sessions.

In addition, both the MD5 version of the session and the SHA256 version are individually salted.

#### Session Validation

Validating a session is a difficult task to do securely.  There are many ways that a session could be stolen or faked, and this application tries to prevent them all.  The session is validated in two different ways.  First, the session cookie is validated and parsed into it's individual pieces (MD5'd session ID, user ID, and key).  Any deviation from the standard format results in a failed validation.  Once the key is obtained, the MD5 session ID is converted to the SHA256 session ID and the database query occurrs.

Various other information is also compared to ensure that the entirty of the cookie is valid, including the user ID.  If session hijack prevention is turned on the validation process will also compare the IP address of the current remote machine with the IP address of the machine that created the session.

(Note: This method will only prevent session hijacks that are attempted from outside a NAT.  From within the NAT, both machines will appear to the server as having the same 'public' IP address)

Once the database information is confirmed, the session is regenerated and verrified to match the original.  A reset of the cookie time occurrs.

#### Session Expiration

A session is destroyed whenever the cookie on the user's computer is destroyed.  This could happen any number of ways.  By default, the session will expire after 7200 seconds (120 minutes, or 2 hours).  If the 'remember me' toggle is checked the session time is extended to 999999999 seconds (16666666.65 minutes, 277777.7775 hours, or 11574.0740625 days).  Every time the user browses to a page where the session needs to be validated, successful validation will result in the cookie being reset.  This will destroy the session automatically after the designated period of inactivity.  A failed validation or other removal or change to the cookie will also result in the destruction of the session cookie.

LICENSE
===================
[Creative Commons Attribution-NonCommercial 4.0 International](http://creativecommons.org/licenses/by-nc/4.0/ "Creative Commons - Attribution-NonCommercial 4.0 International - CC BY-NC 4.0")

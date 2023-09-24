<?php
require_once('../../../config.php');
// The version number (9_5_0) should match version of the Chilkat extension used, omitting the micro-version number.
// For example, if using Chilkat v9.5.0.48, then include as shown here:
include($CFG->dirroot . "/local/cria/classes/chilkat_9_5_0.php");

// An Office365 OAuth2 access token must first be obtained prior
// to running this code.

// Getting the OAuth2 access token for the 1st time requires the O365 account owner's
// interactive authorizaition via a web browser.  Afterwards, the access token
// can be repeatedly refreshed automatically.

// See the following examples for getting and refreshing an OAuth2 access token

// Get Office365 SMTP/IMAP/POP3 OAuth2 Access Token
// Refresh Office365 SMTP/IMAP/POP3 OAuth2 Access Token

// First get our previously obtained OAuth2 access token.
$jsonToken = new CkJsonObject();
$success = $jsonToken->LoadFile('qa_data/tokens/office365.json');
if ($success == false) {
    print 'Failed to open the office365 OAuth JSON file.' . "\n";
    exit;
}

$imap = new CkImap();

$imap->put_Ssl(true);
$imap->put_Port(993);

// Connect to the Office365 IMAP server.
$success = $imap->Connect('outlook.office365.com');
if ($success != true) {
    print $imap->lastErrorText() . "\n";
    exit;
}

// Use OAuth2 authentication.
$imap->put_AuthMethod('XOAUTH2');

// Login using our username (i.e. email address) and the access token for the password.
$success = $imap->Login('OFFICE365_EMAIL_ADDRESS',$jsonToken->stringOf('access_token'));
if ($success != true) {
    print $imap->lastErrorText() . "\n";
    exit;
}

print 'O365 OAuth authentication is successful.' . "\n";

// Do something...
$success = $imap->SelectMailbox('Inbox');
if ($success != true) {
    print $imap->lastErrorText() . "\n";
    exit;
}

// Your application can continue to do other things in the IMAP session....

// When finished, logout and close the connection.
$success = $imap->Logout();
$success = $imap->Disconnect();

print 'Finished.' . "\n";

?>
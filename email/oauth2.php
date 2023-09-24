<?php

// The version number (9_5_0) should match version of the Chilkat extension used, omitting the micro-version number.
// For example, if using Chilkat v9.5.0.48, then include as shown here:
require_once('../../../config.php');
include($CFG->dirroot . "/local/cria/classes/chilkat_9_5_0.php");

// This example requires the Chilkat API to have been previously unlocked.
// See Global Unlock Sample for sample code.

// -----------------------------------------------------------------------------------------------
// Important:  Setup your App Registration in Azure beforehand.
//
// See Create Azure App Registration for use with IMAP, POP3, and SMTP
// -----------------------------------------------------------------------------------------------

$oauth2 = new CkOAuth2();

// This should be the port in the localhost callback URL for your app.
// The callback URL would look like "http://localhost:3017/" if the port number is 3017.
$oauth2->put_ListenPort(3017);

// Specify your OAuth2 authorization and token endpoints
// Replace "xxxxxxxxxx-71bf-4ebe-a866-738364321bf2" with your tenant ID,
// or one of the following keywords: "common", "organizations", "consumers".
//
// For more information about the /authorize endpoint, see Microsoft identity platform and OAuth 2.0 authorization code flow
$tenant_id = 'bbd8a7d5-a560-461b-9187-5408ab4e5b10';
$client_id = '0509f301-1787-4afc-ac73-5d7660a54cb7';
$client_secret = 'f.d8Q~GojYQvdCa~cpkVc8Emd_ok-y1fyw3Tjds3';


$oauth2->put_AuthorizationEndpoint('https://login.microsoftonline.com/' . $tenant_id . '-71bf-4ebe-a866-738364321bf2/oauth2/v2.0/authorize');
$oauth2->put_TokenEndpoint('https://login.microsoftonline.com/' . $tenant_id . '-71bf-4ebe-a866-738364321bf2/oauth2/v2.0/token');

// Replace these with actual values.
$oauth2->put_ClientId($client_id);
$oauth2->put_ClientSecret($client_secret);

$oauth2->put_CodeChallenge(false);

// Provide a SPACE separated list of scopes.
// Important: The offline_access scope is needed to get a refresh token.
$oauth2->put_Scope('openid profile offline_access https://outlook.office365.com/SMTP.Send https://outlook.office365.com/POP.AccessAsUser.All https://outlook.office365.com/IMAP.AccessAsUser.All');

// ----------------------------------------------------------------------
// Note: It is only the initial access token that must be obtained interactively using a browser.
// Once the initial OAuth2 access token is obtained, then it can refreshed indefinitely with no user interaction.
// ----------------------------------------------------------------------

// Begin the OAuth2 three-legged flow.  This returns a URL that should be loaded in a browser.
$url = $oauth2->startAuth();
if ($oauth2->get_LastMethodSuccess() != true) {
    print $oauth2->lastErrorText() . "\n";
    exit;
}

// At this point, your application should load the URL in a browser.
// For example,
// in C#: System.Diagnostics.Process.Start(url);
// in Java: Desktop.getDesktop().browse(new URI(url));
// in VBScript: Set wsh=WScript.CreateObject("WScript.Shell")
//              wsh.Run url
// in Xojo: ShowURL(url)  (see http://docs.xojo.com/index.php/ShowURL)
// in Dataflex: Runprogram Background "c:\Program Files\Internet Explorer\iexplore.exe" sUrl
// The Microsoft account owner would interactively accept or deny the authorization request.

// Add the code to load the url in a web browser here...
// Add the code to load the url in a web browser here...
// Add the code to load the url in a web browser here...

// ----------------------------------------------------------------------
// Note: Read about how browser caching of credentials can cause problems.
// See OAuth2 Browser Caching Credentials
// ----------------------------------------------------------------------

// Now wait for the authorization.
// We'll wait for a max of 30 seconds.
$numMsWaited = 0;
while (($numMsWaited < 30000) and ($oauth2->get_AuthFlowState() < 3)) {
    $oauth2->SleepMs(100);
    $numMsWaited = $numMsWaited + 100;
}

// If there was no response from the browser within 30 seconds, then
// the AuthFlowState will be equal to 1 or 2.
// 1: Waiting for Redirect. The OAuth2 background thread is waiting to receive the redirect HTTP request from the browser.
// 2: Waiting for Final Response. The OAuth2 background thread is waiting for the final access token response.
// In that case, cancel the background task started in the call to StartAuth.
if ($oauth2->get_AuthFlowState() < 3) {
    $oauth2->Cancel();
    print 'No response from the browser!' . "\n";
    exit;
}

// Check the AuthFlowState to see if authorization was granted, denied, or if some error occurred
// The possible AuthFlowState values are:
// 3: Completed with Success. The OAuth2 flow has completed, the background thread exited, and the successful JSON response is available in AccessTokenResponse property.
// 4: Completed with Access Denied. The OAuth2 flow has completed, the background thread exited, and the error JSON is available in AccessTokenResponse property.
// 5: Failed Prior to Completion. The OAuth2 flow failed to complete, the background thread exited, and the error information is available in the FailureInfo property.
if ($oauth2->get_AuthFlowState() == 5) {
    print 'OAuth2 failed to complete.' . "\n";
    print $oauth2->failureInfo() . "\n";
    exit;
}

if ($oauth2->get_AuthFlowState() == 4) {
    print 'OAuth2 authorization was denied.' . "\n";
    print $oauth2->accessTokenResponse() . "\n";
    exit;
}

if ($oauth2->get_AuthFlowState() != 3) {
    print 'Unexpected AuthFlowState:' . $oauth2->get_AuthFlowState() . "\n";
    exit;
}

print 'OAuth2 authorization granted!' . "\n";
print 'Access Token = ' . $oauth2->accessToken() . "\n";

// Get the full JSON response:
$json = new CkJsonObject();
$json->Load($oauth2->accessTokenResponse());
$json->put_EmitCompact(false);

// The JSON response looks like this:
//
// {
//   "token_type": "Bearer",
//   "scope": "IMAP.AccessAsUser.All openid POP.AccessAsUser.All profile SMTP.Send email",
//   "expires_in": 3599,
//   "ext_expires_in": 3599,
//   "access_token": "...",
//   "refresh_token": "...",
//   "id_token": "...",
//   "expires_on": "1592748507"
// }

print $json->emit() . "\n";

// Save the JSON to a file for future requests.
$fac = new CkFileAccess();
$fac->WriteEntireTextFile('qa_data/tokens/office365.json',$json->emit(),'utf-8',false);

?>

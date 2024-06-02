<?php

/**
* This file is part of Cria.
* Cria is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
* Cria is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Cria. If not, see <https://www.gnu.org/licenses/>.
*
* @package    local_cria
* @author     Patrick Thibaudeau
* @copyright  2024 onwards York University (https://yorku.ca)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/


require_once('../../../config.php');
$tenant_id = 'bbd8a7d5-a560-461b-9187-5408ab4e5b10';
$client_id = '0509f301-1787-4afc-ac73-5d7660a54cb7';
$client_secret = 'f.d8Q~GojYQvdCa~cpkVc8Emd_ok-y1fyw3Tjds3';
$redirect_uri = 'http://localhost/local/cria/email/inbox.php';

//$auth_url = "https://login.microsoftonline.com/$tenant_id/oauth2/v2.0/authorize?client_id=$client_id&response_type=code&redirect_uri=$redirect_uri&response_mode=query&scope=openid%20offline_access%20https%3A%2F%2Foutlook.office.com%2FIMAP.AccessAsUser.All";
//print_object($auth_url);
//// Redirect the user to $auth_url and get the authorization code from the response
//redirect($auth_url);
$token_url = "https://login.microsoftonline.com/$tenant_id/oauth2/v2.0/token";
$code = 'YOUR_AUTHORIZATION_CODE';

$data = array(
    'grant_type' => 'client_credentials',
    'client_id' => $client_id,
    'client_secret' => $client_secret,
//    'code' => $code,
    'redirect_uri' => $redirect_uri
);

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($token_url, false, $context);
$token = json_decode($result)->access_token;

// Use the token to authenticate to IMAP
$inbox = imap_open('{outlook.office365.com:993/imap/ssl}INBOX', '', '', null, 1, array('DISABLE_AUTHENTICATOR' => 'GSSAPI'));
imap_errors();
imap_alerts();
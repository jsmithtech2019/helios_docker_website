<?php
/*
    I'm going to be honest with you, this php is complete nonsense to me and this
    script is mostly a skeleton from this article online:
    https://www.php.net/manual/en/features.http-auth.php

    Basically the function pulls all usernames and password pairs from the database
    and stores them in a string key array. This array is then compared against the
    input data received from the user. Used to authenticate the Admin pages and
    the Results page.
 */

// Create connection to MySQL database
$con=mysqli_connect("db","root","helios","hitch");

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$users = array();
$result = mysqli_query($con,"SELECT email, pass FROM ADMIN_DATA");

while($row = mysqli_fetch_array($result))
{
    $users[$row['email']] = $row['pass'];
}

if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Digest realm="'.$realm.
           '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');

    die('Please enter credentials to access this page.');
}

// analyze the PHP_AUTH_DIGEST variable
if (!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) || !isset($users[$data['username']])){
    header('HTTP/1.1 401 Unauthorized');
    die('Wrong Credentials!');
}

// generate the valid response
$A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

if ($data['response'] != $valid_response){
    header('HTTP/1.1 401 Unauthorized');
    die('Wrong Credentials!');
}

// function to parse the http auth header
function http_digest_parse($txt)
{
    // protect against missing data
    $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
    $data = array();
    $keys = implode('|', array_keys($needed_parts));

    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

    foreach ($matches as $m) {
        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
        unset($needed_parts[$m[1]]);
    }

    return $needed_parts ? false : $data;
}
?>
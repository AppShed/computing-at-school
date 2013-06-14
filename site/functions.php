<?php

function base() {
    $protocol = 'http';
    if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') {
        $protocol = 'https';
    }
    return "$protocol://{$_SERVER['SERVER_NAME']}";
}

function fetchURL($url, $path = null, $repost = false, $auth = false) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    }
    if ($auth) {
        curl_setopt($ch, CURLOPT_USERPWD, "{$auth['username']}:{$auth['password']}");
    }

    if ($repost) {
        curl_setopt($ch, CURLOPT_POST, true);
        // same as <input type="file" name="file_box">
        $post = array();
        foreach ($_FILES as $key => $file) {
            $post[$key] = "@" . $file['tmp_name'];
        }
        foreach ($_POST as $key => $value) {
            $post[$key] = $value;
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }

    if ($path == null) {
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    }
    else {
        $fp = fopen($path, 'w+');
        curl_setopt($ch, CURLOPT_FILE, $fp);
    }
    $data = curl_exec($ch);

    if ($path) {
        fclose($fp);
    }

    if (curl_errno($ch)) {
        curl_close($ch);
        return false;
    }

    curl_close($ch);
    return $data;
}

function time_since($since) {
    $chunks = array(
        array(60 * 60 * 24 * 365 , 'year'),
        array(60 * 60 * 24 * 30 , 'month'),
        array(60 * 60 * 24 * 7, 'week'),
        array(60 * 60 * 24 , 'day'),
        array(60 * 60 , 'hour'),
        array(60 , 'minute'),
        array(1 , 'second')
    );

    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];
        if (($count = floor($since / $seconds)) != 0) {
            break;
        }
    }

    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
    return $print;
}
<?php
/* If the stream is non-SSL, loading this page with SSL can cause an error in
  some browsers. Using an environment variable we can redirect to HTTP. */

if (in_array('https', array($_SERVER['HTTP_X_FORWARDED_PROTO'], $_SERVER['REQUEST_SCHEME']))
    && str_starts_with(getenv('STREAM_URL'), 'http:')
    && (int) filter_var(getenv('REDIRECT_HTTPS'), FILTER_VALIDATE_BOOLEAN)) {
  header('HTTP/1.1 301 Moved Permanently');
  header("Location: http://" . $_SERVER['HTTP_HOST']);
  die();
}

error_reporting(E_ALL ^ E_NOTICE);
ini_set("display_errors", 1);

$servers = explode(' ', getenv('SERVERS'));
$pool_str = getenv('POOL_ADDRESS');

function query_ntp_server($timeserver) {
    try {
        $socket = fsockopen("udp://$timeserver", 123, $err_no, $err_str, 1);
        if ($socket) {
            $ret = '<span style="color:green;">✔</span> Online';
            fclose($socket);
        }
    } catch (Exception $e) {
        $ret = '<span style="color:red;">✘</span> Offline' . PHP_EOL;
    }
    return $ret;
}
?>

<!doctype html>
<html>
<head>
<title><?php print $pool_str; ?> status</title>
<meta name="description" content="NTP Server Status">
<meta name="keywords" content="ntp time monitoring">
<link rel="stylesheet" href="./simple.min.css">
<link rel="stylesheet" href="./custom.css">
</head>
<body>
<header>
  <h1><?php print $pool_str; ?> status</h1>
</header>

<main>
  <figure style="float:left;width:50%;display:inline-block;">
    <img src="<?php print getenv('STREAM_URL'); ?>" style="margin-left:auto;margin-right:auto;display:block;" />
    <figcaption><?php print getenv('STREAM_CAPTION'); ?></figcaption>
  </figure>

  <table style="float:right;">
  <?php
    foreach ($servers as $server) {
      print "    <tr><td>$server</td><td>" . query_ntp_server($server) . "</td></tr>" . PHP_EOL;
    }
  ?>
  </table>
</main>

</body>
</html>

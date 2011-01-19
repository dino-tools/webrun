<?php

/**
 * コマンドラインからウェブアプリケーションを起動するための簡易ドライバです。
 * 
 * $_SERVERや$_POSTに値を詰め込んでから目標のPHPファイルを起動することで
 * あたかもウェブから起動されたかのような動作を起こすのが目的です。
 *
 * しょぼいツールですが、わりと便利です。
 */

/**
 * usage
 */
function _webrun_usage()
{
  echo "usage:\n";
  echo "  php webrun.php POST index.php item=123&name=TAROU\n";
  exit(1);
}

$requestMethod = @$argv[1];
$targetSrc = @$argv[2];
$queryString = @$argv[3];

$requestMethod || _webrun_usage();
$targetSrc || _webrun_usage();

_webrun_chargeRequestParameter($requestMethod, $targetSrc, $queryString);
_webrun_chargeHttpdEnvironment($requestMethod, $targetSrc, $queryString);

_webrun_execEx($targetSrc);

/**
 * 実行して結果を出力
 * 目標のスクリプトから戻ってこないこと（exitしているなど）が予想される場合はこちらを使って下さい。
 */
function _webrun_execEx($targetSrc)
{
  include($targetSrc);
}

/**
 * 実行して結果を出力
 */
function _webrun_exec($targetSrc)
{
  ob_start();
  include($targetSrc);
  $contents = ob_get_clean();
  
  foreach (headers_list() as $hdr) {
    echo $hdr, "\n";
  }
  echo "\n";
  echo $contents, "\n";
}

/**
 * グローバル変数 $_GET, $_POST, $_REQUEST へ
 * 適当なリクエストパラメータを突っ込みます。
 * @return void
 */
function _webrun_chargeRequestParameter($requestMethod, $targetSrc, $queryString)
{
  if ($requestMethod == 'POST') {
    parse_str($queryString, $_POST);
    parse_str($queryString, $_REQUEST);
  } else if ($requestMethod == 'GET') {
    parse_str($queryString, $_GET);
    parse_str($queryString, $_REQUEST);
  } else {
    _webrun_mydie("unknown method");
  }
}

/**
 * グローバル変数 $_SERVER へ
 * 適当なhttpd環境変数を突っ込みます。
 * @return void
 */
function _webrun_chargeHttpdEnvironment($requestMethod, $targetSrc, $queryString)
{
  $_SERVER['HTTP_HOST'] = '127.0.0.1';
  $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ja; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13 ( .NET CLR 3.5.30729)';
  $_SERVER['HTTP_ACCEPT'] = 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
  $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'en-us,en;q=0.5';
  $_SERVER['HTTP_ACCEPT_ENCODING'] = 'gzip,deflate';
  $_SERVER['HTTP_ACCEPT_CHARSET'] = 'Shift_JIS,utf-8;q=0.7,*;q=0.7';
  $_SERVER['HTTP_KEEP_ALIVE'] = '115';
  $_SERVER['HTTP_CONNECTION'] = 'keep-alive';
  $_SERVER['HTTP_COOKIE'] = '';
  // $_SERVER['PATH'] = "";
  $_SERVER['SERVER_SIGNATURE'] = '<address>Atache/9.1.3 (GiantOS) Server at 127.0.0.1 Port 80</address>';
  $_SERVER['SERVER_SOFTWARE'] = 'Atache/9.1.3 (GiantOS)';
  $_SERVER['SERVER_NAME'] = '127.0.0.1';
  $_SERVER['SERVER_ADDR'] = '127.0.0.1';
  $_SERVER['SERVER_PORT'] = '80';
  $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
  $_SERVER['DOCUMENT_ROOT'] = dirname($targetSrc);
  $_SERVER['SERVER_ADMIN'] = 'root@localhost';
  $_SERVER['SCRIPT_FILENAME'] = $targetSrc;
  $_SERVER['REMOTE_PORT'] = '12345';
  $_SERVER['GATEWAY_INTERFACE'] = 'CGI/1.1';
  $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
  $_SERVER['REQUEST_METHOD'] = $requestMethod;
  $_SERVER['QUERY_STRING'] = $queryString;
  $_SERVER['REQUEST_URI'] = "/". basename($targetSrc);
  $_SERVER['SCRIPT_NAME'] = "/". basename($targetSrc);;
  $_SERVER['PHP_SELF'] = "/". basename($targetSrc);;
  $_SERVER['REQUEST_TIME'] = time();
}

/**
 * dieする
 */
function _webrun_mydie($msg)
{
  echo $msg, "\n";
  exit(1);
}

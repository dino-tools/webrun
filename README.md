# NAME

webrun

# WHAT IS THIS?

シェルのコマンドラインからウェブアプリケーションを起動するための簡易ドライバです。

# INSTALL

このファイル単体で動作しますので、チェックアウトしてそのままお使い下さい。

# DESCRIPTION

これはコマンドライン用のスクリプトです。

以下のような引数を取ります。

* 必須 "GET" or "POST"
* 必須 起動したいウェブアプリのPHPファイル名
* 任意 クエリーストリング（hoge=1&puga=abcとかいうやつ）

このツールは、以下のような動作を行います。

1. 引数のクエリストリングを$_POSTなどに値をつめこむ
2. $_SERVERに適当なhttpdっぽい値をつめこむ
3. 目標のPHPファイルをinclude()して実行する

$_SERVERや$_POSTに値を詰め込んでから目標のPHPファイルを起動することで
あたかもウェブから起動されたかのような動作を起こすのが目的です。

しょぼいツールですがわりと便利です。

# EXAMPLE

このような感じでシェルから起動して下さい

    $ php webrun.php POST /path/to/docroot/index.php item=123&name=TAROU
    
# CHANGELOG

* 2011/01/19 初版

# AUTHOR

* ryer

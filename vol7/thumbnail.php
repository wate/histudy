<?php
/*
 * -------------------
 *【概要】
 * -------------------
 * 元画像の縦横比を保持して画像のリサイズを行う
 *
 * -------------------
 *【利用方法】
 * -------------------
 * /thumbnail.php?src=<元画像ファイル>&w=<幅(最大)>&h=<高さ(最大)>&e=<拡大する？>q=<圧縮率(JPGのみ)
 * 　※srcを相対パスで指定する場合は、このファイルから見た場合のパスを指定
 * 　※「e」は省略可能(省略時は拡大しない)
 * 　※「q」は省略可能(省略時は100)
 *
 * -------------------
 *【ディレクトリ構造】
 * -------------------
 * /root/
 * 　├ cache/ <- ここに要書き込み権限
 * 　├ lib/PHPThumb/ <- ここに「PHP Thumbnailer」のファイルを展開
 * 　└ thumbnail.php
 */

//-----------------------
// 設定ここから
//-----------------------
//キャッシュファイルの格納先ディレクトリ
$cacheDir = dirname(__FILE__).'/cache/';

/**
 * PHP Thumbnailer
 * http://phpthumb.gxdlabs.com/
 * リサイズ用のライブラリの読み込み(要パス調整)
 */
require './lib/PHPThumb/ThumbLib.inc.php';

//ドキュメントルートのパス(基本的に修正不要)
$rootDir = preg_match('@/$@', $_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['DOCUMENT_ROOT']."/";

//-----------------------
// 設定ここまで
//-----------------------

try{
	if($_GET['src'] && $_GET['w'] && $_GET['h']){
		//キャッシュファイル名
		$cacheFile = false;

		$imageSrc = $_GET['src'];
		$maxWidth = $_GET['w'];
		$maxHeight = $_GET['h'];

		if(preg_match("/\.(gif|jpe?g|png)$/i", $imageSrc, $matches)){
			//拡張子
			$fileExt = $matches[1];

			//JPG画像の圧縮率(初期値)
			$resizeOption = array("jpegQuality" => 100);

			$jpegQuality = intval($_GET['q']);
			if(preg_match("/^jpe?g$/", $fileExt) && $jpegQuality && $jpegQuality <= 100){
				//JPGのみ圧縮率のパラメーターをチェックし、圧縮率を上書き
				$resizeOption["jpegQuality"] = $jpegQuality;
			}

			//画像の拡大を許可するか否かを設定
			$resizeOption["resizeUp"] = $_GET['e'] ? true : false;


			//URLのホスト名が現在のホスト名と一致する場合は、サイトルートからのパスに変換
			if(preg_match("@^https?://@", $imageSrc) && parse_url($imageSrc, PHP_URL_HOST) == $_SERVER['HTTP_HOST']){
				$imageSrc = parse_url($imageSrc, PHP_URL_PATH);
			}

			if( ! preg_match("@^https?://@", $imageSrc)){
				//画像ファイルがURLでない場合(ローカルファイルの場合)、引数を絶対パスに変換
				$imageSrc = preg_match("@^/@", $imageSrc) ? realpath($rootDir.substr($imageSrc, 1)) : realpath($rootDir.$imageSrc);

				//Windows用
				if(strtoupper(substr(PHP_OS, 0, 3) == "WIN")) $imageSrc = str_replace("\\", "/", $imageSrc);

				if($imageSrc && preg_match("@^".$rootDir."@", $imageSrc)){
					//絶対パスへの変換が成功した場合(ファイルが存在する場合)、キャッシュファイル名を生成
					//キャッシュファイル名：ハッシュ_<最大横幅>x<最大高さ>_<圧縮率>_<拡大>.<元拡張子>
					if(preg_match("/^jpe?g$/", $fileExt)){
						$cacheFile = md5($imageSrc)."_".$maxWidth."x".$maxHeight.'_'.$resizeOption["jpegQuality"].'_'.($_GET['e'] ? "1" : "0").'.'.$fileExt;
					}else{
						$cacheFile = md5($imageSrc)."_".$maxWidth."x".$maxHeight.'_'.($_GET['e'] ? "1" : "0").'.'.$fileExt;
					}
				}else{
					throw new Exception("Parameter error");
				}
			}

			if($imageSrc){
				//サムネイル化開始
				$thumb = PhpThumbFactory::create($imageSrc);
				$thumb->setOptions($resizeOption);
				if($cacheFile){
					//キャッシュが有効な場合
					if(file_exists($cacheDir.$cacheFile) && filemtime($cacheDir.$cacheFile) > filemtime($imageSrc)){
						switch (strtolower($fileExt)){
							case "gif":
								header('Content-type: image/gif');
								break;
							case "jpg":
							case "jpeg":
								header('Content-type: image/jpeg');
								break;
							case "png":
								header('Content-type: image/png');
								break;
						}
						header('Content-Length: '.filesize($cacheDir.$cacheFile));
						readfile($cacheDir.$cacheFile);
						exit;
					}else{
						//キャッシュファイルを生成して表示
						$thumb->resize($maxWidth, $maxHeight)->save($cacheDir.$cacheFile)->show();
					}
				}else{
					//キャッシュファイルを生成せずに表示
					$thumb->resize($maxWidth, $maxHeight)->show();
				}
			}else{
				throw new Exception("Parameter Error");
			}
		}else{
			throw new Exception("File type Error");
		}
	}else{
		throw new Exception("Parameter Error");
	}
}catch(Exception $e){
	exit($e->getMessage());
}

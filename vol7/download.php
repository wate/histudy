<?php
/*
 * -------------------
 *【概要】
 * -------------------
 * 画像とかPDFファイルをダウンロードさせる
 *
 * -------------------
 *【利用方法】
 * -------------------
 * /download.php?file=<ダウンロードさせるファイル>
 *
 * -------------------
 *【ディレクトリ構造】
 * -------------------
 * /download.php
 *
 */

//ドキュメントルートのパス(基本的に修正不要)
$rootDir = preg_match('@/$@', $_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['DOCUMENT_ROOT']."/";

//ダウンロードを許可するファイルの拡張子
$allowExtension = array(
						//MS Word
						"doc",
						"docx",

						//MS Excel
						"xls",
						"xlsx",

						//Image
						"jpg",
						"gif",
						"png",

						//Text
						"txt",
						"csv",
						"log",
						);

try{
	if($_GET["file"]){
		$filePath = $_GET["file"];
		if(preg_match("/\.".join("|", $allowExtension)."$/i", $filePath)){
			$filePath = preg_match("@^/@", $filePath) ? realpath($rootDir.substr($filePath, 1)) : realpath($rootDir.$filePath);
			//Windows用
			if(strtoupper(substr(PHP_OS, 0, 3) == "WIN")) $filePath = str_replace("\\", "/", $filePath);
			if($filePath){
				header("Content-length: ".filesize($filePath));
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
				readfile($filePath);
				exit;
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

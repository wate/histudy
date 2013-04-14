<?php
/**
 * h
 *
 * @param $data
 * @return void
 */
function h($data){
	return is_array($data) ? array_map(__FUNCTION__,$data) : htmlentities($data,ENT_COMPAT,"UTF-8");
}
/**
 * rh
 *
 * @param $data
 * @return void
 */
function rh($data){
	return is_array($data) ? array_map(__FUNCTION__,$data) : html_entity_decode($data,ENT_COMPAT,"UTF-8");
}
/**
 * f
 *
 * @param $number
 * @param $decimals
 * @return void
 */
function f($number,$decimals=0){
	return number_format($number,$decimals);
}
/**
 * df
 *
 * @param $dateString
 * @return void
 */
function df($dateString){
	return str_replace("-", "/", $dateString);
}
/**
 * idf
 *
 * @param $id
 * @param $padLength
 * @param $padString
 * @return void
 */
function idf($id,$padLength=6,$padString="0"){
	return str_pad($id,$padLength,$padString,STR_PAD_LEFT);
}
/**
 * r
 *
 * @param $funcName
 * @param $funcParam
 * @return void
 */
function r($funcName,$funcParam){
	return is_array($funcParam) ? array_map(__FUNCTION__,array($funcName,$funcParam)) : call_user_func($funcName,$funcParam);
}
/**
 *
 * @param type $string
 * @return type
 */
function ue($string){
	return urlencode($string);
}
/**
 *
 * @param type $string
 * @return type
 */
function ud($string){
	return urldecode($string);
}
?>
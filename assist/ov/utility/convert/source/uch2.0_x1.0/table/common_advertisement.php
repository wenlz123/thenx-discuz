<?php
/*
 *
 *  * Copyright 2012-2020 the original author or authors.
 *  *
 *  * Licensed under the Apache License, Version 2.0 (the "License");
 *  * you may not use this file except in compliance with the License.
 *  * You may obtain a copy of the License at
 *  *
 *  *      https://www.apache.org/licenses/LICENSE-2.0
 *  *
 *  * Unless required by applicable law or agreed to in writing, software
 *  * distributed under the License is distributed on an "AS IS" BASIS,
 *  * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  * See the License for the specific language governing permissions and
 *  * limitations under the License.
 *
 */

/**
 * DiscuzX Convert
 *
 * $Id: common_advertisement.php 10469 2010-05-11 09:12:14Z monkey $
 */

$curprg = basename(__FILE__);

$table_source = $db_source->tablepre.'ad';
$table_target = $db_target->tablepre.'common_advertisement';

$limit = 100;
$nextid = 0;

$start = getgpc('start');

$query = $db_source->query("SELECT  * FROM $table_source WHERE adid>'$start' AND pagetype IN('header','footer','rightside','couplet','feedbox') ORDER BY adid LIMIT $limit");
while ($ad = $db_source->fetch_array($query)) {
	$nextid = $ad['adid'];

	switch($ad['pagetype']) {
		case 'header':
			$ad['pagetype'] = 'headerbanner';break;
		case 'footer':
			$ad['pagetype'] = 'footerbanner';break;
		case 'rightside':
			$ad['pagetype'] = 'blog';break;
		case 'couplet':
			$ad['pagetype'] = 'couplebanner';break;
		case 'feedbox':
			$ad['pagetype'] = 'feed';break;
	}

	$advnew = unserialize($ad['adcode']);
	foreach($advnew as $k => $v) {
		if($k == 'flashheight') {
			$advarr = array('height' => $advnew['flashheight'], 'width' => $advnew['flashwidth'], 'url' => $advnew['flashurl'], 'style' => 'flash');
		} elseif($k == 'imageheight') {
			$advarr = array('height' => $advnew['imageheight'], 'width' => $advnew['imagewidth'], 'url' => $advnew['imagesrc'],
							'link' => $advnew['imageurl'], 'alt' => $advnew['imagealt'], 'style' => 'image');
		} elseif($k == 'textcontent') {
			$advarr = array('title' => $advnew['textcontent'], 'link' => $advnew['texturl'], 'size' => $advnew['textsize'], 'style' => 'text');
		} elseif($advnew['type'] == 'html') {
			$advarr = array('style' => 'code');
		}
	}
	if($advarr['style'] == 'code') {
		$html = $advarr['html'] = $advnew['html'];
		$html = daddslashes($html);
	} else {
		$html = daddslashes(encodeadvcode($advarr));
		$advarr['html'] = $html;
	}

	$advarr = daddslashes($advarr);
	$parameters = serialize($advarr);

	$ad  = daddslashes($ad, 1);

	$db_target->query("INSERT INTO $table_target SET `available`='".$ad[available].
					"',`type`='".$ad[pagetype]."',`displayorder`='".$ad[system]."',`parameters`='".$parameters."',`title`='".$ad[title]."',`targets`='home',`code`='".$html."'");
}

$res = $db_target->fetch_first("SELECT * FROM {$db_target->tablepre}common_advertisement_custom WHERE name='UCHOME'");
if(!$res) {
	$db_target->query("INSERT INTO {$db_target->tablepre}common_advertisement_custom SET `name`='UCHOME'");
}

if($nextid) {
	showmessage("继续转换数据表 ".$table_source." adid> $nextid", "index.php?a=$action&source=$source&prg=$curprg&start=$nextid");
}

function encodeadvcode($advnew) {
	switch($advnew['style']) {
		case 'text':
			$advnew['code'] = '<a href="'.$advnew['link'].'" target="_blank" '.($advnew['size'] ? 'style="font-size: '.$advnew['size'].'"' : '').'>'.$advnew['title'].'</a>';
			break;
		case 'image':
			$advnew['code'] = '<a href="'.$advnew['link'].'" target="_blank"><img src="'.$advnew['url'].'"'.($advnew['height'] ? ' height="'.$advnew['height'].'"' : '').($advnew['width'] ? ' width="'.$advnew['width'].'"' : '').($advnew['alt'] ? ' alt="'.$advnew['alt'].'"' : '').' border="0"></a>';
			break;
		case 'flash':
			$advnew['code'] = '<embed width="'.$advnew['width'].'" height="'.$advnew['height'].'" src="'.$advnew['url'].'" type="application/x-shockwave-flash" wmode="transparent"></embed>';
			break;
	}
	return $advnew['code'];
}
?>
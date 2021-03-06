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
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: thread_album.php 28709 2012-11-08 08:53:48Z liulanbo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require_once libfile('function/attachment');
$imglist = $albumpayaids = $attachmentlist = array();
foreach(C::t('forum_attachment_n')->fetch_all_by_id('tid:'.$_G['tid'], 'tid', $_G['tid'], 'aid') as $attach) {
	if($attach['uid'] != $_G['forum_thread']['authorid'] && IN_MOBILE != 2) {
		continue;
	}
	if($attach['isimage'] && !$_G['setting']['attachimgpost']) {
		$attach['isimage'] = 0;
	}
	$attach['attachimg'] = $attach['isimage'] && (!$attach['readperm'] || $_G['group']['readaccess'] >= $attach['readperm']) ? 1 : 0;
	if(!$attach['attachimg']) {
		continue;
	}
	if($attach['price'] && $_G['uid'] != $attach['uid']) {
		$albumpayaids[$attach['aid']] = $attach['aid'];
		$attach['payed'] = 0;
	} else {
		$attach['payed'] = 1;
	}
	$attachmentlist[$attach['aid']] = $attach;
}

if($albumpayaids) {
	foreach(C::t('common_credit_log')->fetch_all_by_uid_operation_relatedid($_G['uid'], 'BAC', array_keys($albumpayaids)) as $creditlog) {
		$attachmentlist[$creditlog['relatedid']]['payed'] = 1;
	}
}

foreach($attachmentlist as $attach) {
	if($attach['payed'] == 0) {
		continue;
	}
	$attach['url'] = ($attach['remote'] ? $_G['setting']['ftp']['attachurl'] : $_G['setting']['attachurl']).'forum/';
	$attach['dbdateline'] = $attach['dateline'];
	$attach['dateline'] = dgmdate($attach['dateline'], 'u');
	$imglist['aid'][] = $attach['aid'];
	$imglist['url'][] = $attach['url'].$attach['attachment'];
	$apids[] = $attach['pid'];
}

if(empty($imglist)) {
	showmessage('author_not_uploadpic');
}

foreach($postlist as $key=>$subpost) {
	if($subpost['first'] == 1 || in_array($subpost['pid'], $apids)) {
		unset($postlist[$key]);
	}
}
?>
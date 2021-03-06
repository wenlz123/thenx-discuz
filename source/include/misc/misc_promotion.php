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
 *      $Id: misc_promotion.php 25889 2011-11-24 09:52:20Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

global $_G;

if(!empty($_GET['fromuid'])) {
	$fromuid = intval($_GET['fromuid']);
	$fromuser = '';
} else {
	$fromuser = $_GET['fromuser'];
	$fromuid = '';
}

if(!$_G['uid'] || !($fromuid == $_G['uid'] || $fromuser == $_G['username'])) {

	if($_G['setting']['creditspolicy']['promotion_visit']) {
		if(!C::t('forum_promotion')->fetch($_G['clientip'])) {
			C::t('forum_promotion')->insert(array('ip' => $_G['clientip'], 'port' => $_G['remoteport'], 'uid' => $fromuid, 'username' => $fromuser), false, true);
			updatecreditbyaction('promotion_visit', $fromuid);
		}
	}

	if($_G['setting']['creditspolicy']['promotion_register']) {
		if(!empty($fromuser) && empty($fromuid)) {
			if(empty($_G['cookie']['promotion'])) {
				$fromuid = C::t('common_member')->fetch_uid_by_username($fromuser);
			} else {
				$fromuid = intval($_G['cookie']['promotion']);
			}
		}
		if($fromuid) {
			dsetcookie('promotion', ($_G['cookie']['promotion'] = $fromuid), 1800);
		}
	}

}

?>
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
 *      $Id: member_regverify.php 28003 2012-02-20 09:55:39Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

define('NOROBOT', TRUE);

if($_G['setting']['regverify'] == 2 && $_G['groupid'] == 8 && submitcheck('verifysubmit')) {

	if(($verify_member = C::t('common_member_validate')->fetch($_G['uid'])) && $verify_member['status'] == 1) {
		C::t('common_member_validate')->update($_G['uid'], array(
			'submittimes' => $verify_member['submittimes']+1,
			'submitdate' => $_G['timestamp'],
			'status' => '0',
			'message' => dhtmlspecialchars($_GET['regmessagenew'])
		));
		showmessage('submit_verify_succeed', 'home.php?mod=spacecp&ac=profile');
	} else {
		showmessage('undefined_action');
	}

}

?>
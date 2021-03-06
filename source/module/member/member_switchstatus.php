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
 *      $Id: member_switchstatus.php 27203 2012-01-11 03:14:19Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

define('NOROBOT', TRUE);

if($_G['uid']) {

	if(!$_G['group']['allowinvisible']) {
		showmessage('group_nopermission', NULL, array('grouptitle' => $_G['group']['grouptitle']), array('login' => 1));
	}

	$_G['session']['invisible'] = $_G['session']['invisible'] ? 0 : 1;
	C::app()->session->update_by_uid($_G['uid'], array('invisible' => $_G['session']['invisible']));
	C::t('common_member_status')->update($_G['uid'], array('invisible' => $_G['session']['invisible']), 'UNBUFFERED');
	if(!empty($_G['setting']['sessionclose'])) {
		dsetcookie('ulastactivity', TIMESTAMP.'|'.getuserprofile('invisible'), 31536000);
	}
	$language = lang('forum/misc');
	$msg = $_G['session']['invisible'] ? $language['login_invisible_mode'] : $language['login_normal_mode'];
	showmessage('<a href="member.php?mod=switchstatus" title="'.$language['login_switch_invisible_mode'].'" onclick="ajaxget(this.href, \'loginstatus\');return false;" class="xi2">'.$msg.'</a>', dreferer(), array(), array('msgtype' => 3, 'showmsg' => 1));

}

?>
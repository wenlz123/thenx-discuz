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
 *      $Id: magic_checkonline.php 29373 2012-04-09 07:55:30Z chenmengshu $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class magic_checkonline {

	var $version = '1.0';
	var $name = 'checkonline_name';
	var $description = 'checkonline_desc';
	var $price = '10';
	var $weight = '10';
	var $useevent = 1;
	var $targetgroupperm = true;
	var $copyright = '<a href="http://www.comsenz.com" target="_blank">Comsenz Inc.</a>';
	var $magic = array();
	var $parameters = array();

	function getsetting(&$magic) {
	}

	function setsetting(&$magicnew, &$parameters) {
	}

	function usesubmit() {
		global $_G;
		if(empty($_GET['username'])) {
			showmessage(lang('magic/checkonline', 'checkonline_info_nonexistence'));
		}

		$member = getuserinfo($_GET['username']);
		$this->_check($member['groupid']);

		$online = C::app()->session->fetch_by_uid($member['uid']);

		usemagic($this->magic['magicid'], $this->magic['num']);
		updatemagiclog($this->magic['magicid'], '2', '1', '0', 0, 'uid', $member['uid']);

		if($member['uid'] != $_G['uid']) {
			notification_add($member['uid'], 'magic', lang('magic/checkonline', 'checkonline_notification'), array('magicname' => $this->magic['name']), 1);
		}

		if($online) {
			$time = dgmdate($online['lastactivity'], 'u');
			if($online['invisible']) {
				showmessage(lang('magic/checkonline', 'checkonline_hidden_message'), '', array('username' => $_GET['username'], 'time' => $time), array('alert' => 'info', 'showdialog' => 1));
			} else {
				showmessage(lang('magic/checkonline', 'checkonline_online_message'), '', array('username' => $_GET['username'], 'time' => $time), array('alert' => 'info', 'showdialog' => 1));
			}
		} else {
			showmessage(lang('magic/checkonline', 'checkonline_offline_message'), '', array('username' => $_GET['username']), array('alert' => 'info', 'showdialog' => 1));
		}
	}

	function show() {
		global $_G;
		$user = !empty($_GET['id']) ? dhtmlspecialchars($_GET['id']) : '';
		if($user) {
			$member = getuserinfo($user);
			$this->_check($member['groupid']);
		}
		magicshowtype('top');
		magicshowsetting(lang('magic/checkonline', 'checkonline_targetuser'), 'username', $user, 'text');
		magicshowtype('bottom');
	}

	function buy() {
		global $_G;
		if(!empty($_GET['id'])) {
			$member = getuserinfo($_GET['id']);
			$this->_check($member['groupid']);
		}
	}

	function _check($groupid) {
		if(!checkmagicperm($this->parameters['targetgroups'], $groupid)) {
			showmessage(lang('magic/checkonline', 'checkonline_info_noperm'));
		}
	}

}

?>
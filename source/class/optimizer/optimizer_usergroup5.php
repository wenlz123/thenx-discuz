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
 *      $Id: optimizer_usergroup5.php 33594 2013-07-12 07:38:33Z jeffjzhang $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class optimizer_usergroup5 {

	public function __construct() {

	}

	public function check() {
		$usergroup = C::t('common_usergroup')->fetch(5);
		$usergroupfield = C::t('common_usergroup_field')->fetch(5);
		if(!$usergroup['allowsendpm'] && !$usergroupfiled['allowposturl'] && !$usergroupfield['allowgroupposturl'] && !$usergroupfield['allowpost'] && !$usergroupfield['allowreply'] && !$usergroupfiled['allowdirectpost'] && !$usergroupfield['allowgroupdirectpost']) {
			$return = array('status' => 0, 'type' =>'none', 'lang' => lang('optimizer', 'optimizer_usergroup5_no_need'));
		} else {
			$option = array(
				'allowsendpm' => lang('optimizer', 'optimizer_usergroup_need_allowsendpm'),
				'allowposturl' => lang('optimizer', 'optimizer_usergroup_need_allowposturl'),
				'allowgroupposturl' => lang('optimizer', 'optimizer_usergroup_need_allowgroupposturl'),
				'allowpost' => lang('optimizer', 'optimizer_usergroup_need_allowpost'),
				'allowreply' => lang('optimizer', 'optimizer_usergroup_need_allowreply'),
				'allowdirectpost' => lang('optimizer', 'optimizer_usergroup_need_allowdirectpost'),
				'allowgroupdirectpost' => lang('optimizer', 'optimizer_usergroup_need_allowgroupdirectpost'),
			);
			$usergroup = array_merge((array)$usergroup, (array)$usergroupfield);
			$desc = array();
			foreach($option as $key => $value) {
				if($usergroup[$key]) {
					$desc[] = $value;
				}
			}
			$return = array('status' => 1, 'type' =>'header', 'lang' => lang('optimizer', 'optimizer_usergroup5_need', array('desc' => implode(',', $desc))));
		}
		return $return;
	}

	public function optimizer() {
		$adminfile = defined(ADMINSCRIPT) ? ADMINSCRIPT : 'admin.php';
		dheader('Location: '.$_G['siteurl'].$adminfile.'?action=usergroups&operation=edit&id=5');
	}
}

?>
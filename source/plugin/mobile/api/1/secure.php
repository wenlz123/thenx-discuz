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
 *      $Id: secure.php 34397 2014-04-14 06:53:24Z nemohou $
 */

if(!defined('IN_MOBILE_API')) {
	exit('Access Denied');
}

include_once 'misc.php';

class mobile_api {

	function common() {
		global $_G;
		$seccodecheck = $secqaacheck = false;
		if($_GET['type'] == 'register') {
			$seccodecheck = $_G['setting']['seccodestatus'] & 1;
			$secqaacheck = $_G['setting']['secqaa']['status'] & 1;
		} elseif($_GET['type'] == 'login') {
			$seccodecheck = $_G['setting']['seccodestatus'] & 2;
		} elseif($_GET['type'] == 'post') {
			$seccodecheck = ($_G['setting']['seccodestatus'] & 4) && (!$_G['setting']['seccodedata']['minposts'] || getuserprofile('posts') < $_G['setting']['seccodedata']['minposts']);
			$secqaacheck = $_G['setting']['secqaa']['status'] & 2 && (!$_G['setting']['secqaa']['minposts'] || getuserprofile('posts') < $_G['setting']['secqaa']['minposts']);
		}
		$sechash = random(8);
		if($seccodecheck || $secqaacheck) {
			$variable = array('sechash' => $sechash);
			if($seccodecheck) {
				$variable['seccode'] = $_G['siteurl'].'api/mobile/index.php?module=seccode&sechash='.$sechash.'&version='.(empty($_GET['secversion']) ? '1' : $_GET['secversion']);
			}
			if($secqaacheck) {
				require_once libfile('function/seccode');
				$variable['secqaa'] = make_secqaa($sechash);
			}
		}
		mobile_core::result(mobile_core::variable($variable));
	}

	function output() {}

}

?>
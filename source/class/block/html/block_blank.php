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
 *      $Id: block_blank.php 27543 2012-02-03 08:56:21Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once libfile('commonblock_html', 'class/block/html');

class block_blank extends commonblock_html {

	function __construct() {}

	function name() {
		return lang('blockclass', 'blockclass_html_script_blank');
	}

	function getsetting() {
		global $_G;
		$settings = array(
			'content' => array(
				'title' => 'blank_content',
				'type' => 'mtextarea'
			)
		);
		return $settings;
	}

	function getdata($style, $parameter) {
		require_once libfile('function/home');
		$return = getstr($parameter['content'], '', 1, 0, 0, 1);
		return array('html' => $return, 'data' => null);
	}
}

?>
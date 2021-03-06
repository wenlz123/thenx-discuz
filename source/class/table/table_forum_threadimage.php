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
 *      $Id: table_forum_threadimage.php 31800 2012-10-11 02:43:06Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_forum_threadimage extends discuz_table
{
	public function __construct() {

		$this->_table = 'forum_threadimage';
		$this->_pk    = '';

		parent::__construct();
	}

	public function delete($tid) {
		return ($tid = dintval($tid)) ? DB::delete('forum_threadimage', "tid='$tid'") : false;
	}
	public function delete_by_tid($tids) {
		return !empty($tids) ? DB::delete($this->_table, DB::field('tid', $tids)) : false;
	}
	public function fetch_all_order_by_tid($start = 0, $limit = 0) {
		return DB::fetch_all('SELECT * FROM %t ORDER BY tid DESC '.DB::limit($start, $limit), array($this->_table), 'tid');
	}

}

?>
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
 *      $Id: table_forum_memberrecommend.php 27449 2012-02-01 05:32:35Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_forum_memberrecommend extends discuz_table
{
	public function __construct() {

		$this->_table = 'forum_memberrecommend';
		$this->_pk    = '';

		parent::__construct();
	}

	public function fetch_by_recommenduid_tid($uid, $tid) {
		return DB::fetch_first('SELECT * FROM %t WHERE recommenduid=%d AND tid=%d', array($this->_table, $uid, $tid));
	}

	public function count_by_recommenduid_dateline($uid, $dateline) {
		return DB::result_first('SELECT COUNT(*) FROM %t WHERE recommenduid=%d AND dateline>%d',array($this->_table, $uid, $dateline));
	}

}

?>
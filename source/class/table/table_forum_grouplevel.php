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
 *      $Id: table_forum_grouplevel.php 27449 2012-02-01 05:32:35Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_forum_grouplevel extends discuz_table
{
	public function __construct() {

		$this->_table = 'forum_grouplevel';
		$this->_pk    = 'levelid';

		parent::__construct();
	}
	public function fetch_all_creditslower_order() {
		return DB::fetch_all("SELECT * FROM ".DB::table('forum_grouplevel')." WHERE 1 ORDER BY creditslower");
	}
	public function fetch_count() {
		return DB::result_first("SELECT count(*) FROM ".DB::table('forum_grouplevel'));
	}
	public function fetch_by_credits($credits = 0) {
		return DB::fetch_first("SELECT * FROM %t WHERE creditshigher<=%d AND %d<creditslower LIMIT 1", array($this->_table, $credits, $credits));
	}
}

?>
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
 *      $Id: table_portal_topic_pic.php 27449 2012-02-01 05:32:35Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_portal_topic_pic extends discuz_table
{
	public function __construct() {

		$this->_table = 'portal_topic_pic';
		$this->_pk    = 'picid';

		parent::__construct();
	}

	public function count_by_topicid($topicid) {
		return $topicid ? DB::result_first('SELECT COUNT(*) FROM %t WHERE topicid=%d', array($this->_table, $topicid)) : 0;
	}

	public function fetch_all_by_topicid($topicid, $start = 0, $limit = 0) {
		return $topicid ? DB::fetch_all('SELECT * FROM %t WHERE topicid=%d ORDER BY picid DESC'.DB::limit($start, $limit), array($this->_table, $topicid)) : array();
	}

}

?>
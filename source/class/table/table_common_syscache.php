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
 *      $Id: table_common_syscache.php 31119 2012-07-18 04:21:20Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_common_syscache extends discuz_table
{
	private $_isfilecache;

	public function __construct() {

		$this->_table = 'common_syscache';
		$this->_pk    = 'cname';
		$this->_pre_cache_key = '';
		$this->_isfilecache = getglobal('config/cache/type') == 'file';
		$this->_allowmem = memory('check');

		parent::__construct();
	}

	public function fetch($cachename) {
		$data = $this->fetch_all(array($cachename));
		return isset($data[$cachename]) ? $data[$cachename] : false;
	}

	public function fetch_all($cachenames) {
		$data = array();
		$cachenames = is_array($cachenames) ? $cachenames : array($cachenames);
		if ($this->_allowmem) {
			if (($index = array_search('setting', $cachenames)) !== FALSE) {
				if (memory('exists', 'setting')) {
					unset($cachenames[$index]);
					$settings = new memory_setting_array();
				}
			}

			$data = memory('get', $cachenames);
			if (isset($settings)) {
				$data['setting'] = $settings;
			}
			$newarray = $data !== false ? array_diff($cachenames, array_keys($data)) : $cachenames;
			if (empty($newarray)) {
				return $data;
			} else {
				$cachenames = $newarray;
			}
		}

		if($this->_isfilecache) {
			$lostcaches = array();
			foreach($cachenames as $cachename) {
				if(!@include_once(DISCUZ_ROOT.'./data/cache/cache_'.$cachename.'.php')) {
					$lostcaches[] = $cachename;
				} elseif($this->_allowmem) {
					memory('set', $cachename, $data[$cachename]);
				}
			}
			if(!$lostcaches) {
				return $data;
			}
			$cachenames = $lostcaches;
			unset($lostcaches);
		}

		$query = DB::query('SELECT * FROM '.DB::table($this->_table).' WHERE '.DB::field('cname', $cachenames));
		while($syscache = DB::fetch($query)) {
			$data[$syscache['cname']] = $syscache['ctype'] ? unserialize($syscache['data']) : $syscache['data'];
			if ($this->_allowmem) {
				if ($syscache['cname'] === 'setting') {
					memory_setting_array::save($data[$syscache['cname']]);
				} else {
					memory('set', $syscache['cname'], $data[$syscache['cname']]);
				}
			}
			if($this->_isfilecache) {
				$cachedata = '$data[\''.$syscache['cname'].'\'] = '.var_export($data[$syscache['cname']], true).";\n\n";
				if(($fp = @fopen(DISCUZ_ROOT.'./data/cache/cache_'.$syscache['cname'].'.php', 'wb'))) {
					fwrite($fp, "<?php\n//Discuz! cache file, DO NOT modify me!\n//Identify: ".md5($syscache['cname'].$cachedata.getglobal('config/security/authkey'))."\n\n$cachedata?>");
					fclose($fp);
				}
			}
		}

		foreach($cachenames as $name) {
			if($data[$name] === null) {
				$data[$name] = null;
				$this->_allowmem && (memory('set', $name, array()));
			}
		}

		return $data;
	}

	public function insert($cachename, $data) {

		parent::insert(array(
			'cname' => $cachename,
			'ctype' => is_array($data) ? 1 : 0,
			'dateline' => TIMESTAMP,
			'data' => is_array($data) ? serialize($data) : $data,
		), false, true);

		if ($this->_allowmem && memory('exists', $cachename) !== false) {
			if ($cachename === 'setting') {
				memory_setting_array::save($data);
			} else {
				memory('set', $cachename, $data);
			}
		}
		$this->_isfilecache && @unlink(DISCUZ_ROOT.'./data/cache/cache_'.$cachename.'.php');
	}

	public function update($cachename, $data) {
		$this->insert($cachename, $data);
	}

	public function delete($cachenames) {
		parent::delete($cachenames);
		if($this->_allowmem || $this->_isfilecache) {
			foreach((array)$cachenames as $cachename) {
				$this->_allowmem && memory('rm', $cachename);
				$this->_isfilecache && @unlink(DISCUZ_ROOT.'./data/cache/cache_'.$cachename.'.php');
			}
		}
	}
}

?>
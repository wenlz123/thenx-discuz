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
 *      $Id: cache_file.php 6757 2010-03-25 09:01:29Z cnteacher $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class ultrax_cache {

	function __construct($conf) {
		$this->conf = $conf;
	}

	function get_cache($key) {
		if($this->cache_exists($key)) {
			$data = $this->_get_cache($key);
			return $data['data'];
		}
		return false;
	}

	function set_cache($key, $value, $life) {
		global $_G;
		$data = array($key => array('data' => $value, 'life' => $life));
		require_once libfile('function/cache');
		$cache_file = $this->get_cache_file_path($key);
		dmkdir(dirname($cache_file));
		$cachedata = "\$data = ".arrayeval($data).";\n";
		if($fp = @fopen($cache_file, 'wb')) {
			fwrite($fp, "<?php\n//Discuz! cache file, DO NOT modify me!".
				"\n//Created: ".date("M j, Y, G:i").
				"\n//Identify: ".md5($cache_file.$cachedata.$_G['config']['security']['authkey'])."\n\nif(!defined('IN_DISCUZ')) {\n\texit('Access Denied');\n}\n\n$cachedata?>");
			fclose($fp);
		} else {
			exit('Can not write to cache files, please check directory ./data/ and ./data/ultraxcache/ .');
		}
		return true;
	}

	function del_cache($key) {
		$cache_file = $this->get_cache_file_path($key);
		if(file_exists($cache_file)) {
			return @unlink($cache_file);
		}
		return true;
	}

	function _get_cache($key) {
		static $data = null;
		if(!isset($data[$key])) {
			include $this->get_cache_file_path($key);
		}
		return $data[$key];
	}

	function cache_exists($key) {
		$cache_file = $this->get_cache_file_path($key);
		if(!file_exists($cache_file)) {
			return false;
		}
		$data = $this->_get_cache($key);
		if($data['life'] && (filemtime($cache_file) < time() - $data['life'])) {
			return false;
		}
		return true;
	}

	function get_cache_file_path($key) {
		static $cache_path = null;
		if(!isset($cache_path[$key])) {
			$dir = hexdec($key[0].$key[1].$key[2]) % 1000;
			$cache_path[$key] = $this->conf['path'].'/'.$dir.'/'.$key.'.php';
		}
		return $cache_path[$key];
	}

}
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
 * DiscuzX Convert
 *
 * $Id: pollvoter.php 8815 2010-04-23 02:05:15Z monkey $
 */

$curprg = basename(__FILE__);
$table_source = $db_source->tablepre . 'polloptions';
$table_target = $db_target->tablepre . 'forum_pollvoter';

$limit = 1000;

$pstep = getgpc('pstep');
$pstep = intval($pstep);

$total = getgpc('total');
$total = intval($total);

$offset = $pstep * $limit;

$continue = false;

$query = $db_source->query("SELECT * FROM $table_source ORDER BY polloptionid LIMIT $offset, $limit");
while($row = $db_source->fetch_array($query)) {
	$voterids = trim($row['voterids']);
	$voterids = explode("\t", $voterids);
	foreach($voterids as $voterid) {
		$options = $db_target->result_first("SELECT options FROM $table_target WHERE tid='{$row['tid']}' AND uid='$voterid'");
		$options = explode("\t", $options);
		if(!in_array($row['polloptionid'], $options)) {
			$options[] = $row['polloptionid'];
		}
		$options_str = trim(implode("\t", $options));
		$db_target->query("UPDATE $table_target SET options='$options_str' WHERE tid='{$row['tid']}' AND uid='$voterid'");
	}
	$continue = true;
	$total ++;
}

$nextpstep = $pstep + 1;
if($continue) {
	showmessage("继续转换数据表 ".$table_source."，已转换 $total 条记录。", "index.php?a=$action&source=$source&prg=$curprg&pstep=$nextpstep&total=$total");
}
?>
<?
	header("Content-Type: text/html; charset=utf-8");
	ob_start();
	set_time_limit(0);
	include 'db.php';

	$getWay = (int)$_REQUEST['radWay'];
	if($getWay == 0) {								//检索语句
		$tcn = $_REQUEST['txt0'];	//母语
		$tjp = $_REQUEST['txt1'];	//第二语言
	}else{											//检索语误
		$ecn = $_REQUEST['err0'];
		$ejp = $_REQUEST['err1'];
	}

	$id = explode(":", $_REQUEST['ids']);
	$sql = '0';
	reset($id); //初始化数组指针
	next($id);
	while(list(, $ele) = each($id)) {
		$sql.= ",'$ele'";
	}
	$ret = $GLOBALS['db']->query("SELECT * FROM `books` WHERE id IN ($sql);")->fetchAll(PDO::FETCH_NUM);
	//print_r($ret);
	//exit();
	$c = 0;
	foreach($ret as $item) {
		$table  = "{$item[1]}_{$item[3]}_{$item[4]}";
		$column = "{$item[6]}_{$item[5]}";

		$sql = '';
		if($getWay == 0) {	//查询语句
			if($item[6] == 0) {
				if($tcn) $sql = "SELECT * FROM `$table` WHERE `$column` LIKE '%$tcn%';"; //查询汉语语句
			}else{
				if($tjp) $sql = "SELECT * FROM `$table` WHERE `$column` LIKE '%$tjp%';"; //查询日语语句
			}
		}else{				//查询语误
			if($item[6] == 0) {
				if($ecn) $sql = "SELECT * FROM `$table` WHERE `{$column}_err` LIKE '%$ecn%';";
			}else{
				if($ejp) $sql = "SELECT * FROM `$table` WHERE `{$column}_err` LIKE '%$ejp%';";
			}
		}
		// echo $sql.'<br/>';
		if($sql) {
			$ret = $GLOBALS['db']->query($sql)->fetchAll(PDO::FETCH_ASSOC);
			if(count($ret)) {
				$v[$item[1]][$item[3]][$item[4]]["$item[6]_$item[5]"] = $ret;
				$c += count($ret);
			}
		}
		//	author
		//		work
		//			chapter
		//				lang_editor
		//						Result
	}
	// print_r($v);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link charset="UTF-8" href="font.css" rel="stylesheet" type="text/css" />
<link charset="UTF-8" href="table.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="search.js"></script>
<title>検索結果</title>
</head>
<body onload="$('#selauthor')[0].onchange();initOutput()">
<div id="divList" style="display:none;"></div>
<input id="btnOutput" type="button" value="結果のエクスポート" style="display:none" />
<?
	echo "計 $c 件，";
	if($getWay == 0) 
		echo '言葉:'.trim("$tcn $tjp");
	else
		echo '語誤:'.trim("$ecn $ejp");

	if($c == 0) exit();
?>
<br/><hr/>作者:
<select id="selauthor" onchange="showWorks(this);">
<?
	$authors = array_keys($v);
	foreach($authors as $item) {
		echo "<option value=\"$item\">$item</option>";
	}
	echo '</select>';
?>
作文:
<?
	foreach($v as $author => $works) {
		echo "<select id=\"$author\" class=\"selWorks\" onchange=\"showChapter(this);\" style=\"display:none;\">";
		$works = array_keys($works);
		foreach($works as $item) {
			echo "<option value=\"$item\">$item</option>";
		}
		echo '</select>';
	}
?>
番号:
<?
	reset($v);
	while(list($author, $works) = each($v)) {
		reset($works);
		while(list($work, $chapters) = each($works)) {
			echo "<select id=\"{$author}_{$work}\" class=\"selChapters\" onchange=\"setList(this);\" style=\"display:none;\">";
			$chapters = array_keys($chapters);
			foreach($chapters as $item) {
				echo "<option value=\"$item\">$item</option>";
			}
			echo '</select>';
		}
	}
?>
　<input id="chkAll" type="checkbox" onclick="showAll(this)" />すべてを表示
<br/><br/>
<?
	foreach($v as $author => $works) {
		foreach($works as $work => $chapters) {
			foreach($chapters as $chapter => $arr4) {
				$table = "{$author}_{$work}_{$chapter}";
				echo "<div id=\"$table\" name=\"$table\" class=\"divList\" style=\"display:none;\">";
			    echo "<span class='divHead' style='display:none;'>$table</span><br/>";
				echo '<table><thead><tr>';
			    echo '<td width="6%">編集</td>';
			    echo '<td width="3%">ID</td>';
			    echo '<td width="9%">提供者</td>';
			    echo '<td width="36%">検索結果</td>';
			    echo '<td width="36%">対応する文章</td>';
			    echo '<td width="9%">提供者</td>';
				echo '</tr></thead><tbody>';
				$langs0 = array_keys($arr4); // 0 代表 查询文本提供者 1 代表 译文提供者
				foreach($arr4 as $key => $list) {
					$lang0 = (int)substr($key,0,1);
					$lang1 = ($lang0 == 0) ? 1 : 0;
					$editor = substr($key,2);

					foreach($list as $item) {
						$id = $item['num'];
						unset($item['num']);
						$cntTrans = -1;
						$value = '';
						$trans = array();
						foreach($item as $key2 => $val) {
							if(substr($key2, -4) != '_err') {
								if((int)substr($key2,0,1) == $lang1) {
									$cntTrans++;
									$trans[] = array(substr($key2, 2), strip_tags($val));
								}else{
									$value = $val;
									if($getWay == 0) {	// 语句
										$value = strip_tags($value);
										if($lang0 == 0) {
											$value = str_replace($tcn, "<span style=\"background-color:yellow\">$tcn</span>", $value);
										}else{
											$value = str_replace($tjp, "<span style=\"background-color:yellow\">$tjp</span>", $value);
										}
									}else{				// 语误
										$tags = explode('/', $item["{$key}_err"]);
										$needle = ($lang0 == 0) ? $ecn : $ejp;
										foreach($tags as $tag) {
											if(strpos($tag, $needle) !== false) break;
										}
										// echo '<!--'.$tag.':'.$needle.'-->';
										$value = str_replace("<$tag>", "＜span style=\"color:gray;text-decoration:line-through;\">", $value);
										// echo '<!--'.$value.'-->';
										$pattern = '/<\/'.$tag.'[|]([^\n]+)[>]/U';
										$replacement = '＜/span>＜span style="background-color:yellow">\1＜/span>';
										$value = preg_replace($pattern, $replacement, $value);
										// echo '<!--'.$value.'-->';
										$value = strip_tags($value);
										$value = str_replace("＜", "<", $value);
									}
								}
							}
						}

						echo "<tr column=\"$key\">";
						echo "<td rowspan=\"$cntTrans\">";
						echo '<input type="button" value="前文脈" onclick="showLine(this,-1)" />';
						echo '<input type="button" value="後文脈" onclick="showLine(this, 1)" />';
						echo '</td>';
						echo "<td rowspan=\"$cntTrans\">$id</td>";
						echo "<td rowspan=\"$cntTrans\">$editor</td>";
						echo "<td rowspan=\"$cntTrans\" style=\"text-align:left;\">$value</td>";

						echo "<td style=\"text-align:left;\">{$trans[0][1]}</td>";
						echo "<td>{$trans[0][0]}</td></tr>";
						for($n = 1; $n < $cntTrans; $n++) {
							echo '<tr>';
							echo "<td style='text-align:left;'>{$trans[$n][0]}</td>";
							echo "<td>{$trans[$n][0]}</td>";
							echo '</tr>';
						}
					}
				}
				echo '</tbody></table><br/></div>';
			}
		}
	}

?>
</body>
</html>
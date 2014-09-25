<?php
header("Content-Type: text/html; charset=utf-8");
ob_start();
include 'db.php';

$m = $_GET['m'];
switch ($m) {
	case 'authors':

		echo '<select id="authors" onchange="setAuthor(this)"><option>(全て)</option>';

		$country = @$_GET["country"];
		if(strlen($country)) $country = "country='$country' AND ";

		$sql = "SELECT DISTINCT author FROM `books` WHERE $country (1);";
		$ret = $GLOBALS['db']->query($sql)->fetchAll();
		foreach($ret as $item) {
			echo "<option value=\"$item[0]\">$item[0]</option>";
		}

		echo '</select>';

		break;
	case 'works':

		echo '<select id="works"><option>(全て)</option>';

		$country = @$_GET["country"];
		if(strlen($country)) $country = "country='$country' AND ";

		$author = @$_GET["author"];
		if(strlen($author)) $author = "author='$author' AND ";

		$sql = "SELECT DISTINCT work FROM `books` WHERE $author $country (1);";
		$ret = $GLOBALS['db']->query($sql)->fetchAll();
		foreach($ret as $item) { 
			echo "<option value=\"$item[0]\">$item[0]</option>";
		}

		echo '</select>';

		break;
	case 'work':

		?>
		<table>
		    <thead>
		        <td>作者</td>
		        <td style="display:none;">国籍</td>
		        <td>作文</td>
		        <td style="display:none;">番号</td>
		        <td style="display:none;">提供者</td>
		        <td>言語</td>
		        <td width="130px">編集</td>
		    </thead>
		    <tbody>
		<?
		$country = @$_GET["country"];
		if(strlen($country))	$country = "country='$country' AND ";
		$author = @$_GET["author"];
		if(strlen($author))		$author = "author='$author' AND ";
		$work = @$_GET["work"];
		if(strlen($work))		$work = "work='$work' AND ";
		$lst = "";
		$sql = "SELECT * FROM `books` WHERE $author $country $work (1);";
		$ret = $GLOBALS['db']->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($ret as $item) {
			$lst.= ":$item[id]";
			echo "<tr>";
		    echo "<td>$item[author]</td>";
		    echo "<td style=\"display:none;\">$item[country]</td>";
		    echo "<td>$item[work]</td>";
		    echo "<td style=\"display:none;\">$item[chapter]</td>";
		    echo "<td style=\"display:none;\">$item[editor]</td>";
		    echo '<td>'.($item['lang'] == 0 ? '中国語' : '日本語'),'</td>';
		    echo "<td><input type=\"button\" value=\"修正\" onclick=\"editItem(this, '$item[id]')\" /><input type=\"button\" value=\"削除\" onclick=\"delItem(this, '$item[id]')\" /></td>";
		    echo '</tr>';
		}
		echo "</tbody></table></>$lst";
}
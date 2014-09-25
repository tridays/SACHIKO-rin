<?php
header("Content-Type: text/html; charset=utf-8");
ob_start();
include 'db.php';

set_time_limit(0);
mb_internal_encoding('UTF-8');

if($_GET["mode"] == 'del') {
	$sql = "SELECT id FROM `books`;";
	$ret = $GLOBALS['db']->query($sql)->fetchAll(PDO::ARRAY_NUM);
	$ret = $ret[0];
	foreach($ret as $id) {
		$GLOBALS['db']->exec("DROP TABLE `$id`;");
	}
	$GLOBALS['db']->exec('TRUNCATE TABLE `books`;');
	exit('終了');
}

function make_number_semiangle($str) { 
	$arr = array('０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',    
				 '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
				); 
    return strtr($str, $arr);
}

function make_xml_semiangle($str) {
	$arr = array('＜' => '<', '＞' => '>', '／' => '/', '｜' => '|'); 
    return strtr($str, $arr);
}

/*
function make_semiangle($str) {    
	$arr = array('０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',    
				 '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',    
				 'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',    
				 'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',    
				 'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',    
				 'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',    
				 'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',    
				 'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',    
				 'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',    
				 'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',    
				 'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',    
				 'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',    
				 'ｙ' => 'y', 'ｚ' => 'z',    
				 '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[',    
				 '】' => ']', '〖' => '[', '〗' => ']', '“' => '[', '”' => ']',    
				 '‘' => '[', '’' => ']', '｛' => '{', '｝' => '}', '《' => '<',    
				 '》' => '>',    
				 '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',    
				 '：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.',    
				 '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',    
				 '”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"',    
				 '　' => ' ','＄'=>'$','＠'=>'@','＃'=>'#','＾'=>'^','＆'=>'&','＊'=>'*', 
				 '＂'=>'"'); 
    return strtr($str, $arr);    
}
*/

function addItem($author, $country, $work, $chapter, $editor, $lang, $path) {
	/*
	$pathSQL = addslashes($path);
	if(count($ret = $hDB->query("SELECT `ID` FROM `lists` WHERE Path='$pathSQL';")->fetchAll()))
		$hDB->exec("UPDATE `lists` SET Author='$author', Work='$work', Chapter='$chapter', Lang='$lang', Trans='$trans', TransLang='$translang' WHERE ID='{$ret[0][0]}';");
	else
	*/
	$GLOBALS['db']->exec("INSERT INTO `books`(`author`, `country`, `work`, `chapter`, `editor`, `lang`) VALUES ('$author', '$country', '$work', '$chapter', '$editor', '$lang');");

	readText("{$author}_{$work}_{$chapter}", "{$translang}_{$trans}", $path);
}

function readText($table, $colEdit, $path) {
	$GLOBALS['db']->exec("CREATE TABLE `$table` (`num` INT(10) NOT NULL PRIMARY KEY) COLLATE='utf8_general_ci' ENGINE=InnoDB;");
	$GLOBALS['db']->exec("ALTER TABLE `$table` DROP COLUMN `$colEdit`;");
	$GLOBALS['db']->exec("ALTER TABLE `$table` ADD COLUMN `$colEdit` TEXT;");
	$GLOBALS['db']->exec("ALTER TABLE `$table` DROP COLUMN `{$colEdit}_err`;");
	$GLOBALS['db']->exec("ALTER TABLE `$table` ADD COLUMN `{$colEdit}_err` TEXT;");

	$txts = explode("＠", file_get_contents($path));

	$sql = "INSERT INTO `$table` (`num`, `$colEdit`, `{$colEdit}_err`) VALUES ";
	$val = array();
	for($j = 1; $j < count($txts); $j++) {
		$row = trim($txts[$j]);
		$pos = 0;
		$do = true;
		for($k = 0; ($k < strlen($row)) && $do; $k++) {
			switch(mb_substr($row, $k, 1, "utf-8")) {
				case '１': case '２': case '３': case '４': case '５': case '６': case '７': case '８': case '９': case '０':
					$pos++; break;
				default:
					$do = false;
			}
		}
		$sNum = make_number_semiangle(mb_substr($row, 0, $pos));
		$sTxt = make_xml_semiangle(mb_substr($row, $pos, mb_strlen($row) - $pos));

		$sTxt = preg_replace('/<(.+?)\/(.+?)→(.+?)>/', '<$1>$2</$1|$3>', $sTxt);

		preg_match_all('/<\/(.+?)\|/', $sTxt, $sErr, PREG_PATTERN_ORDER);

		$sErr = '/'.implode('/', $sErr[1]);

		// $pos = 0;
		// $sErr = '';
		// while(($pos = mb_strpos($sTxt, '</', $pos)) !== false) {
		// 	$pos++;
		// 	$sErr .= mb_substr($sTxt, $pos, mb_strpos($sTxt, '|', $pos) - $pos);
		// }

		if($j != 1) $sql.=',';
		$sql .= "(?,?,?)";
		array_push($val, $sNum, $sTxt, $sErr);
	}
	$st = $GLOBALS['db']->prepare($sql." ON DUPLICATE KEY UPDATE `$colEdit`=VALUES(`$colEdit`), `{$colEdit}_err`=VALUES(`{$colEdit}_err`);");
	$st->execute($val);
}

if($_GET["mode"] == 'xls') {
	//读表格
	require_once 'excel.php';
	$upfile = $_FILES['path'];
	Read_Excel_File($upfile['tmp_name'], $xls);
	$sheet = $xls['Sheet1'];
	$rows = count($sheet);

	for($i = 1; $i < $rows; $i++) {
		$rs = $sheet[$i];
		$GLOBALS['db']->exec("INSERT INTO `books`(`author`, `country`, `work`,   `chapter`, `editor`, `lang`) VALUES
												 ('$rs[0]', '$rs[1]',  '$rs[2]', '$rs[3]',  '$rs[4]', '$rs[5]');");
		$langPHP = (strrpos(strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']), 'zh') !== false)?"GBK":"ASCII";
		$pathPHP = iconv("utf-8", $langPHP, $rs[6]);
		readText("{$rs[0]}_{$rs[2]}_{$rs[3]}", "{$rs[5]}_{$rs[4]}", dirname(__FILE__)."\\txt\\$pathPHP");
	}
	exit('終了');
}

$author		= $_POST['author'];
$country	= $_POST['country'];
$work 		= $_POST['work'];
$chapter 	= $_POST['chapter'];
$table 		= "{$author}_{$work}_{$chapter}";
$editor 	= $_POST['editor'];
$lang 		= $_POST['lang'];
$strColumn 	= "{$lang}_{$editor}";

switch($_GET["mode"]) {
	case 'add':
		$st = $GLOBALS['db']->prepare('INSERT INTO `books` (`author`, `country`, `work`, `chapter`, `editor`, `lang`) VALUES(?, ?, ?, ?, ?, ?); ');
		$st->execute(array($author, $country, $work, $chapter, $editor, $lang));

		readText($table, $strColumn, $_FILES['path']['tmp_name']);
		break;
	case 'mod':
		$id = $_GET['id'];
		$old = $GLOBALS['db']->query('SELECT * FROM `books` WHERE id='.$id)->fetchAll(PDO::FETCH_NUM);
		$old = $old[0];
		$old_table = "$old[1]_$old[3]_$old[4]";
		$old_column = "$old[6]_$old[5]";

		if(@$_FILES['path']['tmp_name'] != '') {
			if(($strColumn != $old_column) || ($table != $old_table)) {
				$GLOBALS['db']->exec("ALTER TABLE `$old_table` DROP COLUMN `$old_column`;");
				$GLOBALS['db']->exec("ALTER TABLE `$old_table` DROP COLUMN `{$old_column}_err`;");
				echo '删除旧数据<br/>';
			}

			readText($table, $strColumn, $_FILES['path']['tmp_name']);
			echo '新文件覆写<br/>';
		} else {
			if($table != $old_table) {
				/*
				$GLOBALS['db']->exec("CREATE TABLE `$table` (`num` INT(10) NOT NULL PRIMARY KEY) COLLATE='utf8_general_ci' ENGINE=InnoDB;");
				$GLOBALS['db']->exec("ALTER TABLE `$table` DROP COLUMN `$old_column`;");
				$GLOBALS['db']->exec("ALTER TABLE `$table` ADD COLUMN  `$old_column` TEXT;");
				$GLOBALS['db']->exec("ALTER TABLE `$table` DROP COLUMN `{$old_column}_err`;");
				$GLOBALS['db']->exec("ALTER TABLE `$table` ADD COLUMN  `{$old_column}_err` TEXT;");

				$GLOBALS['db']->exec("UPDATE `$table` SET (`$old_column`,`{$old_column}_err`)=(SELECT `$old_column`,`{$old_column}_err` FROM `$old_table`);");
				//$GLOBALS['db']->exec("INSERT INTO `$table` (SELECT `$old_column`,`{$old_column}_err` FROM `$old_table`) ON DUPLICATE KEY UPDATE `$old_column`=VALUES(`$old_column`), `{$old_column}_err`=VALUES(`{$old_column}_err`);");
				var_dump($GLOBALS['db']->errorInfo());

				$GLOBALS['db']->exec("ALTER TABLE `$old_table` DROP COLUMN `$old_column`;");
				$GLOBALS['db']->exec("ALTER TABLE `$old_table` DROP COLUMN `{$old_column}_err`;");
				echo '迁移表<br/>';
				*/
				die('拒绝改变s');
			}
			if($strColumn != $old_column) {
				$GLOBALS['db']->exec("ALTER TABLE `$table` CHANGE COLUMN `$old_column` `$strColumn` TEXT;");
				$GLOBALS['db']->exec("ALTER TABLE `$table` CHANGE COLUMN `{$old_column}_err` `{$strColumn}_err` TEXT;");
				//$st = $GLOBALS['db']->prepare("ALTER TABLE `$table` CHANGE COLUMN ? ? TEXT;");
				//$st->execute(array($old_column, $strColumn));
				//$st->execute(array($old_column.'_err', $strColumn.'_err'));
				echo '迁移列<br/>';
			}
		}

		$st = $GLOBALS['db']->prepare('UPDATE `books` SET author=?, country=?, work=?, chapter=?, editor=?, lang=? WHERE id=?;');
		$st->execute(array($author, $country, $work, $chapter, $editor, $lang, $id));
}
exit('終了');
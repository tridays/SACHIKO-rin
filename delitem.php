<?
	header("Content-Type: text/html; charset=utf-8");
	ob_start();
    include 'db.php';
	
	$id = $_GET['id'];

	$ret = $GLOBALS['db']->query("SELECT `author`, `work`, `chapter`, `editor`, `lang` FROM `books` WHERE id='$id';")->fetchAll(PDO::FETCH_NUM);
	$ret = $ret[0];
	delData("{$ret[0]}_{$ret[1]}_{$ret[2]}", "{$ret[4]}_{$ret[3]}");
	delItem($id);

	function delItem($id) {
		echo $GLOBALS['db']->exec("DELETE FROM `books` WHERE id='$id'");
	}

	function delData($table, $strColumn) {
		$cnt = $GLOBALS['db']->query("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema='sachiko-rin' AND table_name='$table';")->fetchAll(PDO::FETCH_NUM);
		if($cnt[0][0] == '3') {
			$GLOBALS['db']->exec("DROP TABLE `$table`;");
		}else{
			//echo "{$ret[4]}_{$ret[3]}";
			$GLOBALS['db']->exec("ALTER TABLE `$table` DROP COLUMN `$strColumn`;");
			$GLOBALS['db']->exec("ALTER TABLE `$table` DROP COLUMN `{$strColumn}_err`;");
		}
	}
?>
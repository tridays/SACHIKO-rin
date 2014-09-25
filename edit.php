<?
	header("Content-Type: text/html; charset=utf-8");
	ob_start();
	include_once 'db.php';

	$id = $_GET['id'];
	$ret = $GLOBALS['db']->query("SELECT * FROM `books` WHERE id='$id'")->fetchAll();
	$ret = $ret[0];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link charset="UTF-8" href="font.css" rel="stylesheet" type="text/css" />
<title>修正</title>
</head>
<body>
<form id="frmMod" action="add.php?mode=mod&id=<?echo $id?>" method="POST" enctype="multipart/form-data">
<fieldset><legend>修正</legend>
	<table>
	<tr><td>作者</td>
		<td><input type="text" name="author" value="<?echo $ret[1]?>" /></td></tr>
	<tr><td>国籍</td>
		<td>
			<input type="radio" name="country" value="1" <?if($ret[2] == '1') echo "checked=\"true\""?>/>日本
			<input type="radio" name="country" value="0" <?if($ret[2] == '0') echo "checked=\"true\""?>/>中国
		</td></tr>
	<tr><td>作文</td>
		<td><input type="text" name="work" value="<?echo $ret[3]?>" /></td></tr>
	<tr><td>番号</td>
		<td><input type="text" name="chapter" value="<?echo $ret[4]?>" /></td></tr>
	<tr><td>提供者</td>
		<td><input type="text" name="editor" value="<?echo $ret[5]?>" /></td></tr>
	<tr><td>言語</td>
		<td>
			<input type="radio" name="lang" value="1" <?if($ret[6] == '1') echo "checked=\"true\""?>/>日本語
			<input type="radio" name="lang" value="0" <?if($ret[6] == '0') echo "checked=\"true\""?>/>中国語
		</td></tr>
	<tr><td>パス</td>
		<td><input type="file" name="path" /><br/><font color="red">(必要)</font></td></tr>
	</table>
	<br/><input type="submit" />
</fieldset>
</form>
</body>
</html>
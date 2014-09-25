<?php
    header("Content-Type: text/html; charset=utf-8");
    ob_start();
    include_once 'db.php';
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link charset="UTF-8" href="font.css"  rel="stylesheet" type="text/css" />
<link charset="UTF-8" href="table.css" rel="stylesheet" type="text/css" />
<script charset="UTF-8" type="text/javascript" src="jquery.js"></script>
<script charset="UTF-8" type="text/javascript" src="ajax.js"  ></script>
<script charset="UTF-8" type="text/javascript" src="main.js"  ></script>
<title>SACHIKO-rin (Enhanced Version)</title>
</head>
<body onload="setCountry(radc2);">

<div style="float:left;height:600px;overflow-y:auto;width:67%;">

<input type="button" value="コーパスを入力" onclick="window.open('add.htm','_blank');" />
<br/>
<table><tr>
    <td width="180px" style="text-align:left;">
        <input id="radc0" name="radCountry" type="radio" value="0" onclick="setCountry(this);" />中国人日本語学習者<br/>
        <input id="radc1" name="radCountry" type="radio" value="1" onclick="setCountry(this);" />日本人中国語学習者<br/>
        <input id="radc2" name="radCountry" type="radio" value=""  onclick="setCountry(this);" checked="true" />両方
    </td>
    <td style="text-align:left;">
        　作者:<span id="divAuthors"></span><br/>
        　作文:<span id="divWorks"></span><br/>
        　<input id="btnShowList" type="button" value="作文一覧" onclick="showWork(authors, works);" />
    </td>
</tr></table>
<hr/>
<div id="lstItems"></div>

</div>

<div style="float:right;width:33%;">
<form action="search.php" target="_blank" method="POST">
    <br/>
    <font size="6">　検索条件を指定する</font>
    <br/>
    <br/>　　検索方法：
    <br/>　　　<input id="radw0" name="radWay" type="radio" value="0" onclick="setWay(this);" checked="true" />言葉で検索
    <br/>　　　<input id="radw1" name="radWay" type="radio" value="1" onclick="setWay(this);" />誤用で検索
    <br/>

    <span id="lblText">
        <br/>　　キーワードを入力:
        <span id="lbltxt1"><br/>　　　日本語:<input id="txt1" name="txt1" type="text" value="" /></span>
        <span id="lbltxt0"><br/>　　　中国語:<input id="txt0" name="txt0" type="text" value="" /></span>
        <br/>　　　両方入力可。
    </span>

    <span id="lblYuwu" style="display:none;">
        <br/>　　誤用タイプを選択:
        <span id="diverr1"><br/>　　　日本語:
        <select id="selerr1" name="err1">
            <option value=""></option>
            <option value="Ａ">Ａ 単</option>
            <option value="Ｂ">Ｂ 単</option>
            <option value="Ｃ">Ｃ 単</option>
            <option value="Ｄ">Ｄ 単</option>
            <option value="Ｅ">Ｅ 単</option>
            <option value="Ｆ">Ｆ 単</option>
            <option value="Ｇ">Ｇ 短</option>
            <option value="Ｈ">Ｈ 短</option>
            <option value="Ｉ">Ｉ 短</option>
            <option value="Ｊ">Ｊ 短</option>
            <option value="Ｋ">Ｋ 短</option>
            <option value="Ｌ">Ｌ 短</option>
            <option value="Ｍ１">Ｍ 単</option>
            <option value="Ｍ２">Ｍ 複</option>
            <option value="Ｍ３">Ｍ 動相</option>
            <option value="Ｎ１">Ｎ 単</option>
            <option value="Ｎ２">Ｎ 複</option>
            <option value="Ｎ３">Ｎ 動相</option>
            <option value="Ｏ１">Ｏ 文</option>
            <option value="Ｏ２">Ｏ 複</option>
            <option value="Ｐ">Ｐ 短</option>
            <option value="Ｑ">Ｑ 短</option>
            <option value="Ｒ">Ｒ 短</option>
            <option value="Ｓ">Ｓ 短</option>
            <option value="Ｔ">Ｔ 短</option>
            <option value="Ｕ">Ｕ 文</option>
            <option value="Ｖ">Ｖ 文</option>
            <option value="Ｗ">Ｗ 文</option>
            <option value="Ｘ">Ｘ 文</option>
            <option value="Ｙ１">Ｙ 文</option>
            <option value="Ｙ２">Ｙ 複</option>
            <option value="Ｚ１">Ｚ 文</option>
            <option value="Ｚ２">Ｚ 複</option>
        </select></span>
        <span id="diverr0"><br/>　　　中国語:
        <select id="selerr0" name="err0">
            <option value=""></option>
            <option value="Ａ">Ａ 语误</option>
            <option value="Ｂ">Ｂ 语误</option>
            <option value="Ｃ">Ｃ 语误</option>
            <option value="Ｚ１">Ｚ 语误</option>
        </select></span>
        <br/>　　　両方選択可。
    </span>
    <br/>
    <br/>　　　<input type="submit" value="検索" onclick="btnShowList.click();" />
    <br/>
    <input id="ids" name="ids" type="hidden" />
</form>
</div>

</body>
</html>
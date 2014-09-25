function setCountry(obj) {
	var country = '&country=' + obj.value;
	$("#divAuthors").html(AJAX("show.php?m=authors" + country));
	$("#authors")[0].onchange();
}

function setAuthor(obj) {
	var country = '&country=' + $("input[name='radCountry']:checked").val();
	var a = obj.selectedIndex == 0 ? "" : encodeURI(obj.value);
    $("#divWorks").html(AJAX("show.php?m=works&author=" + a + country));
}

function showWork(authors, works) {
	var strA = "", strW = "";
	if(authors.selectedIndex != 0) strA = "&author=" + encodeURI(authors.value);
	if(works.selectedIndex   != 0) strW = "&work="   + encodeURI(works.value);

	var country = '&country=' + $("input[name='radCountry']:checked").val();

	var ret = AJAX("show.php?m=work" + strA + strW + country).split('</>');
	$("#lstItems").html(ret[0]);
	$("#ids").attr("value", ret[1]);
}

function setWay(obj) {
	if(obj.value == '0') {
		$("#lblText").show();
		$("#lblYuwu").hide();
	} else {
		$("#lblText").hide();
		$("#lblYuwu").show();
	}
}

function editItem(obj, index) {
	var wnd = window.open('edit.php?id=' + index, '', "width=400, height=318, toolbar=no, menubar=no, location=no, status=no");
	wnd.opener = null;
	wnd.document.close();
}

function delItem(obj, index) {
	if(window.confirm("削除しますか？")) {
		obj.parentNode.parentNode.style.display = 'none';
		AJAX('delitem.php?id=' + index);
	}
}
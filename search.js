function showWorks(obj) {
	var selWorks = $('.selWorks');
	var i, c = selWorks.size();
	for(i = 0; i < c; i++) {
		selWorks[i].style.display = 'none';
	}
	var iObj = $('#' + obj.value)[0];
	iObj.style.display = 'inline';

	showChapter(iObj);
}

function showChapter(obj) {
	var selChapters = $('.selChapters');
	var i, c = selChapters.size();
	for(i = 0; i < c; i++) {
		selChapters[i].style.display = 'none';
	}
	var iObj = $('#' + obj.id + '_' + obj.value)[0];
	iObj.style.display = 'inline';

	setList(iObj);
}

function setList(obj) {
	$('#divList').text(obj.id + '_' + obj.value);
	$('#chkAll')[0].checked = false;
	$('.divHead').hide();
	showList();
}

function showList() {
	if($('#divList').text().length == 0) return;
	var Lists = $('.divList');
	var i, c = Lists.size();
	for(i = 0; i < c; i++) {
		Lists[i].style.display = 'none';
	}
	var iObj = $('#' + $('#divList').text())[0];
	iObj.style.display = 'inline';
}

function showLine(obj, mode) {
	var table = "&table=" + encodeURI(obj.parentNode.parentNode.parentNode.parentNode.parentNode.getAttribute("name")); //div无name属性
	var column = "&column=" + encodeURI(obj.parentNode.parentNode.getAttribute("column"));
	var num = "&num=" + (Number(obj.parentNode.nextSibling.innerHTML) + Number(mode));
	var wnd = window.open('showline.php?' + num + table + column, '', "width=1000, height=618, menubar=no, location=no, status=no");
	wnd.opener = null;
	wnd.document.close();
}

function showAll(obj) {
	if(obj.checked){
		$('.divHead').show();
		$('.divList').show();
	}else{
		$('#selauthor')[0].onchange();
	}
}

/*
function initOutput() {
	/*
    var Sys = {};
    var ua = navigator.userAgent.toLowerCase();
    window.ActiveXObject ? Sys.ie = ua.match(/msie ([\d.]+)/)[1] :
    document.getBoxObjectFor ? Sys.firefox = ua.match(/firefox\/([\d.]+)/)[1] :
    window.MessageEvent && !document.getBoxObjectFor ? Sys.chrome = ua.match(/chrome\/([\d.]+)/)[1] :
    window.opera ? Sys.opera = ua.match(/opera.([\d.]+)/)[1] :
    window.openDatabase ? Sys.safari = ua.match(/version\/([\d.]+)/)[1] : 0;
    *//*
    ZeroClipboard.setMoviePath("ZeroClipboard/ZeroClipboard.swf");
    var clip = new ZeroClipboard.Client();						// 新建一个对象 
	clip.setHandCursor(true);									// 设置鼠标为手型 
	clip.setText($("#" + $('#divList').text())[0].outerHTML);	// 设置要复制的文本。 
	clip.glue("btnOutput");										// 和上一句位置不可调换 
}

function copyTable() {
	//editor.document.designMode = 'On'; // 将iframe变成可编辑模式,即HTML编辑器
	//editor.document.write("<body></body>"); // 初始化编辑器
	//editor.document.body.innerHTML = obj.outerHTML;
	document.body.createTextRange().select(); // 选中编辑器内所有内容
	document.execCommand("copy","",null); // 复制
}

*/
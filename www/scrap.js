var articleContent;
var temp;
var VIABOOK ={};
var DOM ={};

DOM = function(){

}

DOM.prototype ={
	getId	:	function(id_str){
		return document.getElementById(id_str);
	},
	getTag	:	function(tag_str){
		return document.getElementById(tag_str);
	},
	makeEle	:	function(tag,attrObj){
		var element = document.createElement(tag);
		if(attrObj != undefined){
			this.setAttr(element,attrObj);
		}
		return element;
	},
	appendEle : function(target,obj){
		if (obj.nodeType == 1) {
			obj = [obj];
		}

		if (obj.constructor != Array) {
			return;
		}

		for(var i in obj){
			target.appendChild(obj[i]);
		}
		return target;
	},
	appendEleWithDom : function(target,obj){
		if (typeof(obj) != "object") {
			return;
		}
		for(var i=0;i<obj.length;i++){
		 	target.appendChild(obj.item(i));
		}
		return target;
	},
	setAttr	:	function(ele,obj){
		for(var i in obj){
			ele.setAttribute(i,obj[i]);
		}
	},
	removeAttr	:	function(ele,obj){
		if (obj.nodeType == 1) {
			obj = [obj];
		}
		for(var i in obj){
			dg(obj);
			ele.removeAttribute(obj[i]);
		}
	},
	getAttr : 	function(ele,str){
		return ele.getAttribute(str);
	},
	getComputed : 	function(ele,str){
		return ele.getComputedStyle(str);
	},
	removeEle	:	function(ele){
		if(ele.parentNode){
			ele.parentNode.removeChild(ele);
		}	
	},
	setCss :	function(ele,obj){
		for(var i in obj){
			try{
				ele.style[i] = obj[i]
			}catch(e){
			//	alert(e);
			}
		}
		return ele;
	},
	getCss :	function(ele,obj){
		var temp = [];
		for(var i in obj){
			temp[i] = ele.style[i]
		}
		return temp;
	}

}
var dom = new DOM();

VIABOOK = function(){
	if(COMMON != undefined){
		alert("hear is local");
		return false;
	}

	this.doc 		= document;
	this.body		= this.doc.body;
	this.url		= this.doc.location;
	this.domain		= "lo.ggugi.com";
	this.toggle		=	true;
	this.targetUrl  = this.url.protocol + "//" +this.domain;
}


VIABOOK.prototype = {
	target		:	{}, 
	doc			:	{},
	body		:	{},
	url			:	{},	
	targetUrl	:	"",
	contents	:	"",
	license		:	"",
	temp		:	{},
	imgArr		:	[],
	toggle		:	"",
	article		:	{},
	callback	:	{},
	status		:	"ready",
	cache		:	{},
	debug		:	true,
	dummy		:	{},
	init		:	function(){
		if(document.getElementById("keepreading")){
			alert("already used");
			return false;
		}
		this.license = this.getLicense();
		//this.modal("show");
		window.scrollTo(0,0);
		this.cache['contents'] = this.body.innerHTML;
		/*
		if(document.getElementsByTagName('frameset').item(0)!=null){
			var frame = document.getElementsByTagName('frameset').item(0);
			for(var i in frame.childNodes){
				alert(i);
			}
			return false;
		}
		*/
		articleContent = readability.grabArticle();
		document.body.innerHTML = this.cache['contents'];
		this.makeSimpleLayer();
		if(!articleContent){
			if(!confirm("R u scrapping by youself?")){
			this.error();
			return false;
			}
			this.status = "error";
			articleContent = dom.makeEle("div");
		}

		this.dummy = dom.setCss(dom.makeEle("div"),{
			"display" 	: "block",
			"width" 	: "1px",
			"height"	: "1px",
			"overflow"	: "hidden" 
		});
		dom.appendEleWithDom(document.body,this.dummy);

		if(this.status == "ready"){
			this.dummy.innerHTML = articleContent.innerHTML;		

			this.cache["enconvert"] = this.convert(this.dummy); 
			document.getElementById("preview").contentWindow.document.body.innerHTML = this.cache['enconvert'];
			document.getElementById("source").value = this.cache['enconvert'];
		}
		this.checkUserLogin();

		window.onscroll = function(){
			var t = document.body.scrollTop;
			dom.setCss(dom.getId("loadingDiv"),{
				"top"	: t+"px"
			});
		}

		//$("input#subjectTxt").val(document.title);
		
	},
	error	:	function(){
		this.showLayer("status_layer");
		document.getElementById("text_msg").innerHTML = "Error..<br>Can not correct!!";
	//	dom.setCss(document.getElementById("loadingDiv"),{"display" : "none"});
		setTimeout(this.finishKeepReading,5000);
	},
	modal	:	function(status){
		if(status=="show"){

			$("body").css({"overflow-y" : "hidden"});

			 $("body").append($("<div id='bgdiv'></div>").css({
			  background: '#333333',
			  width:'100%',
			  height:'100%',
			  position:'absolute',
			  opacity:'0.6',
			  'top':'0',
			  'left':'0',
			  'z-index':'10'
			  })
			 ); 		
		}else{
			$("#bgdiv").remove();
		}
	},
	makeSimpleLayer 	: 	function(){
		var thisObj = this;
		var div = dom.makeEle("div",{id : "keepreading"});
		dom.setCss(div,{"position" : "absolute","z-index" : "5554","top":"0","left":"0"});
		var divTag = document.body.appendChild(div);

		var loadingDiv = dom.makeEle("div",{"id" : "loadingDiv"});
		dom.setCss(loadingDiv,{ 
			"backgroundColor" : "#fff",
			"border" : "solid 2px #333",
			"color" : "#2F639C",
			"position":"absolute",
			"top":"10px",
			"left":"10px",
			"zIndex":"5556",
			"width" : "220px",
			"textAlign" : "center",
			"fontSize" : "12px",
			"fontFamaily" : "돋움",
			"padding" : "10px"
		});

		var logo = dom.makeEle("h1",{"id" : "via_logo"});
		dom.setCss(logo,{
			"padding":0, 
			"margin":0, 
			"fontSize":"16px", 
			"fontFamily":"arial", 
			"color":"#333",
			"textAlign" : "left"
		});
		logo.innerHTML = "My Paper";

		var layer_content = dom.makeEle("div",{"id" : "layer_content"});
		dom.setCss(layer_content,{
			"padding":"10px 0",
			"margin":0
		});

		var login_layer = dom.makeEle("div",{"id" : "login_layer"});
		dom.setCss(login_layer,{"display":"none"});
		var prepare_layer = dom.makeEle("div",{"id" : "prepare_layer"});
		dom.setCss(prepare_layer,{"display":"none"});
		var login_fail_layer = dom.makeEle("div",{"id" : "login_fail_layer"});
		dom.setCss(login_fail_layer,{"display":"none"});
		var status_layer = dom.makeEle("div",{"id" : "status_layer"});
		dom.setCss(status_layer,{"display":"block"});
		layer_content.appendChild(login_layer);
		layer_content.appendChild(prepare_layer);
		layer_content.appendChild(login_fail_layer);
		layer_content.appendChild(status_layer);

		var close_area = dom.makeEle("p",{"id" : "close_area"});
		dom.setCss(close_area,{
			"position":"absolute", 
			"right":"10px", 
			"top":"10px", 
			"padding":0, 
			"margin":0
		});
		var close_btn = dom.makeEle("a",{"id" : "close_btn"});
		dom.setCss(close_btn,{
			"color":"#666", 
			"fontWeight":"bold", 
			"textDecoration":"none", 
			"display":"block", 
			"width":"15px", 
			"height":"15px", 
			"fontFamily":"verdana", 
			"border":"1px solid #ccc", 
			"textAlign":"center",
			"cursor":"pointer"	
		});
		close_btn.innerHTML = "X";
		close_area.appendChild(close_btn);


		loadingDiv.appendChild(logo);
		loadingDiv.appendChild(layer_content);
		loadingDiv.appendChild(close_area);
		divTag.appendChild(loadingDiv);


		var source = dom.makeEle("textarea",{id : "source"});
		dom.setCss(source,{"display" : "none"});
		var sourceTag = divTag.appendChild(source);
		dom.setCss(source,{width : "500px", height : "500px"});
		var iframe = dom.makeEle("iframe",{"name" : "hiddenFrame","id" : "hiddenFrame","src" : "","width" : "0","height" : "0","border" : "0","frameborder":"0"});
		divTag.appendChild(iframe);
		var form = dom.makeEle("form",
							  {		"id" : "frm",
									"method":"post",
									"target":"hiddenFrame",
									"action":"http://"+this.domain+"/scrap.php?service=api_scrap_insert"
							  });
		var subject_input = dom.makeEle("input",{id : "hTitle",type:"hidden",name	:"subjects"});
		var url_input = dom.makeEle("input",{id : "hUrl",type:"hidden",name	:"content_url"});
		var tag_input = dom.makeEle("input",{id : "hTag",type:"hidden",name	:"tag"});
		var content_input = dom.makeEle("input",{id : "hContent",type:"hidden",name	:"content"});
		var copyright_input = dom.makeEle("input",{id : "hLicense",type:"hidden",name	:"copyright"});
		form.appendChild(subject_input)
		form.appendChild(url_input);
		form.appendChild(tag_input);
		form.appendChild(content_input);
		form.appendChild(copyright_input);
		var formTag = divTag.appendChild(form);

	    var statusTag = '<p id="text_msg" style="padding:20px 0; color:#666;text-align:center; line-height:1.5em">';
	//	statusTag 	+= '	Have Saved content at viabook';
		statusTag 	+= '	Check user login...';
		statusTag 	+= '</p>';


		var prepare_content ='<ul style="; margin:0; padding:0; overflow:hidden; list-style:none;">'
		prepare_content +='<li>'
		prepare_content +='<div style="float:left; ; padding:2px 0; margin:0; overflow:hidden;">';
		prepare_content +='<label for="f_at" style="display:block; color:#666;float:left; width:65px; margin-top:3px;">';
		prepare_content +='SUBJECT</label>';
		prepare_content +='<span style="display:block; float:left; width:155px;">';
		prepare_content +='<input type="text" id="s_title" value="'+document.title+'" style="width:151px; height:20px; padding:3px 0 0 2px; border:1px solid #ccc; color:#666; font-size:12px;background-color:#fff;"></span>';
		prepare_content +='</div>';
		prepare_content +='</li>';
		prepare_content +='<li>';
		prepare_content +='<div style="float:left; ; padding:2px 0; margin:0; overflow:hidden;">';
		prepare_content +='<label for="f_url" style="display:block; float:left; width:65px; color:#666;margin-top:3px;">';
		prepare_content +='TAG';
		prepare_content +='</label>';
		prepare_content +='<span>';
		prepare_content +='<input type="hidden" value="'+document.location+'" id="s_url">';
		prepare_content +='<input type="text" value="" id="s_tag" style="width:151px; height:20px; padding:3px 0 0 2px; border:1px solid #ccc; color:#666; font-size:12px;line-hegiht:12px;background-color:#fff;">';
		prepare_content +='</span>';
		prepare_content +='</div>';
		prepare_content +='</li>';
		prepare_content +='<li>';
		prepare_content +='<div id="preview_area" style="display:none;">';
		prepare_content +='</div>';
		prepare_content +='</li>';

		prepare_content +='</ul>';
		prepare_content +='<div style="text-align:right;margin-top:10px">';
		prepare_content +='<div style="padding-bottom:10px;color:#666;">';
		prepare_content +='<span id="viabook_user"></span> Login ggugi.com ';
		//prepare_content +='<a href="javascript:via.logoutProcess()" style="color:#666; font-weight:bold; color:#666;text-decoration:none">Logout</a>';
		prepare_content +='</div>';
		prepare_content +='<input type="button" id="edit_btn" value="Edit" style="border:1px solid #D6D6D6; padding:2px 5px; color:#666; font-size:12px">';
		prepare_content +='<input type="button" id="save_btn" value="Save" style="border:1px solid #D6D6D6; padding:2px 5px; color:#666; font-size:12px">';
		prepare_content +='</div>';


	   
	    var loginTag ='<ul style="; margin:0; padding:0; overflow:hidden; list-style:none;">';
		loginTag +='	<li>';
		loginTag +='<div style="float:left; ; padding:2px 0; margin:0; overflow:hidden;">';
		loginTag +='<label for="f_email" style="display:block; float:left; color:#666;width:65px; margin-top:3px;">';
		loginTag +='E-mail';
		loginTag +='</label>';
		loginTag +='<span style="display:block; float:left; width:155px;">';
		loginTag +='<input type="text" name="" id="v_email" style="width:151px; height:20px;background-color:#fff; padding:3px 0 0 2px; border:1px solid #ccc; color:#666; font-size:12px"></span>';
		loginTag +='</div>';
		loginTag +='</li>';
		loginTag +='<li>';
		loginTag +='<div style="float:left; ; padding:2px 0; margin:0; overflow:hidden;">';
		loginTag +='<label for="f_password" style="display:block; float:left; color:#666;width:65px; margin-top:3px;">Password</label>';
		loginTag +='<span style="display:block; float:left; width:155px;">';
		loginTag +='<input type="password" name="" id="v_pass" style="width:151px;background-color:#fff; height:20px; padding:3px 0 0 2px; border:1px solid #ccc; color:#666; f)ont-size:12px"></span>';
		loginTag +='</div>';
		loginTag +='</li>';
		loginTag +='</ul>';

		loginTag +='<div style="text-align:right;margin-top:10px">';
		loginTag +='<input type="button" value="Login" id="loginBtn" style="border:1px solid #D6D6D6; padding:2px 5px; color:#666; font-size:12px">';
		loginTag +='<input type="button" value="Cencel" id="login_cencel_btn" style="border:1px solid #D6D6D6; padding:2px 5px; color:#666; font-size:12px">';
		loginTag +='</div>';

		var loginFailTag ='<div style="padding:10px 0; margin:0">';
		loginFailTag +='<p style="padding:10px 0; text-align:center; color:#666;line-height:1.5em">';
		loginFailTag +='로그인 실패했습니다.<br>다시 시도해주세요.';
		loginFailTag +='</p>';
		loginFailTag +='<div style="text-align:right">';
		loginFailTag +='<input type="button" value="다시 시도" id="login_again_btn" style="border:1px solid #D6D6D6; padding:2px 5px; color:#666; font-size:12px">';
		loginFailTag +='<input type="button" value="취소" id="cencel_keep_btn" style="border:1px solid #D6D6D6; padding:2px 5px; color:#666; font-size:12px">';
		loginFailTag +='</div>';
 
	 	prepare_layer.innerHTML = prepare_content;
		//Make Editor
		var preview = dom.makeEle("iframe",{id : "preview"});
		var previewTag = dom.getId("preview_area").appendChild(preview);
		previewTag.contentWindow.document.designMode = "On";
		dom.setCss(previewTag,{
			"background-color" : "#fff",
			"width"	 	: "500px",
			"height" 	: "500px",
			"overflow"	: "auto",
			"border"	: "solid 1px #888"
		});
		//dom.appendEleWithDom(dom.getId("preview_area"),preview);

	 	login_layer.innerHTML = loginTag;
		login_fail_layer.innerHTML = loginFailTag;
		status_layer.innerHTML = statusTag;
		document.getElementById("loginBtn").onclick =function(){
			if(document.getElementById("v_email").value.length < 2){
				alert("Please check your Email.");
				document.getElementById("v_email").focus();
				return false;
			}
			if(document.getElementById("v_pass").value.length < 2){
				alert("Please check your password.");
				document.getElementById("v_pass").focus();
				return false;
			}
			via.loginProcess();
		}
		document.getElementById("login_cencel_btn").onclick = function(){
			document.getElementById("v_email").value="";
			document.getElementById("v_pass").value="";
			document.getElementById("v_email").focus();
		
		}
		document.getElementById("login_again_btn").onclick = function(){	
			document.getElementById("v_email").value="";
			document.getElementById("v_pass").value="";
			via.showLayer("login_layer");	
			document.getElementById("v_email").focus();
		}
		document.getElementById("save_btn").onclick = function(){
			via.postServer(via.convert(dom.getId("preview").contentWindow.document.body));
		}
		document.getElementById("edit_btn").onclick = function(){
			dom.setCss(dom.getId("loadingDiv"),{"width" : "520px"});
			dom.setCss(dom.getId("preview_area"),{
				display : "block"
			});
			via.customerEditMode();
		}


 		close_btn.onclick = document.getElementById("cencel_keep_btn").onclick = function(){
			via.finishKeepReading();
		}

			
	},
	customerEditMode:	function(){
		dom.getId("loadingDiv").onmouseover	= function(){
			dom.setCss(dom.getId("preview_area"),{"display" : "block"});
			dom.setCss(this,{
				opacity	: 1.0,
				filter	: "alpha(opacity=100)"
			});
		};

		dom.getId("loadingDiv").onmouseout	= function(){
			dom.setCss(dom.getId("preview_area"),{"display" : "none"});
			dom.setCss(this,{
				opacity	: 1.0,
				filter	: "alpha(opacity=100)"
			});
		};

	},
	loginProcess	:	function(){			
		var jsonCallback = "via.makeJson2";			
		var request_url = "http://"+this.domain+"/GLOBAL/SET_SIGN_IN/output:jsonp";
		request_url += "/userEmail:"+document.getElementById("v_email").value;
		request_url += "/userPassword:"+document.getElementById("v_pass").value;
		request_url += "/jsoncallback:"+jsonCallback;
		var jsonp = dom.makeEle("script",{src:request_url});
		document.getElementById("keepreading").appendChild(jsonp);
	},
	logoutProcess	:	function(){			
		var jsonCallback = "via.afterLogout";			
		var request_url = "http://"+this.domain+"/GLOBAL/SET_SIGN_OUT/output:jsonp";
		request_url += "/jsoncallback:"+jsonCallback;
		var jsonp = dom.makeEle("script",{src:request_url});
		document.getElementById("keepreading").appendChild(jsonp);
	},
	checkUserLogin	:	function(){
		var jsonCallback = "via.makeJson";			
		var jsonp = dom.makeEle("script",{src:"http://"+this.domain+"/common.php?service=secure_common_check_login&jsoncallback="+jsonCallback});
		document.getElementById("keepreading").appendChild(jsonp);

	},
	showLayer	:	function(layer_id){
		dom.setCss(document.getElementById("prepare_layer"),{"display":"none"});
		dom.setCss(document.getElementById("login_layer"),{"display":"none"});
		dom.setCss(document.getElementById("login_fail_layer"),{"display":"none"});
		dom.setCss(document.getElementById("status_layer"),{"display":"none"});
		dom.setCss(document.getElementById(layer_id),{"display":"block"});
	},
	makeJson	:	function(obj){
		this.callback = obj;
		if(this.callback.RESULT_SET.flag != true){
			if(confirm("No login. Do you want login in ggugi.com?")){
				window.open("login","http://www.ggugi.com");
			}
			//this.showLayer("login_layer");
		}else{

			this.showLayer("prepare_layer");
			//this.postServer(this.cache['enconvert']);
			//document.getElementById("viabook_user").innerHTML= this.callback.RESPONSE.user_name;
		}
	},
	afterLogout	:	function(obj){
		this.callback = obj;
		if(this.callback.RESULT_SET.flag == "T"){
			this.showLayer("login_layer");
		}
	},
	makeJson2	:	 function(obj){
		this.callback = obj;
		if(this.callback.RESULT_SET.flag == "T"){
			this.postServer(this.cache['enconvert']);
		}else{
			this.showLayer("login_fail_layer");
		}

	
	},
	/*주석제거*/
	removeContext	:	function(str){
		return str.replace(new RegExp("<!--(\\s[a-zA-Z\_\@]*)?(\\s)*-->","gim"), "");
	},
	/*to get license of document*/
	getLicense	:	function(){
		var aTag = this.body.getElementsByTagName("a");
		for(var str="",i=0;i<aTag.length;i++){
			if(aTag[i].getAttribute("rel")!="license") continue;
			str = aTag[i].getAttribute("href")
		}
		//http://creativecommons.org/licenses/by-nc-nd/2.0/kr/
       var match_result =str.match(new RegExp("http\:\/\/creativecommons\.org\/licenses\/(.*)\/[0-9.]*\/.*"));
	   return match_result == null ? "" : match_result[1];
		
	},
	/*html 태그 제거*/
	convert		:	function(element){
		hateTag = "style|script|noscript|iframe|frame|input|select|option|map|area|button|ins";
		hateAttribute = "onclick|alt";
		removeTag  = "table|thead|tbody|th|tr|td|center|span|font|h[1-7]|strong|em|u|ul|li|wps|ol";
		removeTag += "|a|input|textarea|form|fieldset|legend|code|button|ins";
		removeTag += "|select|option|map|area|embed|pre|var|b|i|center";
		keepTag = "p|img";
		var ht = hateTag.split("|");
		for(var i=0;i<ht.length;i++){
			var remove_element= element.getElementsByTagName(ht[i]);
			var remove_element_cnt = remove_element.length;	
			for(var j=0;j<remove_element_cnt;j++){
				try{
					remove_element[j].parentNode.removeChild(remove_element[j]);
				}catch(e){
					//document.body.removeChild(remove_element[j]);
				}	

			}
		}
		
			
		var img = element.getElementsByTagName('img');
		for(var i=0;i<img.length;i++){
			dom.setAttr(img[i],{src : img[i].src});
			if(img[i].offsetWidth <30 || img[i].offsetHeight < 30){
				try{
					img[i].parentNode.removeChild(img[i]);
					
				}catch(e){}
			}else{
				dom.setAttr(img[i],{"width" : "90%"});
				img[i].removeAttribute("height");
			}
			this.imgArr.push(img[i]);
		}		
		var ha = hateAttribute.split("|");
		var allElements = element.getElementsByTagName("*");
		for(var i=0;i<allElements.length;i++){
				for(var j=0;j<ha.length;j++){
					var re =allElements[i].removeAttribute(ha[j],1);
				}
		}
		var str = element.innerHTML
		str = str.replace(new RegExp("<(/)?p(\\s)*(\\s[a-zA-Z]*=[^>]*)?>",'gim'), "\r");
		str = str.replace(new RegExp("<(/)?div(\\s)*(\\s[a-zA-Z]*=[^>]*)?>",'gim'), "\r");
		str = str.replace(new RegExp("<(/)?br(\\s)*(\\s[a-zA-Z]*=[^>]*)?(\\s)*(/)?>",'gim'), "\r");
		str = str.replace(new RegExp("^(\\s)*",'gim'),"");

		str = str.replace(new RegExp("<(/)?("+removeTag+")(\\s[a-zA-Z]*=[^>]*)?(\\s)*(/)?>","gim"), "");
		
		str = str.replace(new RegExp("<!--(\\s)*(.*)*(\\s)*-->","gim"), "");
		str = str.replace(new RegExp("(\\r)",'gim'),"</p>\r<p>");
	//	str = this.removeContext(str);
		str = str.replace(new RegExp("&nbsp;","gim")," ");
		str = str.replace(/\n/,"");
		str = str.replace(/\r/,"");
		str = str.replace(/\r\n/,"");
		str = str.replace(/^\s*/,"");
	//	str = str.replace(new RegExp(":p:(\\s)?(:p:)*","gim"),"</p><p>");
	//	str = str.replace(new RegExp(":p:","gim"),"");
		str = "<p>"+str+"</p>";
		str = str.replace(new RegExp("<p>(\\s)?</p>","gim"),"");


		element.innerHTML = str;		


	
		//str = str.replace(new RegExp(":/p:","gim"),"</p>");
		//str = this.deconvert(str);
		return str;
	},
	getBrowser	:	function(){
			var brs
			if(navigator.appName == "Netscape"){
				brs= navigator.userAgent.match(new RegExp("Chrome","i")) ? 'chrome' : 'firefox';
			}else{
				brs='msie';
			}
			return brs;
	},
	finishKeepReading	:	function(){
		dom.removeEle(document.getElementById("keepreading"));
		//$("div#bgdiv").remove();
		//$("body").css({"overflow-y" : "auto"});
	},
	showLoading	:	function(){
		$("body").append("<div id='loading'>Loading.....<br><br><img src='"+this.targetUrl+"/img/loading.gif'></div>");
			$("div#loading").css({
				"width"	:	"300px",
				"height"	:	"150px",
				"background-color"	:	"#ffffff",
				"fontSize"	:	"30px",
				"fontWeight"	:	"bold",
				"color"		:	"#808080",
				"border"	:	"solid 2px #808080",
				"position"	:	"absolute",
				"z-index"	:	"999999",
				"top"		:	"150px",
				"left"		:	"150px",
				"opacity"	:	"0.6",
				"text-align":	"center"
			});

			setTimeout(this.hideLoading,2000);
	},
	hideLoading : function(){
		$("div#loading").remove();
	},
	postServer : function(data){
		//dom.setCss(document.getElementById("loginLayer"),{"display":"none"});
		document.getElementById("hContent").value = data;
		document.getElementById("hTitle").value = document.getElementById("s_title").value;
		document.getElementById("hUrl").value = document.getElementById("s_url").value;
		document.getElementById("hTag").value = document.getElementById("s_tag").value;
		document.getElementById("hLicense").value = this.license;
		document.charset='utf-8';
		document.getElementById("hiddenFrame").setAttribute("name","hiddenFrame");
		if(window.frames['hiddenFrame'].name != 'hiddenFrame') { window.frames['hiddenFrame'].name = 'hiddenFrame'; } 
		document.getElementById("frm").target = window.frames['hiddenFrame'].name;
		document.getElementById("frm").submit();
		this.showLayer("status_layer");
		document.getElementById("text_msg").innerHTML="Have saved to ggugi";
		if(this.debug){
			dom.setCss(document.getElementById("loadingDiv"),{"display" : "none"});
			dom.setAttr(document.getElementById("hiddenFrame"),{"width":"500","height":"500"});
			dom.setCss(document.getElementById("source"),{"width":"500","height":"500","display":"inline"});
			return;
		}
		setTimeout(this.finishKeepReading,2000);
	}
}


var dg = (typeof console !== 'undefined') ? function(s) {
    console.log("ggugi: " + s);
} : function() {};

var dbg = (typeof console !== 'undefined') ? function(s) {
    console.log("Readability: " + s);
} : function() {};

/*
 * Readability. An Arc90 Lab Experiment. 
 * Website: http://lab.arc90.com/experiments/readability
 * Source:  http://code.google.com/p/arc90labs-readability
 *
 * "Readability" is a trademark of Arc90 Inc and may not be used without explicit permission. 
 *
 * Copyright (c) 2010 Arc90 Inc
 * Readability is licensed under the Apache License, Version 2.0.
**/
var readStyle="";
var readMargin="";
var readSize="";
var readability = {
    version:                '1.7.1',
    emailSrc:               'http://lab.arc90.com/experiments/readability/email.php',
    iframeLoads:             0,
    convertLinksToFootnotes: false,
    reversePageScroll:       false, /* If they hold shift and hit space, scroll up */
    frameHack:               false, /**
                                      * The frame hack is to workaround a firefox bug where if you
                                      * pull content out of a frame and stick it into the parent element, the scrollbar won't appear.
                                      * So we fake a scrollbar in the wrapping div.
                                     **/
    biggestFrame:            false,
    bodyCache:               null,   /* Cache the body HTML in case we need to re-use it later */
    flags:                   0x1 | 0x2 | 0x4,   /* Start with all flags set. */

    /* constants */
    FLAG_STRIP_UNLIKELYS:     0x1,
    FLAG_WEIGHT_CLASSES:      0x2,
    FLAG_CLEAN_CONDITIONALLY: 0x4,

    maxPages:    30, /* The maximum number of pages to loop through before we call it quits and just show a link. */
    parsedPages: {}, /* The list of pages we've parsed in this call of readability, for autopaging. As a key store for easier searching. */
    pageETags:   {}, /* A list of the ETag headers of pages we've parsed, in case they happen to match, we'll know it's a duplicate. */
    
    /**
     * All of the regular expressions in use within readability.
     * Defined up here so we don't instantiate them repeatedly in loops.
     **/
    regexps: {
        unlikelyCandidates:    /combx|comment|community|disqus|extra|foot|header|menu|remark|rss|shoutbox|sidebar|sponsor|ad-break|agegate|pagination|pager|popup|tweet|twitter/i,
        okMaybeItsACandidate:  /and|article|body|column|main|shadow/i,
        positive:              /article|body|content|entry|hentry|main|page|pagination|post|text|blog|story/i,
        negative:              /combx|comment|com-|contact|foot|footer|footnote|masthead|media|meta|outbrain|promo|related|scroll|shoutbox|sidebar|sponsor|shopping|tags|tool|widget/i,
        extraneous:            /print|archive|comment|discuss|e[\-]?mail|share|reply|all|login|sign|single/i,
        divToPElements:        /<(a|blockquote|dl|div|ol|p|pre|table|ul)/i,
        replaceBrs:            /(<br[^>]*>[ \n\r\t]*){2,}/gi,
        replaceFonts:          /<(\/?)font[^>]*>/gi,
        trim:                  /^\s+|\s+$/g,
        normalize:             /\s{2,}/g,
        killBreaks:            /(<br\s*\/?>(\s|&nbsp;?)*){1,}/g,
        videos:                /http:\/\/(www\.)?(youtube|vimeo)\.com/i,
        skipFootnoteLink:      /^\s*(\[?[a-z0-9]{1,2}\]?|^|edit|citation needed)\s*$/i,
        nextLink:              /(next|weiter|continue|>([^\|]|$)|»([^\|]|$))/i, // Match: next, continue, >, >>, » but not >|, »| as those usually mean last.
        prevLink:              /(prev|earl|old|new|<|«)/i
    },

    /**
     * Runs readability.
     * 
     * Workflow:
     *  1. Prep the document by removing script tags, css, etc.
     *  2. Build readability's DOM tree.
     *  3. Grab the article content from the current dom tree.
     *  4. Replace the current DOM tree with the new one.
     *  5. Read peacefully.
     *
     * @return void
     **/


    /**
     * Run any post-process modifications to article content as necessary.
     * 
     * @param Element
     * @return void
    **/
    postProcessContent: function(articleContent) {
        if(readability.convertLinksToFootnotes && !window.location.href.match(/wikipedia\.org/g)) {
            readability.addFootnotes(articleContent);
        }
        readability.fixImageFloats(articleContent);
    },

    /**
     * Some content ends up looking ugly if the image is too large to be floated.
     * If the image is wider than a threshold (currently 55%), no longer float it,
     * center it instead.
     *
     * @param Element
     * @return void
    **/
    fixImageFloats: function (articleContent) {

        var imageWidthThreshold = Math.min(articleContent.offsetWidth, 800) * 0.55,
            images              = articleContent.getElementsByTagName('img');

        for(var i=0, il = images.length; i < il; i++) {
            var image = images[i];
            
            if(image.offsetWidth > imageWidthThreshold) {
                image.className += " blockImage";
            }
        }

		
    },

    /**
     * Get the article tools Element that has buttons like reload, print, email.
     *
     * @return void
     **/
    getArticleTools: function () {
        var articleTools = document.createElement("DIV");

        articleTools.id        = "readTools";
        return articleTools;
    },

    /**
     * retuns the suggested direction of the string
     *
     * @return "rtl" || "ltr"
     **/
    getSuggestedDirection: function(text) {
        function sanitizeText() {
            return text.replace(/@\w+/, "");
        }
        
        function countMatches(match) {
            var matches = text.match(new RegExp(match, "g"));
            return matches != null ? matches.length : 0; 
        }
        
        function isRTL() {            
            var count_heb =  countMatches("[\\u05B0-\\u05F4\\uFB1D-\\uFBF4]");
            var count_arb =  countMatches("[\\u060C-\\u06FE\\uFB50-\\uFEFC]");

            // if 20% of chars are Hebrew or Arbic then direction is rtl
            return  (count_heb + count_arb) * 100 / text.length > 20;
        }

        text  = sanitizeText(text);
        return isRTL() ? "rtl" : "ltr";
    },

    
    /**
     * Get the article title as an H1.
     *
     * @return void
     **/
    getArticleTitle: function () {
        var curTitle = "",
            origTitle = "";

        try {
            curTitle = origTitle = document.title;
            
            if(typeof curTitle != "string") { /* If they had an element with id "title" in their HTML */
                curTitle = origTitle = readability.getInnerText(document.getElementsByTagName('title')[0]);             
            }
        }
        catch(e) {}
        
        if(curTitle.match(/ [\|\-] /))
        {
            curTitle = origTitle.replace(/(.*)[\|\-] .*/gi,'$1');
            
            if(curTitle.split(' ').length < 3) {
                curTitle = origTitle.replace(/[^\|\-]*[\|\-](.*)/gi,'$1');
            }
        }
        else if(curTitle.indexOf(': ') !== -1)
        {
            curTitle = origTitle.replace(/.*:(.*)/gi, '$1');

            if(curTitle.split(' ').length < 3) {
                curTitle = origTitle.replace(/[^:]*[:](.*)/gi,'$1');
            }
        }
        else if(curTitle.length > 150 || curTitle.length < 15)
        {
            var hOnes = document.getElementsByTagName('h1');
            if(hOnes.length == 1)
            {
                curTitle = readability.getInnerText(hOnes[0]);
            }
        }

        curTitle = curTitle.replace( readability.regexps.trim, "" );

        if(curTitle.split(' ').length <= 4) {
            curTitle = origTitle;
        }
        
        var articleTitle = document.createElement("H1");
        articleTitle.innerHTML = curTitle;
        
        return articleTitle;
    },

    /**
     * Get the footer with the readability mark etc.
     *
     * @return void
     **/
    getArticleFooter: function () {
        var articleFooter = document.createElement("DIV");
        articleFooter.id = "readFooter";
        return articleFooter;
    },
    
    /**
     * Prepare the HTML document for readability to scrape it.
     * This includes things like stripping javascript, CSS, and handling terrible markup.
     * 
     * @return void
     **/
    prepDocument: function () {
        /**
         * In some cases a body element can't be found (if the HTML is totally hosed for example)
         * so we create a new body node and append it to the document.
         */
        if(document.body === null)
        {
            var body = document.createElement("body");
            try {
                document.body = body;       
            }
            catch(e) {
                document.documentElement.appendChild(body);
                dbg(e);
            }
        }

        document.body.id = "readabilityBody";

        var frames = document.getElementsByTagName('frame');
        if(frames.length > 0)
        {
            var bestFrame = null;
            var bestFrameSize = 0;    /* The frame to try to run readability upon. Must be on same domain. */
            var biggestFrameSize = 0; /* Used for the error message. Can be on any domain. */
            for(var frameIndex = 0; frameIndex < frames.length; frameIndex++)
            {
                var frameSize = frames[frameIndex].offsetWidth + frames[frameIndex].offsetHeight;
                var canAccessFrame = false;
                try {
                    frames[frameIndex].contentWindow.document.body;
                    canAccessFrame = true;
                }
                catch(eFrames) {
                    dbg(eFrames);
                }

                if(frameSize > biggestFrameSize) {
                    biggestFrameSize         = frameSize;
                    readability.biggestFrame = frames[frameIndex];
                }
                
                if(canAccessFrame && frameSize > bestFrameSize)
                {
                    readability.frameHack = true;
    
                    bestFrame = frames[frameIndex];
                    bestFrameSize = frameSize;
                }
            }

            if(bestFrame)
            {
                var newBody = document.createElement('body');
                newBody.innerHTML = bestFrame.contentWindow.document.body.innerHTML;
                newBody.style.overflow = 'scroll';
                document.body = newBody;
                
                var frameset = document.getElementsByTagName('frameset')[0];
                if(frameset) {
                    frameset.parentNode.removeChild(frameset); }
            }
        }

        /* Remove all stylesheets */
		/*
        for (var k=0;k < document.styleSheets.length; k++) {
            if (document.styleSheets[k].href !== null && document.styleSheets[k].href.lastIndexOf("readability") == -1) {
                document.styleSheets[k].disabled = true;
            }
        }
		*/
        /* Remove all style tags in head (not doing this on IE) - TODO: Why not? */
        var styleTags = document.getElementsByTagName("style");
        for (var st=0;st < styleTags.length; st++) {
            styleTags[st].textContent = "";
        }

        /* Turn all double br's into p's */
        /* Note, this is pretty costly as far as processing goes. Maybe optimize later. */
        document.body.innerHTML = document.body.innerHTML.replace(readability.regexps.replaceBrs, '</p><p>').replace(readability.regexps.replaceFonts, '<$1span>');
    },

    /**
     * For easier reading, convert this document to have footnotes at the bottom rather than inline links.
     * @see http://www.roughtype.com/archives/2010/05/experiments_in.php
     *
     * @return void
    **/
    addFootnotes: function(articleContent) {
        var footnotesWrapper = document.getElementById('readability-footnotes'),
            articleFootnotes = document.getElementById('readability-footnotes-list');
        
        if(!footnotesWrapper) {
            footnotesWrapper               = document.createElement("DIV");
            footnotesWrapper.id            = 'readability-footnotes';
            footnotesWrapper.innerHTML     = '<h3>References</h3>';
            footnotesWrapper.style.display = 'none'; /* Until we know we have footnotes, don't show the references block. */
            
            articleFootnotes    = document.createElement('ol');
            articleFootnotes.id = 'readability-footnotes-list';
            
            footnotesWrapper.appendChild(articleFootnotes);
    
            var readFooter = document.getElementById('readFooter');
            
            if(readFooter) {
                readFooter.parentNode.insertBefore(footnotesWrapper, readFooter);
            }
        }

        var articleLinks = articleContent.getElementsByTagName('a');
        var linkCount    = articleFootnotes.getElementsByTagName('li').length;
        for (var i = 0; i < articleLinks.length; i++)
        {
            var articleLink  = articleLinks[i],
                footnoteLink = articleLink.cloneNode(true),
                refLink      = document.createElement('a'),
                footnote     = document.createElement('li'),
                linkDomain   = footnoteLink.host ? footnoteLink.host : document.location.host,
                linkText     = readability.getInnerText(articleLink);
            
            if(articleLink.className && articleLink.className.indexOf('readability-DoNotFootnote') !== -1 || linkText.match(readability.regexps.skipFootnoteLink)) {
                continue;
            }
            
            linkCount++;

            /** Add a superscript reference after the article link */
            refLink.href      = '#readabilityFootnoteLink-' + linkCount;
            refLink.innerHTML = '<small><sup>[' + linkCount + ']</sup></small>';
            refLink.className = 'readability-DoNotFootnote';
            try { refLink.style.color = 'inherit'; } catch(e) {} /* IE7 doesn't like inherit. */
            
            if(articleLink.parentNode.lastChild == articleLink) {
                articleLink.parentNode.appendChild(refLink);
            } else {
                articleLink.parentNode.insertBefore(refLink, articleLink.nextSibling);
            }

            articleLink.name        = 'readabilityLink-' + linkCount;
            try { articleLink.style.color = 'inherit'; } catch(e) {} /* IE7 doesn't like inherit. */

            footnote.innerHTML      = "<small><sup><a href='#readabilityLink-" + linkCount + "' title='Jump to Link in Article'>^</a></sup></small> ";

            footnoteLink.innerHTML  = (footnoteLink.title ? footnoteLink.title : linkText);
            footnoteLink.name       = 'readabilityFootnoteLink-' + linkCount;
            
            footnote.appendChild(footnoteLink);
            footnote.innerHTML = footnote.innerHTML + "<small> (" + linkDomain + ")</small>";
            
            articleFootnotes.appendChild(footnote);
        }

        if(linkCount > 0) {
            footnotesWrapper.style.display = 'block';
        }
    },

    useRdbTypekit: function () {
        var rdbHead      = document.getElementsByTagName('head')[0];
        var rdbTKScript  = document.createElement('script');
        var rdbTKCode    = null;

        var rdbTKLink    = document.createElement('a');
            rdbTKLink.setAttribute('class','rdbTK-powered');
            rdbTKLink.setAttribute('title','Fonts by Typekit');
            rdbTKLink.innerHTML = "Fonts by <span class='rdbTK'>Typekit</span>";

        if (readStyle == "style-athelas") {
            rdbTKCode = "sxt6vzy";
            dbg("Using Athelas Theme");

            rdbTKLink.setAttribute('href','http://typekit.com/?utm_source=readability&utm_medium=affiliate&utm_campaign=athelas');
            rdbTKLink.setAttribute('id','rdb-athelas');
            document.getElementById("rdb-footer-right").appendChild(rdbTKLink);
        }
        if (readStyle == "style-apertura") {
            rdbTKCode = "bae8ybu";
            dbg("Using Inverse Theme");

            rdbTKLink.setAttribute('href','http://typekit.com/?utm_source=readability&utm_medium=affiliate&utm_campaign=inverse');
            rdbTKLink.setAttribute('id','rdb-inverse');
            document.getElementById("rdb-footer-right").appendChild(rdbTKLink);
        }

        /**
         * Setting new script tag attributes to pull Typekits libraries
        **/
        rdbTKScript.setAttribute('type','text/javascript');
        rdbTKScript.setAttribute('src',"http://use.typekit.com/" + rdbTKCode + ".js");
        rdbTKScript.setAttribute('charset','UTF-8');
        rdbHead.appendChild(rdbTKScript);

        /**
         * In the future, maybe try using the following experimental Callback function?:
         * http://gist.github.com/192350
         * &
         * http://getsatisfaction.com/typekit/topics/support_a_pre_and_post_load_callback_function
        **/
        var typekitLoader = function() {
            dbg("Looking for Typekit.");
            if(typeof Typekit != "undefined") {
                try {
                    dbg("Caught typekit");
                    Typekit.load();
                    clearInterval(window.typekitInterval);
                } catch(e) {
                    dbg("Typekit error: " + e);
                }
            }
        };

        window.typekitInterval = window.setInterval(typekitLoader, 100);
    },

    /**
     * Prepare the article node for display. Clean out any inline styles,
     * iframes, forms, strip extraneous <p> tags, etc.
     *
     * @param Element
     * @return void
     **/
    prepArticle: function (articleContent) {
        readability.cleanStyles(articleContent);
        readability.killBreaks(articleContent);

        /* Clean out junk from the article content */
        readability.cleanConditionally(articleContent, "form");
        readability.clean(articleContent, "object");
        readability.clean(articleContent, "h1");

        /**
         * If there is only one h2, they are probably using it
         * as a header and not a subheader, so remove it since we already have a header.
        ***/
        if(articleContent.getElementsByTagName('h2').length == 1) {
            readability.clean(articleContent, "h2");
        }
        readability.clean(articleContent, "iframe");

        readability.cleanHeaders(articleContent);

        /* Do these last as the previous stuff may have removed junk that will affect these */
        readability.cleanConditionally(articleContent, "table");
        readability.cleanConditionally(articleContent, "ul");
        readability.cleanConditionally(articleContent, "div");

        /* Remove extra paragraphs */
        var articleParagraphs = articleContent.getElementsByTagName('p');
        for(var i = articleParagraphs.length-1; i >= 0; i--) {
            var imgCount    = articleParagraphs[i].getElementsByTagName('img').length;
            var embedCount  = articleParagraphs[i].getElementsByTagName('embed').length;
            var objectCount = articleParagraphs[i].getElementsByTagName('object').length;
            
            if(imgCount === 0 && embedCount === 0 && objectCount === 0 && readability.getInnerText(articleParagraphs[i], false) == '') {
                articleParagraphs[i].parentNode.removeChild(articleParagraphs[i]);
            }
        }

        try {
            articleContent.innerHTML = articleContent.innerHTML.replace(/<br[^>]*>\s*<p/gi, '<p');      
        }
        catch (e) {
            dbg("Cleaning innerHTML of breaks failed. This is an IE strict-block-elements bug. Ignoring.: " + e);
        }
    },
    
    /**
     * Initialize a node with the readability object. Also checks the
     * className/id for special names to add to its score.
     *
     * @param Element
     * @return void
    **/
    initializeNode: function (node) {
        node.readability = {"contentScore": 0};         

        switch(node.tagName) {
            case 'DIV':
                node.readability.contentScore += 5;
                break;

            case 'PRE':
            case 'TD':
            case 'BLOCKQUOTE':
                node.readability.contentScore += 3;
                break;
                
            case 'ADDRESS':
            case 'OL':
            case 'UL':
            case 'DL':
            case 'DD':
            case 'DT':
            case 'LI':
            case 'FORM':
                node.readability.contentScore -= 3;
                break;

            case 'H1':
            case 'H2':
            case 'H3':
            case 'H4':
            case 'H5':
            case 'H6':
            case 'TH':
                node.readability.contentScore -= 5;
                break;
        }
       
        node.readability.contentScore += readability.getClassWeight(node);
    },
    
    /***
     * grabArticle - Using a variety of metrics (content score, classname, element types), find the content that is
     *               most likely to be the stuff a user wants to read. Then return it wrapped up in a div.
     *
     * @param page a document to run upon. Needs to be a full document, complete with body.
     * @return Element
    **/
    grabArticle: function (page) {
        var stripUnlikelyCandidates = readability.flagIsActive(readability.FLAG_STRIP_UNLIKELYS),
            isPaging = (page !== null) ? true: false;

        page = page ? page : document.body;
		this.removeScripts(page);

        var pageCacheHtml = page.innerHTML;

        var allElements = page.getElementsByTagName('*');

        /**
         * First, node prepping. Trash nodes that look cruddy (like ones with the class name "comment", etc), and turn divs
         * into P tags where they have been used inappropriately (as in, where they contain no other block level elements.)
         *
         * Note: Assignment from index for performance. See http://www.peachpit.com/articles/article.aspx?p=31567&seqNum=5
         * TODO: Shouldn't this be a reverse traversal?
        **/
        var node = null;
        var nodesToScore = [];
        for(var nodeIndex = 0; (node = allElements[nodeIndex]); nodeIndex++) {
            /* Remove unlikely candidates */
            if (stripUnlikelyCandidates) {
                var unlikelyMatchString = node.className + node.id;
                if (
                    (
                        unlikelyMatchString.search(readability.regexps.unlikelyCandidates) !== -1 &&
                        unlikelyMatchString.search(readability.regexps.okMaybeItsACandidate) == -1 &&
                        node.tagName !== "BODY"
                    )
                )
                {
                    dbg("Removing unlikely candidate - " + unlikelyMatchString);
                    node.parentNode.removeChild(node);
                    nodeIndex--;
                    continue;
                }               
            }

            if (node.tagName === "P" || node.tagName === "TD" || node.tagName === "PRE") {
                nodesToScore[nodesToScore.length] = node;
            }

            /* Turn all divs that don't have children block level elements into p's */
            if (node.tagName === "DIV") {
                if (node.innerHTML.search(readability.regexps.divToPElements) === -1) {
                    var newNode = document.createElement('p');
                    try {
                        newNode.innerHTML = node.innerHTML;             
                        node.parentNode.replaceChild(newNode, node);
                        nodeIndex--;

                        nodesToScore[nodesToScore.length] = node;
                    }
                    catch(e) {
                        dbg("Could not alter div to p, probably an IE restriction, reverting back to div.: " + e);
                    }
                }
                else
                {
                    /* EXPERIMENTAL */
                    for(var i = 0, il = node.childNodes.length; i < il; i++) {
                        var childNode = node.childNodes[i];
                        if(childNode.nodeType == 3) { // Node.TEXT_NODE
                            var p = document.createElement('p');
                            p.innerHTML = childNode.nodeValue;
                            p.style.display = 'inline';
                            p.className = 'readability-styled';
                            childNode.parentNode.replaceChild(p, childNode);
                        }
                    }
                }
            } 
        }

        /**
         * Loop through all paragraphs, and assign a score to them based on how content-y they look.
         * Then add their score to their parent node.
         *
         * A score is determined by things like number of commas, class names, etc. Maybe eventually link density.
        **/
        var candidates = [];
        for (var pt=0; pt < nodesToScore.length; pt++) {
            var parentNode      = nodesToScore[pt].parentNode;
            var grandParentNode = parentNode ? parentNode.parentNode : null;
            var innerText       = readability.getInnerText(nodesToScore[pt]);

            if(!parentNode || typeof(parentNode.tagName) == 'undefined') {
                continue;
            }

            /* If this paragraph is less than 25 characters, don't even count it. */
            if(innerText.length < 25) {
                continue; }

            /* Initialize readability data for the parent. */
            if(typeof parentNode.readability == 'undefined') {
                readability.initializeNode(parentNode);
                candidates.push(parentNode);
            }

            /* Initialize readability data for the grandparent. */
            if(grandParentNode && typeof(grandParentNode.readability) == 'undefined' && typeof(grandParentNode.tagName) != 'undefined') {
                readability.initializeNode(grandParentNode);
                candidates.push(grandParentNode);
            }

            var contentScore = 0;

            /* Add a point for the paragraph itself as a base. */
            contentScore++;

            /* Add points for any commas within this paragraph */
            contentScore += innerText.split(',').length;
            
            /* For every 100 characters in this paragraph, add another point. Up to 3 points. */
            contentScore += Math.min(Math.floor(innerText.length / 100), 3);
            
            /* Add the score to the parent. The grandparent gets half. */
            parentNode.readability.contentScore += contentScore;

            if(grandParentNode) {
                grandParentNode.readability.contentScore += contentScore/2;             
            }
        }

        /**
         * After we've calculated scores, loop through all of the possible candidate nodes we found
         * and find the one with the highest score.
        **/
        var topCandidate = null;
        for(var c=0, cl=candidates.length; c < cl; c++)
        {
            /**
             * Scale the final candidates score based on link density. Good content should have a
             * relatively small link density (5% or less) and be mostly unaffected by this operation.
            **/
            candidates[c].readability.contentScore = candidates[c].readability.contentScore * (1-readability.getLinkDensity(candidates[c]));

            dbg('Candidate: ' + candidates[c] + " (" + candidates[c].className + ":" + candidates[c].id + ") with score " + candidates[c].readability.contentScore);

            if(!topCandidate || candidates[c].readability.contentScore > topCandidate.readability.contentScore) {
                topCandidate = candidates[c]; }
        }

        /**
         * If we still have no top candidate, just use the body as a last resort.
         * We also have to copy the body node so it is something we can modify.
         **/
        if (topCandidate === null || topCandidate.tagName == "BODY")
        {
            topCandidate = document.createElement("DIV");
            topCandidate.innerHTML = page.innerHTML;
			//alert(page);
            //page.innerHTML = "";
            page.appendChild(topCandidate);
            readability.initializeNode(topCandidate);
        }

        /**
         * Now that we have the top candidate, look through its siblings for content that might also be related.
         * Things like preambles, content split by ads that we removed, etc.
        **/
        var articleContent        = document.createElement("DIV");
        if (isPaging) {
            articleContent.id     = "readability-content";
        }
        var siblingScoreThreshold = Math.max(10, topCandidate.readability.contentScore * 0.2);
        var siblingNodes          = topCandidate.parentNode.childNodes;


        for(var s=0, sl=siblingNodes.length; s < sl; s++) {
            var siblingNode = siblingNodes[s];
            var append      = false;

            /**
             * Fix for odd IE7 Crash where siblingNode does not exist even though this should be a live nodeList.
             * Example of error visible here: http://www.esquire.com/features/honesty0707
            **/
            if(!siblingNode) {
                continue;
            }

            dbg("Looking at sibling node: " + siblingNode + " (" + siblingNode.className + ":" + siblingNode.id + ")" + ((typeof siblingNode.readability != 'undefined') ? (" with score " + siblingNode.readability.contentScore) : ''));
            dbg("Sibling has score " + (siblingNode.readability ? siblingNode.readability.contentScore : 'Unknown'));

            if(siblingNode === topCandidate)
            {
                append = true;
            }

            var contentBonus = 0;
            /* Give a bonus if sibling nodes and top candidates have the example same classname */
            if(siblingNode.className == topCandidate.className && topCandidate.className != "") {
                contentBonus += topCandidate.readability.contentScore * 0.2;
            }

            if(typeof siblingNode.readability != 'undefined' && (siblingNode.readability.contentScore+contentBonus) >= siblingScoreThreshold)
            {
                append = true;
            }
            
            if(siblingNode.nodeName == "P") {
                var linkDensity = readability.getLinkDensity(siblingNode);
                var nodeContent = readability.getInnerText(siblingNode);
                var nodeLength  = nodeContent.length;
                
                if(nodeLength > 80 && linkDensity < 0.25)
                {
                    append = true;
                }
                else if(nodeLength < 80 && linkDensity === 0 && nodeContent.search(/\.( |$)/) !== -1)
                {
                    append = true;
                }
            }

            if(append) {
                dbg("Appending node: " + siblingNode);

                var nodeToAppend = null;
                if(siblingNode.nodeName != "DIV" && siblingNode.nodeName != "P") {
                    /* We have a node that isn't a common block level element, like a form or td tag. Turn it into a div so it doesn't get filtered out later by accident. */
                    
                    dbg("Altering siblingNode of " + siblingNode.nodeName + ' to div.');
                    nodeToAppend = document.createElement("DIV");
                    try {
                        nodeToAppend.id = siblingNode.id;
                        nodeToAppend.innerHTML = siblingNode.innerHTML;
                    }
                    catch(er) {
                        dbg("Could not alter siblingNode to div, probably an IE restriction, reverting back to original.");
                        nodeToAppend = siblingNode;
                        s--;
                        sl--;
                    }
                } else {
                    nodeToAppend = siblingNode;
                    s--;
                    sl--;
                }
                
                /* To ensure a node does not interfere with readability styles, remove its classnames */
                nodeToAppend.className = "";

                /* Append sibling and subtract from our list because it removes the node when you append to another node */
                articleContent.appendChild(nodeToAppend);
            }
        }

        /**
         * So we have all of the content that we need. Now we clean it up for presentation.
        **/
        readability.prepArticle(articleContent);

        if (readability.curPageNum === 1) {
            articleContent.innerHTML = '<div id="readability-page-1" class="page">' + articleContent.innerHTML + '</div>';
        }

        /**
         * Now that we've gone through the full algorithm, check to see if we got any meaningful content.
         * If we didn't, we may need to re-run grabArticle with different flags set. This gives us a higher
         * likelihood of finding the content, and the sieve approach gives us a higher likelihood of
         * finding the -right- content.
        **/
        if(readability.getInnerText(articleContent, false).length < 250) {
        page.innerHTML = pageCacheHtml;

            if (readability.flagIsActive(readability.FLAG_STRIP_UNLIKELYS)) {
                readability.removeFlag(readability.FLAG_STRIP_UNLIKELYS);
                return readability.grabArticle(page);
            }
            else if (readability.flagIsActive(readability.FLAG_WEIGHT_CLASSES)) {
                readability.removeFlag(readability.FLAG_WEIGHT_CLASSES);
                return readability.grabArticle(page);
            }
            else if (readability.flagIsActive(readability.FLAG_CLEAN_CONDITIONALLY)) {
                readability.removeFlag(readability.FLAG_CLEAN_CONDITIONALLY);
                return readability.grabArticle(page);
            } else {
                return null;
            }
        }
        
        return articleContent;
    },
    
    /**
     * Removes script tags from the document.
     *
     * @param Element
    **/
    removeScripts: function (doc) {
        var scripts = doc.getElementsByTagName('script');
        for(var i = scripts.length-1; i >= 0; i--)
        {
            if(typeof(scripts[i].src) == "undefined" || (scripts[i].src.indexOf('readability') == -1 && scripts[i].src.indexOf('typekit') == -1))
            {
                scripts[i].nodeValue="";
                scripts[i].removeAttribute('src');
		if (scripts[i].parentNode) {
	                scripts[i].parentNode.removeChild(scripts[i]);          
		}
            }
        }
    },
    
    /**
     * Get the inner text of a node - cross browser compatibly.
     * This also strips out any excess whitespace to be found.
     *
     * @param Element
     * @return string
    **/
    getInnerText: function (e, normalizeSpaces) {
        var textContent    = "";

        if(typeof(e.textContent) == "undefined" && typeof(e.innerText) == "undefined") {
            return "";
        }

        normalizeSpaces = (typeof normalizeSpaces == 'undefined') ? true : normalizeSpaces;

        if (navigator.appName == "Microsoft Internet Explorer") {
            textContent = e.innerText.replace( readability.regexps.trim, "" ); }
        else {
            textContent = e.textContent.replace( readability.regexps.trim, "" ); }

        if(normalizeSpaces) {
            return textContent.replace( readability.regexps.normalize, " "); }
        else {
            return textContent; }
    },

    /**
     * Get the number of times a string s appears in the node e.
     *
     * @param Element
     * @param string - what to split on. Default is ","
     * @return number (integer)
    **/
    getCharCount: function (e,s) {
        s = s || ",";
        return readability.getInnerText(e).split(s).length-1;
    },

    /**
     * Remove the style attribute on every e and under.
     * TODO: Test if getElementsByTagName(*) is faster.
     *
     * @param Element
     * @return void
    **/
    cleanStyles: function (e) {
        e = e || document;
        var cur = e.firstChild;

        if(!e) {
            return; }

        // Remove any root styles, if we're able.
        if(typeof e.removeAttribute == 'function' && e.className != 'readability-styled') {
            e.removeAttribute('style'); }

        // Go until there are no more child nodes
        while ( cur !== null ) {
            if ( cur.nodeType == 1 ) {
                // Remove style attribute(s) :
                if(cur.className != "readability-styled") {
                    cur.removeAttribute("style");                   
                }
                readability.cleanStyles( cur );
            }
            cur = cur.nextSibling;
        }           
    },
    
    /**
     * Get the density of links as a percentage of the content
     * This is the amount of text that is inside a link divided by the total text in the node.
     * 
     * @param Element
     * @return number (float)
    **/
    getLinkDensity: function (e) {
        var links      = e.getElementsByTagName("a");
        var textLength = readability.getInnerText(e).length;
        var linkLength = 0;
        for(var i=0, il=links.length; i<il;i++)
        {
            linkLength += readability.getInnerText(links[i]).length;
        }       

        return linkLength / textLength;
    },
    
    /**
     * Find a cleaned up version of the current URL, to use for comparing links for possible next-pageyness.
     *
     * @author Dan Lacy
     * @return string the base url
    **/
    findBaseUrl: function () {
        var noUrlParams     = window.location.pathname.split("?")[0],
            urlSlashes      = noUrlParams.split("/").reverse(),
            cleanedSegments = [],
            possibleType    = "";

        for (var i = 0, slashLen = urlSlashes.length; i < slashLen; i++) {
            var segment = urlSlashes[i];

            // Split off and save anything that looks like a file type.
            if (segment.indexOf(".") !== -1) {
                possibleType = segment.split(".")[1];

                /* If the type isn't alpha-only, it's probably not actually a file extension. */
                if(!possibleType.match(/[^a-zA-Z]/)) {
                    segment = segment.split(".")[0];                    
                }
            }
            
            /**
             * EW-CMS specific segment replacement. Ugly.
             * Example: http://www.ew.com/ew/article/0,,20313460_20369436,00.html
            **/
            if(segment.indexOf(',00') !== -1) {
                segment = segment.replace(',00', '');
            }

            // If our first or second segment has anything looking like a page number, remove it.
            if (segment.match(/((_|-)?p[a-z]*|(_|-))[0-9]{1,2}$/i) && ((i === 1) || (i === 0))) {
                segment = segment.replace(/((_|-)?p[a-z]*|(_|-))[0-9]{1,2}$/i, "");
            }


            del = false;

            /* If this is purely a number, and it's the first or second segment, it's probably a page number. Remove it. */
            if (i < 2 && segment.match(/^\d{1,2}$/)) {
                del = true;
            }
            
            /* If this is the first segment and it's just "index", remove it. */
            if(i === 0 && segment.toLowerCase() == "index")
                del = true;

            /* If our first or second segment is smaller than 3 characters, and the first segment was purely alphas, remove it. */
            if(i < 2 && segment.length < 3 && !urlSlashes[0].match(/[a-z]/i))
                del = true;

            /* If it's not marked for deletion, push it to cleanedSegments. */
            if (!del) {
                cleanedSegments.push(segment);
            }
        }

        // This is our final, cleaned, base article URL.
        return window.location.protocol + "//" + window.location.host + cleanedSegments.reverse().join("/");
    },

    /**
     * Look for any paging links that may occur within the document.
     * 
     * @param body
     * @return object (array)
    **/
    findNextPageLink: function (elem) {
        var possiblePages = {},
            allLinks = elem.getElementsByTagName('a'),
            articleBaseUrl = readability.findBaseUrl();

        /* Hack for NYTimes print view erroneous multipage. Fixed in new version, this is a short term fix. */
        if (window.location.hostname == "www.nytimes.com" && window.location.search.indexOf("pagewanted=print") !== -1) {
            return null;
        }

        /**
         * Loop through all links, looking for falses that they may be next-page links.
         * Things like having "page" in their textContent, className or id, or being a child
         * of a node with a page-y className or id.
         *
         * Also possible: levenshtein distance? longest common subsequence?
         *
         * After we do that, assign each page a score, and 
        **/
        for(i = 0, il = allLinks.length; i < il; i++) {
            var link     = allLinks[i],
                linkHref = allLinks[i].href.replace(/#.*$/, '').replace(/\/$/, '');

            /* If we've already seen this page, ignore it */
            if(linkHref == "" || linkHref == articleBaseUrl || linkHref == window.location.href || linkHref in readability.parsedPages) {
                continue;
            }
            
            /* If it's on a different domain, skip it. */
            if(window.location.host != linkHref.split(/\/+/g)[1]) {
                continue;
            }
            
            var linkText = readability.getInnerText(link);

            /* If the linkText looks like it's not the next page, skip it. */
            if(linkText.match(readability.regexps.extraneous) || linkText.length > 25) {
                continue;
            }

            /* If the leftovers of the URL after removing the base URL don't contain any digits, it's certainly not a next page link. */
            var linkHrefLeftover = linkHref.replace(articleBaseUrl, '');
            if(!linkHrefLeftover.match(/\d/)) {
                continue;
            }
            
            if(!(linkHref in possiblePages)) {
                possiblePages[linkHref] = {"score": 0, "linkText": linkText, "href": linkHref};             
            } else {
                possiblePages[linkHref].linkText += ' | ' + linkText;
            }

            linkObj = possiblePages[linkHref];

            /**
             * If the articleBaseUrl isn't part of this URL, penalize this link. It could still be the link, but the odds are lower.
             * Example: http://www.actionscript.org/resources/articles/745/1/JavaScript-and-VBScript-Injection-in-ActionScript-3/Page1.html
            **/
            if(linkHref.indexOf(articleBaseUrl) !== 0) {
                linkObj.score -= 25;
            }

            var linkData = linkText + ' ' + link.className + ' ' + link.id;
            if(linkData.match(readability.regexps.nextLink)) {
                linkObj.score += 50;
            }
            if(linkData.match(/pag(e|ing|inat)/i)) {
                linkObj.score += 25;
            }
            if(linkData.match(/(first|last)/i)) { // -65 is enough to negate any bonuses gotten from a > or » in the text, 
                /* If we already matched on "next", last is probably fine. If we didn't, then it's bad. Penalize. */
                if(!linkObj.linkText.match(readability.regexps.nextLink)) 
                    linkObj.score -= 65;              
            }
            if(linkData.match(readability.regexps.negative) || linkData.match(readability.regexps.extraneous)) {
                linkObj.score -= 50;
            }
            if(linkData.match(readability.regexps.prevLink)) {
                linkObj.score -= 200;
            }

            /* If a parentNode contains page or paging or paginat */
            var parentNode = link.parentNode,
                positiveNodeMatch = false,
                negativeNodeMatch = false;
            while(parentNode) {
                var parentNodeClassAndId = parentNode.className + ' ' + parentNode.id;
                if(!positiveNodeMatch && parentNodeClassAndId && parentNodeClassAndId.match(/pag(e|ing|inat)/i)) {
                    positiveNodeMatch = true;
                    linkObj.score += 25;
                }
                if(!negativeNodeMatch && parentNodeClassAndId && parentNodeClassAndId.match(readability.regexps.negative)) {
                    /* If this is just something like "footer", give it a negative. If it's something like "body-and-footer", leave it be. */
                    if(!parentNodeClassAndId.match(readability.regexps.positive)) {
                        linkObj.score -= 25;
                        negativeNodeMatch = true;                       
                    }
                }
                
                parentNode = parentNode.parentNode;
            }

            /**
             * If the URL looks like it has paging in it, add to the score.
             * Things like /page/2/, /pagenum/2, ?p=3, ?page=11, ?pagination=34
            **/
            if (linkHref.match(/p(a|g|ag)?(e|ing|ination)?(=|\/)[0-9]{1,2}/i) || linkHref.match(/(page|paging)/i)) {
                linkObj.score += 25;
            }

            /* If the URL contains negative values, give a slight decrease. */
            if (linkHref.match(readability.regexps.extraneous)) {
                linkObj.score -= 15;
            }

            /**
             * Minor punishment to anything that doesn't match our current URL.
             * NOTE: I'm finding this to cause more harm than good where something is exactly 50 points.
             *       Dan, can you show me a counterexample where this is necessary?
             * if (linkHref.indexOf(window.location.href) !== 0) {
             *    linkObj.score -= 1;
             * }
            **/

            /**
             * If the link text can be parsed as a number, give it a minor bonus, with a slight
             * bias towards lower numbered pages. This is so that pages that might not have 'next'
             * in their text can still get scored, and sorted properly by score.
            **/
            linkTextAsNumber = parseInt(linkText, 10);
            if(linkTextAsNumber) {
                // Punish 1 since we're either already there, or it's probably before what we want anyways.
                if (linkTextAsNumber === 1) {
                    linkObj.score -= 10;
                }
                else {
                    // Todo: Describe this better
                    linkObj.score += Math.max(0, 10 - linkTextAsNumber);
                }
            }
        }

        /**
         * Loop thrugh all of our possible pages from above and find our top candidate for the next page URL.
         * Require at least a score of 50, which is a relatively high confidence that this page is the next link.
        **/
        var topPage = null;
        for(var page in possiblePages) {
            if(possiblePages.hasOwnProperty(page)) {
                if(possiblePages[page].score >= 50 && (!topPage || topPage.score < possiblePages[page].score)) {
                    topPage = possiblePages[page];
                }
            }
        }

        if(topPage) {
            var nextHref = topPage.href.replace(/\/$/,'');

            dbg('NEXT PAGE IS ' + nextHref);
            readability.parsedPages[nextHref] = true;
            return nextHref;            
        }
        else {
            return null;
        }
    },

    /**
     * Build a simple cross browser compatible XHR.
     *
     * TODO: This could likely be simplified beyond what we have here right now. There's still a bit of excess junk.
    **/
    xhr: function () {
        if (typeof XMLHttpRequest !== 'undefined' && (window.location.protocol !== 'file:' || !window.ActiveXObject)) {
            return new XMLHttpRequest();
        }
        else {
            try { return new ActiveXObject('Msxml2.XMLHTTP.6.0'); } catch(sixerr) { }
            try { return new ActiveXObject('Msxml2.XMLHTTP.3.0'); } catch(threrr) { }
            try { return new ActiveXObject('Msxml2.XMLHTTP'); } catch(err) { }
        }

        return false;
    },

    successfulRequest: function (request) {
        return (request.status >= 200 && request.status < 300) || request.status == 304 || (request.status === 0 && request.responseText);
    },

    ajax: function (url, options) {
        var request = readability.xhr();

        function respondToReadyState(readyState) {
            if (request.readyState == 4) {
                if (readability.successfulRequest(request)) {
                    if (options.success) { options.success(request); }
                }
                else {
                    if (options.error) { options.error(request); }
                }
            }
        }

        if (typeof options === 'undefined') { options = {}; }

        request.onreadystatechange = respondToReadyState;
        
        request.open('get', url, true);
        request.setRequestHeader('Accept', 'text/html');

        try {
            request.send(options.postBody);
        }
        catch (e) {
            if (options.error) { options.error(); }
        }

        return request;
    },

    /**
     * Make an AJAX request for each page and append it to the document.
    **/
    curPageNum: 1,

    appendNextPage: function (nextPageLink) {
        readability.curPageNum++;

        var articlePage       = document.createElement("DIV");
        articlePage.id        = 'readability-page-' + readability.curPageNum;
        articlePage.className = 'page';
        articlePage.innerHTML = '<p class="page-separator" title="Page ' + readability.curPageNum + '">&sect;</p>';

        document.getElementById("readability-content").appendChild(articlePage);

        if(readability.curPageNum > readability.maxPages) {
            var nextPageLink = "<div style='text-align: center'><a href='" + nextPageLink + "'>View Next Page</a></div>";

            articlePage.innerHTML = articlePage.innerHTML + nextPageLink;
            return;
        }
        
        /**
         * Now that we've built the article page DOM element, get the page content
         * asynchronously and load the cleaned content into the div we created for it.
         *
         * Todo: try using a self-calling function rather than with
         *
         * Yes, "with statement is considered harmful". But this is using with as a replacement for let which is in ecmascript 1.7, so it's okay.
         * See here: http://stackoverflow.com/questions/61552/are-there-legitimate-uses-for-javascripts-with-statement#answer-185283
        **/
        with({pageUrl: nextPageLink, thisPage: articlePage}) {
            readability.ajax(pageUrl, {
                success: function(r) {

                    /* First, check to see if we have a matching ETag in headers - if we do, this is a duplicate page. */
                    var eTag = r.getResponseHeader('ETag');
                    if(eTag) {
                        if(eTag in readability.pageETags) {
                            dbg("Exact duplicate page found via ETag. Aborting.");
                            articlePage.style.display = 'none';
                            return;
                        } else {
                            readability.pageETags[eTag] = 1;
                        }                       
                    }

                    // TODO: this ends up doubling up page numbers on NYTimes articles. Need to generically parse those away.
                    var page = document.createElement("DIV");

                    /**
                     * Do some preprocessing to our HTML to make it ready for appending.
                     * • Remove any script tags. Swap and reswap newlines with a unicode character because multiline regex doesn't work in javascript.
                     * • Turn any noscript tags into divs so that we can parse them. This allows us to find any next page links hidden via javascript.
                     * • Turn all double br's into p's - was handled by prepDocument in the original view.
                     *   Maybe in the future abstract out prepDocument to work for both the original document and AJAX-added pages.
                    **/
                    var responseHtml = r.responseText.
                                        replace(/\n/g,'\uffff').replace(/<script.*?>.*?<\/script>/gi, '').
                                        replace(/\uffff/g,'\n').
                                        replace(/<(\/?)noscript/gi, '<$1div').
                                        replace(readability.regexps.replaceBrs, '</p><p>').
                                        replace(readability.regexps.replaceFonts, '<$1span>');
                    
                    page.innerHTML = responseHtml;

                    /**
                     * Reset all flags for the next page, as they will search through it and disable as necessary at the end of grabArticle.
                    **/
                    readability.flags = 0x1 | 0x2 | 0x4;

                    var nextPageLink = readability.findNextPageLink(page),
                        content      =  readability.grabArticle(page);

                    if(!content) {
                        dbg("No content found in page to append. Aborting.")
                        return;
                    }

                    /**
                     * Anti-duplicate mechanism. Essentially, get the first paragraph of our new page.
                     * Compare it against all of the the previous document's we've gotten. If the previous
                     * document contains exactly the innerHTML of this first paragraph, it's probably a duplicate.
                    **/
                    firstP = content.getElementsByTagName("P").length ? content.getElementsByTagName("P")[0] : null;
                    if(firstP && firstP.innerHTML.length > 100) {
                        for(var i=1; i <= readability.curPageNum; i++) {
                            var rPage = document.getElementById('readability-page-' + i);
                            if(rPage && rPage.innerHTML.indexOf(firstP.innerHTML) !== -1) {
                                dbg('Duplicate of page ' + i + ' - skipping.');
                                articlePage.style.display = 'none';
                                readability.parsedPages[pageUrl] = true;
                                return;
                            }
                        }
                    }
                    
                    readability.removeScripts(content);

                    thisPage.innerHTML = thisPage.innerHTML + content.innerHTML;

                    /**
                     * After the page has rendered, post process the content. This delay is necessary because,
                     * in webkit at least, offsetWidth is not set in time to determine image width. We have to
                     * wait a little bit for reflow to finish before we can fix floating images.
                    **/
                    window.setTimeout(
                        function() { readability.postProcessContent(thisPage); },
                        500
                    );

                    if(nextPageLink) {
                        readability.appendNextPage(nextPageLink);
                    }
                }
            });
        }
    },
    
    /**
     * Get an elements class/id weight. Uses regular expressions to tell if this 
     * element looks good or bad.
     *
     * @param Element
     * @return number (Integer)
    **/
    getClassWeight: function (e) {
        if(!readability.flagIsActive(readability.FLAG_WEIGHT_CLASSES)) {
            return 0;
        }

        var weight = 0;

        /* Look for a special classname */
        if (typeof(e.className) === 'string' && e.className != '')
        {
            if(e.className.search(readability.regexps.negative) !== -1) {
                weight -= 25; }

            if(e.className.search(readability.regexps.positive) !== -1) {
                weight += 25; }
        }

        /* Look for a special ID */
        if (typeof(e.id) === 'string' && e.id != '')
        {
            if(e.id.search(readability.regexps.negative) !== -1) {
                weight -= 25; }

            if(e.id.search(readability.regexps.positive) !== -1) {
                weight += 25; }
        }

        return weight;
    },

    nodeIsVisible: function (node) {
        return (node.offsetWidth !== 0 || node.offsetHeight !== 0) && node.style.display.toLowerCase() !== 'none';
    },

    /**
     * Remove extraneous break tags from a node.
     *
     * @param Element
     * @return void
     **/
    killBreaks: function (e) {
        try {
            e.innerHTML = e.innerHTML.replace(readability.regexps.killBreaks,'<br />');       
        }
        catch (eBreaks) {
            dbg("KillBreaks failed - this is an IE bug. Ignoring.: " + eBreaks);
        }
    },

    /**
     * Clean a node of all elements of type "tag".
     * (Unless it's a youtube/vimeo video. People love movies.)
     *
     * @param Element
     * @param string tag to clean
     * @return void
     **/
    clean: function (e, tag) {
        var targetList = e.getElementsByTagName( tag );
        var isEmbed    = (tag == 'object' || tag == 'embed');
        
        for (var y=targetList.length-1; y >= 0; y--) {
            /* Allow youtube and vimeo videos through as people usually want to see those. */
            if(isEmbed) {
                var attributeValues = "";
                for (var i=0, il=targetList[y].attributes.length; i < il; i++) {
                    attributeValues += targetList[y].attributes[i].value + '|';
                }
                
                /* First, check the elements attributes to see if any of them contain youtube or vimeo */
                if (attributeValues.search(readability.regexps.videos) !== -1) {
                    continue;
                }

                /* Then check the elements inside this element for the same. */
                if (targetList[y].innerHTML.search(readability.regexps.videos) !== -1) {
                    continue;
                }
                
            }

            targetList[y].parentNode.removeChild(targetList[y]);
        }
    },
    
    /**
     * Clean an element of all tags of type "tag" if they look fishy.
     * "Fishy" is an algorithm based on content length, classnames, link density, number of images & embeds, etc.
     *
     * @return void
     **/
    cleanConditionally: function (e, tag) {

        if(!readability.flagIsActive(readability.FLAG_CLEAN_CONDITIONALLY)) {
            return;
        }

        var tagsList      = e.getElementsByTagName(tag);
        var curTagsLength = tagsList.length;

        /**
         * Gather counts for other typical elements embedded within.
         * Traverse backwards so we can remove nodes at the same time without effecting the traversal.
         *
         * TODO: Consider taking into account original contentScore here.
        **/
        for (var i=curTagsLength-1; i >= 0; i--) {
            var weight = readability.getClassWeight(tagsList[i]);
            var contentScore = (typeof tagsList[i].readability != 'undefined') ? tagsList[i].readability.contentScore : 0;
            
            dbg("Cleaning Conditionally " + tagsList[i] + " (" + tagsList[i].className + ":" + tagsList[i].id + ")" + ((typeof tagsList[i].readability != 'undefined') ? (" with score " + tagsList[i].readability.contentScore) : ''));

            if(weight+contentScore < 0)
            {
                tagsList[i].parentNode.removeChild(tagsList[i]);
            }
            else if ( readability.getCharCount(tagsList[i],',') < 10) {
                /**
                 * If there are not very many commas, and the number of
                 * non-paragraph elements is more than paragraphs or other ominous signs, remove the element.
                **/
                var p      = tagsList[i].getElementsByTagName("p").length;
                var img    = tagsList[i].getElementsByTagName("img").length;
                var li     = tagsList[i].getElementsByTagName("li").length-100;
                var input  = tagsList[i].getElementsByTagName("input").length;

                var embedCount = 0;
                var embeds     = tagsList[i].getElementsByTagName("embed");
                for(var ei=0,il=embeds.length; ei < il; ei++) {
                    if (embeds[ei].src.search(readability.regexps.videos) == -1) {
                      embedCount++; 
                    }
                }

                var linkDensity   = readability.getLinkDensity(tagsList[i]);
                var contentLength = readability.getInnerText(tagsList[i]).length;
                var toRemove      = false;

                if ( img > p ) {
                    toRemove = true;
                } else if(li > p && tag != "ul" && tag != "ol") {
                    toRemove = true;
                } else if( input > Math.floor(p/3) ) {
                    toRemove = true; 
                } else if(contentLength < 25 && (img === 0 || img > 2) ) {
                    toRemove = true;
                } else if(weight < 25 && linkDensity > 0.2) {
                    toRemove = true;
                } else if(weight >= 25 && linkDensity > 0.5) {
                    toRemove = true;
                } else if((embedCount == 1 && contentLength < 75) || embedCount > 1) {
                    toRemove = true;
                }

                if(toRemove) {
                    tagsList[i].parentNode.removeChild(tagsList[i]);
                }
            }
        }
    },

    /**
     * Clean out spurious headers from an Element. Checks things like classnames and link density.
     *
     * @param Element
     * @return void
    **/
    cleanHeaders: function (e) {
        for (var headerIndex = 1; headerIndex < 3; headerIndex++) {
            var headers = e.getElementsByTagName('h' + headerIndex);
            for (var i=headers.length-1; i >=0; i--) {
                if (readability.getClassWeight(headers[i]) < 0 || readability.getLinkDensity(headers[i]) > 0.33) {
                    headers[i].parentNode.removeChild(headers[i]);
                }
            }
        }
    },

    /*** Smooth scrolling logic ***/
    
    /**
     * easeInOut animation algorithm - returns an integer that says how far to move at this point in the animation.
     * Borrowed from jQuery's easing library.
     * @return integer
    **/
    easeInOut: function(start,end,totalSteps,actualStep) { 
        var delta = end - start; 

        if ((actualStep/=totalSteps/2) < 1) { return delta/2*actualStep*actualStep + start; }
        return -delta/2 * ((--actualStep)*(actualStep-2) - 1) + start;
    },
    
    /**
     * Helper function to, in a cross compatible way, get or set the current scroll offset of the document.
     * @return mixed integer on get, the result of window.scrollTo on set
    **/
    scrollTop: function(scroll){
        var setScroll = typeof scroll != 'undefined';

        if(setScroll) {
            return window.scrollTo(0, scroll);
        }
        if(typeof window.pageYOffset != 'undefined') {
            return window.pageYOffset;
        }
        else if(document.documentElement.clientHeight) {
            return document.documentElement.scrollTop;
        }
        else {
            return document.body.scrollTop;
        }
    },
    
    /**
     * scrollTo - Smooth scroll to the point of scrollEnd in the document.
     * @return void
    **/
    curScrollStep: 0,
    scrollTo: function (scrollStart, scrollEnd, steps, interval) {
        if(
            (scrollStart < scrollEnd && readability.scrollTop() < scrollEnd) ||
            (scrollStart > scrollEnd && readability.scrollTop() > scrollEnd)
          ) {
            readability.curScrollStep++;
            if(readability.curScrollStep > steps) {
                return;
            }

            var oldScrollTop = readability.scrollTop();
            
            readability.scrollTop(readability.easeInOut(scrollStart, scrollEnd, steps, readability.curScrollStep));

            // We're at the end of the window.
            if(oldScrollTop == readability.scrollTop()) {
                return;
            }

            window.setTimeout(function() {
                readability.scrollTo(scrollStart, scrollEnd, steps, interval);
            }, interval);
        }
    },

    
    /**
     * Show the email popup.
     *
     * @return void
     **/
    emailBox: function () {
        var emailContainerExists = document.getElementById('email-container');
        if(null !== emailContainerExists)
        {
            return;
        }

        var emailContainer = document.createElement("DIV");
        emailContainer.setAttribute('id', 'email-container');
        emailContainer.innerHTML = '<iframe src="'+readability.emailSrc + '?pageUrl='+escape(window.location)+'&pageTitle='+escape(document.title)+'" scrolling="no" onload="readability.removeFrame()" style="width:500px; height: 490px; border: 0;"></iframe>';

        document.body.appendChild(emailContainer);          
    },
    
    /**
     * Close the email popup. This is a hacktackular way to check if we're in a "close loop".
     * Since we don't have crossdomain access to the frame, we can only know when it has
     * loaded again. If it's loaded over 3 times, we know to close the frame.
     *
     * @return void
     **/
    removeFrame: function () {
        readability.iframeLoads++;
        if (readability.iframeLoads > 3)
        {
            var emailContainer = document.getElementById('email-container');
            if (null !== emailContainer) {
                emailContainer.parentNode.removeChild(emailContainer);
            }

            readability.iframeLoads = 0;
        }           
    },
    
    htmlspecialchars: function (s) {
        if (typeof(s) == "string") {
            s = s.replace(/&/g, "&amp;");
            s = s.replace(/"/g, "&quot;");
            s = s.replace(/'/g, "&#039;");
            s = s.replace(/</g, "&lt;");
            s = s.replace(/>/g, "&gt;");
        }
    
        return s;
    },

    flagIsActive: function(flag) {
        return (readability.flags & flag) > 0;
    },
    
    addFlag: function(flag) {
        readability.flags = readability.flags | flag;
    },
    
    removeFlag: function(flag) {
        readability.flags = readability.flags & ~flag;
    }
    
};


	var via = new VIABOOK();
	via.init();


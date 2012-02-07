var BOOK = {};
BOOK = function(m_idx,status){
	this.status = status == "" ? "static" : "refresh";
	this.m_idx = m_idx;
	//this.init();
}

BOOK.prototype = {
	tag				:	"",
	totalPage		:	1,
	totalContents	:	0,
	type			:	"",/*TAG,SCRAP,DIARY,BLOG,SNS*/
	currentPage		:	0,
	articleData		:	[],
	contentData		:	{},
	contentGroup	:	[],
	status			:	"static",
	contentGroup	:	[],
	move_page		:	0,
	size			:	{
		"xsmall" : ["138px","230px","0px" ,"0px" ,"22px" ,"11px"],
		"small"	 : ["138px","523px","0", "0", "0px", "11px"],
		"middle" : ["328px","230px","0", "0", "22px", "0"],
		"large"	 : ["328px","523px","0"]	
	},
	init			:	function(){
//		this.moveFirstPage();
		var event_manager = new EventManager(this);
		this.loadContents();
	},
	open			: function(type,tag){
		var self = this;
		self.tag = tag;
		self.type = type;

		self.totalPage = 1;
		self.currentPage = 0;
		self.contentData = [];
		self.contentGroup = [];
		self.move_page = 0;
		self.current_page = 0;
		Dom.setCss(Dom.getId("viewer_body"),{"display" : "block"});
		self.loadContents();
	},
	close			: function(tag){
		var self = this;
		self.tag = tag;
		Dom.setCss(Dom.getId("viewer_body"),{"display" : "none"});
	},
	setLayOut 	:	function(temp){
		if(temp.length == 3){
			if(this.contentData[temp[0]].size == "xsmall"){
				this.contentData[temp[1]].size = "xsmall";
				this.contentData[temp[2]].size = "middle";
			}else if(this.contentData[temp[0]].size == "small"){
				this.contentData[temp[1]].size = "xsmall";
				this.contentData[temp[2]].size = "xsmall";
			}else if(this.contentData[temp[0]].size == "middle"){
				this.contentData[temp[1]].size = "xsmall";
				this.contentData[temp[2]].size = "xsmall";
			}
		} 
		if(temp.length == 2){
			if(this.contentData[temp[0]].size == "middle"){
				this.contentData[temp[1]].size = "middle";
			}else if(this.contentData[temp[0]].size == "small"){
				this.contentData[temp[1]].size = "small";
			}else if(this.contentData[temp[0]].size == "xsmall"){
				this.contentData[temp[0]].size = "small";
				this.contentData[temp[1]].size = "small";
			}

		} 

		if(temp.length == 1){
				this.contentData[temp[0]].size = "large";
		} 

	},

	//하나의 아티클 만들기
	makeContentBox	:	function(idx){
		var obj = this.contentData[idx];	
		//console.log(obj);
		//기본베이스 구성 
		var pbox = Dom.makeEle("div",{"class" : "pbox"});
		Dom.setCss(pbox,{
				"background":"#222",
				"float":"left",
				"width" : this.size[obj.size][0],
				"height" : this.size[obj.size][1],
				"border": "1px solid #8C8B87",
				"overflow": "hidden",
				"padding": "19px",
				"position": "relative",
				"margin-right" : this.size[obj.size][5],
				"margin-bottom" : this.size[obj.size][4]
		});
		
		var feed_user = Dom.makeEle("div",{"class" : "feed_user","id" : "feed_user_"+idx});
		//if(obj.source_from=="TWITTER" || obj.source_from == "FACEBOOK"){

		//사용자 프로파일 이름 
			var u_photo = Dom.makeEle("div",{"class" : "u_photo"});
			Dom.setCss(u_photo,{"float":"left","width":"50px","margin-right":"20px","margin-bottom" : "6px"})
			var u_photo_a = Dom.makeEle("a");
			var img_photo = Dom.makeEle("img",{
					"src" :obj.m_profile_img,
					"alt":obj.h_name,
					"width" : 30,
					"height" : 30,
					"title" : obj.h_name});	
			u_photo_a.appendChild(img_photo);
			u_photo.appendChild(u_photo_a);
			feed_user.appendChild(u_photo);
		//}
		
		//사용자 이름,날짜 
		var u_info = Dom.makeEle("div",{"class" : "u_info"});
		Dom.setCss(u_info,{
			"line-height" : "1.2em"
		});
		var user_name = Dom.makeEle("em");
		user_name.innerHTML=obj.h_name;
		Dom.setCss(user_name,{
			"display":"block",
			"font-family":"돋움",
			"font-size":"16px",
			"font-style":"normal",
			"font-weight":"bold",
			"margin-bottom":"6px"	
		});
		var f_day = Dom.makeEle("span",{"class" : "f_day"});
		f_day.innerHTML= obj.date + "<br>via " + obj.source_from;		
		Dom.setCss(f_day,{
			"color":" #8C8C8C",
			"font-family":" 돋움",
			"font-size":" 11px",
			"line-height":" 1em"
		});
		u_info.appendChild(user_name);
		u_info.appendChild(f_day);
		Dom.appendEle(feed_user,u_info);

		//내용 부분 
		var con_area = Dom.makeEle("div",{"id" : "article_"+idx,"onclick" : "Book.detailOpen("+idx+")"});
		Dom.setCss(con_area,{
			//"width"	 : "100%",	
			"overflow"	:	"hidden",
			//"height" : "71%",
			"padding-bottom"	:	"10px",
			//"height" : "45%",
			"margin-bottom" : "10px",
			"cursor"	:	"pointer",
			"font-size"	:	"12px",
			"line-height"	:	"20px",
			"color"		:	"#eee"
		});
		con_area.onclick = function(){
			Book.detailOpen(idx);
		}
		if(obj.h_type =="PHOTO"){
			var media = Dom.makeEle("p",{"align" : "center"});
			var media_source = Dom.makeEle("img",{"src" : obj.h_file});
			if(Dom.getAttr(media_source,"height")!=null){
				Dom.setCss(media_source,{
						"width"	 :	"90%",
						"height" :  "90%"
				});
			}else{
				Dom.setCss(media_source,{
						"width"	 :	"90%",
				});
			}
			Dom.appendEle(con_area,Dom.appendEle(media,media_source));
		}


		if(obj.h_type == "VIDEO1"){
			var media = Dom.makeEle("p",{"align" : "center","class" : "movie"});
			var video =obj.h_file.match(/^(.*)\/(.*)\/(.*)\?(.*)/); 
			if(video != null){
				/*
				var media_source = Dom.makeEle("iframe",{
						"src" : video[1]+"/"+"embed"+"/"+video[3],
						"width" : "100%",
						"height" : "100%",
						"border" : "0",
						"frameborder" : "0",
						"allowfullscreen" : ""
				});
				*/
				var media_source = Dom.makeEle("div",{
					"width"		:	"90%",
					"height"	:	"90%",
					"class"		:	"video_area"
				});
				var video_source = Dom.makeEle("img",{
					"src"		:	obj.media_thumb,
					"width" 	: 	"90%",
					"height"	:	"90%"
				});
				var play_icon = Dom.makeEle("img",{
					"src"		:	"/images/common/btn_play.png"
				});
				Dom.setCss(play_icon,{
					"position"	: 	"relative",
					"top"		:	"50%",
					"left"		:	"40%"	
				});

				Dom.appendEle(media_source,[play_icon,video_source]);
				Dom.appendEle(con_area,[Dom.appendEle(media,media_source)]);
			}
		}	

		var content = Dom.makeEle("p");

		try{
			content.innerHTML = obj.h_content;
		}catch(e){
			jQuery(content).html(obj.h_content);
		}

		var w_btn = Dom.makeEle("p",{"class" : "w_btn"});
		Dom.setCss(w_btn,{
			"position" : "absolute",
			"right"	:	"10px",
			"bottom"	:	"10px",
			"display"	:	"none"

		});
		var w_btn_a = Dom.makeEle("a",{"href" :"javascript:Book.detailOpen("+idx+")"});
		Dom.setCss(w_btn_a,{
			"height":" 14px",
			"line-height":" 1em",
			"padding":" 5px 0px 0px",
			"width":" 65px"
		});
		w_btn_a.innerHTML = "상세보기";

		Dom.appendEle(w_btn,w_btn_a);
		Dom.appendEle(con_area,[content]);

		if(obj.type =="link" && obj.source=="Facebook"){
			var media = Dom.makeEle("ul");
			var media_left = Dom.makeEle("li");
			var media_right = Dom.makeEle("li");
			Dom.setCss(media_left,{
				"float"	:	"left",
				"padding-right"	:	"10px"
			});
			var media_source = Dom.makeEle("img",{	"src" : obj.media_thumb,
													"align"	: "left"
												  });
			Dom.appendEle(media_left,media_source);
			var media_link =Dom.makeEle("a",{"href" : obj.url[0],"target" : "blank"});
			media_link.innerHTML = obj.url[0];
			var media_description = Dom.makeEle("em");
			Dom.setCss(media_description,{
				"font-size" : "0.8em",
				"width"	:	"100px",
				"font-style" : "normal"
			});
			var line_blank=Dom.makeEle("br",{
				
			});
			media_description.innerHTML = obj.media_description;
			Dom.appendEle(media_right,[media_link,Dom.makeEle("br"),media_description]);
			
			Dom.appendEle(con_area,Dom.appendEle(media,[media_left,media_right]));
		}
		Dom.appendEle(pbox,[feed_user,con_area,w_btn]);
		var temp = Dom.makeEle("div");
		return Dom.appendEle(temp,pbox).innerHTML;
	},

	//
	makeLayout : function(){
		var data=[];
		this.pageNation();
		for(var i =0;i<this.totalContents;i++){
			//if(this.articleData[i] != undefined) return this.articleData[i];
				data[i] = this.makeContentBox(i); 	
		}
		this.articleData = data;
		this.goToPage(0);
	},

	//사이즈별로 페이지당 컨텐츠 조합
	pageNation	 : function(){
		var sum=0;
		var temp = new Array();
		for(var i=0;i<this.contentData.length;i++){
			sum += this.contentData[i].size_value;	
			if(sum < 4){
				temp.push(i);
			}else if(sum == 4){
				temp.push(i);
				this.contentGroup.push(temp);
				this.setLayOut(temp);
				temp =[];
				sum=0;
			}else if(sum > 4){
				this.contentData[i].size = "xsmall";
				this.contentData[i].size_value = "1";
				temp.push(i);
				this.contentGroup.push(temp);
				this.setLayOut(temp);
				temp =[];
				sum=0
			}
		}

		if(sum < 4){
				this.contentGroup.push(temp);
				this.setLayOut(temp);
		}
		//debug(this.contentData.length);	

		this.totalPage = Math.ceil(this.contentGroup.length/2);
		//this.totalPage = this.totalPage == 2 ? 4 : this.totalPage;
	},

	//컨텐츠 페이지에 삽입
	innerContent : function(page){
		var i = page*2;
		var left_source ="";
		var right_source ="";
		var temp =[];
		jQuery("#left_contents div").remove();
		jQuery("#right_contents div").remove();
        if(this.contentGroup[i] != undefined){
			for(var j=0;j<this.contentGroup[i].length;j++){
				left_source += this.articleData[this.contentGroup[i][j]];
			}
	    }   
        if(this.contentGroup[i+1] != undefined){ 
			for(var j=0;j<this.contentGroup[i+1].length;j++){
				right_source += this.articleData[this.contentGroup[i+1][j]];
			}
        } 

		Dom.getId("left_contents").innerHTML = left_source; 	
		Dom.getId("right_contents").innerHTML = right_source; 	
		Dom.getId("left_page_count").innerHTML = i+1;
		Dom.getId("right_page_count").innerHTML = i+2;
	},

	moveFirstPage	:	function(){
		jQuery("#book_recommand").hide();
		this.move = 1;
		this.changePage(this.move);
	},
	goToPage	:	function(idx){
		this.move_page = idx;
		this.currentPage = idx;
		this.changePage(this.move_page);
	},
	moveLastPage	:	function(){
		jQuery("#book_overview").hide();
		jQuery("#book_intro").hide();	
		this.move = this.totalPage-1;
		this.changePage(this.move);
	},
	changePage		:	function(page){
		if(page < 0){
			//앞커버
			this.currentPage=0;
			return false;
		}else if(page >= this.totalPage){
			//책 뒷표지
			this.currentPage=this.totalPage;
		}else{
			this.innerContent(page);
			this.currentPage = this.move_page;
		}
		debug(this.currentPage);
	},

	//다음 페이지로 이동
	nextPage		:	function(){
		this.move_page = this.currentPage +1;
		this.changePage(this.move_page);
	},

	//이전 페이지로 이동
	prevPage		:	function(){
		this.move_page = this.currentPage -1;
		this.changePage(this.move_page);
	},

	//상세보기 닫기
	detailClose	:	function(){
		jQuery("#detail_layer").hide("fast");
	},

	//상세보기 열기 
	detailOpen	:	function(idx){
		jQuery("#feed_user_detail").html(Dom.getId("feed_user_"+idx).innerHTML);
		jQuery("#content_message").html(Dom.getId("article_"+idx).innerHTML);

		if(this.contentData[idx].type=="video"){
			var video =this.contentData[idx].url[0].match(/^(.*)\/(.*)\/(.*)\?(.*)/); 
			var media_source = Dom.makeEle("iframe",{
				"src" : video[1]+"/"+"embed"+"/"+video[3],
				"width" : "100%",
				"height" : "100%",
				"border" : "0",
				"frameborder" : "0",
				"allowfullscreen" : ""
			});

			jQuery("#content_message div.video_area").html("");
			jQuery("#content_message div.video_area").append(media_source);
		}
		
		//make link to source page
		if(this.contentData[idx].h_url != null){
			var w_btn = Dom.makeEle("p",{"class" : "w_btn"});
			var w_btn_a = jQuery(".w_btn>a").attr({"href" : this.contentData[idx].h_url});
		}

		//make comment area 
		if(this.contentData[idx].comments != null){
			jQuery("p#comment_bar")
				.show()
				.html("Comment ("+this.contentData[idx].comments.count+")")
		}else{
			jQuery("p#comment_bar").hide();
		}

		//make subject area
		if(this.contentData[idx].h_title != null){
			jQuery("#detail_subject").html(this.contentData[idx].h_title);
		}

		jQuery("#detail_layer").show("fast");
	},
	loadContents	:	function(){
			var self = this;
			jQuery.ajax({
				url			:	"paper.php",
				type		:	"post",
				dataType	:	"json",
				data		:	{
					"service" 	: "api_paper_getData",
					"type" 		: self.type,
					"h_tag" 	: self.tag,
					"m_idx" 	: self.m_idx,"status" : self.status
				},
				success		:	function(re){
					if(re.RESULT_SET.flag == "F"){
						alert(re.RESULT_SET.msg);
						return false;
					}
					self.contentData = re.RESPONSE.paper_list;	
					self.totalContents = re.RESPONSE.paper_list.length;
					self.makeLayout();
				}
			});
		}
    	
}   	
    	
    	
    	
    	
var EventManager ={}
EventManager = function(obj){
	this.target = obj;
	this.init();
}

EventManager.prototype = 
 {
	target:{},
	init:function(){
		var thisObj = this;
		jQuery("body").keyup(function(e){
			thisObj.onKeyUp(e);
		});
	},
	
	onKeyUp:function(e)
	{
		var keyCode = 0;
		keyCode = e.keyCode;
	
		switch ( keyCode ) {
		default:
		break;
	
		case 38: //PAGE UP
			this.target.moveFirstPage();
		break;
	
		case 40: //PAGE DOWN
			this.target.moveLastPage();
		break;
		
		case 37: //LEFT
			this.target.prevPage();
		break;
		
		case 39: //RIGHT
			this.target.nextPage();
		break;
	}
	}
}






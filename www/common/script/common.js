var COMMON = {};
/*
var is_mobile=0;
var agent = navigator.userAgent;
   if (agent.match(/iPhone/) != null || agent.match(/iPod/) != null) {
       document.write('<meta name="viewport" content="minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />');
   }else if(agent.match(/Mobile/)){
       document.write('<meta name="viewport" content="minimum-scale=0.75,maximum-scale=0.75,initial-scale=0.75,user-scalable=no" />');
   is_mobile =1;
   }
*/

function debug( msg )
{
	if ( typeof console != 'undefined' ) {
		console.debug( ( ( arguments.length == 1 ) ? arguments[0] : arguments ) );
	}
};


var go_sns = function(type,caller){
	if(type == "facebook"){
		window.open("/common.php?service=view_common_facebookPopUp&is_mobile="+is_mobile+"&caller="+caller,"sns");
	}else{
		window.open("/common.php?service=view_common_twitterPopUp&is_mobile="+is_mobile+"&caller="+caller,"sns");
	}
}



/// @brief
/// @params
/// @return 
/// @author ggugi
COMMON	= {
	is_mobile	: 0,
	init	: function(){
		var self = this;
		self.UI.init();
	},
	logout	: function(){
		var self = this; 
		var sendData = {
			"service"	: "api_common_logout"
		};

		$.ajax({
			url     : "/common.php",
			type    : "post",
			async	: false,
			dataType: "json",
			data    : sendData,
			success : function(data){
				if(data.RESULT_SET.flag){
					alert(data.RESULT_SET.msg);
                    location.href="/index.php";
					return false;
				}
			} 
		}); 
	},
	explain_scrap : function(){
		alert("Drag your book mark area in Browser");
	}

}



/// @brief
/// @params
/// @return 
/// @author ggugi

$(function(){

COMMON.UI	= {
	agent		: "",
	$activity_point : $("#activity_point"),
	$emotion_point : $("#emotion_point"),
	$activity_rate : jQuery("#activity_rate"),
	$baloon_emotion: jQuery("#baloon_emotion"),
	$emotion_status: jQuery("#emotion_status"),
	$level_box	   : jQuery("div.level_box"),
	init	: function(){
		var self = this;
		self.checkDevice();
		self.bind();
		self.resize();
	},
	checkDevice	: function(){
		var self = this;
		self.agent = navigator.userAgent;
		console.log(self.agent);
   		if (self.agent.match(/iPhone/) != null || self.agent.match(/iPod/) != null) {
       		document.write('<meta name="viewport" content="minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no" />');
   		}else if(self.agent.match(/Mobile/)){
       		document.write('<meta name="viewport" content="minimum-scale=0.75,maximum-scale=0.75,initial-scale=0.75,user-scalable=no" />');
   			COMMON.is_mobile =1;
   		}
	},
	restruct: function(){
		var self = this;
	
	},
	bind	: function(){
		var self = this;
		self.makeModal();
		if(COMMON.is_mobile){
			self.makeAlert();
		}

		//baloon setting 
		self.$emotion_status
			.mouseover(function(){
				self.$baloon_emotion.show();
			})
			.mouseout(function(){
				self.$baloon_emotion.hide();
			});

		jQuery("div.level_box")
			.mouseover(function(e){
				$(".baloon_explain",this).show();
			})
			.mouseout(function(e){
				$(".baloon_explain",this).hide();
			});

    },
	resize	: function(){
		var self = this;
        $("#top").css({'width' : $("body").width()-20+"px"})
        $("#content").css({"height" :$("body").height()-40+"px"}); 
        $(window).resize(function(e){
        	$("#top").css({'width' : $("body").width()-20+"px"})
            $("#content").css({"height" :$("body").height()-40+"px"}); 
        }); 

        if (self.agent.match(/Chrome/) != null) { 
            $("#content").removeClass("overflow");
            $("#top").css({"position" : "fixed"});
            $("#left").css({"position" : "fixed"});
		}
	
	},
	makeAlert	: function(){
		var self = this;

		if(COMMON.is_mobile==0){
			window.alert = function(msg){
				if(document.getElementById("alert") == undefined){
					var div_alert = '<div id="alert"><p>'+msg+'</p><p><input type="button" value="    Ok   "></p></div>'
					$("body").append(div_alert);
					$("div#alert>p>input").click(function(e){
						$("div#alert").hide("fast");
						window.modal("hide");
					});
				}	
				window.modal("show");
				var hei = ((window.screen.height/2 - $("div#alert").height()/2)+$(document).scrollTop());
				var wid = ($("body").width()/2 - $("div#alert").width()/2);
				$("div#alert").css({'top' : hei+'px',left : wid+'px'}).show("fast");
			}
		}
	},
	makeModal	: function(){
		var self = this;
		window.modal = function(action){
			if(document.getElementById("modal") == undefined){
				var div_alert = '<div id="modal"></div>'
				$("body").append(div_alert);
				if(action == undefined){
					return false;
				}
			}	
			
			if(action == "show"){
				var hei = $("body").height()+$(document).scrollTop();
				$("div#modal").css({'height' : hei+'px'}).show();
			}else{
				$("div#modal").hide();
			}
		}
	},
	set_level	: function(level){
		var self = this; 
		$("div#level_"+level).css({
			"background-color"	: "#d40000"
		});
	}	

}
});

var Dom ={
	getId	:	function(id_str){
		return document.getElementById(id_str)
	},
	getTag	:	function(tag_str){
		return document.getElementById(tag_str)
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
	setAttr	:	function(ele,obj){
		for(var i in obj){
			ele.setAttribute(i,obj[i]);
		}
	},
	getAttr : 	function(ele,str){
		return ele.getAttribute(str);
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
	},
	getCss :	function(ele,obj){
		var temp = [];
		for(var i in obj){
			temp[i] = ele.style[i]
		}
		return temp;
	}

}





$(function(){
	COMMON.init();
});


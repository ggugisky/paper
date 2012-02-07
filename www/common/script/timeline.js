	var TIMELINE = {
		init	: function(){
			var self = this;	
			self.EXTERNAL.init();
			self.HISTORY.init();
			self.UI.init();
		},
		total_count	: 0,
		m_level		: 6,
		m_idx		: 0,
		m_emotion_point : 0
	}

	TIMELINE.UI = {
		emotion_flag	 : ['ㅡ_ㅡ','O_O','^__^','^O^'],
		$facebook : jQuery("#facebook"),
		$twitter : jQuery("#twitter"),
		$activity_point : $("#activity_point"),
		$emotion_point : $("#emotion_point"),
		$activity_rate : jQuery("#activity_rate"),
		$baloon_emotion: jQuery("#baloon_emotion"),
		$emotion_status: jQuery("#emotion_status"),
		$level_box	   : jQuery("div.level_box"),
		$btn_msg_type  : jQuery("ul#msg_type>li"),
		$btn_upload_photo  : jQuery("#upload_photo"),
		$frm_photo  : jQuery("#frm_photo"),
		init	: function(){
			var self = this;
			self.bind();
			self.set_level(TIMELINE.m_level);
			self.set_emotion(TIMELINE.m_emotion_point);
		},
		bind	: function(){
			var self = this;	

            if(COMMON.UI.agent.match(/Mobile/) != null){
                 
                $("#content").removeClass("overflow");
                $("#top").css({"position" : "fixed"});
                $("#left").css({"position" : "fixed"});
                $("#left").hide();
                $("#top").css({'width' : $("body").width()-20+"px"})
                $("#content").css({"padding-left" : "0","width":"100%"});
                $("#txt_status").css({"width":"80%"});
                $("#status").css({"width":"100%"});
                $(".history").css({"width":"100%"});
            }

     

			self.$facebook.click(function(){
				if(TIMELINE.EXTERNAL.info.FACEBOOK == undefined){
					if(confirm("페이스북 계정에 연동이 않되어 있군요...\n 연결 할래요?")){
						go_sns("facebook","TIMELINE");
					}
					return false;
				}	
			});
			
			self.$twitter.click(function(){
				if(TIMELINE.EXTERNAL.info.TWITTER == undefined){
					if(confirm("트위터 계정에 연동이 않되어 있군요...\n 연결 할래요?")){
						go_sns("twitter","TIMELINE");
					}
					return false;
				}	
			});


			//set messge type button
			self.$btn_msg_type
				.mouseover(function(e){
					$(this).addClass("checked_type");
				})
				.mouseout(function(e){
					$(this).removeClass("checked_type");
				})
				.click(function(e){
					self.$btn_msg_type.removeClass("checked_type2");
					$(this).addClass("checked_type2");
					if($(this).attr("alt") == "photo"){
						$("#txt_status").css({"height" : "72px"});
						$("#upload_photo_area").show();
						TIMELINE.HISTORY.type="PHOTO";
					}

					if($(this).attr("alt") == "message"){
						$("#txt_status").css({"height" : "100px"});
						$("#upload_photo_area").hide();
						TIMELINE.HISTORY.type="TEXT";
					}
					
				});
			self.$btn_upload_photo
				.change(function(){
					if(this.value.length < 1) return false;
					self.$frm_photo.ajaxSubmit({
						url: "/timeline.php?service=api_timeline_upload_photo",
						type: "POST",
						dataType: "json",
						success: function(data){
							if(!data.RESULT_SET.flag){
								alert(data.RESULT_SET.msg);
								return false;
							}
							TIMELINE.HISTORY.photo_data = data.RESPONSE.result[0];
						}
					});
				});

		},
		set_emotion : function(emotion_point){
			var self = this;
			var $total_emotion = $("#total_emotion");
			if(emotion_point > 100){
				$("span#total_emotion").html(self.emotion_flag[3]);
			}else if(emotion_point > 50){
				$("span#total_emotion").html(self.emotion_flag[2]);
			}else if(emotion_point > 0){
				$("span#total_emotion").html(self.emotion_flag[1]);
			}else if(emotion_point > -50){
				$("span#total_emotion").html(self.emotion_flag[0]);
			}
			var temp_emotion = $total_emotion.html();
			$total_emotion.html(">_<");
			setTimeout(function(){$total_emotion.html(temp_emotion)},500);
		},
		set_level	: function(level){
			var self = this; 
			$("div#level_"+level).css({
				"background-color"	: "#d40000"
			});

			//console.log(TIMELINE.m_level-1);
			if(TIMELINE.m_level > level){
				if(confirm("Congratulation!\n U can read more my article. \n The article more dangerous article. Be careful.\n Do U want to refresh this page?")){
					TIMELINE.HISTORY.page=1;
					$("#history_area").empty();
					TIMELINE.HISTORY.init();
				}
			}
		}	
	}

	TIMELINE.EXTERNAL = {
		info	: {},
		init	: function(){
			var self = this;
			self.getExternalInfo();
		},
		getExternalInfo		: function(){
			var self = this; 
			var sendData = {
				"service"	: "api_get_externalInfo"
			};

			$.ajax({
				url     : "/timeline.php",
				type    : "post",
				async	: false,
				dataType: "json",
				data    : sendData,
				success : function(data){
					if(!data.RESULT_SET.flag){
						alert(data.RESULT_SET.msg);
						return false;
					}
					self.info =data.RESPONSE;
				} 
			}); 
		}
	}

	TIMELINE.HISTORY = {
		photo_data	: {},
		select_h_uid	: 0,
		emotion : ["",-2,-1,1,2],
		page	: 1,
		count	: 1,
		checked_emotion	: false,
		type	: "TEXT",
		init	: function(){
			var self = this;
			self.load(0,TIMELINE.total_count);
		},
		load	: function(start_pos,count){
			var self = this;
			var $history_area = $("#history_area");
			var sendData = {
				"service"	: "view_timeline_list",
				"start_pos"	: start_pos,
				//"page"		: (page == undefined) ? self.page : page,
				"count"		: (count == undefined) ? self.count : count
			};
			$.ajax({
				url     : "/timeline.php",
				type    : "post",
				dataType: "html",
				data    : sendData,
				success : function(data){
					$history_area.append(data); 
				 	TIMELINE.UI.$activity_rate.html(Math.round($(".history").length/TIMELINE.total_count*10000)/100);
					self.page = $(".history").length; 
				} 
			}); 
		},
		more	: function(){
			var self = this;
			var current_count = $(".history").length;
			var $last_history = $(".history:last");
			if($last_history.attr("data-checked_activity") != "1"){
				alert("Sorry!\nU should choice emotion or write ur message for last timeline!");
				return false;
			}
			self.page++;
			self.load(current_count,TIMELINE.total_count);
			if(current_count>50){
				for(var i=0;i<50;i++){
					$(".history:visible").eq(i).hide();
				}
			}
		},
		show_emotion	: function(obj,h_uid){
			var self = this;
			self.select_h_uid = h_uid;
			var $emotion_area = $("ul.emotion",obj);
			//$emotion_area.show();
			document.getElementById("timebox_"+h_uid).designMode="On";
		},
		set_emotion		: function(e_flag,obj){
			var self = this;

			if($($(obj).parent()).attr("data-em_emotion") != 0) return false;
			var sendData = {
				"service"	: "api_set_emotion",
				"h_uid"		: self.select_h_uid,
				"em_emotion": self.emotion[e_flag]
			}	
			var $last_emotion = $("ul.emotion:last");

			$.ajax({
				url     : "/timeline.php",
				type    : "post",
				dataType: "json",
				data    : sendData,
				success : function(data){
					if(!data.RESULT_SET.flag){
						alert(data.RESULT_SET.msg);
						return false;
					}
					$("#timebox_"+self.select_h_uid+" ul.emotion li")
                        .css({"opacity" : "0.5","filter" : "alpha(opacity=50)"});
					$(obj).attr({"alt" : "checked"})
                        .css({"opacity" : "1.0","filter" : "alpha(opacity=100)"});
					$("#timebox_"+self.select_h_uid).attr({"data-checked_activity" : "1"});
					if(self.select_h_uid == $last_emotion.attr("data-h_uid")){
						TIMELINE.HISTORY.more();
					}
					$($(obj).parent()).attr({"data-em_emotion" : data.RESPONSE.em_emotion});
					TIMELINE.UI.$activity_point.html(data.RESPONSE.m_total_point);
					TIMELINE.UI.$emotion_point.html(data.RESPONSE.m_emotion_point);
					TIMELINE.UI.set_level(data.RESPONSE.m_level);
					TIMELINE.UI.set_emotion(data.RESPONSE.m_emotion_point);

				} 
			}); 
		},

		set_level		: function(level,obj){
			var self = this;
			if($($(obj).parent()).attr("data-m_idx") != TIMELINE.m_idx){
				alert("U don't have permission.");
				return false;
			}
			var sendData = {
				"service"	: "api_timeline_set_level",
				"h_uid"		: self.select_h_uid,
				"h_level"	: level
			}	

			$.ajax({
				url     : "/timeline.php",
				type    : "post",
				dataType: "json",
				data    : sendData,
				success : function(data){
					if(!data.RESULT_SET.flag){
						alert(data.RESULT_SET.msg);
						return false;
					}
					$("#timebox_"+self.select_h_uid+" ul.btn_level li")
                        .css({"opacity" : "0.5","filter" : "alpha(opacity=50)"});
					$(obj).attr({"alt" : "checked"})
                        .css({"opacity" : "1.0","filter" : "alpha(opacity=100)"});
				} 
			}); 
		},

		remove			: function(obj){
			var self=this;
			if(!confirm("Do U want to remove this one?")){
				return false;
			}

			var sendData = {
				"service"	: "api_timeline_remove",
				"h_uid"		: self.select_h_uid
			}	

			$.ajax({
				url     : "/timeline.php",
				type    : "post",
				dataType: "json",
				data    : sendData,
				success : function(data){
					if(!data.RESULT_SET.flag){
						alert(data.RESULT_SET.msg);
						return false;
					}
					$("table#timebox_"+data.RESPONSE.h_uid).remove();
				} 
			}); 


		},
		hide_emotion	: function(obj){
			var self = this;
			var $emotion_area = $("ul.emotion",obj);
			//$emotion_area.hide();
		},
		refresh	: function(){
			var self = this;
			var $history_area = $("#history_area");
			$history_area.empty();
			self.page =1;
			self.load();
		},
		set_status_insert	: function(){
			var self = this;
			var $txt_status = $("#txt_status");	
			var $facebook = $("#facebook");	
			var $twitter = $("#twitter");

			if($txt_status.val().length < 1){
				alert("Hey! Damn it!! Have to write message");
				return false;
			}

			var sendData = {
				"service"	: "api_timeline_status_insert",
				"content"	: $txt_status.val(),
				"type"		: self.type,
				"facebook"	: $facebook.attr("checked"),
				"twitter"	: $twitter.attr("checked"),
				"h_file"	: self.photo_data.path
			}	
			$txt_status.val("Saving....");
			$txt_status.attr({"disabled" : "true"});
			$.ajax({
				url     : "/timeline.php",
				type    : "post",
				dataType: "json",
				data    : sendData,
				success : function(data){

					TIMELINE.UI.$btn_upload_photo.val("");
					$txt_status.removeAttr("disabled");
					$txt_status.val("");
					if(!data.RESULT_SET.flag){
						alert(data.RESULT_SET.msg);
						return false;
					}
					TIMELINE.UI.$activity_point.html(data.RESPONSE.m_total_point);
					TIMELINE.UI.set_level(data.RESPONSE.m_level);
					TIMELINE.UI.set_emotion(data.RESPONSE.m_emotion_point);
					self.refresh();
				} 
			}); 

		}
	
	}


	TIMELINE.COMMENT = {
		comment_once_template : [
			'<tbody style="padding-bottom:10px;border-bottom:dashed 1px #eee;">'+
    		'<tr>'+
        	'<td width="32" height="50">'+
			'<img src="',/*m_profile_img*/,'" width="30" height="30" style="vertical-align:top;">'+
			'</td>'+
			'<td><b class="fc" style="padding-right:10px;">',/*c_name*/,'</b>',/*c_content*/,
			'<br>',/*c_reg_time*/,'</td>'+
    		'</tr>'+
			'</tbody>'],
		add	: function(obj){
			var self = this;
			var $txt_comment = $(obj);
			var $last_txt_comment = $(".txt_comment:last");
			if(event.keyCode != 13){
				return false;
			}
			if($txt_comment.val().length < 3){
				if(event.keyCode == 13){
					$txt_comment.val("");
				}
				return false;
			}

			var sendData = {
				"service"	: "api_timeline_comment_insert",
				"c_content"	: $txt_comment.val(),
				"h_uid"		: $txt_comment.attr("data-h_uid")
			}	
			$txt_comment.val("Saving....");
			//$txt_comment.attr({"disabled" : "true"});
			$.ajax({
				url     : "/timeline.php",
				type    : "post",
				dataType: "json",
				data    : sendData,
				success : function(data){
					$txt_comment.removeAttr("disabled");
					$txt_comment.val("");
					if(!data.RESULT_SET.flag){
						alert(data.RESULT_SET.msg);
						return false;
					}
					var json = data.RESPONSE;
					self.comment_once_template[1] = json.member_info.m_profile_img;
					self.comment_once_template[3] = json.member_info.m_name;
					self.comment_once_template[5] = json.c_content;
					self.comment_once_template[7] = json.c_reg_date;
					$("table#comment_list_"+json.h_uid).append(self.comment_once_template.join(''));
					$("#timebox_"+json.h_uid).attr({"data-checked_activity" : "1"});
					if(json.h_uid == $last_txt_comment.attr("data-h_uid")){
						TIMELINE.HISTORY.more();
					}
					TIMELINE.UI.$activity_point.html(data.RESPONSE.m_total_point);
					TIMELINE.UI.set_level(data.RESPONSE.m_level);
					TIMELINE.UI.set_emotion(data.RESPONSE.m_emotion_point);
				} 
			}); 
		}
	}


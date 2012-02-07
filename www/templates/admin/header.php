<html>
<head>
	<title>CMS 관리자</title>

	<meta content="text/html; charset=utf-8" http-equiv="content-type" />
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0/jquery.mobile-1.0.min.css" />
	<script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
	<!--<script type="text/javascript" src="http://code.jquery.com/mobile/1.0/jquery.mobile-1.0.js"></script>-->
	<style type="text/css">
		body,li{
			font-size:12px;
			font-color:#888;
		}
		ul,li{
			padding:0;
			margin:0;
		}
		ul{
			list-style:none;
		}
		ul.frame_set{
			list-style:none;
		}
		li.frame{
			float:left;
			height:768px;
		}	
		li#left_frame{
			width:20%;
			border:solid 1px #333;
		}
		li#right_frame{
			width:79%;
			border:solid 1px #333;
		}
		div#contents_area{
			padding:20px 20px 20px 20px;
			width:460px;
			height:500px;
		}

		ul#category_ul{
			list-style:none;
		}

		ul#category_ul li.logo{
			padding:5px 10px 5px 0;
			font-weight:bold;
			background-color:#888;
			text-align:center;
			font-size:30px;
		}
		ul#category_ul li{
			border:inset 1px #333;
			margin:1px;
		}

		ul#category_ul li.menu_tit{
			padding:5px 10px 5px 0;
			font-weight:bold;
			background-color:#888;
		}

		/*bbsWrite*/
		input#txt_subject{
			width:400px;
		}
		input#txt_content{
			width:400px;
		}
		input#btn_cancel, input#btn_save{
			width:100px;
		}


		/*bbsList*/
		table.bbs_list_set{
			width:100%;	
			border-bottom:solid 1px #333;
			height:30px;
			cursor:pointer;
		}


		


	</style>
	<script>
	
		var ADMIN = {};
		ADMIN.bbsInsert = function(mode){
			var $cc_idx = $("#cc_idx");
			var $cb_idx = $("#cb_idx");
			var $subject = $("#txt_subject");
			var content = CKEDITOR.instances.txt_content.getData();
			var service	= mode == "edit" ? "api_admin_bbsUpdate" : "api_admin_bbsInsert";

		

			$.ajax({
				url		: "/cms_admin.php",
				type	: "post",
				data	: {
					"service"	: service,
					"cc_idx"	: $cc_idx.val(),
					"cb_idx"	: $cb_idx.val(),
					"mode"		: mode,
					"subject"	: $subject.val(),
					"content"	: content
				},
				dataType: "json",
				success : function(json){
					if(json.RESULT_SET.flag){
						alert(json.RESULT_SET.msg);
						if(mode == "edit"){	
							ADMIN.goToBbsView(json.RESPONSE.cb_idx);
						}else{
							location.href="/cms_admin.php?service=view_admin_bbsList&cc_idx="+$cc_idx.val();	
						}
					}else{
						alert(json.RESULT_SET.msg);
					}
				}
			});
		}

		ADMIN.goToBbsWrite = function(){
			location.href="/cms_admin.php?service=view_admin_bbsWrite&cc_idx=<?=$result["cc_idx"]?>";
		}

		ADMIN.goToBbsView = function(cb_idx){
			location.href="/cms_admin.php?service=view_admin_bbsView&cc_idx=<?=$result["cc_idx"]?>&cb_idx="+cb_idx;
		}

		ADMIN.goToBbsEdit = function(cb_idx){
			location.href="/cms_admin.php?service=view_admin_bbsWrite&mode=edit&cc_idx=<?=$result["cc_idx"]?>&cb_idx="+cb_idx;
		}

		ADMIN.bbsDelete = function(cb_idx){
			if(!confirm("이 포스트를 삭제 하시겠습니까?")){
				return false;
			}

			$.ajax({
				url		: "/cms_admin.php",
				type	: "post",
				data	: {
					"service"	: "api_admin_bbsDelete",
					"cb_idx"	: cb_idx
				},
				dataType: "json",
				success : function(json){
					if(json.RESULT_SET.flag){
						alert("삭제되었습니다.");
						location.href="/cms_admin.php?service=view_admin_bbsList&cc_idx=<?=$result["cc_idx"]?>"	
					}else{
						alert(json.RESULT_SET.msg);
					}
				}
			});



			//location.href="/cms_admin.php?service=view_admin_bbsView&cc_idx=<?=$result["cc_idx"]?>&cb_idx="+cb_idx;
		}
	
	
</script>

</head>
<body>
	<ul class="frame_set">
		<li class="frame" id="left_frame">
			<ul id="category_ul">
				<li class="logo">CMS ADMIN</li>
				<li class="menu_tit">MANAGE ADMIN</li>
				<li class="sub_tit">CHANGE PASSWD</li>
				<li class="menu_tit">MANAGE CATEGORY</li>
				<li class="sub_tit">CREATE CATEGORY</li>
				<li class="menu_tit">CATEGORY LIST</li>
				<li class="sub_tit">
					<div id="category_list">
					<ul>
						<?foreach($result["category_list"] as $val){?> 
						<li><a href="/cms_admin.php?service=view_admin_bbsList&cc_idx=<?=$val["CC_IDX"]?>"><?=$val["CATEGORY_TITLE"]?></a></li>
						<?}?>
					</ul>
					</div>
				</li>

			</ul>
		</li>
		<li class="frame" id="right_frame">
			<div id="contents_area">

<HTML>
<HEAD>
<TITLE>한강공원</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0/jquery.mobile-1.0.min.css" />
	<script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/mobile/1.0/jquery.mobile-1.0.js"></script>

<style>
	body{
		background-color:#fff;
	}
	table,td{
		padding:0;
		margin:0;
		font-size:17px;
		color:#aaa;
	}
	table.bbs_list_set{
		cursor:pointer;
	}
</style>
<script>
	var agent = navigator.userAgent;
		if (agent.match(/iPhone/) != null || agent.match(/iPod/) != null) {
			//location.href = 'http://' + location.host + '/m' + location.pathname;
			document.write('<meta name="viewport" content="minimum-scale=0.68,maximum-scale=0.68,initial-scale=0.68,user-scalable=no" />');

		}else {
			document.write('<meta name="viewport" content="minimum-scale=0.75,maximum-scale=0.75,initial-scale=0.75,user-scalable=no" />');
	}
	var BBS={};

	BBS.goToBbsView = function(cb_idx){
		location.href="/cms_board.php?service=view_board_view&cc_idx=<?=$result["cc_idx"]?>&cb_idx="+cb_idx;
	}


</script> 

</HEAD>
<BODY BGCOLOR=#FFFFFF LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0 align="center">

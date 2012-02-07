<TABLE WIDTH=480 BORDER=0 CELLPADDING=0 CELLSPACING=0 background="/common/image/boardList_top_bg.gif">
	<TR>
		<TD" WIDTH=480 HEIGHT=70></TD>
	</TR>
	<TR>
		<TD WIDTH=480 HEIGHT=60></TD>
	</TR>
	<TR>
		<TD WIDTH=480 HEIGHT=44 align="center" style="color:#35D5D7;font-size:25px;" valgin="middle">
		<?=$result["category_info"]["CATEGORY_TITLE"]?>
		</TD>
	</TR>
</table>
<table width="480" border="0" cellpadding="0" cellspacing="0"  bgcolor="#fff">
	<tr>
		<td>
			<img src="/common/image/boardList_04.gif" width="480" height="34"></td>
	</tr>
	<tr>
		<td>
			<img src="/common/image/boardList_05.gif" width="480" height="2"></td>
	</tr>

	<?for($i=$result["start_pos"];$i<$result["end_pos"];$i++){ 
	 	$val = $result["bbs_list"][$i];
		if(empty($val["CB_IDX"])) break;
	?>	
	<tr>
		<td width="480" height="39">
		<table border="0"  class="bbs_list_set" width="480" height="39" id="<?=$val["CB_IDX"]?>">
		<tr>
		<td width="10%"><?=$result["bbs_total"]-$result["no"]?></td>
		<td><a href="/cms_board.php?service=view_board_view&cc_idx=<?=$result["cc_idx"]?>&cb_idx=<?=$val["CB_IDX"]?>"><?=$val["SUBJECT"]?></a></td>
		<td width="20%"><?=date("y.m.d",$val["UPDATE_TIME"])?></td>
		</tr>
		</table>
		</td>
	</tr>
	<?$result["no"]++;}?>

	
	<tr>
		<td width="480" height="28"></td>
	</tr>
	<tr>
		<td width="480" height="36" align="center">
			<a href="#"><img src="/common/image/list_prev_btn.gif" id="prev_btn"></a>
			<a href="/cms_board.php?service=view_board_list&cc_idx=<?=$result["cc_idx"]?>&page=1"><img src="/common/image/list_dot_on.gif" id="page1"></a>
			<a href="/cms_board.php?service=view_board_list&cc_idx=<?=$result["cc_idx"]?>&page=2"><img src="/common/image/list_dot_off.gif" id="page1"></a>
			<a href="/cms_board.php?service=view_board_list&cc_idx=<?=$result["cc_idx"]?>&page=3"><img src="/common/image/list_dot_off.gif" id="page1"></a>
			<a href="/cms_board.php?service=view_board_list&cc_idx=<?=$result["cc_idx"]?>&page=4"><img src="/common/image/list_dot_off.gif" id="page1"></a>
			<a href="/cms_board.php?service=view_board_list&cc_idx=<?=$result["cc_idx"]?>&page=5"><img src="/common/image/list_dot_off.gif" id="page1"></a>
			<a href="#"><img src="/common/image/list_next_btn.gif" id="next_btn"></a>
		</td>
	</tr>
	<tr>
		<td width="480" height="49"></td>
	</tr>
	<tr>
		<td>
			<img src="/common/image/boardList_11.gif" width="480" height="29"></td>
	</tr>
	<tr>
		<td>
			<img src="/common/image/boardList_12.gif" width="480" height="149"></td>
	</tr>
</tbody></table>

	<script>
		/*
		$("table.bbs_list_set").click(function(){
			var cb_idx = $(this).attr("id");
			BBS.goToBbsView(cb_idx);	
		});
		*/
	</script>


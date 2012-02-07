	<table class="bbs_btn_area" border=0 width="100%" bgcolor="#fff">
	<tr>
			<td align="left">CMS BOARD NAME</td>
			<td align="right"><input type="button" id="btn_write" value="Write" onclick="ADMIN.goToBbsWrite()"></td>
	</tr>
	</table>
	<table class="bbs_list_header" border=0 width="100%">
	<tr>
			<th class="bbs_list_no" width="30">No</td>
			<th class="bbs_list_subject">Subject</td>
			<th class="bbs_list_date" width="120">Date</td>
			<th class="bbs_list_view_count" width="30">Count</td>
	</tr>
	</table>
	<?for($i=0;$i<$result["bbs_total"];$i++){ $val = $result["bbs_list"][$i];?>	
		<table class="bbs_list_set" border=0 id="<?=$val["CB_IDX"]?>">
			<tr>
			<td class="bbs_list_no" width="30" align="center"><?=$result["bbs_total"]-$result["no"]?></td>
			<td class="bbs_list_subject"><?=$val["SUBJECT"]?></td>
			<td class="bbs_list_date" width="120" align="center"><?=date("y-m-d(H:i)",$val["UPDATE_TIME"])?></td>
			<td class="bbs_list_view_count" width="30" align="center"><?=$val["VIEW_COUNT"]?></td>
			</tr>
		</table>
	<?$result["no"]++;}?>

	<script>
		$("table.bbs_list_set").click(function(){
			var cb_idx = $(this).attr("id");
			ADMIN.goToBbsView(cb_idx);	
		});
	</script>

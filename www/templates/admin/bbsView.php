	<table class="bbs_btn_area" border=0 width="100%" style="border:solid 1px #888;margin-bottom:10px;">
	<tr>
			<td align="left">CMS BOARD NAME</td>
			<td align="right"><input type="button" id="btn_write" value="Write" onclick="ADMIN.goToBbsWrite()"></td>
	</tr>
	</table>
	<table class="bbs_list_header" border=0 width="100%" style="border:solid 1px #333">
	<tr>
			<th class="bbs_list_subject" align="left">Subject</th>
			<th class="bbs_list_date" width="120">Date</th>
			<th class="bbs_list_view_count" width="30">Count</th>
	</tr>
	<tr>
			<td class="bbs_list_subject"><?=$result["bbs_view"]["SUBJECT"]?></td>
			<td class="bbs_list_date" width="120"><?=date("Y-m-d (H:i)",$result["bbs_view"]["UPDATE_TIME"])?></td>
			<td class="bbs_list_view_count" width="30"><?=$result["bbs_view"]["VIEW_COUNT"]?></td>
	</tr>
	</table>

	<table class="bbs_view_content" border=0 width="100%" height="400">
	<tr>
		<td valign="top"><?=$result["bbs_view"]["CONT"]?></td>
	</tr>
	</table>
	
	<table class="bbs_btn_area" border=0 width="100%" style="border:solid 1px #888;margin-top:10px;">
	<tr>
			<td align="right">
				<input type="button" id="btn_write" value="EDIT" onclick="ADMIN.goToBbsEdit(<?=$result["cb_idx"]?>)">
				<input type="button" id="btn_write" value="DELETE" onclick="ADMIN.bbsDelete(<?=$result["cb_idx"]?>)">
			</td>
	</tr>
	</table>

	<script>
		$("table.bbs_list_set").click(function(){
			var cb_idx = $(this).attr("id");
			ADMIN.goToBbsView(cb_idx);	
		});
	</script>

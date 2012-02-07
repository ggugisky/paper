
<script type="text/javascript" src="/common/script/timeline.js"></script>
<table id="status">
<tr>
	<td colspan="3" align="right">
	    <div style="width:410px;">
		<ul id="msg_type">
			<li alt="message">MESSAGE</li>
			<li alt="photo">PHOTO</li>
			<li alt="video">VIDEO</li>
			<li alt="status">STATUS</li>
		</ul>
		</div>
	</td>
</tr>
<tr>
    <td width="80" valign="top">
	<img src="<?=$result["member_info"]["m_profile_img"]?>" width=80 height=80><br>
	<input type="checkbox" id="facebook"> F	
	<input type="checkbox" id="twitter"> T	
	</td>
    <td height="40">
        <textarea name="g_content" tabindex="2" id="txt_status"></textarea>
		<div id="upload_photo_area" style="display:none;">
			<form id="frm_photo" style="margin:0" enctype="multipart/form-data" type="post">
			<input type="file" name="upload_photo" id="upload_photo">
			</form>
		</div>
    <td width="100" valign="top">
       <input type="button" tabindex="3" id="btn_status" value="SAVE" onclick="TIMELINE.HISTORY.set_status_insert();">
    </td>
</tr>
</table>


<div class="title" style="width:70%">HISTORY</div>
<div id="history_area"></div>

<div id="bottom_bar">
	<span id="btn_more" style="padding-left:20px;float:left;"><a href="javascript:void(0);" onclick="TIMELINE.HISTORY.more();">More</a></span> 
	<div id="activity_rate">0</div><span style="vertical-align:bottom;line-height:30px;padding-top:20px;">%</span>
</div>
<script>
    $(function(){
		TIMELINE.total_count=<?=$result["timeline_count"]?>;
		TIMELINE.m_idx=<?=$result["member_info"]["m_idx"]?>;
		TIMELINE.m_level=<?=$result["member_info"]["m_level"]?>;
		TIMELINE.m_emotion_point=<?=$result["member_info"]["m_emotion_point"]?>;
		TIMELINE.init();
    });
</script>

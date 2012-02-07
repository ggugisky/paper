<?
if(!is_array($result["timeline_list"])){die();}
foreach($result["timeline_list"] as $val){
    if(empty($val[h_update_time])) break;
	//if(count($result[comment_list][$val[h_uid]]) < 1) break;
	$emotion_list = $result["emotion_list"][$val["h_uid"]];
	
?>
 <table class="history" id="timebox_<?=$val[h_uid]?>"  onmouseover="TIMELINE.HISTORY.show_emotion(this,<?=$val["h_uid"]?>)" onmouseout="TIMELINE.HISTORY.hide_emotion(this)" data-checked_activity="<?=$val["checked_activity"]?>">
	<tr>
	<td colspan="2" align="right" valign="top" data-h_uid="<?=$val["h_uid"]?>" style="background-color: #333;height:28px;"> 
        <span style="float:left;font-size:14px;font-weight:bold;line-height:25px;vertical-align:middle">Level : </span>
		<ul class="btn_level" data-h_uid="<?=$val["h_uid"]?>" data-m_idx="<?=$val["m_idx"]?>">
			<li class="btn_amoeba" <?=$val["h_level"]==6?"style='opacity:1.0;filter:alpha(opacity=100)''":""?> onclick="TIMELINE.HISTORY.set_level(6,this);">Amoeba</li>
			<li class="btn_stranger" <?=$val["h_level"]==5?"style='opacity:1.0;filter:alpha(opacity=100)''":""?> onclick="TIMELINE.HISTORY.set_level(5,this);">Stranger</li>
			<li class="btn_goodman" <?=$val["h_level"]==4?"style='opacity:1.0;filter:alpha(opacity=100)''":""?> onclick="TIMELINE.HISTORY.set_level(4,this);">Good Man</li>
			<li class="btn_friend" <?=$val["h_level"]==3?"style='opacity:1.0;filter:alpha(opacity=100)''":""?> onclick="TIMELINE.HISTORY.set_level(3,this);">Friend</li>
			<li class="btn_soulmate" <?=$val["h_level"]==2?"style='opacity:1.0;filter:alpha(opacity=100)''":""?> onclick="TIMELINE.HISTORY.set_level(2,this);">Soul Mate</li>
		</ul>


		<?if($val["m_idx"] == $result["m_idx"]){?>
		<span class="btn_delete"><a href="javascript:void(0);" onclick="TIMELINE.HISTORY.remove(this)">x</a></span>
		<?}?>
	</td>
	</tr>
   	<tr>
        <td valign="top">
            <img src="<?=$val["m_profile_img"]?>" width=80 height=80 onerror="this.src='/image/no_profile.gif'">
            <span class="history_name"><?=$val[h_name]?></span>   
        </td>
        <td align="left" class="history_content">
            <?if($val[h_type] == "PHOTO"){?>
            <img src="<?=$val["h_file"]?>" width="400"><br>
            <?}?>
            <?if($val[h_type] == "VIDEO"){?>
            <embed src="<?=$val["h_file"]?>" width="400"></embed><br>
            <?}?>
            <?=nl2br($val[h_content])?>
        </td>
  	</tr>
    <tr>
        <td colspan="2" align="right" valign="top" data-h_uid="<?=$val["h_uid"]?>" style="background-color: #333;height:28px;"> 
        <span style="float:left;font-size:14px;font-weight:bold;line-height:25px;vertical-align:middle">Emotion : </span>
		<ul class="emotion" data-h_uid="<?=$val["h_uid"]?>" data-em_emotion="<?=$emotion_list[0]["em_emotion"]?>">
			<li class="btn_hate" <?=$emotion_list[0]["em_emotion"]==-2?"style='opacity:1.0;filter:alpha(opacity=100)'":""?> onclick="TIMELINE.HISTORY.set_emotion(1,this);">ㅡ_ㅡ</li>
			<li class="btn_soso" <?=$emotion_list[0]["em_emotion"]==-1?"style='opacity:1.0;filter:alpha(opacity=100)''":""?>onclick="TIMELINE.HISTORY.set_emotion(2,this);">O__O</li>
			<li class="btn_like" <?=$emotion_list[0]["em_emotion"]==1?"style='opacity:1.0;filter:alpha(opacity=100)''":""?>onclick="TIMELINE.HISTORY.set_emotion(3,this);">^__^</li>
			<li class="btn_love" <?=$emotion_list[0]["em_emotion"]==2?"style='opacity:1.0;filter:alpha(opacity=100)''":""?>onclick="TIMELINE.HISTORY.set_emotion(4,this);">^O^</li>
		</ul>
        <span class="history_date"><?=date("Y.m.d (H:i)",$val[h_update_time])?></span></td>
   </tr>
   <tr>
   <td colspan="2" bgcolor="#101010">
  <div id="comment_list_area">
    <table width="70%" id="comment_list_<?=$val["h_uid"]?>">
  	<?
	$idx=0;
	$comment = $result[comment_list][$val[h_uid]];
	while($comment[$idx]){?>
	<tbody style="padding-bottom:10px;">
    <tr>
        <td width="32" height="50" valign="top">
			<img src="<?=$comment[$idx]["m_profile_img"]?>" width="30" height="30" style="vertical-align:top;" onerror="this.src='/image/no_profile.gif'">
		</td>
		<td><b class="fc" style="padding-right:10px;"><?=$comment[$idx][c_name]?></b><?=$comment[$idx][c_content]?><br />
        <span class="comment_date"><?=date("Y.m.d (H:i)",$comment[$idx][c_update_time])?></span>
		</td>
    </tr>
	</tbody>
	<?$idx++;}?>
    </table>
	</div> 
    <div class="comment_area">
	  <span style="padding:5px;float:left;">
	  	<img src="<?=$result["member_info"]["m_profile_img"]?>" width="30" height="30" style="vertical-align:top;">
	  </span>
	  <div class="feex_area">
      <textarea type="text" data-h_uid="<?=$val["h_uid"]?>" class="txt_comment" onkeyup="TIMELINE.COMMENT.add(this);"></textarea>
      <input type="button" class="btn_comment_write" value="write" onclick="TIMELINE.COMMENT.add();">
	  </div>
    </div>
	</td>
	</tr>
	</table>
<?}?>


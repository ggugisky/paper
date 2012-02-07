<? 
include_once("./config/db.php");
include_once("./config/lib.php");
$ntc = sql_loop("select * from history order by h_update_time desc");
?>
<?foreach($ntc as $val){
    if(empty($val[h_update_time])) break;
?>
   <table class="history">
   <tr>
        <td valign="top">
            <img src="/image/no_profile.gif" width=80 height=80>
            <span class="history_name"><?=$val[h_name]?></span>   
        </td>
        <td align="left" class="history_content">
            <?if($val[h_type] == "PHOTO"){?>
            <img src="<?=$val["h_file"]?>" width=75%><br>
            <?}?>
            <?=nl2br($val[h_content])?>
        </td>
   </tr>
   <tr>
        <td class="history_name"></td>
        <td class="history_date"><?=date("m/d/Y (H:i)",$val[h_update_time])?></td>
   </tr>
<tr>
<td colspan=2>
  <div id="comment_list_area">
    <table width="100%">
                
    <tr>
        <td width="100"><b class="fc">sunny</b></td>
        <td>멋지다..이사진.. 나도 나도^^</td>
        <td width="100">10-10-04</td>
    </tr>
    <tr>
        <td height="5" colspan="3"></td>
    </tr>
    </table>
</div> 

    <div id="commentWritetArea">
      <textarea type="text" id="cmtContent"></textarea>
      <p align="right"><input type="button" value="write" onclick="commentWrite()"></p>
    </div>
</td>
</tr>
</table>
<?}?>

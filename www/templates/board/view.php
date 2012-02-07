<TABLE WIDTH=480 BORDER=0 CELLPADDING=0 CELLSPACING=0 background="/common/image/boardView_top_bg.gif">
	<TR>
		<TD WIDTH=480 HEIGHT=70 ALT=""></TD>
	</TR>
	<TR>
		<TD WIDTH=480 HEIGHT=30 align="center"><?=$result["category_info"]["CATEGORY_TITLE"]?></TD>
	</TR>
	<TR>
		<TD WIDTH=480 HEIGHT=60 align="center"><?=$result["bbs_view"]["SUBJECT"]?></TD>
	</TR>
	</table>

	<TABLE WIDTH=480 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD WIDTH=480 HEIGHT=45 align="right" style="background-color:#fff">
			<span>등록일</span> <?=date("Y.m.d",$result["bbs_view"]["UPDATE_TIME"])?>
		</TD>
	</TR>
	<TR>
		<TD WIDTH=480 HEIGHT=648 style="padding:10px 10px 20px 10px;background-color:#fff;" valign="top">
			<?=$result["bbs_view"]["CONT"]?>
		</TD>
	</TR
	<TR>
		<TD>
			<IMG SRC="/common/image/boardView_06.gif" WIDTH=480 HEIGHT=184 ALT=""></TD>
	</TR>
</TABLE>

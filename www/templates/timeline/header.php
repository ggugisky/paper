<!DOCTYPE html>
<html lang="ko">
<head>
 <title>ggugi.com Episode#My Paper</title>
<meta content="text/html; charset=euc-kr" http-equiv="content-type" />
<link rel="shortcut icon" href="/image/ggugi.ico" /><link href="/common/css/css.css" rel="stylesheet" type="text/css">
<link href="/common/css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="/common/script/jquery.min.js"></script>
<script type="text/javascript" src="/common/script/jquery.blockUI.js"></script>
<script type="text/javascript" src="/common/script/jquery.ui.min.js"></script>
<script type="text/javascript" src="/common/script/jquery.form.js"></script>
<script type="text/javascript" src="/common/script/func.js"></script>
<script type="text/javascript" src="/common/script/common.js"></script>
</head>
<body bgcolor="#000000">
	<div id="top" class="gnb_menu">
	<span id="btn_timeline"><a href="/timeline.php">History</a></span>
	<span id="btn_mypaper"><a href="/paper.php">Paper</a></span>
	<span id="btn_logout"><a href="javascript:void(0);" onclick="COMMON.logout();">Logout</a></span>
	</div>
    <div>
	<div id="left" valign="top" width="150" style="width:150px">
	<!--
    <img src="./image/intro.jpg" width="150">
	<ul>
	<li><span style="font-weight:bold;">Email</span> : <?=$result["member_info"]["m_email"]?></li>
	<li><span style="font-weight:bold;">Name</span> : <?=$result["member_info"]["m_name"]?></li>
	<li>
		<span style="font-weight:bold;">F</span>acebook : 
		<?=date("Y.m.d",$result["external_info"]["FACEBOOK"]["e_reg_time"])?>
		<br> 
		<span style="font-weight:bold;">T</span>witter :
		<?=date("Y.m.d",$result["external_info"]["TWITTER"]["e_reg_time"])?>
	</li>
	</ul>
	-->
	<h1 id="left_tit">Emotion</h1>
	<div id="emotion_status">
		<div id="baloon_emotion" class="baloon_explain">
			<b>How is ur feeling?</b>
			<p>So so.. nth special</p>
			The rate of emotion is between 50 and 100. 
		</div>
		<span id="total_emotion">ㅡ_ㅡ</span>
	</div>

	<br />
	<h1 id="left_tit">Level</h1>
	<div class="level_box" id="level_2">
		<div class="baloon_explain">
			<b>WHO R U?</b>
			<p>U r my soul mate.</p>
			The point of activity is above 128,000. 
		</div>
		Soul Mate
	</div>
	<div class="level_box" id="level_3">
		<div class="baloon_explain">
			<b>WHO R U?</b>
			<p>U r my friend.</p>
			The point of activity is between 32,000 and 128,000. 
		</div>
		Friend
	</div>
	<div class="level_box" id="level_4">
		<div class="baloon_explain">
			<b>WHO R U?</b>
			<p>Hey buddy.</p>
			The point of activity is between 8,000 and 32,0000. 
		</div>
		Good Man
	</div>
	<div class="level_box" id="level_5">
		<div class="baloon_explain">
			<b>WHO R U?</b>
			<p>Hello stranger</p>
			The point of activity is between 2,000 and 8,000. 
		</div>
		Stranger
	</div>
	<div class="level_box" id="level_6">
		<div class="baloon_explain">
			<b>WHO R U?</b>
			<p>$@@#$$!@<-the amoeba language</p>
			The point of activity is between 0 and 2,000. 
		</div>
		Amoeba
	</div>

	<br />
	<h1 id="left_tit">Point</h1>
	<div id="txt_point">
	Activity : <br><span id="activity_point"><?=$result["member_info"]["m_total_point"]?></span><br>
	Emotion : <br><span id="emotion_point"><?=$result["member_info"]["m_emotion_point"]?></span>
	</div>

		<p></p>
		<a href="javascript:function iprl5(){var d=document,z=d.createElement('scr'+'ipt'),b=d.body,l=d.location;try{if(!b)throw(0);z.setAttribute('src','http://www.ggugi.com/scrap.js?u='+encodeURIComponent(l.href)+'&t='+(new Date().getTime()));b.appendChild(z);}catch(e){alert('Please wait until the page has loaded.');}}iprl5();void(0)" class="bookmarklet" onclick="return COMMON.explain_scrap();" title="scrap">Scrap</a>

		<div style="width:150;word-break:break-word;display:none;">
		javascript:function iprl5(){var d=document,z=d.createElement('scr'+'ipt'),b=d.body,l=d.location;try{if(!b)throw(0);z.setAttribute('src','http://www.ggugi.com/scrap.js?u='+encodeURIComponent(l.href)+'&t='+(new Date().getTime()));b.appendChild(z);}catch(e){alert('Please wait until the page has loaded.');}}iprl5();void(0)
		</div>
	<!--
    <table width="150">
    <tr>
    <td class="menu" onclick="location.href='./index.php'">INDEX</td>
    </tr>
    <tr>
    <td class="menu" onclick="location.href='./itinerary.php'">ITINERARY</td>
    </tr>
    <tr>
    <td class="menu" onclick="location.href='./guest.php'">GUEST</td>
    </tr>
    <tr>
    <td class="menu" onclick="location.href='./photo.php'">PHOTO</td>
    </tr>
    <tr>
    <td>
    </td>
    </tr>
    </table>
	-->
  </div>
  <div id="content" valign="top" class="overflow">

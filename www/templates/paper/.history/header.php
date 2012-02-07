<!DOCTYPE html>
<html lang="ko">
<head>
 <title>ggugi.com Episode#My Paper</title>
<meta content="text/html; charset=euc-kr" http-equiv="content-type" /
<link rel="shortcut icon" href="/image/ggugi.ico" /><link href="/common/css/css.css" rel="stylesheet" type="text/css">
<link href="/common/css/css.css" rel="stylesheet" type="text/css">
<link href="/common/css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="/common/script/jquery.min.js"></script>
<script type="text/javascript" src="/common/script/jquery.blockUI.js"></script>
<script type="text/javascript" src="/common/script/jquery.ui.min.js"></script>
<script type="text/javascript" src="/common/script/jquery.form.js"></script>
<script type="text/javascript" src="/common/script/common.js"></script>
<script type="text/javascript" src="/common/script/paper.js"></script>
<style type="text/css">
	ul{
	list-style:none;
	}
	.pbox{
		float:left;
	}

tent_message{
		height:60%;
	}

t_contents , #right_contents{
		width:420px;
		padding:5px;
		height:600px;
		display:block;
	}

t_page_count, #right_page_count{
		font-size:25px;
		color:#888;

	}

	.page_info{
		display :none;		
	}


	.btn_arrow_left a, .btn_arrow_right a{
		font-size:80px;
		vertical-align:middle;
		color:#111;
	}
	.btn_arrow_left a:hover, .btn_arrow_right a:hover{
		color:#eee;
	}
	.btn_arrow_left, .btn_arrow_right{
		padding-top:250px;
		text-align:center;
	}

	/*
	.btn_arrow_left{
		position:absolute;
		left:10px;
		top:200px;
	}

	.btn_arrow_right{
		position:absolute;
		right:10px;
		top:200px;
	}
	*/

wer_background{
		padding:0px;
	}



ail_layer{
		position:absolute;
		width:500px;
		height:580px;
		background-color:#333;
		padding:20px;
		top:10px;
		border:dashed 1px #eee;
	}

ail_layer div.feed_body{
		height:450px;
		overflow-y:auto;
	}

ail_layer header{
	}

ail_layer header h1#detail_subject{
		width:400px;
	}

ail_layer header p {
		position:absolute;
		left:500px;
		top:20px;
		
	}

ail_layer header p a{
		font-size:25px;
		font-weight:bold;
	}

ail_layer .w_btn{
		border:solid 1px #eee;
	}

	.page_ele, .page_count{
		float:left;
		background-color:#333;
	}

	.page_count{
		height:610px;
		font-weight:bold;
		color:#eee;
	}
	div#viewer_body{
		position:absolute;
		z-index:100;
		left:10%;
		top:5%;
		display:none;
	}
</style>

</head>
<body bgcolor="#000000">


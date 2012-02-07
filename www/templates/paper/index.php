<script type="text/javascript" src="/common/script/paper.js"></script>
<script type="text/javascript" src="/common/script/viewer.js"></script>

<div id="magagine_area" class="body_box">
	<div id="magagine_content_area" class="content_box">
		<div id="magagine_list">
		<ul>
			<li>
				<div class="magagine_each" alt="My Diary" title="My Diary" flag="DIARY">MY DIARY</div>
			</li>
			<li>
				<div class="magagine_each" alt="My Scrap" title="My Scrap" flag="SCRAP">MY SCRAP</div>
			</li>
			<li>
				<div class="magagine_each" alt="Blog paper" title="My paper" flag="BLOG">BLOG PAPER</div>
			</li>
			<li>
				<div class="magagine_each" alt="SNS paper" title="SNS Paper" flag="SNS">SNS PAPER</div>
			</li>
		</ul>
		</div>
	</div>
</div>
<br>
<br>
<div id="tag_area" class="body_box">
	<div id="tag_search_area">
		<span>search </span>
		<input type="text" id="txt_search">
		<input type="button" id="btn_search" value="Search">
	</div>
	<div id="tag_content_area" class="content_box">
		<div id="tag_list"></div>
	</div>
</div>

<!-- viewer body -->
<div id="viewer_body">
	<div id="viewer_top">
		<span id="tag_title"></span>
		<p class="btn_close"><a href="javascript:void(Book.close())" style="background-position-x:-85px">X</a></p>
	</div>
	<div id="viewer_background" class="content_area clfix" >
		<ul>
		<li class="page_count">
			<div class="btn_arrow_left" style="z-index:100">
				<a href="javascript:Book.prevPage()" class="btn_prev">◀</a>
			<div id="left_page_count"></div>
			</div>					
		</li>
		<li id="left_area" class="page_ele">
			<div id="left_title"></div>
			<div class="p_wrap" id="left_contents"></div>
		</li>
		<li id="right_area" class="page_ele">
			<div id="right_title"></div>
			<div class="p_wrap" id="right_contents"></div>
		</li>
		<li class="page_count">
			<div class="btn_arrow_right" style="z-index:100">
				<a href="javascript:Book.nextPage()" class="btn_next">▶</a>
			<div id="right_page_count"></div>
			</div>					
		</li>
		</ul>
	</div>
</div>
<!-- //viewer body -->

<!-- original contents layer -->
<div id="detail_layer" class="lay_feed_detail">
<header>
	<h1 id="detail_subject"></h1>
	<p class="btn_close"><a href="javascript:void(Book.detailClose())" style="background-position-x:-85px">x</a></p>
</header>

	<div class="feed_body">
		<p id="feed_user_detail">text</p>
		<p id="content_message"></p>
	</div>
	<p id="comment_bar"> ▷ Comment ()  </p>
	<footer id="footer">
		<div style="float:left;margin-right:100px;">저작자표시-비영리-동일조건변경허락(BY-NC-SA)</div>
			<p class="w_btn">
				<a href="" target="_blank" style="height: 14px; line-height: 1em; padding-top: 5px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; width: 65px; ">원문보기</a>
			</p>
	</footer>
</div>			
<!-- original contents layer -->

<script type="text/javascript"> 
	var Book = "";
	$(function(){
		Book = new BOOK("<?=$result["m_idx"]?>","static");	
	});
</script>

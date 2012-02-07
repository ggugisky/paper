<style type="text/css">
.pbox{
	float:left;
}
#book_recommand , #book_cover, #book_intro, #book_last,#front_cover{
	display:none;
}

#content_message{
	height:60%;
}

#left_contents , #right_contents{
	width:420px;
	padding:20px;
	height:600px;
	display:block;
	float:left;
	background-color:#333;
}

.page_info{
	display :none;		
}

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

#viewer_background{
	padding-left:50px;
}



#detail_layer{
position:absolute;
width:500px;
height:500px;
overflow-y:auto;
background-color:#333;
border-solid 1px #eee;
padding:20px;
}



</style>

<div id="wrap">
	<!-- layer popup -->
	<div class="lay_wrap">
		<div class="lay_dim"></div>
		<!-- layer : viewer -->
		<div class="lay_viewer lay_daily">
			<!-- 오늘의 책 class -->
			
			<!-- viewer body -->
			<div id="viewer_body">
				<div id="viewer_background" class="content_area clfix" >
					<div class="book_cover" id="front_cover" style="position:absolute;right:0px;top:50px">
						<div class="book_cover_daily">
							<h1>{{TITLE}}<br><span>{{BIG_DATE}}</span></h1>
							<p class="t_username">{{WRITER_NAME}}</p>
						</div>
					</div>						
					<div class="book_cover_daily alt"  id="back_cover" style="display:none;position:absolute;left:0px;top:50px">
							<div class="last_cover">
							</div>
						</div>
			
					<!-- content page -->
					<div class="content_page" id="left_page">
					
					<!-- book overview -->
						<div id="book_overview" class="book_overview" style="display:none;z-index:100">
						</div>
					<!-- //book overview -->					

					
					
					
					
					
					
					
						<div class="p_wrap" id="left_contents">
												
						</div>
						<!-- content page no -->
						<p class="page_info"><span id="left_page_count">3</span> <span>{{TITLE}} {{BIG_DATE}}</span></p>
					</div>
					<!-- //content page -->
					
					<!-- content page / right -->
					<div class="content_page pr_c" id="right_page">

					
						<!-- 첫번째 페이지 -->
						<div id="book_intro" class="p_first">
							<h1>{{TITLE}}</h1>
							<p class="p_day">{{BIG_DATE}}</p>
							
							<div class="p_copyright">
								<p class="p_writer">{{WRITER_NAME}}</p>
							</div>
						</div>
						<!-- //첫번째 페이지 -->
											
						<div class="p_wrap" id="right_contents">
						</div>
						<!-- content page no -->
						<p class="page_info" style="z-index:99"><span></span> <span id="right_page_count">4</span></p>
					</div>
					<!-- //content page / right -->
					<!-- btn arrow -->
					<div class="btn_arrow_left" style="z-index:100">
						<a href="javascript:Book.prevPage()" class="btn_prev">이전</a>
					</div>
					<div class="btn_arrow_right" style="z-index:100">
						<a href="javascript:Book.nextPage()" class="btn_next">다음</a>
					</div>
					<!-- //btn arrow -->
				</div>
			</div>
			<!-- //viewer body -->

		</div>
		<!-- //layer : viewer -->

			<!-- original contents layer -->
			<div id="detail_layer" class="lay_feed_detail" style="left:50%; top:50%;  margin:-329px 0 0 -350px;display:none;">
					<header>
						<h1 id="detail_subject"></h1>
						<p class="btn_close"><a href="javascript:void(Book.detailClose())" style="background-position-x:-85px">close</a></p>
					</header>
					<div class="feed_body">
						<p id="feed_user_detail"></p>
						<p id="content_message"></p>
					</div>
					<p id="comment_bar"> ▷ Comment ()  </p>
					<footer>
						<img src="/images/common/imgS_ccl_6.gif" 
				alt="저작자표시-비영리-동일조건변경허락(BY-NC-SA)"> 
				저작자표시-비영리-동일조건변경허락(BY-NC-SA)
					</footer>
			</div>			
			<!-- original contents layer -->
	</div>
	<!-- //layer popup -->



</div>

<script type="text/javascript"> 
	var Book = "";
	$(function(){
		Book = new BOOK("<?=$result["m_idx"]?>","static");	
	});
</script>

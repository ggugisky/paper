<script>
	
</script>

<ul id="tag_list" data-role="listview" data-inset="true" data-filter="true">
	<?foreach($result["tag_list"] as $val){?>
		<li><a href="#"><?=$val["h_tag"]?></a></li>
	<?}?>
</ul>

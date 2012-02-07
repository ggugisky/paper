var PAPER = {};


PAPER = {
	init	: function(){
		var self = this;
		self.UI.init();
	}
}


$(function(){
PAPER.UI = {
	$tag_list		: Dom.getId("tag_list"),
	$magagine_each	: $(".magagine_each"),
	_json_tag_data 	: {},

	init	: function(){
		var self = this;	
		self.load();
		self.bind();
		
	},
	bind	: function(){
		var self = this;	
		self.$magagine_each.unbind().bind("click",function(){
		 	Book.open($(this).attr("flag"));	
		});
	},
	restruct: function(){
		var self = this;	
		
	},
	load	: function(){
		var self =this;
		$.ajax({
			url		: "/paper.php?service=api_paper_getTagList",
			type	: "post",
			data	: {},
			dataType: "json",
			success	: function(json){
				self._json_tag_data = json.RESPONSE.tag_list;	
				self.makeCloude();
			}
		})
	},
	makeCloude: function(){
		var self = this;	
		for(var i in self._json_tag_data){
			var span_tag = Dom.makeEle("span",{"class" : "span_tag"});
			var span_slash = Dom.makeEle("span",{"class" : "span_slash"});
			span_slash.innerHTML = "";
			var a_tag = Dom.makeEle("a",{
					"href" 	: "javascript:void(0)",
					"alt" 	: self._json_tag_data[i].h_tag 
			});
			a_tag.innerHTML = self._json_tag_data[i].h_tag;
			a_tag.onclick = function(){
				Book.open("TAG",this.getAttribute("alt"));	
			}
			Dom.setCss(a_tag,{
				"font-size" 	: 10 + parseInt(self._json_tag_data[i].cnt) + "px",
				"font-weight" 	: 100 + parseInt(self._json_tag_data[i].cnt),
				"opacity" 		: 0.5 + parseInt(self._json_tag_data[i].cnt/100),
				"filter" 		: "alpha(opacity=" +50 + parseInt(self._json_tag_data[i].cnt/100) + ")"
				
			});
			Dom.appendEle(span_tag,[a_tag]);
			Dom.appendEle(self.$tag_list,[span_tag,span_slash]);
		}
		
	}

}


PAPER.SERVER = {


}
});


$(function(){
	PAPER.init();

})

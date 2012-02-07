function $_(a){
	return document.getElementById(a);
}

function cmtWrite(uid){
  $("#cmt_name"+uid).val();
  if($("#cmt_name"+uid).val().length < 1){
    alert("Please insert for your name.");
    return false;
  }
  if($("#cmt_content"+uid).val().length < 1){
    alert("Please insert for content");
    return false;
  }


  $.ajax({
    url : "./process.php",
    type  : "post",
    data : {
      "cheri"   : "write",
      "c_name"  : $("#cmt_name"+uid).val(),
      "c_content" : $("#cmt_content"+uid).val(),
      "c_img" : $("#cmt_img"+uid).val()
    },
    dataType  : "html",
    success   : function(dd){
      $("#cmt_list_area"+uid).html(dd);
    }
  }
  )
}

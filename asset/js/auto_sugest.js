// JavaScript Document
//Auto Sugest
$(document).ready(function(e) {
    $('#printpreview').removeClass('tab_button');
	$('#printpreview').addClass('tab_select');

});
function auto_suggest(linked,str,id){
	if (str.length == 0) {
		$('#autosuggest_list').fadeOut(100);
	} else {
		var idd=id.split('-');
		$('#'+idd[1]+' input#'+idd[0]).addClass('loading');
		$.post(linked,
			  {'str':str,'induk':idd[1]},
			  function(result){
				  if(trim(result)!=''){
					$('#autosuggest_list').html(result);
					$('#autosuggest_list').fadeIn(100);
					$('#'+idd[1]+' input#'+idd[0]).removeClass('loading');	  
				  }else{
					  $('div.autosuggest').hide();
					  $('#'+idd[1]+' input#'+idd[0]).removeClass('loading');	  
				  }
			  })
			 // pilih(id);
	}
	
}
function auto_suggest2(linked,str,id){
	
	if (str.length == 0) {
		$('#autosuggest_list').fadeOut(100);
	} else {
		var idd=id.split('-');
		$('#'+idd[1]+' input#'+idd[0]).addClass('loading');
		$.post(linked,
			  {'str':str,'induk':idd[1],'fld':idd[0]},
			  function(result){
				  if(trim(result)){
					$('#autosuggest_list').html(result);
					$('#autosuggest_list').fadeIn(100);
					$('#'+idd[1]+' input#'+idd[0]).removeClass('loading');	  
				  }else{
					  $('div.autosuggest').hide();
					  $('#'+idd[1]+' input#'+idd[0]).removeClass('loading');	  
				  }
			  })
	}
	
}
function auto_suggest3(linked,str,id){
		var idd=id.split('-');
		$('#'+idd[1]+' input#'+idd[0]).addClass('loading');
		$.post(linked,
			  {'str':str,'induk':idd[1],'fld':idd[0]},
			  function(result){
				  if(trim(result)){
					$('#autosuggest_list').html(result);
					$('#autosuggest_list').fadeIn(100);
					$('#'+idd[1]+' input#'+idd[0]).removeClass('loading');	  
				  }else{
					  $('div.autosuggest').hide();
					  $('#'+idd[1]+' input#'+idd[0]).removeClass('loading');	  
				  }
			  })
	
}

function pilih(id){
	var active=-1;
	var idd=id.split('-');
	var $input=$('#'+idd[1]+' input#'+idd[0])
	.keydown(function(e) {
		// track last key pressed
		lastKeyPressCode = e.keyCode;
		switch(e.keyCode) {
			case 38: // up
				e.preventDefault();
				moveSelect(-1);
				break;
			case 40: // down
				e.preventDefault();
				moveSelect(1);
				break;
			case 9:  // tab
			case 13: // return
				if( selectCurrent() ){
					// make sure to blur off the current field
					$input.get(0).blur();
					e.preventDefault();
				}
				break;
			default:
				active = -1;
				if (timeout) clearTimeout(timeout);
				timeout = setTimeout(function(){onChange();}, options.delay);
				break;
		}
	})
}

 	function moveSelect(step) {
		var active=-1;
		var lis = $('#autosuggest_list');
		if (!lis) return;

		active += step;

		if (active < 0) {
			active = 0;
		} else if (active >= lis.size()) {
			active = lis.size() - 1;
		}

		lis.removeClass("autosuggest");

		$(lis[active]).addClass("autosuggest");

		// Weird behaviour in IE
		// if (lis[active] && lis[active].scrollIntoView) {
		// 	lis[active].scrollIntoView(false);
		// }

	};

function suggest_click(clicked,id,frm){
	//alert("$('#'"+frm+"' input#'"+id+"').val('"+clicked+"');");
	$('#'+frm+' input#'+id).removeClass('loading');
	$('#'+frm+' input#'+id).val(clicked);
	setTimeout("$('#autosuggest_list').fadeOut(500);", 50);	
	on_clicked(clicked,id,frm);
}
function suggest_click2(clicked,id,frm){
	$('#'+frm+' input#'+id).removeClass('loading');
	$('#frm9 input#'+id).val(clicked);
	setTimeout("$('#autosuggest_list').fadeOut(500);", 50);	
	on_clicked(clicked,id,frm);
}

function pos_div(parent){
	var offst=$(parent).offset();
	var t=offst.top+28;
	var l=offst.left;
	var w=$(parent).width()+5;
	$('div.autosuggest').css({'top':t,'left':l,'width':w,'position':'fixed'});	
}
function pos_info(parent,id){
	var offst=$(parent).offset();
	var t=offst.top+33;
	var l=offst.left;
	var w=$(parent).width()+5;
	$('div#'+id).css({'top':t,'left':l,'width':w,'position':'fixed'});
	$('div#'+id).show();	
}

function trim(str){
	    return str.replace(/^\s+|\s+$/g,'');
}
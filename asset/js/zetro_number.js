// JavaScript Document
	var dateFormat = function () {
		var	token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
			timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
			timezoneClip = /[^-+\dA-Z]/g,
			pad = function (val, len) {
				val = String(val);
				len = len || 2;
				while (val.length < len) val = "0" + val;
				return val;
			};
	
		// Regexes and supporting functions are cached through closure
		return function (date, mask, utc) {
			var dF = dateFormat;
	
			// You can't provide utc if you skip other args (use the "UTC:" mask prefix)
			if (arguments.length == 1 && Object.prototype.toString.call(date) == "[object String]" && !/\d/.test(date)) {
				mask = date;
				date = undefined;
			}
	
			// Passing date through Date applies Date.parse, if necessary
			date = date ? new Date(date) : new Date;
			if (isNaN(date)) throw SyntaxError("invalid date");
	
			mask = String(dF.masks[mask] || mask || dF.masks["default"]);
	
			// Allow setting the utc argument via the mask
			if (mask.slice(0, 4) == "UTC:") {
				mask = mask.slice(4);
				utc = true;
			}
	
			var	_ = utc ? "getUTC" : "get",
				d = date[_ + "Date"](),
				D = date[_ + "Day"](),
				m = date[_ + "Month"](),
				y = date[_ + "FullYear"](),
				H = date[_ + "Hours"](),
				M = date[_ + "Minutes"](),
				s = date[_ + "Seconds"](),
				L = date[_ + "Milliseconds"](),
				o = utc ? 0 : date.getTimezoneOffset(),
				flags = {
					d:    d,
					dd:   pad(d),
					ddd:  dF.i18n.dayNames[D],
					dddd: dF.i18n.dayNames[D + 7],
					m:    m + 1,
					mm:   pad(m + 1),
					mmm:  dF.i18n.monthNames[m],
					mmmm: dF.i18n.monthNames[m + 12],
					yy:   String(y).slice(2),
					yyyy: y,
					h:    H % 12 || 12,
					hh:   pad(H % 12 || 12),
					H:    H,
					HH:   pad(H),
					M:    M,
					MM:   pad(M),
					s:    s,
					ss:   pad(s),
					l:    pad(L, 3),
					L:    pad(L > 99 ? Math.round(L / 10) : L),
					t:    H < 12 ? "a"  : "p",
					tt:   H < 12 ? "am" : "pm",
					T:    H < 12 ? "A"  : "P",
					TT:   H < 12 ? "AM" : "PM",
					Z:    utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
					o:    (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
					S:    ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10],
					w:	  ["WIB"]
				};
	
			return mask.replace(token, function ($0) {
				return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
			});
		};
	}();
	
	// Some common format strings
	dateFormat.masks = {
		"default":      "ddd mmm dd yyyy HH:MM:ss",
		shortDate:      "dd/mm/yyyy",
		mediumDate:     "mmm d, yyyy",
		longDate:       "mmmm d, yyyy",
		fullDate:       "dddd, mmmm d, yyyy",
		shortTime:      "h:MM TT",
		mediumTime:     "h:MM:ss TT",
		longTime:       "h:MM:ss TT Z",
		isoDate:        "yyyy-mm-dd",
		isoTime:        "HH:MM:ss",
		isoDateTime:    "yyyy-mm-dd'T'HH:MM:ss",
		isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
	};
	
	// Internationalization strings
	dateFormat.i18n = {
		dayNames: [
			"Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat",
			"Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
		],
		monthNames: [
			"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
			"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
		]
	};
	
	// For convenience...
	Date.prototype.format = function (mask, utc) {
		return dateFormat(this, mask, utc);
	};
  
  ///additional
  function to_number(st,sp){
			var strp=(sp=='')?'.':",";
			var str='';
			var is_number= /[0-9]/;
			var xx=st.split('.')
			for (i=0;i<=xx[0].length;i++){
				var ch=st.charAt(i);
				if (ch.match(is_number)){
				str=str+ch;
				}
			};
			return str;
	}
	function NumberFormat(str,frm,dec){
		    var nformat='';
			var l=str.length;
			//for (l=0;l<=str.length;i++){
				if (l>=4 && l <=6){
					nformat=str.substr(0,l-3)+frm+str.substr(l-3,3);
				}else if(l>=7 && l<=9){
					nformat=str.substr(0,l-6)+frm+str.substr(l-6,3)+frm+str.substr(l-3,3);
				}else if (l>=10 && l<=12){
					nformat=str.substr(0,l-9)+frm+str.substr(l-9,3)+frm+str.substr(l-6,3)+frm+str.substr(l-3,3);
				}
			(!dec)?dec=',00':dec=','+dec
			return nformat+dec;
			//}
	}

	function tanggal(obj){
			var l=$(obj).val();
			var d= new Date;
			if(l.length==2 || l.length==5){
				$(obj).val(l+'/');
				var x=$(obj).val().split('/');
				if(x[1]>12){
				 alert('Bulan salah tidak boleh lebih dari 12');}else{				
			 	 if(l.length==5){$(obj).val(l+'/'+d.getFullYear());}
				 }
			}
	}
	function nomore(sp,tran,obj){
		//var sp=$('#idcus').val();
		//var tran='SPM';
		$.get('validate.php?prs=penomoran&tr='+tran+'&sp='+sp+'&null='+Math.random,
		function u(res){var nn=res.split('|');$(obj).val(tran+'/'+nn[1]);/*('#ntran').attr('disabled','disabled');*/})
	}

	function idCar(obj){
		var isi=$(obj).val().toUpperCase();
		var np=isi.length;
		if (np==1 || np==6){
			$(obj).val(isi+'-');
		}
		
	}
	function tglNow(obj){
			var l= new Date;
			var d=l.getDate();
            var m=l.getMonth()+1;
            var y=l.getFullYear();
            var dd=(parseInt(d)<10)?'0'+d:d;
            var mm=(parseInt(m)<10)?'0'+m:m;
            $(obj).val(dd+'/'+mm+'/'+y);
	}
	function TimeNow(obj){
		var tgl=new Date;
			$(obj).val(dateFormat(tgl,'yyyy-mm-dd H:M:ss',this));
	}
	function tabField(){
		 $('input:text:first').focus();
			 
		 $('input:text').bind("keydown", function(e) {
			var n = $("input:text").length;
			if (e.which == 13)
			{ //Enter key
			  e.preventDefault(); //Skip default behavior of the enter key
			  var nextIndex = $('input:text').index(this) + 1;
			  if(nextIndex < n)	$('input:text')[nextIndex].focus();
			  else
			  {
				$('input:text')[nextIndex-1].blur();
				$('#btnSubmit').click();
			  }
			}
		  });
	}

	function lock(obj){
		$(obj).attr('disabled','disabled');
	}
	function unlock(obj){
		$(obj).removeAttr('disabled');
	}
	
	function format_number(pnumber,decimals){
		if (isNaN(pnumber)) { return 0};
		if (pnumber=='') { return 0};
		
		var snum = new String(pnumber);
		var sec = snum.split('.');
		var whole = parseFloat(sec[0]);
		var result = '';
		
		if(sec.length > 1){
			var dec = new String(sec[1]);
			dec = String(parseFloat(sec[1])/Math.pow(10,(dec.length - decimals)));
			dec = String(whole + Math.round(parseFloat(dec))/Math.pow(10,decimals));
			var dot = dec.indexOf('.');
			if(dot == -1){
				dec += '.'; 
				dot = dec.indexOf('.');
			}
			while(dec.length <= dot + decimals) { dec += '0'; }
			result = dec;
		} else{
			var dot;
			var dec = new String(whole);
			dec += '.';
			dot = dec.indexOf('.');		
			while(dec.length <= dot + decimals) { dec += '0'; }
			result = dec;
		}	
		return addCommas(result);
	}
	function addCommas(nStr)
	{
		nStr += '';
		x = nStr.split(',');
		x1 = x[0];
		x2 = x.length > 1 ? ',' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}
	function addSeparatorsNF(nStr, inD, outD, sep)
	{
		nStr += '';
		var dpos = nStr.indexOf(inD);
		var nStrEnd = '';
		if (dpos != -1) {
			nStrEnd = outD + nStr.substring(dpos + 1, nStr.length);
			nStr = nStr.substring(0, dpos);
		}
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(nStr)) {
			nStr = nStr.replace(rgx, '$1' + sep + '$2');
		}
		return nStr + nStrEnd;
	}
	
	function open_win(url,w,h){
		var l=(window.screen.availWidth-w)/2
		var t=(window.screen.availHeight-h)/2
		myWindow=window.open(url,'','left='+l+'px,top='+t+'px,menubar=no,titlebar=no,resizable=1,scrollbars=yes,width='+w+'px,height='+h+'px')
		myWindow.focus()
	}
	function getNextDate(date1, duration,strDateDelimiter){   
	date1 = date1.split(strDateDelimiter); 
	sDate = new Date(date1[1]+"/"+date1[0]+"/"+date1[2]);
	  x = sDate.getTime();
	  x = x + parseFloat(duration * 86400000); 
	  sDate.setTime(x);
	  m=sDate.getMonth()+1;
	  date2 = sDate.getDate() + "/" + m + "/" + sDate.getFullYear();
	  if(date2.length==9){
		  var dd=date2.split("/");
		  if (dd[0].length==1){date3="0"+dd[0]+"/"+dd[1]+"/"+dd[2];}else{date3=dd[0]+"/0"+dd[1]+"/"+dd[2];}
	  }else if(date2.length==8){
		  var dd=date2.split("/");
		  date3="0"+dd[0]+"/0"+dd[1]+"/"+dd[2];
	  }else{
		  date3=date2;
	  }
	  return date3;
	
	}
	function nextweek(){
		var today = new Date();
		var nextweek = new Date(today.getFullYear(), today.getMonth(), today.getDate()+7);
		return nextweek;
	}
	function getBulan(dated){
		//var sDate= new Date(date1);
			dDate=dated.split('/');
		var bulan= dDate[1]+'/'+dDate[0]+'/'+dDate[2];
		return bulan;
	}
	
	function isDate(txtDate) {
    var objDate,  // date object initialized from the txtDate string
        mSeconds, // txtDate in milliseconds
        day,      // day
        month,    // month
        year;     // year
    // date length should be 10 characters (no more no less)
    if (txtDate.length !== 10) {
        return false;
    }
    // third and sixth character should be '/'
    if (txtDate.substring(2, 3) !== '/' || txtDate.substring(5, 6) !== '/') {
        return false;
    }
    // extract month, day and year from the txtDate (expected format is mm/dd/yyyy)
    // subtraction will cast variables to integer implicitly (needed
    // for !== comparing)
    month = txtDate.substring(0, 2) - 1; // because months in JS start from 0
    day = txtDate.substring(3, 5) - 0;
    year = txtDate.substring(6, 10) - 0;
    // test year range
    if (year < 1000 || year > 3000) {
        return false;
    }
    // convert txtDate to milliseconds
    mSeconds = (new Date(year, month, day)).getTime();
    // initialize Date() object from calculated milliseconds
    objDate = new Date();
    objDate.setTime(mSeconds);
    // compare input date and parts from Date() object
    // if difference exists then date isn't valid
    if (objDate.getFullYear() !== year ||
        objDate.getMonth() !== month ||
        objDate.getDate() !== day) {
        return false;
    }
    // otherwise return true
    return true;
}
//show loading bar
	function ajax_start(){
		$('div#ajax_start').show();
		$('div#lock').show();
	}
	function ajax_stop(){
		$('div#lock').hide();
		$('div#ajax_start').hide();
	}
//show indicator bar
	function show_indicator(id,col){
	var path=$('#path').val().replace('index.php/','');
		$('table#'+id+' tbody').html("<tr><td class='kotak' colspan='"+col+"'>Data being process, please wait....<img src='"+path+"asset/img/indicator.gif'></td></tr>");	
	}
// penanggalan
	function tglFromSql(txtTgl){
		try {
			if(txtTgl!='undefined')
			{
				var thn=txtTgl.substr(0,4);
				var bln=txtTgl.substr(5,2);
				var day=txtTgl.substr(8,2);
				var tglFromSql=day+'/'+bln+'/'+thn
				return tglFromSql;
			}
		}
		catch(err){
			
		}
	}
	function nBulan(d){
		var month=new Array();
		month[0]="January";
		month[1]="February";
		month[2]="March";
		month[3]="April";
		month[4]="May";
		month[5]="June";
		month[6]="July";
		month[7]="August";
		month[8]="September";
		month[9]="October";
		month[10]="November";
		month[11]="December";
		return month[d.getMonth()];
	}
	function nRomawi(d)
	{
		var month=new Array();
		month[0]="I";
		month[1]="II";
		month[2]="III";
		month[3]="IV";
		month[4]="V";
		month[5]="VI";
		month[6]="VII";
		month[7]="VIII";
		month[8]="IX";
		month[9]="X";
		month[10]="XI";
		month[11]="XII";
		return month[d];	
	}
	function kekata(field){
		$(field).terbilang({'output_div':'terbilang'})//menampikan data terbilang 
		pos_info(field,'terbilang');
	}
	function kekata_hide(){
		$('#terbilang').hide();	
	}
	function pos_info(parent,id){
		var offst=$(parent).offset();
		var t=offst.top+33;
		var l=offst.left;
		var w=$(parent).width()+5;
		$('div#'+id).css({'top':t,'left':l,'width':w,'position':'fixed','z-index':'99999'});
		$('div#'+id).show();	
	}
	function roundNumber(num, dec) {
		var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
		return result;
	}
	function rdOnly(field,stat){
		(stat==true)?
		$(field).attr('readonly','readonly'):
		$(field).removeAttr('readonly');
	}
	function validate(frm){
		$(frm).validationEngine();	
	}
	function formatAngka(id)
	{
		$('#'+id).priceFormat({ prefix: '', centsSeparator: '', thousandsSeparator: ',', centsLimit: 0, allowNegative: true});	
	}
	function formatRp(id)
	{
		$(id).priceFormat({ prefix: '', centsSeparator: '', thousandsSeparator: ',', centsLimit: 0, allowNegative: true});	
	}
/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


//
// vcn_general.js -- general purpose javascript functions for VCN
//

(function($) {
	Drupal.behaviors.vcn_general = {
		attach: function(context, settings) {
			$('div.messages').each(function() {
				$(this).removeClass('messages').addClass('alert');
			});
			$('div.error').each(function() {
				$(this).removeClass('error').addClass('alert-error');
			});			
			$('div.status').each(function() {
				$(this).removeClass('status').addClass('alert-info');
			});
			$('a.vcn-sign-out').click(function() {
                                // log out of moodle - to get this to work we need to call the logout.php page in moodle 
                                // and get the sesskey that is located within the page. Then we call the logout.php page 
                                // again and pass the sesskey to this page which should then log the user out of moodle
                                $.ajax({
                                  url: vcn_get_site_base_url()+vcn_moodle_base_path()+'login/logout.php', 
                                  cache: false,
                                  async: false,
                                  dataType: 'html',
                                  success: function(data) {
                                    var parts = data.split('sesskey=');
                                    if (parts.length > 1) {
                                      var parts2 = parts[1].split('">');

                                      if (parts2.length > 1) {
                                        var sesskey = parts2[0];

                                        $.ajax({
                                          url: vcn_get_site_base_url()+vcn_moodle_base_path()+'login/logout.php?sesskey='+sesskey,
                                          cache: false,
                                          async: false,
                                          dataType: 'html',
                                          success: function() {
                                            // logged out
                                          },
                                          error: function() {
                                            // write error to file
                                          }
                                        }); 
                                      }
                                    }
                                  },
                                  error: function(data) { 
                                    // write error to file
                                  }
                                });
                                
                                // clear sessions
				$.ajax({url: vcn_get_drupal7_base_path()+'unset-session-onetcode', async: false});
			});
			if ($('#vcn-login-register .captcha .fieldset-legend').length) {
				$('#vcn-login-register .captcha .fieldset-legend').text('Verification');
                        }
                        
			// changes for alignment of the "Sign Up for MyVCN" block
			if (!($('#cac-match-interest-outer').length)) {
				$('#choose-a-career #cac-cma').css({"margin":"63px 0 0 0"});
			}
		}
	};
})(jQuery);

//VCN set/get cookie via js ( session cookie is cookie without expiration mentioned while set) (expiretime, cookiepath) [optional]
function vcn_set_cookie(key, value, expiretime, cookiepath) {
	if(cookiepath == "") {
		cookiepath = '/';
	}
	
	if(expiretime == ""){
		jQuery.cookie(key, value, { path: cookiepath});
	}else {
		jQuery.cookie(key, value, { expires: expiretime, path: cookiepath});
	}
	return true;
}

function vcn_delete_cookie(key, cookiepath) {
	if(cookiepath == "") {
		cookiepath = '/';
	}
	jQuery.cookie(key, null, { path: cookiepath});
	return true;
}

//logo popup 
function popit(url)
{
window.open(url,"","height=480,width=640,toolbar=0,resizable=1,scrollbars=1,menubar=1,status=0");
}

function vcn_zipcode_validation(zipcode) {
	var validated = false; 
	if (zipcode.length > 0 && !isNaN(zipcode)) {	
	   	  // make syncronous call to get the zip validation
		jQuery.ajax({url: vcn_get_drupal7_base_path()+'zipcode-validation/ajax?zipcode='+zipcode, 
	 		   		success: function(result) { 
					if (result == true) { 
							validated = true;	 											
						}
					 }, 
			async: false}); 
	}
	
	 return validated;
}

// limit keyboard inputs to alpha characters

function vcn_alphaonly(event) {
  var key = (event.which) ? event.which : event.keyCode;
  if (((key < 65 ||  key > 122) && key!=13 && key!=8 && key!=32 && key!=37 && key!=39 && key!=46 && key!=9) || key==37 || key==94 || key==95)
 { 
    return false;
  }
  return true;
}

function zoomEntirePage(zoomNow) {
  zoomNow = typeof zoomNow !== 'undefined' ? zoomNow : false;
 
  var zoomLevel = jQuery.cookie('pagezoom');
  
  if (zoomNow) {
    if (zoomLevel === '0') {
      zoomLevel = 1;
    } else if (zoomLevel === '1') {
      zoomLevel = 2;
    } else {
      zoomLevel = 0;
    }
  }

  if (!zoomLevel) {
    zoomLevel = '0';
  }
  
  var values = Array('vcnzoomnormal', 'vcnzoomlarger', 'vcnzoomlargest'); //{0: 'vcnzoomnormal', 1: 'vcnzoomlarger', 2: 'vcnzoomlargest'}; //{vcnzoomnormal: 'vcnzoomlarger', vcnzoomlarger: 'vcnzoomlargest', vcnzoomlargest: 'vcnzoomnormal'};
  var newClass = values[zoomLevel];

  jQuery('#page-wrapper').removeClass();
  jQuery('#page-wrapper').addClass(newClass);
  
  jQuery.cookie('pagezoom', zoomLevel, { expires: 7 });
};

function fontsize12() { 
   jQuery('span').not('.noresize').css('font-size','12px');
   jQuery('div').not('.noresize').css('font-size','12px');
   jQuery('center').not('.noresize').css('font-size','12px');
   jQuery('p').not('.noresize').css('font-size','12px');
   jQuery('li').not('.noresize').css('font-size','12px');
   jQuery('td').not('.noresize').css('font-size','12px');
   jQuery('th').not('.noresize').css('font-size','12px');
};

function fontsize15() { 
   jQuery('span').not('.noresize').css('font-size','15px');
   jQuery('div').not('.noresize').not('[class^="progressBar"]').css('font-size','15px');
   jQuery('center').not('.noresize').css('font-size','15px');
   jQuery('p').not('.noresize').css('font-size','15px');
   jQuery('li').not('.noresize').css('font-size','15px');
   jQuery('td').not('.noresize').css('font-size','15px');
   jQuery('th').not('.noresize').css('font-size','15px');
};

function fontsize18() { 
   jQuery('span').not('.noresize').css('font-size','18px');
   jQuery('div').not('.noresize').not('[class^="progressBar"]').css('font-size','18px');
   jQuery('center').not('.noresize').css('font-size','18px');
   jQuery('p').not('.noresize').css('font-size','18px');
   jQuery('li').not('.noresize').css('font-size','18px');
   jQuery('td').not('.noresize').css('font-size','18px');
   jQuery('th').not('.noresize').css('font-size','18px');
};

function fontsize() { // set to current size based on cookie
   var currcookie = jQuery.cookie('fontsize');
   if (currcookie == 'normal') {
      fontsize12();
   } else if (currcookie == 'larger') {
      fontsize15();
   } else if (currcookie == 'largest') {
      fontsize18();
   }
}

function fontresize() { // change to new font size based on current size
   var currcookie = jQuery.cookie('fontsize');
   if ((currcookie==null) || (currcookie == 'normal')) {
      fontsize15();
      jQuery.cookie('fontsize', 'larger', { expires: 1 });
   } else if (currcookie == 'larger') {
      fontsize18();
      jQuery.cookie('fontsize', 'largest', { expires: 1 });
   } else if (currcookie == 'largest') {
      fontsize12();
      jQuery.cookie('fontsize', 'normal', { expires: 1 });
   }
};

function workshowhide(name) {
  if (document.getElementById(name).style.display=='none') {
    document.getElementById(name).style.display='block';
    document.getElementById(name+"more").style.display='none';
    document.getElementById(name+"less").style.display='block';
  } else {
    document.getElementById(name).style.display='none';
    document.getElementById(name+"more").style.display='block';
    document.getElementById(name+"less").style.display='none';
  }
}

function alertfirstvisit(){

   //Pop up for Beta Site 5763
   //Alert message once script- By JavaScript Kit
   //Credit notice must stay intact for use
   //Visit http://javascriptkit.com for this script
   var firstvisit = jQuery.cookie("firstvisit");

   //specify message to alert

   if (firstvisit != "no"){
	var alertmessage="Welcome to the VCN. This is a beta site still undergoing testing and we would appreciate your input. Please provide feedback on your experience by clicking the \"Tell us what you think\" button at the bottom of this page.";
	alert(alertmessage);
	jQuery.cookie("firstvisit", "no");
   }
}

// This function is used to popup an window to display external links.  This will eventually 
// supercede the popit function currently used. The PHP function, vcn_build_external_link_opener, should be
// called to autogenerate code to build the AHREF that uses this function.
function openExternalSite(anchorElement) {
    var url = anchorElement.href;
    if (url.length > 0) {
        window.open(url, "externallinkwindow", "height=480, width=640, toolbar=0, resizable=1, scrollbars=1, menubar=1, status=0");
    }
}

function expandContract(obj, self) {
	if (obj.style.display == 'none') {
		obj.style.display='inline';
		self.innerHTML = 'Less';
	} else {
		obj.style.display='none';
		self.innerHTML = 'More';
	}
}

// jquery document ready
jQuery(document).ready(function() {
    
    // as soon as we stop supporting IE8 we can get rid of this conditional
    if (jQuery("html").hasClass("isIE8")) {
      fontsize();
      jQuery('#zoompage').click(function() {
        fontresize();
      });
    } else {
      zoomEntirePage();  
      jQuery('#zoompage').click(function() {
        zoomEntirePage(true);
      });
    }
    
    // this onclick event handler is used for opening external links in a small popup window
    jQuery('.extlink').click(function(event) { 
        event.preventDefault(); 
        openExternalSite(event.currentTarget); 
    });
    
    jQuery("#edit-search-block-form--2").click(function(){ 
	    jQuery("#edit-search-block-form--2").animate({
		    width: "120px"
		  }, 100 );		  
    }); 
     
    jQuery("#edit-search-block-form--2").focus(function(){ 
	    jQuery("#edit-search-block-form--2").animate({
		    width: "120px"
		  }, 100 );		  
    }); 
    
    jQuery("#edit-search-block-form--2").blur(function(){ 
	    jQuery("#edit-search-block-form--2").animate({
		    width: "60px"
		  }, 100 );		  
    });
    
    //$('#trigger').bind('click', function() { $('#test').animate({"width": "100px"}, "slow"); })
    
});

jQuery(document).ready(function(){  // Side bar toggle click event  
	jQuery(".vcn-sidebar-tools-box h3").click(function () {		
		jQuery(this).next('div').is(':visible') ? jQuery(this).addClass('open-tools-box').removeClass('close-tools-box') : jQuery(this).addClass('close-tools-box').removeClass('open-tools-box');
		jQuery(this).next('div').slideToggle('fast');				
    });
	jQuery('.vcn-sidebar-tools-box h3').trigger("click");
});

//function to check required field and display error.
function check_required_field(fieldid, errormsg) {
	var validated = false; 
	var field_id = '#'+fieldid;
    var val = jQuery(field_id).val();
    
   	if (val == "") {    		
   		field_error_display(fieldid, errormsg);
    }else {	
    	validated = true;    	
    	field_error_hide(fieldid);
    }
	
    return validated; 
}
//function to display error and highlight specific field.
function field_error_display(fieldid, errormsg) {
	var field_id = '#'+fieldid;
	jQuery(field_id).addClass('error');
	custom_error_display(errormsg);
	
	return true;
}
//function to hide error and red border for specific field.
function field_error_hide(fieldid) {
	var field_id = '#'+fieldid;
	jQuery(field_id).removeClass('error');	
	custom_error_hide();
	return true;
}
//function to display custom error message.
function custom_error_display(errormsg) {
	jQuery('.alert').css("display","none");
	//jQuery('#main-wrapper').prepend('<div class="alert alert-error">'+errormsg+'</div>'); // IE does display issue with one line.
	
	jQuery('#main-wrapper').prepend('<div class="alert alert-error"></div>');
	jQuery('.alert').html(errormsg);
	
	jQuery('html, body').animate({
	    scrollTop: jQuery("body").offset().top
	}, 500);
	//jQuery(window).scrollTop(0);	
	
	return true;
}
//function to hide custom error message.
function custom_error_hide() {	
	jQuery('.alert').css("display","none");
	return true;
}

//returns the base url of the site
function vcn_get_site_base_url() {
	return Drupal.settings.drupal_basepaths.site_baseurl;
}

//returns the base path for Drupal7
function vcn_get_drupal7_base_path() {
	return Drupal.settings.drupal_basepaths.drupal7_basepath;
}

//returns the base path for Drupal6
function vcn_get_drupal6_base_path() {
	return Drupal.settings.drupal_basepaths.drupal6_basepath;
}

//returns the base path for all the images. Need to make sure images are stored in logical folder under sites/all/themes/vcnstark/images/
function vcn_get_images_basepath() {
	return Drupal.settings.drupal_basepaths.drupal7_images_basepath;
}

function vcn_get_videos_basepath() {
	return Drupal.settings.drupal_basepaths.drupal7_videos_basepath;
}

function vcn_get_rest_datatable_base_path() {
	return Drupal.settings.drupal_basepaths.drupal7_basepath + '/rest-datatable/';
}

function vcn_moodle_base_path() {
        return Drupal.settings.drupal_basepaths.moodle_basepath;
}

function vcn_get_industry_id() {
	return Drupal.settings.industry.industry_id;
}

function vcn_get_industry_name() {
	return Drupal.settings.industry.industry_name;
}

function vcn_textbox_allow_only_numbers(event) {
	var isnumeric = false;
	if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
         // Allow: Ctrl+A
        (event.keyCode == 65 && event.ctrlKey === true) || (event.keyCode == 67 && event.ctrlKey === true) || (event.keyCode == 86 && event.ctrlKey === true) || 
         // Allow: home, end, left, right
        (event.keyCode >= 35 && event.keyCode <= 39)) {
             // let it happen, don't do anything
		isnumeric = true;
    }
    else {
        // Ensure that it is a number and stop the keypress
        if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
        	isnumeric = false;
        } else {
        	isnumeric = true;
        }
    }
	return isnumeric;
	
}

function vcn_textbox_allow_only_alphabets(event) {
	//http://www.cambiaresearch.com/articles/15/javascript-char-codes-key-codes
	var isaphabet = false;
	if ((event.keyCode > 64 && event.keyCode < 91) || 
    	//Allow spacebar, backspace, delete, tab, escape and enter
    		((event.keyCode == 32 || event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13) || 
    		//Allow: home, end, left, right
    			(event.keyCode >= 35 && event.keyCode <= 39))) {
    	isaphabet = true;
    } else {
    	isaphabet = false;
    }
    return isaphabet;
}

// Tool-kit footer page code selection functions
function fnSelect(objId) {
	fnDeSelect();
	if (document.selection) {
	var range = document.body.createTextRange();
	        range.moveToElementText(document.getElementById(objId));
	range.select();
	}
	else if (window.getSelection) {
	var range = document.createRange();
	range.selectNode(document.getElementById(objId));
	window.getSelection().addRange(range);
	}
}
	
function fnDeSelect() {
	if (document.selection) document.selection.empty(); 
	else if (window.getSelection)
            window.getSelection().removeAllRanges();
}

function vcn_change_tab(tabkey, tabkey_list) {

	var tabkeys = tabkey_list.split(',');	
	for(var i=0; i<tabkeys.length; i++) { 
		
		var tabid = "#tab"+tabkeys[i];
		var divid = "#i"+tabkeys[i]+"middle";
		var divleftid = "#i"+tabkeys[i]+"left";	
		var divrightid = "#i"+tabkeys[i]+"right";	
		var divlink = "#i"+tabkeys[i]+"link";
		var divlinkinner = "#i"+tabkeys[i]+"linkinner";
		
		if(tabkey == tabkeys[i]) {			
			jQuery(tabid).show();			
			
                        jQuery(divleftid).removeClass('vcn-tabs-off-left').addClass('vcn-tabs-on-left');
                        jQuery(divid).removeClass('vcn-tabs-off').addClass('vcn-tabs-on');
                        jQuery(divrightid).removeClass('vcn-tabs-off-right').addClass('vcn-tabs-on-right');	
			
			var divlinkinner_html = jQuery(divlinkinner).html();
			divlinkinner_html = '<div id="i'+tabkeys[i]+'linkinner">'+divlinkinner_html+'</div>';
			jQuery(divlink).html(divlinkinner_html);			
			
		}else {			
			jQuery(tabid).hide();
			
                        jQuery(divleftid).removeClass('vcn-tabs-on-left').addClass('vcn-tabs-off-left');
			jQuery(divid).removeClass('vcn-tabs-on').addClass('vcn-tabs-off');
                        jQuery(divrightid).removeClass('vcn-tabs-on-right').addClass('vcn-tabs-off-right');	
			
			var divlinkinner_html = jQuery(divlinkinner).html();
			divlinkinner_html = '<div id="i'+tabkeys[i]+'linkinner" onmouseout="vcn_tabs_mouseover(\''+tabkeys[i]+'\',\'off\');"	onmouseover="vcn_tabs_mouseover(\''+tabkeys[i]+'\',\'on\');">'+divlinkinner_html+'</div>';
			jQuery(divlink).html(divlinkinner_html);
			
		}
	}
}

function vcn_tabs_mouseover(tabkey, tabevent) {
	
	var divid = "#i"+tabkey+"middle";
	var divleftid = "#i"+tabkey+"left";	
	var divrightid = "#i"+tabkey+"right";	
	
	if(tabevent == 'on') {
                jQuery(divleftid).removeClass('vcn-tabs-off-left').addClass('vcn-tabs-on-left');
		jQuery(divid).removeClass('vcn-tabs-off').addClass('vcn-tabs-on');
                jQuery(divrightid).removeClass('vcn-tabs-off-right').addClass('vcn-tabs-on-right');		
	} else {
                jQuery(divleftid).removeClass('vcn-tabs-on-left').addClass('vcn-tabs-off-left');
		jQuery(divid).removeClass('vcn-tabs-on').addClass('vcn-tabs-off');
                jQuery(divrightid).removeClass('vcn-tabs-on-right').addClass('vcn-tabs-off-right');	
	}
	
}

function vcn_monkeyPatchAutocomplete() {
	//http://stackoverflow.com/questions/2435964/jqueryui-how-can-i-custom-format-the-autocomplete-plug-in-results

    // Don't really need to save the old fn, 
    // but I could chain if I wanted to
    var oldFn = jQuery.ui.autocomplete.prototype._renderItem;

    jQuery.ui.autocomplete.prototype._renderItem = function( ul, item) {
        var re = new RegExp(this.term, 'i');
        var t = item.label.replace(re,'<span class="autocomplete-highlight-text">' + this.term + '</span>');
        return jQuery('<li></li>')
            .data('item.autocomplete', item)
            .append('<a>' + t + '</a>')
            .appendTo(ul);
    };
}

function vcn_show_hide_debug_div(indexId) {
  if (jQuery('#debuglink'+indexId).text().indexOf('show') !== -1) {
    jQuery('#debugdiv'+indexId).css('height', 'auto');
    jQuery('#debuglink'+indexId).text('[hide details]');
  } else {
    jQuery('#debugdiv'+indexId).css('height', '40px');
    jQuery('#debuglink'+indexId).text('[show details]');
  }
}

function vcn_telephone_number_format(text, need_brackets) {
	if (text == null || text == '') {
		return 'N/A';
	}
	if (need_brackets) {
		text = text.replace(/(\d{3})(\d{3})(\d{4})/, "($1) $2-$3");
	} else {
		text = text.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
	}
	
    return text;
}

//used to update the value of the vcnuser_onetcode session variable when user changes their target career
function update_vcnuser_onetcode_session(onetcode) {
	var drupal7_basepath = vcn_get_drupal7_base_path();
	jQuery.ajax({
	    url: drupal7_basepath + 'vcnuser-onetcode/update/' + onetcode
	});
	
}

function saveUserCareer(onetcode, isUserLoggedIn, vcnUserId) {
	var drupal7_basepath = vcn_get_drupal7_base_path(); //Drupal.settings.drupal_basepaths.drupal7_basepath;	
	vcnSaveTarget(drupal7_basepath+"cma/ajax/save-target-notebook-item/save/career/"+onetcode+"/"+onetcode, "career", "save", vcnUserId, isUserLoggedIn, onetcode);	
}

function targetUserCareer(onetcode, isUserLoggedIn, vcnUserId) {	
	var drupal7_basepath = vcn_get_drupal7_base_path(); //Drupal.settings.drupal_basepaths.drupal7_basepath;	
	vcnSaveTarget(drupal7_basepath+"cma/ajax/save-target-notebook-item/target/career/"+onetcode+"/"+onetcode, "career", "target", vcnUserId, isUserLoggedIn, onetcode);	
}

//Save/Target Career/Program/Certification/Licenses/Courses; type=career/program/certification/license; task=save/target; vcnuserid=cma_id
function vcnSaveTarget(url, type, task, vcnUserId, isUserLoggedIn, onetcode) {
	
	var drupal7_basepath = vcn_get_drupal7_base_path(); //Drupal.settings.drupal_basepaths.drupal7_basepath;
	
	saveTargetOptions = new Object();
	saveTargetOptions.url = url;
	saveTargetOptions.type = type;
	saveTargetOptions.task = task;
	saveTargetOptions.vcnUserId = vcnUserId;
	saveTargetOptions.isUserLoggedIn = (isUserLoggedIn < 1 ? "" : 1);
	saveTargetOptions.onetcode = onetcode;
	
		
	if(vcnUserId > 0) { // If userid exists
		saveTargetOptions.isUserNewFlag = false;
		vcnSaveTargetToCMA(saveTargetOptions); // Call save/target to user CMA/wishlist function
	}else {	// If userid doesn't exists, AJAX call to get it and proceed further	 
	    jQuery.ajax({
	      url: drupal7_basepath+'user/getcmauserid/ajax', 
	      cache: false,
	      async: false,
	      dataType: "text",
	      success: function(data) {	  
	    	saveTargetOptions.vcnUserId = data;
	    	saveTargetOptions.isUserNewFlag = true;	    	
	    	vcnSaveTargetToCMA(saveTargetOptions); // Call save/target to user CMA/wishlist function
	      },
	      error: function(data) { 
	    	// Log to file
	      }
	    });	//End of AJAX call
	}  // End of else (If userid doesn't exists)	
	//return true; 
} // End of function

function vcnSaveTargetToCMA(saveTargetOptions) {
	
	var url = saveTargetOptions.url;
	var type = saveTargetOptions.type;	
	var vcnUserId = saveTargetOptions.vcnUserId;
	var isUserLoggedIn = saveTargetOptions.isUserLoggedIn;	
	
	if(type == "career" || type == "program" || type == "certification" || type == "license") {			
		vcnSaveTargetNotebookToCMA(saveTargetOptions);		
	}else if(type == "military_course" || type == 'professional_training_course' || type == 'national_exams_course') {
		vcnSaveCourseToCma(url, type, vcnUserId, isUserLoggedIn);
    }else{
    	// Log error to file
	}
	// return true; 
}

function vcnSaveTargetNotebookToCMA(saveTargetOptions) {	
	var drupal7_basepath = vcn_get_drupal7_base_path();
	
	var url = saveTargetOptions.url;
	var type = saveTargetOptions.type;
	var task = saveTargetOptions.task;	
	var isUserLoggedIn = saveTargetOptions.isUserLoggedIn;	
	
	var typeText = type.charAt(0).toUpperCase() + type.slice(1);	
	
	if(task == "save") { // Save Career/Program/Certification/Licenses	
		  // make ajax call to the following url to save Career/Program/Certification/Licenses...
	      jQuery.ajax({
	        url: url,
	        cache: false,
	        dataType: "xmlDocument",
	        success: function(data) {	

	        	  if(data == "targeted") { // if item is career, and first saved. It will be targeted. both buttons should be disable.	        		 
	        		  jQuery('.save-target-buttons').each(function() {  
        				jQuery(this).addClass("vcn-button-disable");      
        			  });
	        	  }else if(data == "saved"){
	        		  jQuery('.save-button').each(function() {  
	        			jQuery(this).addClass("vcn-button-disable");      
	        		  });
	        	  }
	        	  
	        	  jQuery('.vcn-button-disable').each(function() {  
	        		  //jQuery(this).prop( "onclick", null );	   .prop('disabled', true); 
	        		  jQuery(this).removeAttr( "onclick" );
	        		  //jQuery(this).attr( "disabled", "disabled" );
	        	  });
	        	
	       	      if (!isUserLoggedIn) {	
	       	          //displayNotLoggedInModal('Selected '+typeText+' saved temporarily in your Career Wishlist.', 'Save '+typeText);	       	          
	       	      } else {
	       	          //displaySimpleModal('Selected '+typeText+' is saved in your Career Wishlist.', 'career-wishlist', 'Save '+typeText);
	       	      }
                      // added /my-vcn to end of url to make sure career wishlist displays current user's info and not counselor's student if in session
	       	      window.location.href = drupal7_basepath+'cma/'+type+'s/my-vcn';
	        },
	        error: function(data) {
	          displaySimpleModal('There was an issue saving the '+typeText+'.  Please try again.', 'warning', 'Save '+typeText);
	        }
	      });	   	      	
	}else {	// Target Career/Program/Certification/Licenses				
		vcnTargetNotebookToCMA(saveTargetOptions);			
	}
	
}

function vcnTargetNotebookToCMA(saveTargetOptions) {
	
	var drupal7_basepath = vcn_get_drupal7_base_path(); //Drupal.settings.drupal_basepaths.drupal7_basepath;
	
	var type = saveTargetOptions.type;	
	var vcnUserId = saveTargetOptions.vcnUserId;	
	var onetcode = saveTargetOptions.onetcode;	
	
	var typeText = type.charAt(0).toUpperCase() + type.slice(1);	
	
	if(type == "career") { // Target Career
		vcnTargetNotbookItem(saveTargetOptions);
	}else {	// Target Program/Certification/Licenses	
		// make ajax call to get saved career list, count and targeted career onet and title
		jQuery.ajax({
			url: drupal7_basepath+'get-cma-saved-careers/ajax/'+vcnUserId, 
			cache: false,
			//dataType: "json",
			success: function(data) {  		          
				
		        var saved_onet = jQuery.parseJSON(data);  		        
	        	// check if current onet is same as targeted or not. If not ask user to approve change.
		   		if(saved_onet.targeted_onet == onetcode || saved_onet.targeted_onet == "" || saved_onet.targeted_onet == 0){ 
		   			// Target Program/Certification/Licenses				    			 		   			
		   			vcnTargetNotbookItem(saveTargetOptions);
		   		}else{				    			 		   		
		   			// Ask user to approve change of targeted career.
		   			saveTargetOptions.targeted_onet_title = saved_onet.targeted_onet_title;
		   			targetedOnetChangeApprovalModal(saveTargetOptions);
		   		}	
		   		
			 }, 
			error: function(data) {
				//jQuery('div').dialog("close"); // To close processing dialog modal.
				displaySimpleModal('There was an issue targeting the ' + typeText +'. Please try again.', 'warning', 'Target ' + typeText);
			},
			async: false
		});	
	}		
}


function targetedOnetChangeApprovalModal(saveTargetOptions){  
 
  var type = saveTargetOptions.type;  
  var targeted_onet_title = saveTargetOptions.targeted_onet_title;	
	
  var typeText = type.charAt(0).toUpperCase() + type.slice(1);  
 
  var currentonettitle =  jQuery("#current-career-title").html();	 
  var msg = 'The career associated with the selected ' + typeText + ' is not your currently targeted career, <strong>' + targeted_onet_title +'</strong>. <br/><br/>Click OK to change your targeted career or CANCEL to continue browsing ' + typeText + 's.';

  if(currentonettitle != null  && currentonettitle != "") {
	  msg = 'The career, <strong>'+ currentonettitle +'</strong>, associated with the selected ' + typeText + ' is not your currently targeted career, <strong>' + targeted_onet_title +'</strong>. <br/><br/>Click OK to change your targeted career or CANCEL to continue browsing ' + typeText + 's.';
  }
  
  var title = 'Target ' + typeText;

  var modalDiv = jQuery(document.createElement('div')); 

  var imgHtml = '';	 
  imgHtml = '<img src="' + vcn_get_images_basepath() + 'buttons/error-icon.png" width="42" alt="warning icon" id="simple-modal-icon-warning">';

  
  jQuery(modalDiv).html('<p>' + imgHtml + msg + '</p>');
  
  jQuery(modalDiv).dialog({
    resizable: false,
    title: title,
    modal: true,
    buttons: {
      "Ok": {
        //"class": "vcn-button",
        id: "btn-ok",
        text: "Ok",
        click: function() { 
          jQuery(this).dialog( "close" );
          vcnTargetNotbookItem(saveTargetOptions);        
        }
      },
      "Cancel": {
        //"class": "vcn-button",
        text: "Cancel",
        click: function() { 
          jQuery(this).dialog( "close" );	
         //jQuery('div').dialog("close"); // To close processing dialog modal.      
        }
      }
    },
    open: function(event, ui) {
      //jQuery(":button:contains('Ok')").focus();      
      jQuery("#btn-ok").focus();
	  
	  jQuery('.ui-dialog').each(function() {  
		jQuery(this).addClass("vcn-dialog");      
	  });
	 
	  if (window.PIE) {
	  	jQuery('.ui-button').each(function() {	    			
	          PIE.attach(this);		            
	      });
	  }        
    },
  	close: function() {        
      jQuery('.ui-dialog').each(function() {  
  		jQuery(this).removeClass("vcn-dialog");      
  	  });
    }
  });
	
}

function vcnTargetNotbookItem(saveTargetOptions) {	
	var url = saveTargetOptions.url;
	var type = saveTargetOptions.type;		
	var isUserLoggedIn = saveTargetOptions.isUserLoggedIn;	
	
	var typeText = type.charAt(0).toUpperCase() + type.slice(1);
	
	// make ajax call to the following url to Target Career/Program/Certification/Licenses...
    jQuery.ajax({
      url: url,
      cache: false,
      dataType: "xmlDocument",
      success: function(data) {  
    	  
	    	  if(data == "targeted") { // if item is career, and first saved. It will be targeted. both buttons should be disable.	        		 
	    		  jQuery('.save-target-buttons').each(function() {  
					jQuery(this).addClass("vcn-button-disable");      
				  });
	    	  }
	    	  
	    	  jQuery('.vcn-button-disable').each(function() {  
	    		  //jQuery(this).prop( "onclick", null );
	    		  jQuery(this).removeAttr( "onclick" );
	    		  //jQuery(this).attr( "disabled", "disabled" );
        	  });
    	      	  
     	      if (!isUserLoggedIn) {	
     	          //displayNotLoggedInModal('Selected '+typeText+' targeted temporarily in your Career Wishlist.', 'Target '+typeText);     	    	 
     	      } else {
     	          //displaySimpleModal('Selected '+typeText+' is targeted in your Career Wishlist.', 'career-wishlist', 'Target '+typeText);     	    	  
     	      }	
     	      window.location.href = window.location.href;
      },
      error: function(data) {
        displaySimpleModal('There was an issue Targeting the '+typeText+'.  Please try again.', 'warning', 'Target '+typeText);
      }
    });		
}

function vcnSaveCourseToCma(url, type, vcnUserId, isUserLoggedIn) {	
    jQuery.ajax({
	      url: url, 
	      cache: false,
	      async: false,
	      //dataType: "xmlDocument",
	      success: function(data) {
	    	  var result = jQuery.parseJSON(data);  	
	    	  if(result.result == true) {        		 
	    		  jQuery('.save-target-buttons').each(function() {  
					jQuery(this).addClass("vcn-button-disable");      
				  });
	    		  jQuery('.vcn-button-disable').each(function() {  		    		 
		    		  jQuery(this).removeAttr( "id" );	
		    		  //jQuery(this).removeAttr( "onclick" );
		    		  jQuery(this).unbind('click');
	        	  });
	    	  }    	  
	    	  
	    	  var typeText = "";
	    	  if (type == 'national_exams_course') {
    			  typeText = 'National Exam Course';
    		  } else if (type == 'professional_training_course') {
    			  typeText = 'Professional Training Course';
    		  } else if (type == 'military_course') {
    			  typeText = 'Military Course';
    		  }
	    	  if (isUserLoggedIn < 1) {
	    		  displayNotLoggedInModal('Selected ' + typeText +' is added to your <strong>Learning Inventory</strong>.<br/><br/>', 'Save ' + typeText);
	    	  } else {
	    		  displaySimpleModal('Selected ' + typeText +' is added to your <strong>Learning Inventory</strong>.', 'info', 'Save ' + typeText);
	    	  }

	      },
	      error: function(data) { 
	    	// Log to file
	      }
    });	//End of AJAX call    
   //return true; 
}

//search value update, if starts with ".".
(function($) {$(function() {		
	$('#search-form').submit(function() {
		var searchval = $('#edit-keys').val();	
		var n=searchval.indexOf(".");		
		if(n == 0) {
			var searchnewval = "'"+$('#edit-keys').val()+"'";		
			$('#edit-keys').val(searchnewval);		
		}		  	
	    return true; 	  
	});	
	$('#search-block-form').submit(function() {	
		var searchval = $('#edit-search-block-form--2').val();	
		var n=searchval.indexOf(".");		
		if(n == 0) {
			var searchnewval = "'"+$('#edit-search-block-form--2').val()+"'";		
			$('#edit-search-block-form--2').val(searchnewval);		
		}		  	
	    return true;		  
	});
  });
})(jQuery);

function displayNotLoggedInModal(msg, title) {
	var drupal7_basepath = vcn_get_drupal7_base_path(); //Drupal.settings.drupal_basepaths.drupal7_basepath;	
	var modalDiv = jQuery(document.createElement('div'));
  
	var button = '<div onclick="location.href=\''+drupal7_basepath+'cma/careers\';" class="vcn-button header-buttons header-buttons-small-text noresize">Career Wishlist</div>';
  //var button = '<a href="javascript:void(0);" title="Career Wishlist"><div onclick="location.href=\''+drupal7_basepath+'cma/careers\';" class="vcn-button header-buttons header-buttons-small-text noresize">Career Wishlist</div></a>';
  //var button = '<a href="'+drupal7_basepath+'cma/careers" title="Career Wishlist"><div class="vcn-button header-buttons header-buttons-small-text noresize">Career Wishlist</div></a>';
  if (title.indexOf("Course") > 0) {
    button = '<img src="'+vcn_get_images_basepath()+'tab_images/pla_mli.png" title="Save" alt="Save Course" width="45" height="45"/>';
  }

  jQuery(modalDiv).html('<div id="not-logged-in-modal-img">' + button + '</div>' +
                        '<div id="not-logged-in-modal-txt">' + msg + 
                        '  To make it permanent, please login if you have an account, ' +
                        '  or register to create a new account. Otherwise click continue.' +
                        '</div>' +
                        '<div class="allclear"></div>');
  
  jQuery(modalDiv).dialog({
    resizable: false,
    title: title,
    modal: true,
    buttons: {
      "Register": { 
    	//"class": "vcn-button",
    	id:"btn-register",
        text: "Register",
        click: function() {
          jQuery(this).dialog( "close" );
          location = drupal7_basepath + 'user/register';
        }
      },
      "Login": {
    	//"class": "vcn-button",
    	id:"btn-login",
        text: "Login",
        click: function() {
          jQuery(this).dialog( "close" );
          location = drupal7_basepath + 'user';
        }
      },
      "Continue": {
    	//"class": "vcn-button",
    	id:"btn-continue",
        text: "Continue",
        click: function() {
          jQuery(this).dialog( "close" );
        }
      }
    },
    open: function(event, ui) {
      //jQuery(":button:contains('Continue')").focus();
      //jQuery("#btn-continue").focus();
      //jQuery(".ui-button").addClass("vcn-button");
      
      //jQuery("#btn-register").blur();
      //jQuery("#btn-login").blur();
      //jQuery("#btn-continue").focus();
    	
    	jQuery(this).closest('.ui-dialog').find('.ui-dialog-buttonpane button:eq(2)').focus(); 
    	jQuery(this).closest('.ui-dialog').find('.ui-dialog-buttonpane button:eq(0)').blur(); 
    	jQuery(this).closest('.ui-dialog').find('.ui-dialog-buttonpane button:eq(1)').blur();
	  
	  jQuery('.ui-dialog').each(function() {  
		jQuery(this).addClass("vcn-dialog");      
	  });
	 
      if (window.PIE) {
    	jQuery('.ui-button').each(function() {	    			
            PIE.attach(this);		            
        });
      }
      
    },
	close: function() {        
      jQuery('.ui-dialog').each(function() {  
		jQuery(this).removeClass("vcn-dialog");      
	  });
    }
  });
  
}

function displaySimpleModal(msg, type, title) {
  var drupal7_basepath = vcn_get_drupal7_base_path(); //Drupal.settings.drupal_basepaths.drupal7_basepath;	
  var modalDiv = jQuery(document.createElement('div')); 

  var imgHtml = '';
  if (type == 'info') {
    imgHtml = '<img src="' + vcn_get_images_basepath() + 'buttons/info-icon.png" width="42" alt="info icon" id="simple-modal-icon-info">';	 
  } else if (type == 'warning') {
    imgHtml = '<img src="' + vcn_get_images_basepath() + 'buttons/error-icon.png" width="42" alt="warning icon" id="simple-modal-icon-warning">';
  }else if (type == 'career-wishlist') {
	imgHtml = '<div id="not-logged-in-modal-img">' +
			  '  <div onclick="location.href=\''+drupal7_basepath+'cma/careers\';" class="vcn-button header-buttons header-buttons-small-text noresize">Career Wishlist</div>' +
			  '</div>';
	/*
	 imgHtml = '<div id="not-logged-in-modal-img">' +
			  '  <a href="javascript:void(0);" title="Career Wishlist"><div onclick="location.href=\''+drupal7_basepath+'cma/careers\';" class="vcn-button header-buttons header-buttons-small-text noresize">Career Wishlist</div></a>' +
			  '</div>';
	   
	 imgHtml = '<div id="not-logged-in-modal-img">' +
			  '  <a href="'+drupal7_basepath+'cma/careers" title="Career Wishlist"><div class="vcn-button header-buttons header-buttons-small-text noresize">Career Wishlist</div></a>' +
			  '</div>';
	 */
  }
  
  if (title.indexOf("Course") > 0) {
	  imgHtml = '<img id="simple-modal-icon-info" src="'+vcn_get_images_basepath()+'tab_images/pla_mli.png" title="Save" alt="Save Course" width="45" height="45"/>';
  }
  
  jQuery(modalDiv).html('<p>' + imgHtml + msg + '</p>');
  
  jQuery(modalDiv).dialog({
    resizable: false,
    title: title,
    modal: true,
    buttons: {
      "Ok": {
        //"class": "vcn-button",
        id: "btn-ok",
        text: "Ok",
        click: function() {
          jQuery(this).dialog("close");
        }
      }
    },
    open: function(event, ui) {
      //jQuery(":button:contains('Ok')").focus();
      //jQuery("#btn-ok").focus();
      //jQuery(".ui-button").addClass("vcn-button");
      
      jQuery("#btn-ok").focus();
	  
	  jQuery('.ui-dialog').each(function() {  
		jQuery(this).addClass("vcn-dialog");      
	  });
	 
	  if (window.PIE) {
	  	jQuery('.ui-button').each(function() {	    			
	          PIE.attach(this);		            
	      });
	  }        
    },
  	close: function() {        
      /*jQuery('.ui-dialog').each(function() {  
  		jQuery(this).removeClass("vcn-dialog");      
  	  });*/
    }
  });
}

//displayProcessingModal('Processing ' + task + ' ' + type + '.', 'info', 'Please wait...');
function displayProcessingModal(msg, type, title) {
	
  var modalDiv = jQuery(document.createElement('div')); 

  var imgHtml = '';
  if (type == 'info') {
    imgHtml = '<img src="' + vcn_get_images_basepath() + 'buttons/info-icon.png" width="42" alt="info icon" id="simple-modal-icon-info">';
  } else if (type == 'warning') {
    imgHtml = '<img src="' + vcn_get_images_basepath() + 'buttons/error-icon.png" width="42" alt="warning icon" id="simple-modal-icon-warning">';
  }
  
  jQuery(modalDiv).html('<p>' + imgHtml + msg + '</p>');
  
  jQuery(modalDiv).dialog({
    resizable: false,
    title: title,
    modal: true
  });
}

// 'Tell us What you think' button call function 

function tellUsWhatYouThink(url,theemail,theid,datetime) {
	
  jQuery("#tell-us-dialog-form").html("<form action='javascript:return false;' method='post'>" +
  										"<table width='500' cellpadding='3' cellspacing='0'>" +
  										"<tr><td>Email:</td><td><label for='emailcom'>" +
  										"<input type='text' name='emailcom' value='"+theemail+"' id='emailcom' />" +
  										"</label></td></tr>" +
  										"<tr><td width='75'>Subject:</td><td><label for='subject'>" +
  										"<input type='text' name='subject' id='subject' />" +
  										"</label></td>" +
  										"<tr><td width='75'>Comment:</td><td><label for='comment'>" +
  										"<textarea rows='10' cols='50' name='comment' id='comment'></textarea>" +
  										"</label></td></tr>" +
  										"<tr><td width='75'></td><td align='right'></td></tr>" +
  										"</table>" +
  										"<input type='hidden' name='url' id='url' value='"+url+"' />" +
  										"<label for='bycom'>" +
  										"<input type='hidden' name='bycom' id='bycom' value='"+theid+"' /></label>" +
  										"<label for='datetime'><input type='hidden' name='datetime' id='datetime' value='"+datetime+"' />" +
  										"</label>" +
  										"<div style='display:none;' id='loadherec'></div>" +
  										"</form>");
	  
  jQuery("#tell-us-dialog-form").dialog({
    resizable: false,
	title: 'Enter comment',
	height: 380,
	width: 550,
	modal: true,
	//dialogClass: "vcn-dialog",
	buttons: {
          "Cancel": {
            //"class": "vcn-button",
            text: "Cancel",
            click: function() {
              jQuery(this).dialog( "close" );
            }
          },
	  "Submit": {
            //"class": "vcn-button",
            id: "btn-submit",
            text: "Submit",
            click: function() {   	  
              var url = jQuery("#url").val();
              var email = jQuery("#emailcom").val();
              var id = jQuery("#bycom").val();
              var datetime = jQuery("#datetime").val();
              var subject = jQuery("#subject").val();
              var comment = jQuery("#comment").val();

              comment = comment.replace(/ /g, "~");
              subject = subject.replace(/ /g, "~");

              if (!comment) {
                displaySimpleModal('Please enter a comment.', 'warning', 'Enter comment');
              } else {           
                // make ajax call to the following url ...
                jQuery.ajax({
                  url: vcn_get_drupal7_base_path()+'tell-us-comment/ajax?url='+url+'&email='+email+'&subject='+subject+'&comment='+comment+'&by='+id+'&datetime='+datetime,
                  cache: false,
                  dataType: "html",
                  success: function(data) {
                    //alert(data);
                    displaySimpleModal('Comment submitted.', 'info', 'Enter comment');
                  },
                  error: function(data) {
                    displaySimpleModal('There was an issue with comment.  Please try again.', 'warning', 'Enter comment');
                  }
                });

                jQuery(this).dialog( "close" );

              }	        	
            }
          }
	},
	open: function(event, ui) {
	  //jQuery(":button:contains('Submit')").focus();
	  jQuery("#emailcom").focus();
	  
	  jQuery('.ui-dialog').each(function() {  
		jQuery(this).addClass("vcn-dialog");      
	  });
	 
      if (window.PIE) {
    	jQuery('.ui-button').each(function() {	    			
            PIE.attach(this);		            
        });
      }  	
	},
	close: function() {        
      jQuery('.ui-dialog').each(function() {  
		jQuery(this).removeClass("vcn-dialog");      
	  });
    }
  });	  
}

// End of 'Tell us What you think' button call function

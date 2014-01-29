/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


jQuery(document).ready(function(){

	/* Required courses */
	//This line clones the row inside the '.row' class and transforms it to plain html.
	 //var entTestClonedRow = jQuery('.entrance-tests-row').clone().html();
	 var reqCourseClonedRow = '<div><label><input type="text" class="coursename" name="coursename[]" value=""></label></div><div><label><input type="text" name="coursedesc[]" value=""></label></div><div><label><input type="text" name="courselevel[]" value=""></label></div><div><label><input type="text" name="coursemingpa[]" value=""></label></div><div><input type="button" title="Delete" class="req-courses-delete-this-row vcn-button" value="Delete" /><input type="hidden" name="courseid[]" value=""></div>';
	  
	 //This line wraps the reqCourseClonedRow and wraps it <tr> tags since cloning ignores those tags
	 var reqCourseAppendRow = '<div class = "required-courses-row allclear">' + reqCourseClonedRow + '</div>';  
	      
	 jQuery('#req-courses-btn-add-more').click(function(){
	  //this line get's the last row and appends the reqCourseAppendRow when it finds the correct row.
	        jQuery('.required-courses-form div.required-courses-row:last').after(reqCourseAppendRow);
	    });
	 
	 //when you click on the button called "delete", the function inside will be triggered.
	 jQuery('.req-courses-delete-this-row').live('click',function(){
	     var rowLength = jQuery('.required-courses-row').length;
	         //this line makes sure that we don't ever run out of rows.
	      if(rowLength > 1){
	   deleteRow(this);
	  }else{
	   //jQuery('.required-courses-form div.required-courses-row:last').after(reqCourseAppendRow);
	   deleteRow(this);
	  }
	 });
	
  /* Entrance Tests */
  
 //This line clones the row inside the '.row' class and transforms it to plain html.
 //var entTestClonedRow = jQuery('.entrance-tests-row').clone().html();

 var entTestClonedRow = '<div><label><input type="text" class="testname" name="testname[]" value=""></label></div><div><label><input type="text" name="testdesc[]" value=""></label></div><div><label><input type="text" name="testminscore[]" value=""></label></div><div><input type="button" title="Delete" class="ent-tests-delete-this-row vcn-button" value="Delete" /><input type="hidden" name="testid[]" value=""></div>';
  
 //This line wraps the entTestClonedRow and wraps it <tr> tags since cloning ignores those tags
 var entTestAppendRow = '<div class = "entrance-tests-row allclear">' + entTestClonedRow + '</div>';  
      
 jQuery('#ent-tests-btn-add-more').click(function(){
  //this line get's the last row and appends the entTestAppendRow when it finds the correct row.
        jQuery('.entrance-tests-form div.entrance-tests-row:last').after(entTestAppendRow);
    });
 
 //when you click on the button called "delete", the function inside will be triggered.
 jQuery('.ent-tests-delete-this-row').live('click',function(){
     var rowLength = jQuery('.entrance-tests-row').length;
         //this line makes sure that we don't ever run out of rows.
      if(rowLength > 1){
   deleteRow(this);
  }else{
   //jQuery('.entrance-tests-form div.entrance-tests-row:last').after(entTestAppendRow);
   deleteRow(this);
  }
 });
 
 
 var currCourseClonedRow = '<div><label><input type="text" class="currcoursename" name="currcoursename[]" value=""></label></div><div><label><input type="text" name="currcoursedesc[]" value=""></label></div><div><label><input type="text" name="currcourseduration[]" value=""></label></div><div><label><input type="text" name="currcoursetotalcredits[]" value=""></label></div><div><input type="button" title="Delete" class="curr-courses-delete-this-row vcn-button" value="Delete" /><input type="hidden" name="currcourseid[]" value=""></div>';
 var currCourseAppendRow = '<div class = "curriculum-courses-row allclear">' + currCourseClonedRow + '</div>';
 jQuery('#curr-courses-btn-add-more').click(function(){
	  //this line get's the last row and appends the currCourseAppendRow when it finds the correct row.
	        jQuery('.curriculum-courses-form div.curriculum-courses-row:last').after(currCourseAppendRow);
	    });
 
//when you click on the button called "delete", the function inside will be triggered.
 jQuery('.curr-courses-delete-this-row').live('click',function(){
	deleteRow(this);
 });
 
 
   
 function deleteRow(currentNode){
  jQuery(currentNode).parent().parent().remove();
 }
 
 // Program delete button
 jQuery('#delete-program').click(function() {
		jQuery('#program_task').val("delete");
		jQuery('#vcnprovider-program-form').submit();
 });

});

(function($) {$(function() {
	$('#vcnprovider-form').submit(function() {		
	   var validated = true;
	   var testvalidated = true;
	   var coursevalidated = true;		   
	   var errormsg = "Please provide valid data as per following instructions:";
	  // var errorcount = 0;

	  if($('#edit-provider-logo').val() != "") {
		  var logoname = $('#edit-provider-logo').val();
		  var logoext = logoname.split(".");
		  var files = ["png", "jpg", "jpeg", "gif"];
		  var ext = jQuery.inArray(logoext[logoext.length-1], files);
		  //var ext = files.indexOf();
		  if(ext < 0) {
			  validated = false;
			  errormsg = errormsg + "<ul><li>Only files with the following extensions are allowed: png, jpg, gif.</li></ul>";
			  $('#edit-provider-logo').addClass('error');
		  }else {
			  $('#edit-provider-logo').removeClass('error');	
		  }
	  }

	  
	   if($('#edit-provider-name').val() == "")	{
		   validated = false;
		   errormsg = errormsg + "<ul><li>Please enter a Provider Name.</li></ul>";
		   $('#edit-provider-name').addClass('error');	
	   }else {
		   $('#edit-provider-name').removeClass('error');	
	   }

	   if(isNaN($('#edit-provider-phone').val()))	{
		   validated = false;		   
		   errormsg = errormsg + "<ul><li>Phone number must contain a valid numeric values only.</li></ul>";
		   $('#edit-provider-phone').addClass('error');	
	   }else {
		   $('#edit-provider-phone').removeClass('error');	
	   }	   
	   
	   $('.testname').each(function(){
		  
	   		if($(this).val() == "") { 
		   		validated = false;  
		   		testvalidated = false; 
	   			$(this).addClass('error');	   			
	   		}else {
	   			$(this).removeClass('error');	   			
	   		}		   
		   
		});
	   if (testvalidated == false){
		   errormsg = errormsg + "<ul><li>Please enter a Test Name for School Entrance Tests</li></ul>";
	   }	

	   $('.coursename').each(function(){
		   
	   		if($(this).val() == "") { 
		   		validated = false; 
		   		coursevalidated = false;	
	   			$(this).addClass('error');	   			
	   		}else {
	   			$(this).removeClass('error');	   			
	   		}		   
		   
		});
	   if (coursevalidated == false){
		   errormsg = errormsg + "<ul><li>Please enter a Course Name for School Prerequisite Courses for Admission</li></ul>";
	   }		 
	   
	   if(validated == false){		  	
		   custom_error_display(errormsg);	    
	   }else{
		   custom_error_hide();
	   }   	
	   
	    return validated;   	
	  
	});
}); })(jQuery);


(function($) {$(function() {
	$('#vcnprovider-program-form').submit(function() {	
	
	   var validated = true;
	   var p_task = jQuery('#program_task').val(); 
	   if(p_task != "delete") {
	   
		   var testvalidated = true;
		   var coursevalidated = true;		   
		   var errormsg = "Please provide valid data as per following instructions:";
		   
		   if($('#edit-program-name').val() == "")	{
			   validated = false;
			   errormsg = errormsg + "<ul><li>Please enter a Program Name.</li></ul>";
			   $('#edit-program-name').addClass('error');	
		   }else {
			   $('#edit-program-name').removeClass('error');	
		   }
	
		   if(isNaN($('#edit-program-contact-phone').val()))	{
			   validated = false;		   
			   errormsg = errormsg + "<ul><li>Phone number must contain a valid numeric values only.</li></ul>";
			   $('#edit-program-contact-phone').addClass('error');	
		   }else {
			   $('#edit-program-contact-phone').removeClass('error');	
		   }	   
		   
		   $('.testname').each(function(){
			  
		   		if($(this).val() == "") { 
			   		validated = false;  
			   		testvalidated = false; 
		   			$(this).addClass('error');	   			
		   		}else {
		   			$(this).removeClass('error');	   			
		   		}		   
			   
			});
		   if (testvalidated == false){
			   errormsg = errormsg + "<ul><li>Please enter a Test Name for School Entrance Tests</li></ul>";
		   }	
	
		   $('.coursename').each(function(){
			   
		   		if($(this).val() == "") { 
			   		validated = false; 
			   		coursevalidated = false;	
		   			$(this).addClass('error');	   			
		   		}else {
		   			$(this).removeClass('error');	   			
		   		}		   
			   
			});
		   if (coursevalidated == false){
			   errormsg = errormsg + "<ul><li>Please enter a Course Name for School Prerequisite Courses for Admission</li></ul>";
		   }		 
		   
		   if(validated == false){		  	
			   custom_error_display(errormsg);	    
		   }else{
			   custom_error_hide();
		   }   	
		   
	   }
	   
	    return validated;   	
	  
	});
}); })(jQuery);


/*
jQuery('#add-new-program').click(function() {
	//location.href = jQuery(this).closest("a").attr("href");
});

jQuery('#programs-list').click(function() {
	
});*/

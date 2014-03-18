/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


/*
 * vcnDeleteNotebookItemFromCMA(cmaOptions);
 * 
 * cmaOptions = new Object();
	cmaOptions.dtCurrentRow = dtCurrentRow;
	cmaOptions.itemId = itemId;				
	cmaOptions.itemType = 'career'; //career/program/certification/license
	cmaOptions.itemTypeText = 'Career';	
 * 
 */

function vcnDeleteNotebookItemFromCMA(cmaOptions) {
	
	var itemTypeList = [ "career", "program", "certification", "license" ];
	
	if(cmaOptions.itemId == '' || jQuery.inArray(cmaOptions.itemType, itemTypeList) < 0) { 
		alert('Invalid properties of cmaOptions object.'); return false;
	}
		
	var drupal7_basepath = vcn_get_drupal7_base_path(); //Drupal.settings.drupal_basepaths.drupal7_basepath;
	isUserLoggedIn = Drupal.settings.vcncma.isUserLoggedIn;
	userid = Drupal.settings.vcncma.userid;	
	
	dtCurrentRow = ( cmaOptions.dtCurrentRow ? cmaOptions.dtCurrentRow : 0 );
	itemNotebookId = cmaOptions.itemNotebookId;	
	itemType = cmaOptions.itemType;
	itemTypeText = cmaOptions.itemTypeText;	
	itemTypeTextLowerCase = itemTypeText.toLowerCase();		
	
	//  drupal7_basepath + cma/ajax/remove-notebook-item/%/%/  (item_type = career/program/certification/license, itemNotebookId)
	cmaUrl = drupal7_basepath+'cma/ajax/remove-notebook-item/'+itemType+'/'+itemNotebookId; 			
	
	var modalDiv = jQuery(document.createElement('div')); 
	var title = 'Remove '+itemTypeText;
	var imgHtml = '<img src="' + vcn_get_images_basepath() + 'buttons/info-icon.png" width="42" alt="info icon" id="simple-modal-icon-info">';
	var msg = 'Are you sure you want to remove selected '+itemTypeTextLowerCase+' from your Career Wishlist?';
	
	jQuery(modalDiv).html('<p>' + imgHtml + msg + '</p>');
  	jQuery(modalDiv).dialog({
	    resizable: false,
	    title: title,
	    modal: true,
	    buttons: {
	      "Ok": {
	        //"class": "vcn-button",
	        id: "btn-ok",
	        text: "Yes",
	        click: function() { 
	        	jQuery(this).dialog( "close" ); 				        	
	        	
	        	if(itemType == "career") { // Decrease career item count. and if only two remaining enable targeted career delete button.
	        		jQuery("#cma-careers-count").val(jQuery("#cma-careers-count").val()-1);
	        		if(jQuery("#cma-careers-count").val() == 2){
	        			jQuery(".vcn-button-disable").removeClass("vcn-button-disable");
		        	}		        	
	        	}
	        	
	        	if(dtCurrentRow != 0){ // Delete item listing from data table grid.
	        		oTable.fnDeleteRow(dtCurrentRow); 
	        	}											
				
			   jQuery.ajax({
			      url: cmaUrl, 
			      cache: false,
			      //async: false,
			      dataType: "text",
			      success: function(data) {	//alert(data);	//Career NOT Removed from Career Management Account	//Career Removed from Career Management Account			    	  
			    	  if (!isUserLoggedIn) {	
				          //displayNotLoggedInModal('Career Saved temporarily in your wish list.', 'Save Career');
			    		  //displaySimpleModal('Selected '+itemTypeTextLowerCase+' is removed from your wish list.', 'info', 'Remove '+itemTypeTextLowerCase);
				      } else {
				          //displaySimpleModal('Selected '+itemTypeTextLowerCase+' is removed from Career Management Account.', 'info', 'Remove '+itemTypeTextLowerCase);
				      }					    	  
			      },
			      error: function(data) { 
			    	// Log to file
			      }
			    });	//End of AJAX call			          
	        }
	      },
	      "Cancel": {
	        //"class": "vcn-button",
	        text: "No",
	        click: function() { 
	          jQuery(this).dialog( "close" );					              
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

(function($) {
  Drupal.behaviors.vcncma = {
    attach: function(context, settings) {
      
      var drupal7_basepath = vcn_get_drupal7_base_path();
      
      if ($('#vcn-cma-counselor-users-dropdown')) {
        $('#vcn-cma-counselor-users-dropdown').change(function() {
          $.ajax({
            url: drupal7_basepath+'set-cma-counselor-user-id/ajax/'+$('#vcn-cma-counselor-users-dropdown').val(), 
            cache: false,
            dataType: "text",
            success: function(data) {					    	  
              location = location.href.replace('/my-vcn', '');
            },
            error: function(data) { 
              alert("There was an issue attempting to change to the selected user's MyVCN Account. Please try again.");
            }
          });
        });
      }

    }
  }
})(jQuery);

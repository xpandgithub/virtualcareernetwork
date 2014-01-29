/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


(function($) {
  Drupal.behaviors.vcn_findwork_results = {
    attach: function(context, settings) {

      $('#provider-programs-table').dataTable({
        "sPaginationType": "full_numbers",
        "bFilter": false,
        "bInfo": false,
        "bLengthChange": false,
        "aaSorting": [[ 3, "asc" ]],
        "bAutoWidth": false,
        "aoColumns": [{"sWidth": "25%"}, {"swidth": "38%"}, {"sWidth": "12%"}, {"sWidth": "20%"}, {"sWidth": "5%", "bSortable": false}],
        "bRetrieve": true,
	"bDestroy": true,
        "fnDrawCallback": function(oSettings) {
          if (oSettings._iDisplayLength >= oSettings.fnRecordsDisplay()) {
             jQuery(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
          }
        }
      });

      $('#provider-programs-table').show();
    }
  };

})(jQuery);

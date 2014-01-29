/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


jQuery(document).ready(function() {
	change_current_towards_options(document.getElementById('edu_towards_select').value);
});

function change_current_towards_options(value) {
	
	for (var i=0; i<=7; i++)
		if (document.getElementById('edu_towards_select'+i))
			jQuery('#edu_towards_select'+i).attr("disabled", false);	

	for (var i=0; i<=document.getElementById('edu_current_select').value; i++) 
		if (document.getElementById('edu_towards_select'+i) && document.getElementById('edu_towards_select'+i).value.indexOf)
			jQuery('#edu_towards_select'+(i-1)).attr("disabled", true);

	document.getElementById('edu_towards_select').value = value;

}

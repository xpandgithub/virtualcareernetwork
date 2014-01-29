<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

function vcn_pla_military_credit_details($ace_id, $start_date, $end_date) {
	$page_from = empty($page_from) ? 'pla_grid' : 'mylearning_inventory';
	return theme('vcn_pla_military_credit_details', array('ace_id' => $ace_id, 'start_date' => $start_date, 'end_date' => $end_date));
}


function vcn_pla_military_credit_details_with_additional_params($ace_id, $start_date, $end_date, $branch = NULL, $search_term = NULL) {
	return theme('vcn_pla_military_credit_details', array('ace_id' => $ace_id, 'start_date' => $start_date, 'end_date' => $end_date, 'branch' => $branch, 'search_term' => $search_term));
}


function vcn_pla_military_credit_details_from_mylearning_inventory($ace_id, $start_date, $end_date) {
	return theme('vcn_pla_military_credit_details', array('ace_id' => $ace_id, 'start_date' => $start_date, 'end_date' => $end_date, 'page_from' => 'mylearning_inventory'));
}
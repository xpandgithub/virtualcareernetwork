<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php
/*
 * A function to pass the invoke the VCNRest class and return the data
 * purpose of this function is to keep the calling to the REST API in a central location
 * We do not need to invoke the VCNRest and set the parameters on every single page, instead we need to call this function
 * 
 */

//used to previously have function vcn_rest_wrapper($service, $module, $action, $params, $format='xml', $method='post', $cache_data=true)
//now moved to vcngeneral.module since it is used by most of the pages in VCN
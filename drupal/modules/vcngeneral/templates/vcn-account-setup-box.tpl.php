<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<div id="under-search" class="noresize">
<?php if (!$isLoggedInUser): ?>
	<div id="cac-cma" class="fhcsearch  rndcrnr noresize align-center">
	<?php if($on_explorecareers_page): ?>
		<div class="vcn-signup-careerexplorers-additional-note vcn-signup-block-bold-red-colored">Don't lose all the work you have done to Choose a Career!</div>
	<?php endif; ?>
		<div style="font-size:14px; margin-bottom:10px">Get your free personal VCN Career Management Account</div>
		<a class="strong font-size-20" href="<?php echo $vcn_drupal7_base_path; ?>user/register">Sign Up for MyVCN</a><br/>
		<div class="ital font-size-13">Get more out of your VCN experience!</div>
		<p>
			<span class="vcn-industry-color font-bold">MyVCN</span> lets you save all your education, training and work related information in one place.<br/>
			<span class="strong">Your selections will be saved for the next time you visit VCN.</span>
		</p>
			<div class="noresize">			  
			  <div class="overflowhidden">
			  	<div class="vcn-signup-moreless"><a href="javascript:void(0);" onclick="expandContract(document.getElementById('cmamoretext'), this);">More</a></div>
			  </div>
			  <div id="cmamoretext" style="display:none;" class="noresize">			           		
					<p class="clearfloat"><span class="vcn-industry-color font-bold">MyVCN</span> is private and secure. Any information you provide will be kept secure, encrypted and confidential. <span class="strong">Only you control it.</span></p>
		   		<p>If you choose you can also share selected information in your VCN Account with others such as career counselor.</p>
		   		<p><strong><a href="<?php echo $vcn_drupal7_base_path; ?>user/register">You can sign up Right now!</a></strong><br/><span style="font-style:italic">All it takes is an email and password.</span></p>
		 		</div>
		 </div>
   </div> 
<?php endif; //ending if (!$isLoggedInUser) ?>
</div>
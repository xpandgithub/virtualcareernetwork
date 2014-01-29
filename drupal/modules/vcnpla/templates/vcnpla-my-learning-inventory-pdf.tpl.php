<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<html>
	<head>
		<style>
			body { font-family:Verdana; font-size:9pt; }			
			.logo { width:50%; }
			.title { width:50%; font-weight:bold; font-size:16pt; text-align:right; vertical-align:middle; }
			.usersname { font-weight:bold; font-size:14pt; }
			.usertable { width:350px; }
			.userhead { width:100px; font-weight:bold; vertical-align:top; }
			.userdata { width:200px; }
			.coursetable { width:100%; border-spacing:5px; }
			.coursehead { color:#67b2bf; width:50%; font-weight:bold; font-size:12pt; display:table-cell; vertical-align:bottom; }
			.creditshead { width:50%; font-weight:bold; text-align:right; vertical-align:bottom; }
			.coursedata { width:85%; }
			.creditsdata { width:15%; font-weight:bold; text-align:right; vertical-align:top; padding-right:25px; }
		</style>
	</head>
	<body>
	   <?php if($addendum < 1) {?>
		<div>
			<table  style="width: 100%; padding-top:-15px;">
				<tr>
					<td class="logo"><img src="<?php echo $logo;?>"></td>
					<td class="title">My Learning Inventory</td>
				</tr>
				<tr>
					<td colspan="2"><br /> <br /></td>
				</tr>
			</table>
			<table style="width: 100%;">
				<?php if (strlen($name)) { ?>
				<tr>
					<td colspan="2" class="usersname"><?php echo $name;?></td>
				</tr>
				<?php } ?>
				<?php if (strlen($email) || strlen($targetCareer) || strlen($targetProgramName)) { ?>	
				<tr>
					<td colspan="2"><br /></td>
				</tr>			
				<tr>
          <?php if (strlen($email)) { ?>
            <td style="width: 50%;">	
              <table border="0" class="usertable">
                <tr>
                  <td class="userhead">Email Address:</td>
                  <td class="userdata"><?php echo $email; ?></td>
                </tr>
              </table>				
            </td>	
          <?php } ?>
					<td style="width: 50%;">		
						<table border="0" class="usertable">
						<?php if (strlen($targetCareer)) { ?>
							<tr>
								<td class="userhead">Target Career:</td>
								<td class="userdata"><?php echo $targetCareer;?></td>
							</tr>
						 <?php } 
						 if (strlen($targetProgramName)) { ?>		
							<tr>
								<td class="userhead">Target Program:</td>
								<td class="userdata"><?php echo $targetProgramName;?><br /><?php echo $targetProgramAward;?><br /><?php echo $targetProgramSchool;?></td>
							</tr>
						 <?php } ?>		
						</table>
				 	</td>
				</tr>
				<?php } ?>
				<tr>
					<td colspan="2"><br /> <br /></td>
				</tr>					
			</table>
			
			<?php echo $college_courses_html;?>
			<?php echo $military_training_html;?>	
			<?php echo $professional_training_html;?>	
			<?php echo $national_exams_html;?>	
			<?php echo $vcn_courses_html;?>		
		</div>
		<?php }else {?>
		<div>
			<?php echo $all_course_details_html;?>
		</div>
		<?php } ?>
		<br/><br/>
	</body>
</html>
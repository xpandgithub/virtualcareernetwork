<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php
if (!$is_user_provider) :
?>

  <div id="provider-summary-front-container">
    <div id="provider-summary-front-image">
      <img src="<?php print $vcn_image_path; ?>miscellaneous/provider_homepage.jpg" alt="button navigation">
    </div>
    <div id="provider-summary-front-heading">VCN Provider Portal</div>
    <div id="provider-summary-front-text" class="noresize">
      <p class="noresize">  
        The "Provider Portal" is your window into how job seekers and prospective students will 
        view your institution of higher learning....from here you can update and edit your 
        school's profile as well as the information about the instructional programs you offer. 
        This is your "dashboard" to control your presence on VCN!
      </p>
    </div>
    <div id="provider-summary-front-signin-container">
      <a href="<?php print vcn_drupal7_base_path(); ?>user?type=provider" alt="Sign in" title="Sign in" style="text-decoration:none; color:white;">
        <div id="provider-summary-front-signin-button">
          <p id="provider-summary-front-signin-text">
            <img src="<?php print $vcn_image_path; ?>miscellaneous/lock-icon.png" style="height:28px; width:auto; position:relative; top:5px;" alt="Sign in lock"/> &nbsp;Sign In
          </p>
        </div>
      </a>
    </div>
    <div id="provider-summary-front-register-container">
      <a href="<?php print vcn_drupal7_base_path(); ?>provider-register" alt="Register" title="Register" style="text-decoration:none; color:white;">
        <div id="provider-summary-front-register-button">
          <p id="provider-summary-front-register-text">Register</p>
        </div>
      </a>
    </div>
  </div>
  
<?php  
else:
?>
  
  <?php
  if (isset($user_schools_array) && count($user_schools_array) > 1) :
  ?>
      <label for="listofschools" id="listofschoolslabel">Select a School:</label> <select id="listofschools" onchange="location='<?php print $vcn_base_path; ?>provider/summary/unitid/'+this.value;">
      <?php 
      foreach ($user_schools_array as $school) :
        $isSelected = ($school_id == $school['id']) ? 'selected="selected"' : '';
      ?>
        <option value="<?php print $school['id']; ?>" <?php print $isSelected; ?>><?php print $school['name']; ?></option>
      <?php
      endforeach;
      ?>
    </select>
    <br/><br/>
  <?php
  endif;
  ?>

  <div id="provider-summary-div">
    <div>
      <div id="provider-summary-school-name-heading-cap">
        <img src="<?php print $vcn_image_path; ?>miscellaneous/school_default_logo_small.png" alt="Provider Profile">
      </div>
      <div id="provider-summary-school-name-div">
        <strong id="provider-summary-school-name">
          <?php print $school_name; ?>
        </strong>
        <p>
        <?php print $school_address; ?>
        </p>
      </div>
    </div>

    <div>
      <div id="provider-summary-school-profile-outer-div">	
        <h3 class="provider-summary-div-heading">School Profile</h3>

        <div id="provider-summary-school-profile-inner-div">

          <table class="provider-summary-table school-profile">
            <tr>
              <th class="school-profile">
                Type of School:
              </th>
              <td class="even first school-profile">
                <?php print $school_type; ?>
              </td>
            </tr>
            <tr>
              <th class="school-profile">
                Percent applicants admitted:
              </th>
              <td class="odd school-profile">
                <?php print $percent_applicants_admitted; ?>
              </td>
            </tr>
            <tr>
              <th class="school-profile">
                Total Students: 
              </th>
              <td class="even school-profile">
                <?php print $total_students; ?>
              </td>
            </tr>
            <tr>
              <th class="school-profile">
                Total Undergrads: 
              </th>
              <td class="odd school-profile">
                <?php print $total_undergrads; ?>
              </td>
            </tr>
            <tr>
              <th class="school-profile">
                1st-time Degree-seeking Freshmen: 
              </th>
              <td class="even school-profile">
                <?php print $degree_seeking_freshmen; ?>
              </td>
            </tr>
            <tr>
              <th class="last school-profile">
                Graduate Enrollment: 
              </th>
              <td class="odd school-profile">
                <?php print $graduate_enrollment; ?>
              </td>
            </tr>
          </table>  
        </div> 
        <br>
        <div class="provider-summary-button-div">
          <input type="button" value=" Edit Profile " title="Edit Profile" class="vcn-button" onclick="location.href='<?php print $vcn_base_path . 'provider/profile/' . $school_id; ?>';"/>
        </div>
      </div>

      <div id="provider-summary-programs-outer-div">
        <h3 class="provider-summary-div-heading">Programs</h3>
        <div id="provider-summary-programs-inner-div">

          <table class="provider-summary-table programs <?php print $programs_table_additional_class; ?>">
            <tr>
              <th class="school-programs">
                Programs
              </th>
              <th class="school-programs">
                Award Level
              </th>
            </tr>
            <?php 
            if (count($programs_array) > 0) :
              $i = 0;
              foreach ($programs_array as $program) :
                $rowClass = ($i % 2 == 1) ? 'odd' : 'even';
                $rowClass .= ' school-programs';
                $rowClass .= ($i == count($programs_array)-1) ? ' last' : '';
            ?>
                <tr class="<?php print $rowClass; ?>">
                  <td>
                    <?php print $program['name']; ?>
                  </td>
                  <td class="awardlevel">
                    <?php print $program['awardlevel']; ?>
                  </td>
                </tr>
            <?php
                $i++;
              endforeach;
            else :
            ?>
              <tr class="even school-programs last">
                <td colspan="2">
                  No programs found
                </td>
              </tr>
            <?php  
            endif;
            ?>
          </table>

        </div> 
        <br>
        <div class="provider-summary-button-div">
          <input type="button" value=" Edit Programs " title="Edit Programs" class="vcn-button" onclick="location.href='<?php print $vcn_base_path . 'provider/programs/unitid/' . $school_id; ?>';" onkeypress="location.href='<?php print $vcn_base_path . 'provider/programs/unitid/' . $school_id; ?>';"/>
        </div>
      </div>	

    </div>

  </div>
<?php
endif;
?>
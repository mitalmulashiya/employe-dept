<?php include("includes/config.php");
$page_title="Display Members";
$page="members";
$total_member=0;
$mem_array=array();
if(isset($_POST['doshow']) && $_POST['doshow']==1)
{
	if(isset($_POST['school_id']) && $_POST['school_id']!='')
	{
		// get members record for selected school 
		$member_record=$db->get_memberbyschool($_POST['school_id']);
		$total_member=$db->num_rows;
	}	
}
include("header.php");
?>
<div class="w3-container min-height-400">
<br><table width="100%" cellpadding="6" cellspacing="0">
<form name="frm1" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>">
<tr><td><select class="w3-select w3-border" name="school_id" required>
  <option value="" disabled selected>Choose your school</option>
  <?php
  // get school list from database
  $schools=$db->get_school_record();
	if($db->num_rows>0)
	foreach($schools as $row)
	{?>
  	 <option value="<?php echo $row['school_id'];?>" <?php echo (isset($_POST['school_id']) && $_POST['school_id']==$row['school_id'])?"selected":"";?>><?php echo $row['school_name'];?></option>
	<?php }?>
    </select></td>
    <td><input type="submit" class="w3-btn w3-teal" value="Show members" />
    <input type="hidden" value="1" name="doshow" /></td>
    </tr></form></table>
    <br>
<?php

if($total_member>0)
{	?>
<table class="w3-table w3-striped w3-border" width="100%">
<thead>
<tr class="w3-blue">
  <th>Member Name</th>
  <th>Member Email</th>
  <th>Selected School</th>
</tr>
</thead>
<?php 
// display result records
foreach($member_record as $row)
	{?>
<tr>
  <td><?php echo $row['name'];?></td>
  <td><?php echo $row['email'];?></td>
  <td><?php echo $row['school_name'];?></td>
</tr>
<?php }?>
</table>
<br>
<?php }
else{?>
<p class="w3-container w3-red">No members data found</p>	
<?php }?>
</div>
<?php include("footer.php");
?>
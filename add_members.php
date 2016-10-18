<?php require("includes/config.php");
$page_title="Add Member";
$page="add_members";
$msgerr=$msgsuccess='';
if(isset($_POST['dopost']) && $_POST['dopost']==1)
{
	//php validation code start
	$mname=trim($_POST['mname']);
	$memail=trim($_POST['memail']);
	if($mname=='' || $memail=='')
		$msgerr="Please enter username and email";
	elseif(!isset($_POST['mschool']))
		$msgerr="Please select atleast one school";
	// php validation code end
	else
	{
		//check for the duplicate email
		$member_exists=$db->get_member_record('email',$memail);
		if($db->num_rows>0)
			$msgerr="Email already exists";
		else
		{
			// add record entry to database
			$member_data=array("name"=>$mname,"email"=>$memail,"add_date"=>time());
			$res=$db->add_member($member_data,$_POST['mschool']);
			if($res)
			{
				//clear all variables
				unset($_POST,$memail,$mname);
				$msgsuccess="Member added successfuly";
			}
			else
				$msgerr="Error to add member. Try again";
		}
	}
	
}
include("header.php");?>
<div class="w3-container min-height-400" align="center">
<?php if($msgerr!='' || $msgsuccess!='') //display error or success message
{?>
<?php if($msgerr!=''){?>
		<p class="w3-container w3-red"><?php echo $msgerr;?></p>	
	<?php }else{?>
    	<p class="w3-container w3-blue"><?php echo $msgsuccess;?></p>	
	<?php }?>	
<?php }?>
<table cellpadding="6" cellspacing="0" width="100%">
<form name="frm1" method="post" action="add_members.php">
<tr> <td width="36%">Name: </td>
<td width="64%"><input type="text"  name="mname" id="mname" required  value="<?php echo isset($mname)?$mname:"";?>"/></td>
</tr>
<tr> <td>Email: </td>
<td>  <input type="email" name="memail" id="memail" required value="<?php echo isset($memail)?$memail:"";?>"></td>
</tr>
<tr> <td>Select One or more School: </td>
<td><select multiple="multiple" name="mschool[]" required>
<?php
 //get school list from database
$schools=$db->get_school_record();
if($db->num_rows>0)
	foreach($schools as $row)
	{?>
    <option value="<?php echo $row['school_id'];?>" <?php echo (isset($_POST['mschool']) && in_array($row['school_id'],$_POST['mschool']))?"selected":"";?>><?php echo $row['school_name'];?></option>
	<?php }
?>
</select><br />
<span class="w3-small">Press Ctrl key to select multiple school</span>
</td></tr>
<tr>
<td align="center" colspan="2">
<input type="submit" class="w3-btn w3-teal" value="Add member">
<input type="hidden" name="dopost" value="1" />
</td></tr>
</form>
</table>
</div>
<?php include("footer.php");
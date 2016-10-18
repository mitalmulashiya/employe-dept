<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<link rel="stylesheet" href="css/general.css">
<body>
<div class="w3-container" style="margin:auto; width:80%">
<header class="w3-container w3-teal">
  <h1 align="center">Pelicanconnect: <?php echo $page_title;?></h1>
</header>
 <ul class="w3-navbar w3-black">
    <li><a  <?php if($page=='add_members'){?>class="w3-light-grey"<?php }?> href="add_members.php">Add Member</a></li>
    <li><a <?php if($page=='members'){?>class="w3-light-grey"<?php }?> href="members_by_school.php">Members</a></li>
 </ul>

 

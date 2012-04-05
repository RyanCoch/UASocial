<h3 class="about"><?php echo $about; ?></h3>

<div class="leftblock">&nbsp;
<h3>Haircolor</h3>
<p><?php echo $haircolor; ?></p>

<h3>Body type</h3>
<p><?php echo $body_type; ?></p>

<h3>Tattoo or piercing</h3>
<p><?php echo $tattoo; ?></p>
</div>

<div class="rightblock">&nbsp;
<?php if (($dob_flag != 0)&&($dob != "0")) {
	echo "<h3>Age</h3>";
	echo "<p>$dob</p>";
}
?>

<?php if (($pob_flag != 0)&&($pob != "0")) {
	echo "<h3>Place of birth</h3>";
	echo "<p>$pob</p>";
}
?>

<?php if (($quad_flag != 0)&&($quad != "0")) {
	echo "<h3>Residence</h3>";
	echo "<p>$quad</p>";
}
?>

</div>

<hr />

<div class="leftblock">&nbsp;
<h3>Major</h3>
<p><?php echo $major; ?></p>

<h3>Languages</h3>
<p><?php echo $foreign; ?></p>
</div>

<div class="rightblock">&nbsp;
<h3>Life priorities</h3>
<p><?php echo $life_prior; ?></p>

<h3>Drinking, Smoking</h3>
<p><?php echo $alcohol; ?></p>
</div>

<hr />

<div class="leftblock">&nbsp;
<h3>Looking for</h3>
<p><strong><?php echo $look_for; ?></strong>
<?php if ($look_for != "No answer" && $look_age != "No answer") : ?>
 of age <strong><?php echo $look_age; ?></strong><br />
<?php endif;
if ($look_what) : ?>
for <strong><?php echo $look_what; ?></strong>
<?php endif; ?>
</p>

<?php if ($facebook != "" || $gplus != "" || $twitter != "") : ?>
<h3>Social links</h3>
<p>
	<?php if ($facebook) :?>
        <a href="http://www.facebook.com/<?php echo $facebook; ?>" target="_blank"><img src="images/fbook.png" width="19" height="19" border="0" /></a>
    <?php endif;
		if ($gplus) :?>    
        <a href="https://plus.google.com/<?php echo $gplus; ?>" target="_blank"><img src="images/gplus.png" width="20" height="19" border="0" /></a>
    <?php endif;
		if ($twitter) :?>    
        <a href="http://twitter.com/#!/<?php echo $twitter; ?>" target="_blank"><img src="images/twitter.png" width="20" height="20" border="0" /></a>
    <?php endif;?>

 </p>
<?php endif; ?>

</div>
<div class="rightblock">&nbsp;
<h3>Relationship Status</h3>
<p><?php echo $status; ?></p>

<?php if ($sorfer) : ?>
<h3>Fraternities/Sororities</h3>
<p><?php echo $sorfer; ?></p>
<?php endif; ?>
</div>

<div style="clear:both;">&nbsp;</div>
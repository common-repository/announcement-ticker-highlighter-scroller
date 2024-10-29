<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$did = isset($_GET['did']) ? intval($_GET['did']) : '0';
if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }

// First check if ID exist with requested ID
$sSql = $wpdb->prepare(
	"SELECT COUNT(*) AS `count` FROM ".WP_g_aths_TABLE."
	WHERE `g_aths_id` = %d",
	array($did)
);
$result = '0';
$result = $wpdb->get_var($sSql);

if ($result != '1')
{
	?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist.', 'announcement-ticker-highlighter-scroller'); ?></strong></p></div><?php
}
else
{
	$g_aths_errors = array();
	$g_aths_success = '';
	$g_aths_error_found = FALSE;
	
	$sSql = $wpdb->prepare("
		SELECT *
		FROM `".WP_g_aths_TABLE."`
		WHERE `g_aths_id` = %d
		LIMIT 1
		",
		array($did)
	);
	$data = array();
	$data = $wpdb->get_row($sSql, ARRAY_A);
	
	// Preset the form fields
	$form = array(
		'g_aths_text' => $data['g_aths_text'],
		'g_aths_status' => $data['g_aths_status'],
		'g_aths_order' => $data['g_aths_order']
	);
}
// Form submitted, check the data
if (isset($_POST['g_aths_form_submit']) && $_POST['g_aths_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('g_aths_form_edit');
	
	$form['g_aths_text'] = isset($_POST['g_aths_text']) ? wp_filter_post_kses($_POST['g_aths_text']) : '';
	if ($form['g_aths_text'] == '')
	{
		$g_aths_errors[] = __('Please enter the announcement.', 'announcement-ticker-highlighter-scroller');
		$g_aths_error_found = TRUE;
	}

	$form['g_aths_status'] = isset($_POST['g_aths_status']) ? sanitize_text_field($_POST['g_aths_status']) : '';
	if ($form['g_aths_status'] == '')
	{
		$g_aths_errors[] = __('Please select the display status.', 'announcement-ticker-highlighter-scroller');
		$g_aths_error_found = TRUE;
	}
	
	$form['g_aths_order'] = isset($_POST['g_aths_order']) ? intval($_POST['g_aths_order']) : '';
	if ($form['g_aths_order'] == '')
	{
		$g_aths_errors[] = __('Please enter the display order, only number.', 'announcement-ticker-highlighter-scroller');
		$g_aths_error_found = TRUE;
	}

	//	No errors found, we can add this Group to the table
	if ($g_aths_error_found == FALSE)
	{	
		$sSql = $wpdb->prepare(
				"UPDATE `".WP_g_aths_TABLE."`
				SET `g_aths_text` = %s,
				`g_aths_status` = %s,
				`g_aths_order` = %s
				WHERE g_aths_id = %d
				LIMIT 1",
				array($form['g_aths_text'], $form['g_aths_status'], $form['g_aths_order'], $did)
			);
		$wpdb->query($sSql);
		
		$g_aths_success = __('Details was successfully updated.', 'announcement-ticker-highlighter-scroller');
	}
}

if ($g_aths_error_found == TRUE && isset($g_aths_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $g_aths_errors[0]; ?></strong></p>
	</div>
	<?php
}
if ($g_aths_error_found == FALSE && strlen($g_aths_success) > 0)
{
	?>
	<div class="updated fade">
		<p><strong><?php echo $g_aths_success; ?> 
		<a href="<?php echo WP_g_aths_ADMIN_URL; ?>"><?php _e('Click here to view the details', 'announcement-ticker-highlighter-scroller'); ?></a></strong></p>
	</div>
	<?php
}
?>
<script language="JavaScript" src="<?php echo WP_g_aths_PLUGIN_URL; ?>/pages/noenter.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php _e('Announcement ticker highlighter scroller', 'announcement-ticker-highlighter-scroller'); ?></h2>
	<form name="g_aths_form" method="post" action="#" onsubmit="return g_aths_submit()"  >
      <h3><?php _e('Update details', 'announcement-ticker-highlighter-scroller'); ?></h3>
	  
	  <label for="tag-txt"><?php _e('Announcement', 'announcement-ticker-highlighter-scroller'); ?></label>
      <textarea name="g_aths_text" id="g_aths_text" cols="100" rows="6"><?php echo esc_html(stripslashes($form['g_aths_text'])); ?></textarea>
      <p><?php _e('Please enter your announcement text.', 'announcement-ticker-highlighter-scroller'); ?></p>
      
      <label for="tag-txt"><?php _e('Display status', 'announcement-ticker-highlighter-scroller'); ?></label>
      <select name="g_aths_status" id="g_aths_status">
        <option value=''>Select</option>
		<option value='YES' <?php if($form['g_aths_status']=='YES') { echo 'selected="selected"' ; } ?>>Yes</option>
        <option value='NO' <?php if($form['g_aths_status']=='NO') { echo 'selected="selected"' ; } ?>>No</option>
      </select>
      <p><?php _e('Do you want to show this announcement?', 'announcement-ticker-highlighter-scroller'); ?></p>
	  
	  <label for="tag-txt"><?php _e('Display order', 'announcement-ticker-highlighter-scroller'); ?></label>
	  <input name="g_aths_order" type="text" id="g_aths_order" value="<?php echo $form['g_aths_order']; ?>" maxlength="3" />
	  <p><?php _e('Please enter your display order.', 'announcement-ticker-highlighter-scroller'); ?></p>
	  
      <input name="g_aths_id" id="g_aths_id" type="hidden" value="">
      <input type="hidden" name="g_aths_form_submit" value="yes"/>
      <p style="padding-top:8px;padding-bottom:8px;">
        <input name="publish" lang="publish" class="button" value="<?php _e('Submit', 'announcement-ticker-highlighter-scroller'); ?>" type="submit" />
        <input name="publish" lang="publish" class="button" onclick="g_aths_redirect()" value="<?php _e('Cancel', 'announcement-ticker-highlighter-scroller'); ?>" type="button" />
        <input name="Help" lang="publish" class="button" onclick="g_aths_help()" value="<?php _e('Help', 'announcement-ticker-highlighter-scroller'); ?>" type="button" />
      </p>
	  <?php wp_nonce_field('g_aths_form_edit'); ?>
    </form>
</div>
<p class="description">
	<?php _e('Check official website for more information', 'announcement-ticker-highlighter-scroller'); ?>
	<a target="_blank" href="<?php echo WP_g_aths_FAV; ?>"><?php _e('click here', 'announcement-ticker-highlighter-scroller'); ?></a>
</p>
</div>
<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_g_aths_display']) && $_POST['frm_g_aths_display'] == 'yes')
{
	$did = isset($_GET['did']) ? intval($_GET['did']) : '0';
	if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }
	
	$g_aths_success = '';
	$g_aths_success_msg = FALSE;
	
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
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('g_aths_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".WP_g_aths_TABLE."`
					WHERE `g_aths_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$g_aths_success_msg = TRUE;
			$g_aths_success = __('Selected record was successfully deleted.', 'announcement-ticker-highlighter-scroller');
		}
	}
	
	if ($g_aths_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $g_aths_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php _e('Announcement ticker highlighter scroller', 'announcement-ticker-highlighter-scroller'); ?>
	<a class="add-new-h2" href="<?php echo WP_g_aths_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'announcement-ticker-highlighter-scroller'); ?></a></h2>
    <div class="tool-box">
	<?php
		$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
		$limit = 20;
		$offset = ($pagenum - 1) * $limit;
		$sSql = "SELECT COUNT(g_aths_id) AS count FROM ". WP_g_aths_TABLE;
		$total = 0;
		$total = $wpdb->get_var($sSql);
		$total = ceil( $total / $limit );
	
		$sSql = "SELECT * FROM `".WP_g_aths_TABLE."` order by g_aths_id desc LIMIT $offset, $limit";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<form name="frm_g_aths_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
			<th scope="col"><?php _e('Announcement', 'announcement-ticker-highlighter-scroller'); ?></th>
            <th scope="col"><?php _e('Order', 'announcement-ticker-highlighter-scroller'); ?></th>
			<th scope="col"><?php _e('Status', 'announcement-ticker-highlighter-scroller'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
			<th scope="col"><?php _e('Announcement', 'announcement-ticker-highlighter-scroller'); ?></th>
            <th scope="col"><?php _e('Order', 'announcement-ticker-highlighter-scroller'); ?></th>
			<th scope="col"><?php _e('Status', 'announcement-ticker-highlighter-scroller'); ?></th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			if(count($myData) > 0 )
			{
				foreach ($myData as $data)
				{
					?>
					<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
						<td><?php echo stripslashes($data['g_aths_text']); ?>
						<div class="row-actions">
							<span class="edit"><a title="Edit" href="<?php echo WP_g_aths_ADMIN_URL; ?>&amp;ac=edit&amp;did=<?php echo $data['g_aths_id']; ?>"><?php _e('Edit', 'announcement-ticker-highlighter-scroller'); ?></a> | </span>
							<span class="trash"><a onClick="javascript:g_aths_delete('<?php echo $data['g_aths_id']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'announcement-ticker-highlighter-scroller'); ?></a></span> 
						</div>
						</td>
						<td><?php echo esc_html(stripslashes($data['g_aths_order'])); ?></td>
						<td><?php echo esc_html(stripslashes($data['g_aths_status'])); ?></td>
					</tr>
					<?php 
					$i = $i+1; 
				} 
			}
			else
			{ 
				?><tr><td colspan="3" align="center"><?php _e('No records available.', 'announcement-ticker-highlighter-scroller'); ?></td></tr><?php 
			} 
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('g_aths_form_show'); ?>
		<input type="hidden" name="frm_g_aths_display" value="yes"/>
		<?php
		  $page_links = paginate_links( array(
				'base' => add_query_arg( 'pagenum', '%#%' ),
				'format' => '',
				'prev_text' => __( ' &lt;&lt; ' ),
				'next_text' => __( ' &gt;&gt; ' ),
				'total' => $total,
				'show_all' => False,
				'current' => $pagenum
			) );
		 ?>	
      </form>	
	<div class="tablenav bottom">
		<div class="tablenav-pages">
			<span class="pagination-links"><?php echo $page_links; ?></span>
		</div>
		<div>
			<a href="<?php echo WP_g_aths_ADMIN_URL; ?>&amp;ac=add"><input class="button action" type="button" value="<?php _e('Add New', 'announcement-ticker-highlighter-scroller'); ?>" /></a>
			<a href="<?php echo WP_g_aths_ADMIN_URL; ?>&amp;ac=set"><input class="button action" type="button" value="<?php _e('Setting', 'announcement-ticker-highlighter-scroller'); ?>" /></a>
			<a target="_blank" href="<?php echo WP_g_aths_FAV; ?>"><input class="button action" type="button" value="<?php _e('Help', 'announcement-ticker-highlighter-scroller'); ?>" /></a>
		</div>	
		<h3><?php _e('Plugin configuration option', 'announcement-ticker-highlighter-scroller'); ?></h3>
			<ol>
			<li><?php _e('Add directly in to the theme using PHP code.', 'announcement-ticker-highlighter-scroller'); ?></li>
			<li><?php _e('Drag and drop the widget to your sidebar.', 'announcement-ticker-highlighter-scroller'); ?></li>
			</ol>
		<p class="description">
			<?php _e('Check official website for more information', 'announcement-ticker-highlighter-scroller'); ?>
			<a target="_blank" href="<?php echo WP_g_aths_FAV; ?>"><?php _e('click here', 'announcement-ticker-highlighter-scroller'); ?></a>
		</p>
	</div>
	</div>
</div>
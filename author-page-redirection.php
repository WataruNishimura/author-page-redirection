<?php

/**
 * Plugin Name:     Author Page Custom Redirection
 * Description:     Redirect to 404 page when author page is disabled.
 * Author:          WATARU NISHIMURA
 * Author URI:      https://kraftsman.jp
 * Text Domain:     author-page-redirection
 * Version:         0.1.0
 *
 * @package         Author_Page_Redirection
 */


function apr_display_user_settings($user)
{
?>
  <h3>ライター画面管理</h3>
  <table class="form-table">
    <tr>
      <th><label for="display_author_page">ライター画面の表示</label></th>
      <td>
        <?php $selected = get_the_author_meta('display_author_page', $user->ID); ?>
        <select name="display_author_page" id="display_author_page">
          <option value="yes" <?php echo ($selected == "yes") ?  'selected="selected"' : '' ?>>表示する</option>
          <option value="" <?php echo ($selected != "yes") ?  'selected="selected"' : '' ?>>表示しない</option>
        </select>
      </td>
    </tr>
  </table>
<?php
}

function apr_save_user_settings($user_id) {
  if(!current_user_can("edit_user", $user_id)) {
    return;
  }

  update_user_meta($user_id, "display_author_page", $_POST["display_author_page"]);
}

function apr_redirect_not_found($query) {
  if(is_author()) {
    $is_display = get_the_author_meta("display_author", get_query_var("author"));
    if($is_display != "yes" ) { 
      $query->set_404();
      status_header( 404 );
      return;
      exit;
    }
  }
} 

add_action("pre_get_posts", "apr_redirect_not_found");

add_action("edit_user_profile", "apr_display_user_settings");
add_action("show_user_profile", "apr_display_user_settings");

add_action('profile_update', 'apr_save_user_settings');
add_action('user_register', 'apr_save_user_settings'); 

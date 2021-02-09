<div>
  
  <h2>My Plugin Page Title</h2>
  <form method="post" action="options.php">
  <?php settings_fields( 'myplugin_options_group' ); ?>
  <h3>This is my option</h3>
  <p>Some text here.</p>
  <?php
  $options = get_option( 'sandbox_theme_input_examples' );
//   $checkbox = '<input type="checkbox" id="checkbox_example" name="sandbox_theme_input_examples[checkbox_example]" value="1"' . checked( 1, $options['checkbox_example'], false ) . '/>';
  ?>
  <table>
  <tr valign="top">
  <th scope="row">
  <label for="checkbox_example">This is an example of a checkbox</label>
  </th>
  <td>
  <!-- <input type="checkbox" name="demo-checkbox" value="1" <?php checked(1, get_option('demo-checkbox'), true); ?> /> -->
  <?php //echo $checkbox; ?>
  <!-- <input type="checkbox" id="checkbox_example" name="sandbox_theme_input_examples[checkbox_example]" value="1" <?php echo checked( 1, $options['checkbox_example'], false );?>/> -->
  <!-- <input type="checkbox" id="myplugin_option_name" name="myplugin_option_name" value="<?php echo get_option('myplugin_option_name'); ?>" <?php echo checked( 1, get_option('myplugin_option_name'), false );?> /> -->
  </td>
  <td><input type="text" id="checkbox_example" name="sandbox_theme_input_examples[checkbox_example]" value="1"' . checked( 1, $options['checkbox_example'], false ) . '/></td>
  </tr>
  </table>
  <?php  submit_button(); ?>
  </form>
  </div>
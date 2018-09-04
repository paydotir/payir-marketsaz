<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons">
    <a onclick="$('#form').submit();" class="button">
      <span class="button_left button_save"></span>
      <span class="button_middle"><?php echo $button_save; ?></span>
      <span class="button_right"></span>
    </a>
    <a onclick="location='<?php echo $cancel; ?>';" class="button">
      <span class="button_left button_cancel"></span>
      <span class="button_middle"><?php echo $button_cancel; ?></span>
      <span class="button_right"></span>
    </a>
  </div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form id="form" name="form" method="post" action="<?php echo $action; ?>" enctype="multipart/form-data">
  <div id="tab_general" class="page">
    <table class="form">
      <tr>
        <td width="25%"><span class="required">*</span> <?php echo $entry_api; ?></td>
        <td><input type="text" name="payir_api" value="<?php echo $payir_api; ?>" />
          <br />
          <?php if ($error_api) { ?>
          <span class="error"><?php echo $error_api; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td width="25%"><span class="required">*</span> <?php echo $entry_send; ?></td>
        <td><input type="text" name="payir_send" value="<?php echo $payir_send; ?>" style="direction:ltr;" />
          <br />
          <?php if ($error_send) { ?>
          <span class="error"><?php echo $error_send; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td width="25%"><span class="required">*</span> <?php echo $entry_verify; ?></td>
        <td><input type="text" name="payir_verify" value="<?php echo $payir_verify; ?>" style="direction:ltr;" />
          <br />
          <?php if ($error_verify) { ?>
          <span class="error"><?php echo $error_verify; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td width="25%"><span class="required">*</span> <?php echo $entry_gateway; ?></td>
        <td><input type="text" name="payir_gateway" value="<?php echo $payir_gateway; ?>" style="direction:ltr;" />
          <br />
          <?php if ($error_gateway) { ?>
          <span class="error"><?php echo $error_gateway; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><?php echo $entry_order_status; ?></td>
        <td><select name="payir_order_status_id">
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $payir_order_status_id) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_status; ?></td>
        <td><select name="payir_status">
            <?php if ($payir_status) { ?>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <option value="0"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_sort_order; ?></td>
        <td><input type="text" name="payir_sort_order" value="<?php echo $payir_sort_order; ?>" size="1" /></td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript">
	$.tabs('.tabs a'); 
</script>

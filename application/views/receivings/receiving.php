<?php $this->load->view("partial/header"); ?>

<div id="page_title" style="margin-bottom:8px;"><?php echo $this->lang->line('recvs_register'); ?></div>

<?php
if(isset($error))
{
	echo "<div class='error_message'>".$error."</div>";
}
?>



<div id="register_wrapper">

	<?php echo form_open("receivings/change_almacen",array('id'=>'almacen_form')); ?>
	<span><?php echo $this->lang->line('almacenes_almacen') ?></span>
	<?php echo form_dropdown('almacen',$almacenes,$almacen,'onchange="$(\'#almacen_form\').submit();"'); ?>
	</form>
	
	<?php echo form_open("receivings/change_mode",array('id'=>'mode_form')); ?>
		<span><?php echo $this->lang->line('recvs_mode') ?></span>
	<?php echo form_dropdown('mode',$modes,$mode,'onchange="$(\'#mode_form\').submit();"'); ?>
	</form>
	<?php echo form_open("receivings/add",array('id'=>'add_item_form')); ?>
	<label id="item_label" for="item">

	<?php
	if($mode=='receive')
	{
		echo $this->lang->line('recvs_find_or_scan_item');
	}
	else
	{
		echo $this->lang->line('recvs_find_or_scan_item_or_receipt');
	}
	?>
	</label>
<?php echo form_input(array('name'=>'item','id'=>'item','size'=>'40'));?>
<div id="new_item_button_register" >
		<?php echo anchor("items/view/-1?width=360",
		"<div class='small_button'><span>".$this->lang->line('sales_new_item')."</span></div>",
		array('class'=>'thickbox none','title'=>$this->lang->line('sales_new_item')));
		?>
	</div>

</form>

<!-- Receiving Items List -->

<table id="register">
<thead>
<tr>
<th style="width:11%;"><?php echo $this->lang->line('common_delete'); ?></th>

<th style="width:30%;"><?php echo $this->lang->line('recvs_item_name'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('recvs_cost'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('recvs_quantity'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('recvs_discount'); ?></th>
<th style="width:15%;"><?php echo $this->lang->line('recvs_total'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('recvs_edit'); ?></th>
</tr>
</thead>
<tbody id="cart_contents">
<?php
if(count($cart)==0)
{
?>
<tr><td colspan='7'>
<div class='warning_message' style='padding:7px;'><?php echo $this->lang->line('sales_no_items_in_cart'); ?></div>
</tr></tr>
<?php
}
else
{
	foreach($cart as $item_id=>$item)
	{
		echo form_open("receivings/edit_item/$item_id");
	?>
		<tr>
		<td><?php echo anchor("receivings/delete_item/$item_id",'['.$this->lang->line('common_delete').']');?></td>

		<td style="align:center;"><?php echo $item['name']; ?><br>

		<?php
        	if($item['allow_alt_description']==1 && false)
        	{
        		echo form_input(array('name'=>'description','value'=>$item['description'],'size'=>'20'));
        	}
        	else
        	{
				echo $item['description'];
        		echo form_hidden('description',$item['description']);
        	}
		?>
		<br>



		<?php
        	if($item['is_serialized']==1 && false)
        	{
				echo form_input(array('name'=>'serialnumber','value'=>$item['serialnumber'],'size'=>'20'));
			}
		?></td>


		<?php if ($items_module_allowed)
		{
		?>
			<td><?php echo form_input(array('name'=>'price','value'=>$item['price'],'size'=>'6'));?></td>
		<?php
		}
		else
		{
		?>
			<td><?php echo $item['price']; ?></td>
			<?php echo form_hidden('price',$item['price']); ?>
		<?php
		}
		?>
		<td>
		<?php
        	if($item['is_serialized']==1 && false)
        	{
        		echo $item['quantity'];
        		echo form_hidden('quantity',$item['quantity']);
        	}
        	else
        	{
        		echo form_input(array('name'=>'quantity','value'=>$item['quantity'],'size'=>'2'));
        	}
		?>
		</td>


		<td><?php echo form_input(array('name'=>'discount','value'=>$item['discount'],'size'=>'3'));?></td>
		<td><?php echo to_currency($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100); ?></td>
		<td><?php echo form_submit("edit_item", $this->lang->line('sales_edit_item'));?></td>
		</tr>
		</form>
	<?php
	}
}
?>
</tbody>
</table>
</div>

<!-- Overall Receiving -->

<div id="overall_sale">
	<?php
	if(isset($supplier))
	{
		echo $this->lang->line("recvs_supplier").': <b>'.$supplier. '</b><br />';
		echo anchor("receivings/delete_supplier",'['.$this->lang->line('common_delete').' '.$this->lang->line('suppliers_supplier').']');
	}
	else
	{
		echo form_open("receivings/select_supplier",array('id'=>'select_supplier_form')); ?>
		<label id="supplier_label" for="supplier"><?php echo $this->lang->line('recvs_select_supplier'); ?></label>
		<?php echo form_input(array('name'=>'supplier','id'=>'supplier','size'=>'30','value'=>$this->lang->line('recvs_start_typing_supplier_name')));?>
		</form>
		<div style="margin-top:5px;text-align:center;">
		<h3 style="margin: 5px 0 5px 0"><?php echo $this->lang->line('common_or'); ?></h3>
		<?php echo anchor("suppliers/view/-1?width=350",
		"<div class='small_button' style='margin:0 auto;'><span>".$this->lang->line('recvs_new_supplier')."</span></div>",
		array('class'=>'thickbox none','title'=>$this->lang->line('recvs_new_supplier')));
		?>
		</div>
		<div class="clearfix">&nbsp;</div>
		<?php
	}
	?>

	<div id='sale_details'>
		<div class="float_left" style="width:55%;"><?php echo $this->lang->line('sales_sub_total'); ?>:</div>
		<div class="float_left" style="width:45%;font-weight:bold;"><?php echo to_currency($subtotal); ?></div>

		<div class="float_left" style='width:55%;'><?php echo $this->lang->line('sales_total'); ?>:</div>
		<div class="float_left" style="width:45%;font-weight:bold;"><?php echo to_currency($total); ?></div>
	</div>
	<?php
	if(count($cart) > 0)
	{
	?>
	<div id="Cancel_sale">
	<?php echo form_open("receivings/cancel_receiving",array('id'=>'cancel_sale_form')); ?>
			    <div class='small_button' id='cancel_sale_button' style='float:left;margin-top:5px;'>
					<span><?php echo $this->lang->line('recvs_cancel_receiving');?> </span>
				</div>
        </form>
		</div>
		<div class="clearfix" style="margin-bottom:1px;">&nbsp;</div>
	<?php if(count($payments)>0){?>
	<div id="finish_sale">
		<?php echo form_open("receivings/complete",array('id'=>'finish_sale_form')); ?>
		<label id="comment_label" for="comment"><?php echo $this->lang->line('common_comments'); ?>:</label>
		<?php echo form_textarea(array('name'=>'comment','value'=>'','rows'=>'4','cols'=>'23'));?>
		<br>
        <br>
		<?php echo "<div class='small_button' id='finish_sale_button' style='float:left;margin-top:5px;'><span>".$this->lang->line('recvs_complete_receiving')."</span></div>";
		?>
		</div>

		</form>
		<?php }?>
		
		<table width="100%"><tr>
    <td style="width:55%; "><div class="float_left"><?php echo $this->lang->line('recvs_payments_total').':';?></div></td>
    <td style="width:45%; text-align:right;"><div class="float_left" style="text-align:right;font-weight:bold;"><?php echo to_currency($amount_tendered); ?></div></td>
	</tr>
	<tr>	
	<td style="width:55%; "><div class="float_left" ><?php echo ($amount_due>=0 ? $this->lang->line('recvs_amount_due'):$this->lang->line('recvs_change_due')).':'; ?></div></td>
	<td style="width:45%; text-align:right; "><div class="float_left" style="text-align:right;font-weight:bold;"><?php echo to_currency($amount_due); ?></div></td>
	</tr>
        </table>
		<!-- YOOOO -->
		<div id="Payment_Types" >
		<div style="height:100px;">
			<?php echo form_open("receivings/add_payment",array('id'=>'add_payment_form')); ?>
			<table width="100%">
			<tr>
			<td>
				<?php echo $this->lang->line('sales_payment').':   ';?>
			</td>
			<td>
				<?php echo form_dropdown('payment_type',$payment_options,array(), 'id="payment_types"');?>
			</td>
			</tr>
			<tr>
			<td>
				<?php echo $this->lang->line('sales_amount_tendered').':   ';?>
			</td>
			<td>
				<?php echo form_input(array('name'=>'amount_tendered','value'=>to_currency_no_money($amount_due),'size'=>'10'));	?>
			</td>
			</tr>
        	</table>
			<div class='small_button' id='add_payment_button' style='float:left;margin-top:5px;'>
				<span><?php echo $this->lang->line('sales_add_payment'); ?></span>
			</div>
		</div>
		</form>

		<?php
		// Only show this part if there is at least one payment entered.
		if(count($payments) > 0)
		{
		?>
	    	<table id="register">
	    	<thead>
			<tr>
			<th style="width:11%;"><?php echo $this->lang->line('common_delete'); ?></th>
			<th style="width:60%;"><?php echo 'Type'; ?></th>
			<th style="width:18%;"><?php echo 'Amount'; ?></th>
			</tr>
			</thead>
			<tbody id="payment_contents">
			<?php
				foreach($payments as $payment_id=>$payment)
				{
				echo form_open("receivings/edit_payment/$payment_id",array('id'=>'edit_payment_form'.$payment_id));
				?>
	            <tr>
	            <td><?php echo anchor("receivings/delete_payment/$payment_id",'['.$this->lang->line('common_delete').']');?></td>
				<td><?php echo  $payment_options[$payment_id] ?> </td>
				<td style="text-align:right;"><?php echo  to_currency($payment['payment_amount'])  ?>  </td>
				</tr>
				</form>
				<?php
				}
				?>
			</tbody>
			</table>
		    <br />
		<?php
		}
		?>



	</div>
		<!-- YOOOO -->
	</div>
	<?php
	}
	?>

</div>
<div class="clearfix" style="margin-bottom:30px;">&nbsp;</div>


<?php $this->load->view("partial/footer"); ?>


<script type="text/javascript" language="javascript">
$(document).ready(function()
{
    $("#item").autocomplete('<?php echo site_url("receivings/item_search"); ?>',
    {
    	minChars:0,
    	max:100,
       	delay:10,
       	selectFirst: false,
    	formatItem: function(row) {
			return row[1];
		}
    });

    $("#item").result(function(event, data, formatted)
    {
		$("#add_item_form").submit();
    });

	$('#item').focus();

	$('#item').blur(function()
    {
    	$(this).attr('value',"<?php echo $this->lang->line('sales_start_typing_item_name'); ?>");
    });

	$('#item,#supplier').click(function()
    {
    	$(this).attr('value','');
    });

    $("#supplier").autocomplete('<?php echo site_url("receivings/supplier_search"); ?>',
    {
    	minChars:0,
    	delay:10,
    	max:100,
    	formatItem: function(row) {
			return row[1];
		}
    });

    $("#supplier").result(function(event, data, formatted)
    {
		$("#select_supplier_form").submit();
    });

    $('#supplier').blur(function()
    {
    	$(this).attr('value',"<?php echo $this->lang->line('recvs_start_typing_supplier_name'); ?>");
    });

    $("#finish_sale_button").click(function()
    {
    	if (confirm('<?php echo $this->lang->line("recvs_confirm_finish_receiving"); ?>'))
    	{
    		$('#finish_sale_form').submit();
    	}
    });

    $("#cancel_sale_button").click(function()
    {
    	if (confirm('<?php echo $this->lang->line("recvs_confirm_cancel_receiving"); ?>'))
    	{
    		$('#cancel_sale_form').submit();
    	}
    });
	
	$("#add_payment_button").click(function()
	{
	   $('#add_payment_form').submit();
    });


});

function post_item_form_submit(response)
{
	if(response.success)
	{
		$("#item").attr("value",response.item_id);
		$("#add_item_form").submit();
	}
}

function post_person_form_submit(response)
{
	if(response.success)
	{
		$("#supplier").attr("value",response.person_id);
		$("#select_supplier_form").submit();
	}
}

</script>
<?php 	 

define('MAGENTO', realpath(dirname(__FILE__)));
require_once MAGENTO . '/app/Mage.php';
$store_id = Mage::app()->getStore()->getId();

$resource       = Mage::getSingleton('core/resource');
$writeAdapter   = $resource->getConnection('core_write');
$readAdapter   = $resource->getConnection('core_read');
$orderTable     = $resource->getTableName('sales_flat_order');
$customerGrid   = $resource->getTableName('sales_flat_order_grid');
$addressTable   = $resource->getTableName('sales_flat_order_address');
$paymentTable   = $resource->getTableName('sales_flat_order_payment');
$ordered_product   = $resource->getTableName('sales_flat_order_item');
$customerTable   = $resource->getTableName('customer_entity');


// check if customer exist , if not create order as guest customer
$find_customer = "SELECT `entity_id` FROM {$customerTable} WHERE `email` = 'riteshprajapati3.14@gmail.com'";
$customer = $readAdapter->query($find_customer);
$customer_id = $customer->fetch()['entity_id'];
$cus_col_name="";
$cus_col_val = "";
$customer_is_guest=1;

if(!empty($customer_id) || is_int($customer_id) )
{ 
	$customer_group_id=1;
	$cus_col_name_sales_order_grid = ",`customer_id`";
	$cus_col_val_sales_order_grid = ",$customer_id";
	$cus_col_name_sales_order = ",`customer_id`,`customer_group_id`";
	$cus_col_val_sales_order = ",$customer_id,$customer_group_id";
	$customer_is_guest=0;
}

// Sales Order Variables 
$shipping_description="Flat Rate - Fixed";
$base_grand_total="100";
$base_shipping_amount="2.13";
$base_subtotal=$base_grand_total;
$base_total_invoiced = $base_grand_total;
$base_total_paid = $base_grand_total;
$grand_total = $base_grand_total;
$shipping_amount = $base_shipping_amount;
$shipping_invoiced = $base_shipping_amount;
$subtotal = $base_grand_total;
$subtotal_invoiced = $base_grand_total;
$total_invoiced = $base_grand_total;
$total_paid = $base_grand_total;
$total_qty_ordered='4';

$base_subtotal_incl_tax = $base_grand_total;
$base_total_due=0.0000;
$subtotal_incl_tax = $base_grand_total;
$customer_email='riteshprajapati3.14@gmail.com';

$customer_firstname='Ritesh';
$customer_lastname='Kumar';

$full_name = $customer_firstname." ".$customer_lastname;

$shipping_method='Flat Rate - Fixed';
$store_name = "Website 1 
Website store 1
English Store View";
$date="2014-10-22 00:00:00";	

$increment_id="100000016";
$total_item_count='3';
$shipping_incl_tax = $base_shipping_amount;
$base_shipping_incl_tax = $base_shipping_amount;

//Insert Order

$order_query        = "INSERT INTO {$orderTable} (`state`,`status`,`shipping_description`,`store_id`,`base_grand_total`,`base_shipping_amount`,`base_subtotal`,`base_to_global_rate`,`base_to_order_rate`,`base_total_invoiced`,`base_total_paid`,`grand_total`,`shipping_amount`,`shipping_invoiced`,`store_to_order_rate`,`subtotal`,`subtotal_invoiced`,`total_invoiced`,`total_paid`,`total_qty_ordered`,`customer_is_guest`,`base_subtotal_incl_tax`,`base_total_due`,`subtotal_incl_tax`,`increment_id`,`base_currency_code`,`customer_email`,`customer_firstname`,`customer_lastname`,`global_currency_code`,`order_currency_code`,`shipping_method`,`store_currency_code`,`store_name`,`created_at`,`updated_at`,`total_item_count`,`shipping_incl_tax`,`base_shipping_incl_tax`$cus_col_name_sales_order) VALUES ('complete','complete','$shipping_description','1','$base_grand_total','$base_shipping_amount',$base_subtotal,1.0000,1.0000,'$base_total_invoiced','$base_total_paid','$grand_total','$shipping_amount',$shipping_invoiced,1.0000,$subtotal,$subtotal_invoiced,$total_invoiced,$total_paid,$total_qty_ordered,'$customer_is_guest','$base_subtotal_incl_tax','$base_total_due','$subtotal_incl_tax','$increment_id','USD','$customer_email','$customer_firstname','$customer_lastname','USD','USD','$shipping_method','USD','$store_name','$date','$date','$total_item_count','$shipping_incl_tax',$base_shipping_incl_tax$cus_col_val_sales_order)";

try {

	
	$writeAdapter->query($order_query);
	$order_id = $writeAdapter->lastInsertId();

	// foreach ($products as $value) {
	// 	# code...
	// }

	$product_options = 'a:1:{s:15:"info_buyRequest";a:2:{s:3:"qty";i:1;s:7:"options";a:0:{}}}';
	$sku='102ASD34';
	$name="PRODUCTNAME";
	$qty_ordered='2';
	$price='50';
	$base_price = $original_price = $base_original_price = $price;
	$row_total = (int)$price * (int)$qty_ordered;
	$base_row_total = $row_total;
	$row_invoiced = $row_total;
	$base_row_invoiced = $row_total;
	$price_incl_tax = $price; 
	$base_price_incl_tax = $price;
	$row_total_incl_tax = $row_total;
	$base_row_total_incl_tax = $row_total;

 //Generates products for order 
 	$ordered_product_query = "INSERT INTO {$ordered_product} (`order_id`,`store_id`,`created_at`,`updated_at`,`product_type`,`product_options`,`sku`,`name`,`qty_invoiced`,`qty_ordered`,`qty_shipped`,`price`,base_price,original_price,base_original_price,row_total,base_row_total,row_invoiced,base_row_invoiced,price_incl_tax,base_price_incl_tax) VALUES ('$order_id','$store_id','$date','$date','simple','$product_options','$sku','$name',$qty_ordered,$qty_ordered,$qty_ordered,$price,'$base_price','$original_price','$base_original_price','$row_total','$base_row_total',$row_invoiced,$base_row_invoiced,'$price_incl_tax','$base_price_incl_tax')";
 	$writeAdapter->query($ordered_product_query);	


 	// Billing and Shipping variables
	$region='IA';
	$postcode='50401';
	$street='3349 So. Birch Drive  Mason City, IA 50401 ';
	$city='Mason City';
	$telephone='94532534';
	$country_id='US';


 	// Insert Billing and Shiping information 
	$shipping_update   = "INSERT INTO {$addressTable} (`parent_id`,`region`,`postcode`,`lastname`,`street`,`city`,`email`,`telephone`,`country_id`,`firstname`,`address_type`) VALUES ($order_id,'$region','$postcode','$customer_lastname','$street','$city','$customer_email','$telephone','$country_id','$customer_firstname','shipping');";
	$writeAdapter->query($shipping_update);	
	$shipping_id = $writeAdapter->lastInsertId();

	$billing_update   = "INSERT INTO {$addressTable} (`parent_id`,`region`,`postcode`,`lastname`,`street`,`city`,`email`,`telephone`,`country_id`,`firstname`,`address_type`) VALUES ($order_id,'$region','$postcode','$customer_lastname','$street','$city','$customer_email','$telephone','$country_id','$customer_firstname','billing');";
	$writeAdapter->query($billing_update);	
	$billing_id = $writeAdapter->lastInsertId();
	// ENDS 

	// Update billing and shipping ids in main order
	$order_billing_shipping_update = "UPDATE {$orderTable} SET billing_address_id = '$billing_id',shipping_address_id = 'shipping_id' WHERE entity_id ='$order_id'";
	$writeAdapter->query($order_billing_shipping_update);
	// ENDS 

  //Generate Payment information   
	$method='checkmo';
  $payment_query        = "INSERT INTO {$paymentTable} (`parent_id`,`shipping_captured`,`base_amount_paid`,`base_shipping_amount`,`shipping_amount`,`amount_paid`,`base_amount_ordered`,`amount_ordered`,`method`) VALUES ('$order_id','$base_shipping_amount','$base_grand_total','$base_shipping_amount','$base_shipping_amount','$base_grand_total','$base_grand_total','$base_grand_total','$method');";
	$writeAdapter->query($payment_query);
	$payment_id = $writeAdapter->lastInsertId();

 	// Insert In Order Grid 
  $admin_grid_update        = "INSERT INTO {$customerGrid} (`entity_id`,`status`,`store_id`,`store_name`,`base_grand_total`,`base_total_paid`,`grand_total`,`total_paid`,`increment_id`,`base_currency_code`,`order_currency_code`,`shipping_name`,`billing_name`,`created_at`,`updated_at`$cus_col_name_sales_order_grid) VALUES ($order_id, 'complete',1,'$store_name', $base_grand_total,$base_total_paid,$grand_total,$total_paid,$increment_id,'USD','USD','$full_name','$full_name','$date','$date'$cus_col_val_sales_order_grid);";
	$writeAdapter->query($admin_grid_update);
	$grid_id = $writeAdapter->lastInsertId();

} catch (Exception $e) {
	var_dump($e->getMessage());
	// ROLLBACK DATA IF AN ERROR OCCUR
	if (isset($order_id) && !empty($order_id)) {
		$delete_order = "Delete FROM {$orderTable} WHERE `entity_id` ='$order_id'";
		// all other tables automatically gets deleted because of foriegn key constrain.
		$writeAdapter->query($delete_order); 
	}
}


?>
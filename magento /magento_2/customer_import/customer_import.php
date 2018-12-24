<?php 

function pr($object){
	echo "<pre>";
	var_dump($object);
	echo "</pre>";
}

function prd($object){
	echo "<pre>";
	var_dump($object);
	echo "</pre>";
	die();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

	use \Magento\Framework\App\Bootstrap;
	include('app/bootstrap.php');
	$bootstrap = Bootstrap::create(BP, $_SERVER);
	$objectManager = $bootstrap->getObjectManager();
	$url = \Magento\Framework\App\ObjectManager::getInstance();
	$storeManager = $url->get('\Magento\Store\Model\StoreManagerInterface');
	$mediaurl= $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
	$state = $objectManager->get('\Magento\Framework\App\State');
	$state->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL);
	// Customer Factory to Create Customer
	$customerFactory = $objectManager->get('\Magento\Customer\Model\CustomerFactory');
	$customerResourceFactory = $objectManager->get('\Magento\Customer\Model\ResourceModel\CustomerFactory');
	$websiteId = $storeManager->getWebsite()->getWebsiteId();
	/// Get Store ID
	$store = $storeManager->getStore();
	$storeId = $store->getStoreId();
	
	$directoryList = $objectManager->get('\Magento\Framework\App\Filesystem\DirectoryList');
	$csvProcessor = $objectManager->get('\Magento\Framework\File\Csv');

	try{
		// $fileName = "customer_export_v2.csv";
		$fileName = "demo_customer_import_v2.csv";
	  $fileDirectoryPath = $directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::ROOT)."/".$fileName;
	  $csvData = $csvProcessor->getData($fileDirectoryPath);
	} catch(Exception $e)
	{
		echo "exception occured ".$e->getMessage();
		die();
	}


	function validatePhone($phonenumber)
	{
			return str_replace("'","",$phonenumber);
	}

	$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
	$connection = $resource->getConnection();

    foreach ($csvData as $row => $data) {

		  	$first_name = ($data[0]!='NULL') ? trim($data[0]): '';
		  	$last_name = ($data[1]!='NULL') ? trim($data[1]): '';
		  	$email = ($data[2]!='NULL') ? trim($data[2]): '';
		  	$company = ($data[3]!='NULL') ? trim($data[3]): '';
		  	$address_1 = ($data[4]!='NULL') ? trim($data[4]): '';
		  	$address_2 = ($data[5]!='NULL') ? trim($data[5]): '';
		  	$city = ($data[6]!='NULL') ? trim($data[6]): '';
		  	$province = ($data[7]!='NULL') ? trim($data[7]): '';
		  	$province_code = ($data[8]!='NULL') ? trim($data[8]): '';
		  	$country = ($data[9]!='NULL') ? trim($data[9]): '';
		  	$country_code = ($data[10]!='NULL') ? trim($data[10]): '';
		  	$zip = ($data[11]!='NULL') ? trim($data[11]): '';
		  	$phone = ($data[12]!='NULL') ? trim($data[12]): '';
		  	$customers_password = ($data[13]!='NULL') ? trim($data[13]): '';

		  	$total_address = $address_1 . " ". $address_2;

				$websiteId = ($country_code=='SG' ? 1:2);
				$storeId = ($country_code=='SG' ? 1:3);


				$customer = $customerFactory->create();
				$customer->setWebsiteId($websiteId);
				$customer->setStoreId($storeId);
				$customer->setFirstname($first_name);
				$customer->setLastname($last_name);
				$customer->setEmail($email);
				$customer->setPassword($customers_password);
				$customer->setGroupId(1);

				try {
					//Save data
					$customer->save();
					$address_valid = 1;
					
					if($customer->getId()){
				 		if(
				 			empty($address_1) ||
				 			empty($zip) ||
				 			empty($city) ||
				 			empty($country_code)
				 		)
				 		{
				 			$address_valid = 0;
				 			if(!empty($address_1))
				 			{
					 			$handle = fopen('allies_invalid_details.csv', 'a');
					 			$error_file = $data;
					 			$error_file['error']="Address not created . address is not valid ";
					 			fputcsv($handle, $error_file);
				 			}

				 		}

				 		if($address_valid)
				 		{
							$addresss = $objectManager->get('\Magento\Customer\Model\AddressFactory');
							$address = $addresss->create();
							$address->setCustomerId($customer->getId());
							$address->setFirstname($first_name);
							$address->setLastname($last_name);
							$address->setCompany($company);
							$address->setStreet($total_address);
							$address->setPostcode($zip);
							$address->setTelephone(validatePhone($phone));
							$address->setCountryId($country_code);
							$address->setRegion($province);
							$address->setCity($city);
							$address->setIsDefaultShipping('1');
							$address->setIsDefaultBilling('1');
							$address->setSaveInAddressBook('1');
							
							try {
								$address->save();
								pr($customer->getData());
							}
							catch (Exception $e) {
								Zend_Debug::dump($e->getMessage());
							}
				 		}
					}	 
				}
				catch(Exception $e)
				{	
					echo "Exception occured.Customer not saved";

					$handle = fopen('allies_invalid_details.csv', 'a');
					$error_file = $data;
					$error_file['error']=$e->getMessage();
					fputcsv($handle, $error_file);
					print_r($e->getMessage());
					echo "</br>";
				}
  }
  if(isset($handle))
  {
	  fclose ($handle);
	  chmod("allies_invalid_details.csv",0777);
  }
?>


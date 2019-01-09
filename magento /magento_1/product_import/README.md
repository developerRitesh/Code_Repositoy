NOTE : 	These files are from my own future reference , While the process remain same for all. Feel free to ping me on riteshprajapati3.14@gmail.com in case you need help . 

Instructions to import 
-------------------------------------

There are 2 seperate files one for simple product and another for configurable product , Configurable products can only be created after creating simple products . All the product images should be in a seperate folder named import. That must be placed inside magento-installation-root/media/import


Instruction for simple product
---------------------------
1. sku is required must be unique ,_attribute_set is default , _type is simple,_category enter category name here , if you are not sure click on product->export button from the backend you will get the file containing all product . you can get category from there , i am calling that file as all_product.csv.
2. _root_category,_product_websites have default value. Everthing else is self explanatory you might not have color and size option in that case you can remove those column , i need them as i am creating configurable product based on these two attributes .  
3.images name should be prefixed with / for example /image_3.jpg otherwise they will not be imported the image should also exist in magento-installation-root/media/import , make sure the directory has proper permission .visibility,qty,is_in_stock fill these values otherwise product will not be shown in the frontend . 
4.Color and size attribute must match the values that are present in the system , for that you can refer all_product.csv.
5.product if have multiple images (which we have) must be in seperate line , see coloumn name '_media_image' , here each image is in seperate line . note these images will be visible in configurable products when specific color and size is selected and In the frontend image specified in configurable product will be used .
6. please note sku and url should be unique

Note : if you get any error while importing like column doesnot have required value or not present just refer all_product.csv and include that column . It will work fine . 


Instruction for Configurable product 
---------------------------

follow the configurable.csv file process is similar as simple product. 

1. Here our main concern are columns named _super_products_sku,_super_attribute_code,_super_attribute_option. in these fields we would specify the simple products that we have created in the frontend .

_super_products_sku=> This should contain simple product sku that we want in our product.
_super_attribute_code,_super_attribute_option  => here we will specify the product color and size option for example 

_super_attribute_code      _super_attribute_option

color                      Red
size                       13 D(M) US


for example if we want to include a simple product with sku  'simple-red' the mapping would be like this 

_super_products_sku       	_super_attribute_code      _super_attribute_option

simple-red					color                      Red
simple-red					size                       13 D(M) US
simple-blue					color                      Blue
simple-blue					size                       11 D(M) US

please note . the _super_products_sku field is repeated because of color and size , this is default syntax. 

2.here we have image , small_image, thumbnail all of these field should contain the single image that we want to display in frontend . 
3.images name should be prefixed with / for example /image_3.jpg .
4. category should be specified in _category column , with sku and url being unique. 
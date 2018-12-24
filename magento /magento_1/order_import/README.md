# Script to import order through raw queries 

should be only used if you know what you are doing . There are various free magento 1.9 modules which you can use to import orders . I created this script for maximum optimization and use it when the amount of orders to process are very large or import exceeds the max_execution_time and/or memory_limit of the server. 

steps to use 
-------------------------


1.Just copy this order_raw.php in your system base directory ans run . In your backend if you  see one newly created order that means the script is working . 

2.Now if it is working for you , map the data that you receive from the client with the required variables 
 
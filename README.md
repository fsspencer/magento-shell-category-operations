Magento Category Operations Shell Script
===================


This is a simple shell script for Magento 1.9.x to perform the following operations on categories:

 - Move every product from a category to another one
 - Move a category inside another one
 - Remove assigned products from a category
 - Count assigned products from a category

----------


Requirements
-------------

 - PHP (accessible via command line as "php")
 - Magento 1.9.x
 - Unix/OSX environment


Installation
-------------


    # Go to your magento project directory
    $ cd ~/my-magento-project/shell
    
    # Clone this repository
    $ git clone https://github.com/fsspencer/magento-shell-category-operations ./
    


Usage
-------------


    # Script usage 
    $ php -f shell/magento-shell-category-operations/category_operations.php help
    
    # Move every product from categoryID=5 to categoryID=10
    $ php -f shell/magento-shell-category-operations/category_operations.php moveProdcuts --category-origin 5 --category-destiny 10
    
    # Remove every product from categoryID=10
    $ php -f shell/magento-shell-category-operations/category_operations.php removeProdcuts --category 10
    
    # Move categoryID=5 inside categoryID=10
    $ php -f shell/magento-shell-category-operations/category_operations.php moveCategory --category 5 --category-parent 10
    
    # Check the count of assigned products to categoryID=5
    $ php -f shell/magento-shell-category-operations/category_operations.php status --category 5


<?php

// Change current directory to the directory of current script
chdir(dirname(__FILE__));
require_once '../abstract.php';

class Codealist_Shell_CategoryOperations extends Mage_Shell_Abstract
{

    /**
     * Run script
     *
     */
    public function run()
    {
        try {
            if ($this->getArg('moveCategory')) {
                $this->moveCategory();
            } elseif ($this->getArg('moveProducts')) {
                $this->moveCategoryProducts();
            } elseif ($this->getArg('removeProducts')) {
                $this->removeCategoryProducts();
            } elseif ($this->getArg('status')) {
                $this->countCategoryProducts();
            }
        } catch (Exception $e) {
            echo "Error occurred: {$e->getMessage()}";
        }
    }

    /**
     * Move a Category inside another one
     */
    protected function moveCategory()
    {
        if ($this->getArg('category') && $this->getArg('category-parent')) {
            $categoryId = $this->getArg('category');
            $categoryParentId = $this->getArg('category-parent');

            $category = Mage::getModel('catalog/category')->load($categoryId);
            if (!$category) {
                echo "Provided category child ID doesn't belong to a category";
                exit;
            }

            $categoryParent = Mage::getModel('catalog/category')->load($categoryParentId);
            if (!$categoryParent) {
                echo "Provided category parent ID doesn't belong to a category";
                exit;
            }

            Mage::getSingleton('catalog/category_api')->move($categoryId, $categoryParentId);

            echo "Category \"{$category->getName()}\" moved inside of category \"{$categoryParent->getName()}\"";
        } else {
            echo "No category and/or category-parent argument provided";
        }
    }

    /**
     * Move every product from a category to another one
     */
    protected function moveCategoryProducts()
    {
        if ($this->getArg('category-origin') && $this->getArg('category-destiny')) {
            $categoryOriginId = $this->getArg('category-origin');
            $categoryDestinyId = $this->getArg('category-destiny');

            $categoryOrigin = Mage::getModel('catalog/category')->load($categoryOriginId);
            if (!$categoryOrigin) {
                echo "Provided category origin ID doesn't belong to a category";
                exit;
            }

            $categoryDestiny = Mage::getModel('catalog/category')->load($categoryDestinyId);
            if (!$categoryDestiny) {
                echo "Provided category destiny ID doesn't belong to a category";
                exit;
            }

            $products = Mage::getResourceModel('catalog/product_collection')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->addCategoryFilter($categoryOrigin);

            foreach ($products as $product) {
                Mage::getSingleton('catalog/category_api')->removeProduct($categoryOriginId, $product->getId());
                Mage::getSingleton('catalog/category_api')->assignProduct($categoryDestinyId, $product->getId());
            }

            echo count($products) . " Products were moved from \"{$categoryOrigin->getName()}\" to \"{$categoryDestiny->getName()}\"";
        } else {
            echo "No category-origin and/or category-destiny argument provided";
        }
    }

    /**
     * Move every product from a category to another one
     */
    protected function removeCategoryProducts()
    {
        if ($this->getArg('category')) {
            $categoryId = $this->getArg('category');
            $category = Mage::getModel('catalog/category')->load($categoryId);
            if (!$category) {
                echo "Provided category ID doesn't belong to a category";
                exit;
            }

            $products = Mage::getResourceModel('catalog/product_collection')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->addCategoryFilter($categoryId);

            foreach ($products as $product) {
                Mage::getSingleton('catalog/category_api')->removeProduct($categoryId, $product->getId());
            }

            echo count($products) . " Products were removed from \"{$categoryOrigin->getName()}\"";
        } else {
            echo "No category argument provided";
        }
    }

    /**
     * Count the category products
     */
    protected function countCategoryProducts()
    {
        if ($this->getArg('category')) {
            $categoryId = $this->getArg('category');
            $category = Mage::getModel('catalog/category')->load($categoryId);
            if (!$category) {
                echo "Provided category ID doesn't belong to a category";
                exit;
            }

            $products = Mage::getResourceModel('catalog/product_collection')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->addCategoryFilter($category);
            echo "Category \"{$category->getName()}\" has " . count($products) . " products associated.";
        } else {
            echo "No category argument provided";
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f test.php -- [options]
        php -f test.php -- status --category 8
        php -f test.php -- moveCategory --category-origin 8 --category-destiny 9
        php -f test.php -- moveProducts --category-origin 8 --category-destiny 9
        php -f test.php -- removeProducts --category 8

  status                            Display the count of products associated to a category
  --category <Category ID>          Save log, days. (Minimum 1 day, if defined - ignoring system value)

  moveCategory                      Move products from a category to another
  --category <Category ID>          Category you want to move
  --category-parent <Category ID>   Parent Category

  moveProducts                      Move products from a category to another
  --category-origin <Category ID>   Category origin from where you want to move products
  --category-destiny <Category ID>  Category destiny where you want to move products

  removeProducts                     Move products from a category to another
  --category <Category ID>           Category to clean up

  help                              This help

USAGE;
    }
}

$shell = new Codealist_Shell_CategoryOperations();
$shell->run();


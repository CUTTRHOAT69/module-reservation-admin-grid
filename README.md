## Main Functionalities
 - Admin grid to manage store reservations with ACL to chose who is allowed to view and/or delete
 - CRUD for Reservations, to be able to do many thing more easily, like GetById and DeleteById

## Installation
 - Install the module composer by running `composer require heliton/module-reservation-admin-grid`
 - enable the module by running `php bin/magento module:enable Heliton_ReservationAdminGrid`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

## Configuration
 - Admin menu can be accessed in:  Reservations -> Grid Reservations.


## Specifications

 - Observer
	- checkout_cart_product_add_before > Hibrido\SplitQuoteItems\Observer\Frontend\Checkout\CartProductAddBefore

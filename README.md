## Main Functionalities
 - Admin grid to manage store reservations with ACL to chose who is allowed to view and/or delete
 - CRUD for Reservations, to be able to do many thing more easily, like GetById and DeleteById
 - Custom log called reservations_logger.log

## Installation
 - Install the module composer by running `composer require heliton/module-reservation-admin-grid`
 - enable the module by running `php bin/magento module:enable Heliton_ReservationAdminGrid`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

## Configuration
 - Admin menu can be accessed in:  Reservations -> Grid Reservations.

## Image demonstrations
- ACL

![Captura de tela de 2024-12-02 08-36-24](https://github.com/user-attachments/assets/3391210d-80b5-4002-a6c1-bcbce5f8efb7)

- Admin menu

![Captura de tela de 2024-12-02 08-36-32](https://github.com/user-attachments/assets/6e88c7f3-a1c5-4b94-93bb-4620a08c8075)

- Admin grid

![Captura de tela de 2024-12-02 08-37-33](https://github.com/user-attachments/assets/2a406429-1bcd-4bca-9532-bbf640a7eeb2)



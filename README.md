## Main Functionalities
 - Admin grid to manage store reservations with ACL to chose who is allowed to view and/or delete
 - Admin grid that list inconsistent reservations to analize
 - CRUD for Reservations, to be able to do many things more easily, like GetById and DeleteById
 - Custom log called reservations_logger.log
 - Translation available in pt_BR

## Installation
 - Install the module composer by running `composer require heliton/module-reservation-admin-grid`
 - enable the module by running `php bin/magento module:enable Heliton_ReservationAdminGrid`
 - apply database updates by running `php bin/magento setup:upgrade`
 - Flush the cache by running `php bin/magento cache:flush`

## Configuration
 - Admin menu can be accessed in:  Reservations -> Grid Reservations.

## Image demonstrations
- ACL
![Captura de tela de 2024-12-02 08-36-24](https://github.com/user-attachments/assets/3391210d-80b5-4002-a6c1-bcbce5f8efb7)

- Admin menu
<img width="350" height="644" alt="Captura de tela de 2025-10-07 16-52-39" src="https://github.com/user-attachments/assets/7b37f529-a80e-4575-b2ac-1d5de7d2cc34" />

- Admin Reservation grid
![Captura de tela de 2024-12-02 08-37-33](https://github.com/user-attachments/assets/2a406429-1bcd-4bca-9532-bbf640a7eeb2)

- Admin Inconsistent grid
  <img width="1820" height="506" alt="Captura de tela de 2025-10-07 16-49-45" src="https://github.com/user-attachments/assets/bd27397c-a988-4f7d-a675-5f5e7e980536" />


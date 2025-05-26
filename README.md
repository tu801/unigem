# Ecommerce Website

Hệ thống quản lý bán hàng ecommerce

# Technology Integration

- Framework [Codeigniter 4.6](https://codeigniter.com/user_guide/installation/installing_composer.html)
- Authentication by [Codeigniter 4 Shield](https://shield.codeigniter.com/getting_started/concepts/)

## Installation & updates

Clone dự án từ git

Sau đó chạy lệnh sau để cài các package cần thiết.

```bash
# local install
composer install

# production install
composer install --no-dev --optimize-autoloader
```

Chạy lệnh `composer update` bất kì khi nào cần update package mới

## Setup

Copy file `.env.example` và đổi tên thành `.env` Sau đó chỉnh sửa các thông tin trong file `.env`

Nếu là lần đầu tiên tạo dự án thì chạy lệnh `php spark shield:setup` để setup [Shield authentication](https://shield.codeigniter.com/getting_started/install/) toàn bộ database.

Các lần sau để migrate thêm table thì dùng lệnh: `php spark migrate --all`

Chạy lệnh các lệnh sau để tạo data mẫu:

- `php spark db:seed AcpData` - Tạo tài khoản login Admin mặc định
- `php spark db:seed ProvinceData` - Thêm quyền mặc định

Truy cập trang admin bằng URL `http://localhost:8080/acp`
=> có thể thay domain `http://localhost:8080/` bằng domain local tương ứng

user: `admin`
password: `1234qwer@#$`

random password for change password test: `5vC:BtvYy76Z`

## Image Display

All image upload will be uploaded to `writable/uploads`. Setup permission for all folder in this folder

- Linux: `chmod -R 777 /writable/upload`

Public image for view.

- Window: `mklink /D "E:/[path-to-project-folder]/public/uploads" "E:/[path-to-project-folder]/writable/uploads"` User must have admin permission to do this action.
- Linux: `ln -s /[path-to-project-folder]/writable/uploads /[path-to-project-folder]/public/uploads`

replace `[path-to-project-folder]` with the real path to project folder in Linux server and `E:/[path-to-project-folder]` with the real path to project folder in window

## Server Requirements

PHP version 8.2 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
>
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - If you are still using PHP 7.4 or 8.0, you should upgrade immediately.
> - The end of life date for PHP 8.1 will be December 31, 2025.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

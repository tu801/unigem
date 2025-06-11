# query insert thêm

## 16/05/2025

thêm field cho table language

```sql
ALTER TABLE `language` ADD `currency_symbol` VARCHAR(32) NULL AFTER `currency_code`;

UPDATE `language` SET `name` = 'English', `currency_code` = 'USD', `currency_symbol`='$' WHERE `language`.`id` = 2;
UPDATE `language` SET `currency_code` = 'VND', `currency_symbol`='₫' WHERE `language`.`id` = 1;
```

sau khi thêm thì thiêt lập giá trị tiền tệ cho vnd + usd

## 18/05/2025

sửa lại migrate về giá của table product_content và order
chạy query sau để update table

```sql
ALTER TABLE `product_content` CHANGE `origin_price` `origin_price` DECIMAL(14,2) NOT NULL DEFAULT '0.000000', CHANGE `price` `price` DECIMAL(14,2) NOT NULL DEFAULT '0.000000', CHANGE `price_discount` `price_discount` DECIMAL(14,2) NOT NULL DEFAULT '0.000000';

ALTER TABLE `order` CHANGE `exchange_rate` `exchange_rate` DECIMAL(14,2) NOT NULL DEFAULT '0.000000', CHANGE `sub_total` `sub_total` DECIMAL(14,2) NULL DEFAULT '0.000000', CHANGE `discount_amount` `discount_amount` DECIMAL(14,2) NULL DEFAULT '0.000000', CHANGE `shipping_amount` `shipping_amount` DECIMAL(14,2) NULL DEFAULT '0.000000', CHANGE `total` `total` DECIMAL(14,2) NULL DEFAULT '0.000000', CHANGE `total_amount_vnd` `total_amount_vnd` DECIMAL(14,2) NULL DEFAULT '0.000000', CHANGE `customer_paid` `customer_paid` DECIMAL(14,2) NULL DEFAULT '0.000000';
```

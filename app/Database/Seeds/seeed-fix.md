# query insert thêm

## 16/05/2025

thêm field cho table language

```sql
ALTER TABLE `language` ADD `currency_symbol` VARCHAR(32) NULL AFTER `currency_code`;
```

sau khi thêm thì thiêt lập giá trị tiền tệ cho vnd + usd

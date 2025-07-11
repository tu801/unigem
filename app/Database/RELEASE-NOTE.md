# Release Note

## Phase 2

- Chạy lại migration để tạo table `order`
- chạy seed data exchange rate

```shell
php spark migrate --all

php spark db:seed ExchangeRateSeed
```

- Vào `ACP>Config`. Tạo cấu hình email admin để nhận email khi có order

```text
Tên cấu hình: Admin Order Email Receive
key: admin_order_email_receive
value: xxx
```

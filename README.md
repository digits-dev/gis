# 📄 GASHAPON INVENTORY SYSTEM

---

## 📔 Modules:

### 🪙 Token

-   [x] Add Token Balance
-   [ ] Adjust Token Balance
-   [x] Pullout Defective Token
-   [ ] Disburse Token
-   [ ] Receive Token (Acctg)
-   [ ] Receive Token (Store)
-   [ ] Token Inventory
-   [ ] Token History

### 💊 Capsule

-   [ ] Capsule Refill
-   [ ] Capsule Return
-   [ ] Capsule Swap
-   [ ] Capsule Merge
-   [ ] Capsule Inventory
-   [ ] Capsule History

### 📃 Submaster

-   [x] Gasha Machine List
-   [x] Location
-   [ ] GIMFS
-   [x] Mode of Payment
-   [ ] Token Conversion
-   [ ] Token Conversion History
-   [ ] Token Swap History
-   [x] Float Types
-   [x] Float Entries
-   [x] Cash Float History
-   [x] Cash Float Reconcile

---

## 💻 Merging Your Local Repo from Remote

1. Pull from the remote with github using `git pull`.
2. Check if you have remaining migration files that need to be migrated. Make sure your .env file has the correct credentials of your local machine DB. You can check the status of your migration by running `php artisan migrate:status` on your terminal. Run `php artisan migrate` if there are pending migrations.
3. Run `php artisan db:seed --force` to seed your DB.

---

# 📄 GASHAPON INVENTORY SYSTEM

---

## 📔 Modules

### 🪙 Token

-   [x] Add Token Balance
-   [ ] Adjust Token Balance
-   [x] Pullout Defective Token
-   [x] Disburse Token
-   [ ] Receive Token (Acctg)
-   [x] Receive Token (Store)
-   [x] Token Inventory
-   [x] Token History

### 💊 Capsule

-   [x] Capsule Refill
-   [x] Capsule Return
-   [ ] Capsule Swap
-   [ ] Capsule Merge
-   [x] Capsule Inventory
-   [x] Capsule History

### 💳 Audit

-   [x] Collect Token

### 📃 Submaster

-   [x] Gasha Machine List
-   [x] Location
-   [x] Items
-   [x] Mode of Payment
-   [x] Token Conversion
-   [x] Token Conversion History
-   [ ] Token Swap History
-   [x] Float Types
-   [x] Float Entries
-   [x] Cash Float Reconcile
-   [x] Token Action Type
-   [x] Capsule Action Type
-   [x] Sub Location
-   [x] Statuses

### 🕒 History

-   [x] Receive Token History
-   [x] Cash Float History

---

## 💻 Updating Your Local Repo from Remote

1. Pull from the remote with github using `git pull`.
2. Check if you have remaining migration files that need to be migrated. Make sure your .env file has the correct credentials of your local machine DB. You can check the status of your migration by running `php artisan migrate:status` on your terminal. Run `php artisan migrate` if there are pending migrations.
3. Run `php artisan db:seed --force` to seed your DB.

---

## ⭕ Creating a New Model in Team Workspace

1. Run `php artisan make:model SampleModel -m` on your terminal. This creates a migration file in `database\migrations` and a "SampleModel.php" file in `app\Models` directory. It is a recommended to use CamelCase in creating your model name as it is a naming convention for classes.
2. Modify your migration file. Add necessary columns based on table requirements.
3. Move your model php file on the parent folder designated (e.g. Submaster, Token, Capsule). If the folder does not exist yet, create one.
4. Update namespace path of your model based on its parent folder. (e.g. `namespace App\Models\Submaster;`).
5. Run `php artisan migrate` to migrate the remaining migrations.

---

## ⭕ Creating a New Module and Seeding the DB

1. Create a module in crudbooster module generator.
2. Move the generated controller in its parent folder. (e.g. Submaster, Token, Capsule).
3. Update namespace of your controller file based on its parent folder (e.g. `namespace App\Http\Controllers\Submaster;`)
4. Update the involved seeder files in `database\seeders` path.
5. If you created a new seeder, make sure to add it on the main db seeder file: `database/seeders/DatabaseSeeder.php`.
6. Run `php artisan db:seed --force` to seed the db.

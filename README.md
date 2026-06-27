# نظام إدارة الموارد البشرية (إدارة الموظفين، الميزانيات، التقارير)
==============================

### Overview & Project Purpose

نظام إدارة الموارد البشرية هو تطبيق إلكتروني مصمم لتعزيز كفاءة إدارة الموظفين، الميزانيات، والتقارير في المنظمات. يهدف هذا المشروع إلى توفير منصة مركزية لجمع وتحليل البيانات المتعلقة بالموظفين، مما يسهل اتخاذ القرارات الاستراتيجية.

### Project Structure Mapping


.
├── docker-compose.yml
├── .env
├── app
│   ├── config
│   │   └── database.php
│   ├── controllers
│   │   ├── EmployeeController.php
│   │   ├── BudgetController.php
│   │   └── ReportController.php
│   ├── models
│   │   ├── Employee.php
│   │   ├── Budget.php
│   │   └── Report.php
│   ├── routes
│   │   ├── web.php
│   │   └── api.php
│   ├── views
│   │   ├── employees
│   │   ├── budgets
│   │   └── reports
│   └── public
│       └── index.php
├── database
│   ├── migrations
│   │   └── 2022_01_01_000000_create_employees_table.php
│   ├── seeds
│   │   └── DatabaseSeeder.php
│   └── schema.sql
├── tests
│   ├── Unit
│   │   └── EmployeeTest.php
│   └── Feature
│       └── EmployeeTest.php
└── composer.json


### Running the Environment using Docker-Compose

1. تأكد من أن Docker و Docker-Compose مثبتين على جهازك.
2. افتح ترمينال وانتقل إلى مجلد المشروع.
3. استخدم الأمر التالي لتشغيل بيئة التطوير باستخدام Docker-Compose:

bash
docker-compose up -d


4. بعد تشغيل بيئة التطوير، استخدم الأمر التالي لتشغيل ترمينال Docker:

bash
docker-compose exec app bash


5. الآن يمكنك تشغيل تطبيق Laravel باستخدام الأمر التالي:

bash
php artisan serve


6. افتح متصفح ويب ومرر إليه عنوان URL `http://localhost:8000` لفتح تطبيق Laravel.

### Listing of Modules, Tables, and Roles

#### Modules

* إدارة الموظفين
* إدارة الميزانيات
* إدارة التقارير

#### Tables

* `employees`
* `budgets`
* `reports`

#### Roles

* `admin`
* `hr`
* `employee`

### Contact Developer Details

* **Developer Name:** [Your Name]
* **Email:** [your_email@example.com](mailto:your_email@example.com)
* **Phone:** [your_phone_number]
* **LinkedIn:** [your_linkedin_profile](https://www.linkedin.com/in/your_linkedin_profile)
* **GitHub:** [your_github_profile](https://github.com/your_github_profile)

---

## 📧 للتواصل (Contact)
almednyakrm@gmail.com

# منصة التعليم الإلكتروني (دورات، مواد، اختبارات، سجلات الطلاب)

## Overview & Project Purpose

منصة التعليم الإلكتروني هي منصة تعليمية متكاملة تقدم دورات، مواد، اختبارات، و سجلات الطلاب. تهدف المنصة إلى تسهيل عملية التعليم عن بُعد وتوفير تجربة تعليمية ممتازة للطلاب.

### Project Purpose

* تقديم دورات تعليمية متكاملة
* إدارة مواد واختبارات
* تتبع سجلات الطلاب
* تسهيل عملية التعليم عن بُعد

## Project Structure Mapping


.
├── app
│   ├── __init__.py
│   ├── models
│   │   ├── __init__.py
│   │   ├── course.py
│   │   ├── material.py
│   │   ├── student.py
│   │   └── test.py
│   ├── routes
│   │   ├── __init__.py
│   │   ├── course.py
│   │   ├── material.py
│   │   └── student.py
│   ├── schemas
│   │   ├── __init__.py
│   │   ├── course.py
│   │   ├── material.py
│   │   └── student.py
│   ├── services
│   │   ├── __init__.py
│   │   ├── course.py
│   │   ├── material.py
│   │   └── student.py
│   └── utils
│       ├── __init__.py
│       └── db.py
├── config
│   ├── __init__.py
│   └── settings.py
├── docker-compose.yml
├── Dockerfile
├── requirements.txt
└── run.sh


## Step-by-Step Instructions on Running the Environment using Docker-compose up

1. Clone المشروع إلى جهازك:
bash
git clone https://github.com/your-username/your-repo-name.git

2. انتقل إلى مجلد المشروع:
bash
cd your-repo-name

3. قم بتشغيل docker-compose:
bash
docker-compose up

4. انتظر حتى المشروع يبدأ العمل.
5. افتح متصفحك ومرر إلى `http://localhost:5000` لفتح المنصة.

## Listing of Modules, Tables, and Roles

### Modules

* دورات (Courses)
* مواد (Materials)
* اختبارات (Exams)
* سجلات الطلاب (Student Records)

### Tables

* `courses`
* `materials`
* `exams`
* `students`
* `student_records`

### Roles

* `admin`: يمكن للعضوين الذين يملكون هذا الدور إدارة جميع الأجزاء من المنصة.
* `teacher`: يمكن للعضوين الذين يملكون هذا الدور إضافة و إدارة الدورات والمواد.
* `student`: يمكن للعضوين الذين يملكون هذا الدور إضافة و إدارة سجلاتهم.

## Contact Developer Details

* اسم المطور: [Your Name]
* البريد الإلكتروني: [your-email@example.com](mailto:your-email@example.com)
* رقم الهاتف: [Your Phone Number]
* GitHub: [your-github-username](https://github.com/your-github-username)

---

## 📧 للتواصل (Contact)
almednyakrm@gmail.com

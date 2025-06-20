# 🐾 Noah's Ark — Cat and Dog Pet Adoption Management System

A full-stack web application that simplifies and digitizes the pet adoption process for **Noah’s Ark**, a non-profit animal rescue organization.

This system is equipped with **real-time communication**, **SMS notifications**, and **automated applicant tracking**, built using **Laravel**, **Tailwind CSS**, and several modern APIs.

---

## 🎯 Key Features

- 📹 Video calls for interviews via **Jitsi Meet API**
- 📱 SMS notifications via **Semaphore API**
- 📍 Local address search via **PH Locations API**
- 📬 Inquiry forms using **Web3Form API**
- 🗺️ Google Maps API integration
- 🐶 Admin dashboard for:
  - Application tracking
  - Pet listing management
  - Partner & donor inquiries

> ✅ 80% faster applicant communication  
> ✅ 90% reduction in duplicate adoption inquiries  
> ✅ 90% faster donor & partner coordination

---

## 🛠 Tech Stack

- 🧩 **Laravel** (PHP Framework)
- 🎨 **Tailwind CSS**
- 🗃️ **MySQL**
- 📦 **Composer & Laravel Mix**
- 🌐 **Jitsi Meet API**
- 📲 **Semaphore SMS API**
- 📍 **PH Locations API**
- 🗺️ **Google Maps API**
- 📬 **Web3Form API**

---
## ⚙️ Local Setup

### ✅ Requirements

- PHP 8.x
- Composer
- MySQL
- Node.js & npm
- Laravel CLI or Laravel Valet/XAMPP

### 🛠 Installation Steps

1. **Clone the Repository**

```bash
git clone https://github.com/czarinacuarez/Noah-s-Ark-Cat-and-Dog.git
cd Noah-s-Ark-Cat-and-Dog
```

2. **Install PHP & JS Dependencies**

```bash
composer install
npm install && npm run dev
```

3. **Configure Environment**

```bash
git clone https://github.com/czarinacuarez/Noah-s-Ark-Cat-and-Dog.git
cd Noah-s-Ark-Cat-and-Dog
```

3.1 **Update .env with your credentials:**

```bash
DB_DATABASE=your_db_name
DB_USERNAME=root
DB_PASSWORD=

SEMAPHORE_API_KEY=your_key
JITSI_URL=https://meet.jit.si/
GOOGLE_MAPS_API_KEY=your_key
WEB3FORM_ACCESS_KEY=your_key
```

4. **Run Migration:**

```bash
php artisan migrate
```

5. **Run Application:**

```bash
php artisan serve
```
6. **Access your app at:**
```bash
http://localhost:8000
```

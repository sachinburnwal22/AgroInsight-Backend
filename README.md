# 🌾 AgroInsight Backend

Backend API for **AgroInsight** — a data-driven agricultural analytics platform built using Laravel.

This backend handles data related to land holding, irrigation, and cropping patterns across regions.

---

## 🚀 Tech Stack

* 🐘 PHP (Laravel)
* 🗄️ MySQL
* 🔗 REST API
* 🧪 Eloquent ORM

---

## 🎯 Features

* 📍 Region-based data management
* 🌱 Land holding analysis
* 💧 Irrigation tracking
* 🌾 Crop & cropping pattern analysis
* 📊 Analytics API for dashboard
* 🔗 Clean RESTful APIs

---

## 📂 Database Structure

### Tables:

* `regions`
* `land_holdings`
* `irrigations`
* `crops`
* `cropping_patterns`

---

## 🔗 Relationships

```
Region
 ├── LandHolding (1:1)
 ├── Irrigation (1:M)
 └── CroppingPattern (1:M)
        └── Crop (M:1)
```

---

## ⚙️ Setup Instructions

### 1. Install dependencies

```bash
composer install
```

### 2. Configure environment

Update `.env`:

```
DB_DATABASE=agroinsight
DB_USERNAME=root
DB_PASSWORD=
```

---

### 3. Run migrations

```bash
php artisan migrate
```

---

### 4. Seed database

```bash
php artisan db:seed
```

---

### 5. Start server

```bash
php artisan serve
```

---

## 🔗 API Endpoints

### 📍 Regions

```
GET /api/regions
GET /api/regions/{id}
```

---

### 📊 Analytics

```
GET /api/analytics
```

Returns:

```
{
  "stats": {
    "avg_land_size": number,
    "avg_irrigation": number,
    "top_crop": string
  }
}
```

---

## 🧠 Data Logic

* Calculates average land size
* Determines top crop based on usage
* Computes irrigation coverage

---

## 🔒 CORS

Configured to allow frontend access:

```
allowed_origins: *
```

---

## 🔮 Future Enhancements

* 🤖 AI-based agricultural insights
* 🌦️ Weather API integration
* 📄 Report generation
* 🔐 Authentication (Sanctum)

---

## 👨‍💻 Author

Backend built using Laravel for scalable and clean API architecture.

---

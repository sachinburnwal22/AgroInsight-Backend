# 🐘 AgroInsight Backend API

[![Laravel](https://img.shields.io/badge/Laravel-11-red.svg?logo=laravel&logoColor=white)](https://laravel.com/)
[![PHP](https://img.shields.io/badge/PHP-8.2-purple.svg?logo=php&logoColor=white)](https://www.php.net/)
[![SQLite](https://img.shields.io/badge/Database-SQLite-003B57.svg?logo=sqlite&logoColor=white)](https://sqlite.org/)
[![Google Gemini](https://img.shields.io/badge/AI_Engine-Gemini_2.5-8E75C2.svg?logo=google-gemini&logoColor=white)](https://deepmind.google/technologies/gemini/)

The RESTful API engine for **AgroInsight** providing data management, user profile authentication, region-specific crop intelligence, real-time RSS syncing, and Gemini-based summaries and translations.

---

## 🏛️ Database Tables & Relations

```
    +------------------+         +------------------+         +--------------------+
    |      users       |-------->|  saved_articles  |<--------|   news_articles    |
    | (lat, lng, reg)  | 1:M     | (user_id, art_id)|     M:1 |(summary, category) |
    +------------------+         +------------------+         +--------------------+
             |                                                
             | 1:M                                            +--------------------+
             v                                                | government_schemes |
    +------------------+                                      | (state, category)  |
    |  weather_alerts  |                                      +--------------------+
    | (severity, msg)  |                                      
    +------------------+                                      +--------------------+
                                                              | government_alerts  |
                                                              | (MSP, deadlines)   |
                                                              +--------------------+
```

- **`news_articles`**: Stores parsed agricultural headlines, source details, categories, and cached summaries.
- **`government_schemes`**: Contains central and state-level farmer subsidy, loan, and support criteria.
- **`saved_articles`**: Junction table mapping user bookmarks.
- **`government_alerts`**: Tracks global announcements, weather alerts, and application deadlines.

---

## 🔗 RESTful API Endpoints

### 📰 AgriIntel News Feed
| Method | Endpoint | Auth | Description |
| :--- | :--- | :---: | :--- |
| **`GET`** | `/api/news/live` | Open | Synchronizes latest PIB RSS feeds and returns news with filters. |
| **`GET`** | `/api/news/trending` | Open | Returns current trending/MSP updates. |
| **`GET`** | `/api/news/location-based` | Sanctum | Returns personalized news based on user state and crops. |
| **`POST`** | `/api/news/{id}/ai-summary` | Open | Translates/simplifies news details into chosen language via Gemini. |

### 🏛️ Government Schemes & Subsidies
| Method | Endpoint | Auth | Description |
| :--- | :--- | :---: | :--- |
| **`GET`** | `/api/schemes/all` | Open | Returns all government schemes (with state/category filters). |
| **`GET`** | `/api/schemes/state/{state}` | Open | Lists schemes applicable to the chosen state. |
| **`POST`** | `/api/schemes/{id}/explain` | Open | Invokes Gemini to explain scheme criteria and steps in a chosen language. |

### 🔔 Government Announcements & Bookmarks
| Method | Endpoint | Auth | Description |
| :--- | :--- | :---: | :--- |
| **`GET`** | `/api/alerts/government` | Open | Fetches global and regional notifications for the navbar bell. |
| **`POST`** | `/api/articles/save` | Sanctum | Toggles saved/bookmarked status of a news article. |
| **`GET`** | `/api/articles/saved` | Sanctum | Fetches list of saved articles for the authenticated farmer. |

---

## ⚙️ Core Services & Logic

### 1. Dynamic News Sync (`App\Services\NewsSyncService.php`)
- Queries official Press Information Bureau (PIB) RSS feeds in both Hindi and English.
- Evaluates category tags (e.g. MSP, Technology, Weather) using text-matching heuristics.
- Incorporates a **10-minute cache lock** to prevent redundant HTTP requests and ensure high performance.

### 2. Gemini AI Integration (`App\Services\GeminiService.php`)
- Communicates directly with the `gemini-2.5-flash` model.
- Uses prompt templates to request clean Markdown outputs.
- Translates and simplifies complex legal/government policy wording into farmer-friendly local language advisories on demand.

---

## 🛠️ Verification Commands

```bash
# Verify routes registration
php artisan route:list --path=api

# Run automated tests
php artisan test
```

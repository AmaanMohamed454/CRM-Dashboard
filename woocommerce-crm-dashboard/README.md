# 🚀 WooCommerce CRM Dashboard (Hackathon Edition)

An ultra-modern, fully functional standalone Customer Relationship Management (CRM) dashboard built natively for WooCommerce stores.
Designed with cutting-edge Glassmorphism UI, real-time reactive arrays, and live REST API integration mapping for massive e-commerce environments. 

![Dashboard Preview](https://img.shields.io/badge/Status-Hackathon_Ready-4f46e5?style=for-the-badge) ![Version](https://img.shields.io/badge/Version-1.0.0-emerald?style=for-the-badge)

---

## ⚡ Core Features

*   **🌑 Modern Dark Mode UI:** A fully responsive Tailwind CSS framework leveraging deep contrasts, frosted glass elements, and hyper-smooth micro-animations.
*   **🔗 Live WooCommerce REST API Integration:** Secure, Basic Auth HTTP pipeline that intercepts and synchronizes live orders and data directly from `https://your-store.com/wp-json/wc/v3`. 
*   **📊 Deep Analytics Engine:** Automatically maps the ingested WooCommerce arrays into live data metrics (Average Order Value, Lifetime Value parsing, Order Distribution, etc).
*   **👑 Smart VIP Aggregation:** Intelligently maps orders to unique emails, aggregating total customer spend (LTV) and dynamically injecting top-tier purchasers with "VIP" badges if they exceed the $500 threshold.
*   **🛒 New Order System:** Allows operators to insert new orders directly into the local dashboard cache and gracefully re-calculate the sorting algorithms across all arrays instantly.
*   **🔐 Secure Storage Auth Flow:** Full Login Authentication portal that generates mock-secure browser session tokens via LocalStorage data handling.

---

## 🛠️ Built With
*   **HTML5 & CSS3** (Semantic Layout & Transitions)
*   **Vanilla JavaScript (ES6+)** (DOM Manipulation & Promise API)
*   **Tailwind CSS (CDN)** (Utility-First Styling & Glassmorphism)
*   **WooCommerce REST API** (V3 Authentication)
*   **FontAwesome Icons** (UI Visuals)

---

## 📂 Project Structure (What to Upload to GitHub)

When uploading this project, ensure you include these exact files in the root of your repository:

```text
📁 Your-Repository-Name/
│
├── 📄 dashboard.html      # Main Application Router & UI Shell
├── 📄 login.html          # Authentication Portal 
├── 📄 script.js           # Core Business Logic & API Engine
├── 📄 README.md           # Documentation
└── 📦 WooCRM-Hackathon-Submission.zip # Optional WordPress Plugin Export
```

---

## 🚀 How to Run Locally

You do not need a complex Node.js or PHP server to preview the front-end application!

1. Clone or download this repository.
2. Open `login.html` directly in **Google Chrome** or **Firefox**.
3. Create a dummy account to generate the LocalStorage session token.
4. You will be seamlessly redirected into `dashboard.html`.

**Testing the Live API Integration:**
1. Navigate to the bottom left **Settings** menu and click `API Integrations`.
2. Delete the Demo Store URL and enter your actual WooCommerce Store URL (e.g. `https://my-store.com`).
3. Enter your generated WooCommerce API **Consumer Key (CK)** and **Consumer Secret (CS)**.
4. Connect! The application overrides all local mockup arrays, triggering an asynchronous HTTP `fetch` to instantly render your live store sales matrix.

---
*Built with ❤️ for the 2026 Developer Hackathon.*

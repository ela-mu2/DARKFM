# 📻 DarkFM - Modern Dark-Tone Radio Management System

A linear scheduling and program management system tailored for independent radio stations. The front end features a cool Minimalist dark-toned UI (Teal & Slate color scheme), while the back end is based on a PHP PDO architecture, enabling efficient and secure data-driven operations.

---

## 🚀 Project Features

Project Features

- **Stylish Dark Aesthetics:** Utilizes a Minimalist & Dark Mode UI design, paired with bright neon-bordered cards for an exceptional visual experience.

- **Dynamic Data Rendering:** Asynchronous decoupling between front-end JavaScript and back-end API enables dynamic loading of program schedules and host information without page refreshes.

- **Permission Role Isolation:**

- `Admin`: Full permissions, controlling user accounts, radio station configurations, and core data CRUD operations.

- `Editor`: Manages scheduling and program libraries, but cannot modify system-level settings.

- `User`: Pure front-end interaction, seamlessly browsing today's programs and live broadcast status.

- **Secure Data-Driven Approach:** The back-end fully utilizes PHP PDO prepared statements, eliminating SQL injection risks from the ground up.

---

## 🛠️ Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript (ES6+), Bootstrap 5
- **Backend**: PHP (PDO)
- **Database**: MySQL

---

## 📂 Current Project Structure

```text

├── db.php      # Database underlying PDO connection configuration
├── css         # Dark-themed custom stylesheet
├── login.html     # Login page
├── signup.html    # Registration page
├── dashboard.html # Backend management homepage
└── index.html     # Radio frontend display homepage

```

# 📻 DarkFM - 现代感暗调电台管理系统

一个为独立电台量身定制的线性排班与节目管理系统。前端采用炫酷的 Minimalist 暗色调 UI（Teal & Slate 配色），后端基于 PHP PDO 架构，实现高效、安全的数据驱动。

---

## 🚀 项目特点

- **炫酷暗黑美学**：采用 Minimalist & Dark Mode UI 设计，搭配高亮霓虹边框卡片，视觉体验极佳。
- **动态数据渲染**：前端 JavaScript 与后端 API 异步解耦，实现节目单、主持人信息的无刷新动态加载。
- **权限角色隔离**：
  - `Admin`：全权限，掌控用户账号、电台配置及核心数据增删改查。
  - `Editor`：管理排班与节目库，无法更动系统级设置。
  - `User`：纯前端交互，无缝浏览今日节目与直播状态。
- **安全数据驱动**：后端全面采用 PHP PDO 预处理语句，从底层杜绝 SQL 注入风险。

---

## 🛠️ 技术栈

- **Frontend**: HTML5, CSS3, JavaScript (ES6+), Bootstrap 5
- **Backend**: PHP (PDO)
- **Database**: MySQL

---

## 📂 目前项目结构

```text
├── db.php          # 数据库底层 PDO 连接配置
├── css             # 暗色调自定义样式表
├── login.html          # 登录页
├── signup.html         # 注册页
├── dashboard.html      # 后台管理主页
└── index.html          # 电台前端展示主页

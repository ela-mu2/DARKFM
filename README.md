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

DARKFM/
├── actions/ 					# Handles form submission and synchronous business logic (redirection after execution)
│ ├── action_edit_schedule.php # Modifies scheduling logic
│ ├── login.php 				# Handles login verification
│ └── signup.php 				# Handles user registration
├── api/ 						# Asynchronous API folder (receives Fetch requests and returns plain JSON)
│ ├── api_check_conflict.php 	# Scheduling conflict prevention detection API
│ ├── api_delete_schedule.php 	# One-click deletion of scheduling API
│ └── api_get.php 				# Dynamic data retrieval API
├── config/ 					# Global basic configuration
│ └── db.php 					# PDO database connection
├── includes/ 					# Public components and static resources
│ ├── assets/
│ │ └── css/
│ │ └── styles.css 			# Minimalist dark style custom styles
│ └── header.php 				# Global header (including permission interception, Bootstrap 5 integration)
├── dashboard.php 				# Admin dashboard homepage
├── index.html 					# Static homepage (can be kept or modified as needed)
├── manage-posts-add.php 		# Add article/program announcement page
├── manage-schedule.php 		# Schedule management main list (including search and LIMIT pagination)
├── manage-schedule-edit.php 	# Schedule dynamic editing form page (data automatically displayed)
├── README.md 					# Project architecture documentation
└── student-guide.md 			# Development/student guide manual

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
DARKFM/
├── actions/                  		# 处理表单提交与同步业务逻辑 (执行后重定向)
│   ├── action_edit_schedule.php  	# 修改排班逻辑
│   ├── login.php             		# 处理登录验证
│   └── signup.php            		# 处理用户注册
├── api/                      		# 异步接口文件夹 (接收 Fetch 请求，返回纯 JSON)
│   ├── api_check_conflict.php    	# 排班防冲突检测接口
│   ├── api_delete_schedule.php   	# 一键删除排班接口
│   └── api_get.php               	# 动态获取数据接口
├── config/                   		# 全局基础配置
│   └── db.php                		# PDO 数据库连接
├── includes/                 		# 公共公共组件与静态资源
│   ├── assets/
│   │   └── css/
│   │       └── styles.css    		# 极简暗黑风自定义样式
│   └── header.php            		# 全局头部（包含权限拦截、Bootstrap 5 引入）
├── dashboard.php             		# 管理员仪表盘主页
├── index.html                		# 静态首页 (可根据需求保留或删改)
├── manage-posts-add.php      		# 添加文章/节目公告页
├── manage-schedule.php       		# 排班管理主列表 (包含搜索与 LIMIT 分页)
├── manage-schedule-edit.php  		# 排班动态编辑表单页 (数据自动回显)
├── README.md                 		# 项目架构说明文档
└── student-guide.md          		# 开发/学生指南手册
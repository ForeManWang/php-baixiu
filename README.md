# 项目简介

基于**`PHP+Apach`**的后台管理系统开发

## 项目目标

1. 基本动态网站的实现
2. 网站后台管理的基本功能（增删改查）
3. B/S框架思维
4. JavaScript JQuery AJAX等

## 项目部分功能

一个自媒体信息发布平台

**管理员（编辑）可以通过后台管理界面发布或者维护自媒体内容**

![baixiu主页展示](/assets/baixiu主页展示.png)

### 功能简介

- **用户登录（login.php）**

  - 根据用户是否填写表单内容或者表单内容是否正确来决定是否拒绝用户登录操作
  - 管理员可以通过用户名和密码登录到后台

- 默认页面（index.php）

  - **图表展示网站整体数据**

    ![网站整体数据](/assets/网站整体数据.png)

- 文章管理（posts.php、post-add.php、categories.php）

  - 所有文章（posts.php）

    - **页面呈现数据库查询到的数据**

    - **侧边栏高亮**

    - **筛选功能**

    - **分页功能**

    - **编辑和删除功能**

    ![文章展示](/assets/文章展示.png)

  - 写文章（post-add.php）

    - **添加文章**

    - **富文本编辑**

    ![写文章](/assets/写文章.png)

  - 分类目录（categories.php）

    - **添加、编辑、删除、查询**

    ![分类目录](/assets/分类目录.png)

- 评论管理

  - **数据库查询后数据呈现**

  - **分页功能**

  - **驳回、拒绝、删除、查询操作**

    ![评论管理](/assets/评论管理.png)

- 用户管理

  - **表单验证**

  - **添加、编辑、删除、查询等**

    ![用户管理](/assets/用户管理.png)

- 后台设置（nav-menus.php、slides.php、settings.php）

  - 导航菜单（nav-menus.php）

    - **表单验证**

    - **添加、编辑、删除、查询等**

    ![导航菜单](/assets/导航菜单.png)

  - **图片轮播（皮肤）**

    ![图片轮播](/assets/图片轮播.png)


  - 网站设置

    - **表单优化**

    ![表单优化](/assets/表单优化.png)

- 电影榜单

  - **请求非同源api获取在线数据**

    ![电影榜单](/assets/电影榜单.png)

  

- **用户可以通过前台查看内容**

  ![前台首页](/assets/前台首页.png)

  ## 公共模块

  **首页、侧边栏和中心版块**

## 项目文档

这里有不同模块的文档详细说明

###**[基本项目流程](https://github.com/ForeManWang/php-baixiu/blob/master/baixiu-docs/基本项目流程.md)**

###**[准备工作](https://github.com/ForeManWang/php-baixiu/blob/master/baixiu-docs/准备工作.md)**

###**[管理后台登录](https://github.com/ForeManWang/php-baixiu/blob/master/baixiu-docs/管理后台登录.md)**

###**[管理后台首页](https://github.com/ForeManWang/php-baixiu/blob/master/baixiu-docs/管理后台首页.md)**

###**[文章展示](https://github.com/ForeManWang/php-baixiu/blob/master/baixiu-docs/文章展示.md)**

###**[写文章](https://github.com/ForeManWang/php-baixiu/blob/master/baixiu-docs/写文章.md)**

###**[分类管理](https://github.com/ForeManWang/php-baixiu/blob/master/baixiu-docs/分类管理.md)**

###**[评论管理](https://github.com/ForeManWang/php-baixiu/blob/master/baixiu-docs/评论管理.md)**

###**[用户管理](https://github.com/ForeManWang/php-baixiu/blob/master/baixiu-docs/用户管理.md)**

### **[异步上传](https://github.com/ForeManWang/php-baixiu/blob/master/baixiu-docs/异步上传.md)**


# Hospital Administration System

#### This README is split into three sections and a few subsections:

- [About](#about)
  
  - [What it taught me?](#what-it-taught-me)

- [Setup](#setup)
  
  - [Requirements](#requirements)
  
  - [Configuration](#configuration)

- [Guide](#guide)

## :grey_question:About

The purpose of this project was to make me learn basics of **PHP** with my prior knowledge of programming in other languages such as **C/C++**, **Javascript** and **Lua**.

Before i even wrote my first line of code in PHP, i realized that PHP is not so powerful without a database and therefore this project became more valuable to me.

### :bulb:What it taught me?

- **PHP basics**

- **SQL Code** *(helped in understanding)*

- Some **.htaccess** configuration code *(Apache)*

## :books: Setup

### :gear: Requirements:

| Technology        | Version             |
|:-----------------:|:-------------------:|
| **PHP**           | 8.0.0+              |
| **SQL Database**  | MySQL *(preferred)* |
| **Apache Server** | Version 2.2+        |

You can use XAMPP to run this project.

### :wrench: Configuration:

1. Once everything is installed you should go to the SQL Server and create database called `hospital` (case sensitive).
   
   - Optionally you can also create a user that has full rights <ins>ONLY</ins> to this database.

2. After that you should open `/logic/model.php` and configure first  7 lines of this PHP code: 
   
   ```php
   <?php
   $dbHostname = "localhost";
   $dbUsername = "root";
   $dbPassword = "";
   $dbName = "hospital";
   $dbPort = 3306;
   $db = new mysqli($dbHostname, $dbUsername, $dbPassword, $dbName, $dbPort);
   ```
   
   - If you just installed XAMPP you can skip this configuration step.

3. When you're done, you can place everything in `htdocs` folder of apache server, run both apache and database and move to the next step.

4. If you try to open the page, you should see this: ![Setup screen](README/Setup.png)
   
   - Here you just need to fill all of the fields to create the admin account. Example:
     
     ![Filled setup screen](README/Filled%20Setup.png)

5. Once that is done, you're finished with the configuration section. Now you can see guide to learn about the system.

## :world_map: Guide

> Work In Progress



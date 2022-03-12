# Hospital Administration System

#### This README is split into three sections and a few subsections:

- [About](#grey_questionabout)
  
  - [What it taught me?](#bulbwhat-it-taught-me)

- [Setup](#bookssetup)
  
  - [Requirements](#gearrequirements)
  
  - [Configuration](#wrenchconfiguration)

- [Guide](#world_mapguide)
  
  - [Homepage](#househomepage)
  - [Login](#page_facing_uplogin)
  - [System](#computersystem)
    - [Navbar](#navbar)
    - [Roles](#roles)
    - [Tools](#tools)

## :grey_question:About

The purpose of this project was to make me learn basics of **PHP** with my prior knowledge of programming in other languages such as **C/C++**, **Javascript** and **Lua**.

Before i even wrote my first line of code in PHP, i realized that PHP is not so powerful without a database and therefore this project became more valuable to me.

### :bulb:What it taught me?

- **PHP basics**

- **SQL Code** *(helped in understanding)*

- Some **.htaccess** configuration code *(Apache)*

- **GIT** (learning more elements)

- **HTTP requests**

## :books:Setup

### :gear:Requirements:

| Technology        | Version             |
|:-----------------:|:-------------------:|
| **PHP**           | 8.0.0+              |
| **SQL Database**  | MySQL *(preferred)* |
| **Apache Server** | Version 2.2+        |

You can use XAMPP to run this project.

### :wrench:Configuration:

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

## :world_map:Guide

- ## :house:Homepage
  
  When you go on the website you should be met with this screen: ![](README/Homepage.png)
  
  This page is just some sample page to give some context to the whole project.
  
  It is made out of 4 pages accessible on top of the every page: Home, Offer, Contact and Login. All **3** of them are sample pages, except for **Login** which has it's own section.

- ## :page_facing_up:Login
  
  This is login page: ![](README/Login.png)
  
  On top you have a button to go back to homepage and on the middle login form.
  
  *What are the credentials?* If you remember the setup page, there was **login** field and **password**. In my scenario ([Setup/Configuration](#wrenchconfiguration)) login is: `Blixon` and password: <code>····</code>. If by any accident you type invalid credentials, we've got you covered with simple error telling what's wrong. *For example, missing field:* ![](README/LoginError.png)

- ## :computer:System
  
  - ## Navbar
    
    It is split into 2 parts as on the image: ![](README/Navbar.png)
    
    1. **Tools** - They vary from priveleges to priveleges.
       
       - [List of all tools: **Search**, **Appointments**, **Add**, **Remove**]
    
    2. **Login** - It can be broken down into 2 elements.
       
       1. User's name, which is First name and first letter of Last name.
       
       2. Logout button to log out of the system.
  
  - ## Roles
    
    This section gives a short description of every role and what tools they can access, including image of navbar.
    
    1. **Admin** - He's a computer guy that does the computer stuff, fixes printers, etc.. Has access to every tool.![](README/AdminNav.png)
    
    2. **Doctor** - Fixes humans. Has access to: search, appointments and add. ![](README/DoctorNav.png)
    
    3. **Nurse** - Helps doctors and something else, i don't know. Has access to: appointments and add. ![](README/NurseNav.png)
    
    4. **Patient** - Wants help from doctors. Has only access to appointments. ![](README/PatientNav.png)
  
  - ## Tools
  
  > Work in progress

<body>
    <nav>
        <div class="center">
            <div id="buttons">
                <button id="search" style="background-image: url(assets/dbsearch.png);" ></button>
                <button id="appointments" style="background-image: url(assets/appointments.png);" ></button>
                <button id="add" style="background-image: url(assets/plus.png);" ></button>
                <button id="remove" style="background-image: url(assets/minus.png);" ></button>
            </div>
        </div>
        <div class="center">
            <div id="user">
                <?php
                $data = getUserByLogin($_SESSION['login']);
                ?>
                <p <?php echo ($data['Type'] === "Admin" ? 'style="color: red;"' : "")  ?> ><?php echo $data['FirstName']." ".$data['LastName'][0]."." ?></p>
                <button id="logout" style="background-image: url(assets/logout.png);" ></button>
            </div>
        </div>
    </nav>
<body>
    <nav>
        <div class="center">
            <div id="buttons">
            <?php if(canAccess(["Doctor"])){ ?>
                <button id="search" style="background-image: url(assets/dbsearch.png);" ></button>
            <?php } ?>
            <button id="appointments" style="background-image: url(assets/appointments.png);" ></button>
            <?php if(canAccess(["Doctor", "Nurse"])){ ?>
                <button id="add" style="background-image: url(assets/plus.png);" ></button>
            <?php } if(canAccess()){ ?>
                <button id="remove" style="background-image: url(assets/minus.png);" ></button>
            <?php } ?>
            </div>
        </div>
        <div class="center">
            <div id="user">
                <?php
                $data = getUserByLogin($_SESSION['login']);
                ?>
                <p 
                <?php 
                $color = "";
                switch ($data['Type'])
                {
                    case "Admin":
                        $color = 'style="color: red;"';
                        break;
                    case "Doctor":
                        $color = 'style="color: blue;"';
                        break;
                }
                echo $color;
                echo 'title="'.$data['Type'].'"'?> ><?php echo $data['FirstName']." ".$data['LastName'][0]."." ?></p>
                <button id="logout" style="background-image: url(assets/logout.png);" ></button>
            </div>
        </div>
    </nav>
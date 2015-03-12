<?php require_once("autoload.php"); ?><html>
    <head>
        <title>Sign Up/Login</title>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    </head>
    <style>
        label{
            display: block;
        }
    </style>
    <body>
    
        <form action="signup.php" method="post" enctype="multipart/form-data">
            <h1>Sign up</h1>
            <label>
                Username: <input type="text" name="username" />
            </label>
            <label>
                Password: <input type="password" name="password" />
            </label>
            <label>
                Confirm Password: <input type="password" name="confirm_password" />
            </label>
            <button type="submit">Register</button>
        </form>
        
        <form action="login.php" method="post" enctype="multipart/form-data">
            <h1>Sign in</h1>
            <label>
                Username: <input type="text" name="username" />
            </label>
            <label>
                Password: <input type="password" name="password" />
            </label>
            <button type="submit">Login</button>
        </form>
        
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Color</th>
                    <th>Species</th>
                    <th>Breed</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $animals = \DBObject\Animal::getAnimals();
                    foreach($animals as $animal){
                ?>
                    <tr>
                        <td><?php echo $animal->name ?></td>
                        <td><?php echo $animal->color ?></td>
                        <td><?php echo $animal->species->type ?></td>
                        <td><?php echo $animal->formattedBreeds ?></td>
                        <td><a href="animal.php?id=<?php echo $animal->id ?>"><i class="fa fa-eye"></i></a></td>
                    </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </body>
</html>
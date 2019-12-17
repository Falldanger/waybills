<?php
include_once 'controllers\connectionController.php';
include_once 'controllers\indexController.php';

use controllers\Connection;
use controllers\indexController;

$startView = new indexController(new Connection());
$dataForEdit = $startView->getDataById($_GET['page']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Подключаем Bootstrap CSS -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <!-- Подключаем JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link href="/css/style.css" rel="stylesheet" type="text/css"/>
    <link rel="shortcut icon" href="#"/>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="/">Waybills</a>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<br><br><br>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <form action="api.php" id="form2" method="post">
                <div class="form-group">
                    <label for="ware_name">Ware name</label>
                    <input type="text" class="form-control" id="ware_name" name="ware_name"
                           value="<?php if (isset($_POST['ware_name'])) {
                               echo $_POST['ware_name'];
                           } else {
                               echo $dataForEdit['name'];
                           } ?>">
                </div>
                <div class="form-group">
                    <label for="desc">Description</label>
                    <textarea class="form-control" id="desc" rows="2"
                              name="desc"><?php if (isset($_POST['desc'])) {
                            echo $_POST['desc'];
                        } else {
                            echo $dataForEdit['desc'];
                        } ?></textarea>
                </div>
                <div class="form-group">
                    <label for="units">Units</label>
                    <input type="text" class="form-control" id="units" name="units"
                           value="<?php if (isset($_POST['units'])) {
                               echo $_POST['units'];
                           } else {
                               echo $dataForEdit['units'];
                           } ?>">
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="text" class="form-control" id="price" name="price"
                           value="<?php if (isset($_POST['price'])) {
                               echo $_POST['price'];
                           } else {
                               echo $dataForEdit['price'];
                           } ?>">
                </div>
                <div class="form-group">
                    <label for="count">Count</label>
                    <input type="text" class="form-control" id="count" name="count"
                           value="<?php if (isset($_POST['count'])) {
                               echo $_POST['count'];
                           } else {
                               echo $dataForEdit['count'];
                           } ?>">
                </div>
                <div class="form-group">
                    <input type="hidden" class="form-control" id="id" name="id"
                           value="<?php echo $_GET['page'] ?>">
                </div>
                <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Submit">
            </form>
        </div>
        <div class="col-md-4 offset-md-4" id="result">

            <?php

            if (!empty($_GET) || !empty($_POST)) {
                $data = $startView->getDataById($_GET['page']);
                foreach ($data as $key => $value) {
                    echo $key . ': ' . $value . '</br>';
                }
            }
            ?>

        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    //function to add data
    $(document).ready(function () {
        $('#form2').submit(function (event) {
            var serialize = $('#form2').serializeArray();
            console.log({serialize, 'edit': 1});
            event.preventDefault();
            edit({serialize, 'edit': 1});
        });
    });

    function edit(data) {
        $.ajax({
            url: 'api.php',
            type: "POST",
            data: data,
            dataType: "text",
            error: error,
            success: success
        });
    }

    function error() {
        alert('error');
    }

    function success(result) {
        var newresult = $.parseJSON(result);
        console.log(newresult);

        if (typeof newresult.status !== "undefined" && newresult.status !== 200) // Status code 200 represents Success/OK
        {
            alert("Unprocessable Entity " + newresult.status + '!' + newresult.statusText);
        } else {
            var str = '';

            function logArrayElements(element, index) {
                str = str + element[0] + ': ' + element[1] + '</br>';
            }

            var arr = Object.entries(newresult);
            console.log(arr);
            arr.forEach(logArrayElements);
            $('#result').empty();
            $('#result').append(str);
        }
    }
</script>
</body>
</html>

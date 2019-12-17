<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Подключаем Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Подключаем JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <link rel="shortcut icon" href="#"/>
</head>
<body>
<div class="container">

    <h1>Waybills</h1>
    <br>
    <div class="row">
        <div class="col-md-6">
            <label for="addInvoice"><b>Form adding new invoice:</b></label>
            <form action="api.php" id="addInvoice" method="post">
                <div class="form-group">
                    <label for="invoice_name">Invoice name</label>
                    <input type="text" class="form-control" id="invoice_name" name="invoice_name"
                           value="<?php if (isset($_POST['invoice_name'])) {
                               echo $_POST['invoice_name'];
                           } ?>">
                </div>
                <input type="submit" class="btn btn-primary" id="invoice_submit" name="invoice_submit" value="Submit">
            </form>
        </div>
        <div class="col-md-6">
            <label for="sorting"><b>Form sorting by invoices:</b></label>
            <form method="post" id="sorting" action="api.php">
                <label for="selectByInvoice">Select invoice:</label>
                <select name="select" id="selectByInvoice">
                    <?php

                    use controllers\Connection;
                    use controllers\connectionController;
                    use controllers\indexController;

                    $connection = new connectionController();
                    $startView = new indexController(new Connection());
                    foreach ($startView->select() as $keys => $values) {
                        foreach ($values as $key => $value) {
                            ?>
                            <option value="<?php echo $value; ?>"> <?php echo $value;
                        } ?> </option>
                        <?php
                    }
                    ?>
                </select>
                <input type="submit" class="btn btn-primary" id="sorting_submit" name="sorting_submit" value="Submit">
            </form>
        </div>
        <div class="col-md-6" style="margin-top: 40px;">
            <label for="form1"><b>Form adding items:</b></label>
            <form action="api.php" id="form1" method="post">
                <div class="form-group">
                    <label for="selectByInvoice2">Select invoice:</label>
                    <select name="select" id="selectByInvoice2">
                        <?php
                        foreach ($startView->select() as $keys => $values) {
                            foreach ($values as $key => $value) {
                                ?>
                                <option value="<?php echo $value; ?>"> <?php echo $value;
                            } ?> </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ware_name">Ware name</label>
                    <input type="text" class="form-control" id="ware_name" name="ware_name"
                           value="<?php if (isset($_POST['ware_name'])) {
                               echo $_POST['ware_name'];
                           } ?>">
                </div>
                <div class="form-group">
                    <label for="desc">Description</label>
                    <textarea class="form-control" id="desc" rows="2"
                              name="desc"><?php if (isset($_POST['desc'])) {
                            echo $_POST['desc'];
                        } ?></textarea>
                </div>
                <div class="form-group">
                    <label for="units">Units</label>
                    <input type="text" class="form-control" id="units" name="units"
                           value="<?php if (isset($_POST['units'])) {
                               echo $_POST['units'];
                           } ?>">
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="text" class="form-control" id="price" name="price"
                           value="<?php if (isset($_POST['price'])) {
                               echo $_POST['price'];
                           } ?>">
                </div>
                <div class="form-group">
                    <label for="count">Count</label>
                    <input type="text" class="form-control" id="count" name="count"
                           value="<?php if (isset($_POST['count'])) {
                               echo $_POST['count'];
                           } ?>">
                </div>
                <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Submit">
            </form>
        </div>
        <div class="col-md-6" style="margin-top: 40px;">
            <label for="result"><b>Table of items:</b></label>
            <table id="result">
                <?php

                if (empty($_GET) && empty($_POST)) {
                    echo $startView->index($connection->getData());
                }
                ?>
            </table>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    //function to add data
    $(document).ready(function () {
        $('#form1').submit(function (event) {
            var serialize = $('#form1').serializeArray();
            console.log({serialize, 'add': 1});
            event.preventDefault();
            add({serialize, 'add': 1});
        });
        $('#addInvoice').submit(function (event) {
            var serialize = $('#addInvoice').serializeArray();
            console.log({serialize, 'add_invoice': 1});
            event.preventDefault();
            add_invoice({serialize, 'add_invoice': 1});
        });
        $('#sorting').submit(function (event) {
            var serialize = $('#sorting').serializeArray();
            console.log({serialize, 'sort': 1});
            event.preventDefault();
            sort({serialize, 'sort': 1});
        });
    });

    //Adding items
    function add(data) {
        $.ajax({
            url: 'api.php',
            type: "POST",
            data: data,
            dataType: "text",
            error: error,
            success: success
        });
    }

    //Adding invoice
    function add_invoice(data) {
        $.ajax({
            url: 'api.php',
            type: "POST",
            data: data,
            dataType: "text",
            error: error,
            success: success_invoice
        });
    }

    //Adding invoice
    function sort(data) {
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

    //for sorting by invoice form and index
    function success(result) {
        var newresult = $.parseJSON(result);
        console.log(newresult);

        if (typeof newresult.status !== "undefined" && newresult.status !== 200) // Status code 200 represents Success/OK
        {
            alert("Unprocessable Entity " + newresult.status + '!' + newresult.statusText);
        } else {
            $('#result').empty();
            $('#result').append(newresult);
        }
    }

    //for second form
    function success_invoice(result) {
        var newresult = $.parseJSON(result);
        console.log(newresult);
        if (typeof newresult.status !== "undefined" && newresult.status !== 200) // Status code 200 represents Success/OK
        {
            alert("Unprocessable Entity " + newresult.status + '!' + newresult.statusText);
        } else {
            alert('New invoice was added successfully');
            var str = '';
            for (var i = 0; i < newresult.length; i++) {
                str += '<option value=' + newresult[i].name + '>' + newresult[i].name + '</option>';
            }
            $('#selectByInvoice').empty();
            $('#selectByInvoice').append(str);
            $('#selectByInvoice2').empty();
            $('#selectByInvoice2').append(str);
        }
    }
</script>
</body>
</html>

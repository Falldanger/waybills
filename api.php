<?php

include_once 'controllers\connectionController.php';
include_once 'controllers\indexController.php';

use controllers\Connection;
use controllers\connectionController;
use controllers\indexController;

$connection = new connectionController();
$manipulations = new indexController(new Connection());

if (isset($_POST['add_invoice']) && $_POST['add_invoice'] == 1) {
    if (!empty($data['invoice_name'] = $_POST['serialize'][0]['value'])) {
        if ($manipulations->invoiceAdding($data['invoice_name'])) {
            $manipulations->select();
            echo json_encode($manipulations->select());
        }
    }
    else{
        $response["status"] = 422;
        $response["statusText"] = "\nInvoice name require!";
        echo json_encode($response);
    }
}
if (isset($_POST['sort']) && $_POST['sort'] == 1) {
    if (!empty($data['sort'] = $_POST['serialize'][0]['value'])) {
        $sortedData = $manipulations->sortByName($data['sort']);
        echo json_encode($manipulations->index($sortedData));
    }
}

if (isset($_POST['add']) && $_POST['add'] == 1) {
    $data['select'] = $_POST['serialize'][0]['value'];
    $data['ware_name'] = $_POST['serialize'][1]['value'];
    $data['desc'] = $_POST['serialize'][2]['value'];
    $data['units'] = $_POST['serialize'][3]['value'];
    $data['price'] = (float)$_POST['serialize'][4]['value'];
    $data['count'] = (int)$_POST['serialize'][5]['value'];
    $data['sum'] = $data['price'] * $data['count'];


    $response = container($data);
    if ($response["status"] == 200) {
        if ($manipulations->add($data)) {
            echo json_encode($manipulations->index($connection->getData()));
        }
    }
    if ($response["status"] == 422) {
        echo json_encode($response);
    }
}
if (isset($_POST['edit']) && $_POST['edit'] == 1) {
    $data['ware_name'] = $_POST['serialize'][0]['value'];
    $data['desc'] = $_POST['serialize'][1]['value'];
    $data['units'] = $_POST['serialize'][2]['value'];
    $data['price'] = (float)$_POST['serialize'][3]['value'];
    $data['count'] = (int)$_POST['serialize'][4]['value'];
    $data['sum'] = $data['price'] * $data['count'];
    $data['id'] = $_POST['serialize'][5]['value'];

    $response = container($data);
    if ($response["status"] == 200) {
        if ($manipulations->edit($data)) {
            echo json_encode($manipulations->getDataById($data['id']));
        }
    }
    if ($response["status"] == 422) {
        echo json_encode($response);
    }
}

function container($data)
{
    $response["status"] = 200;
    $response["statusText"] = '';

    if (!empty($data['ware_name'])) {
        if (strlen($data['ware_name']) < 2) {
            $response["status"] = 422;
            $response["statusText"] = "\nWare name should has more 1 symbols!";
        }
    } else {
        $response["status"] = 422;
        $response["statusText"] = $response["statusText"] . "\nWare name require!";
    }
    if (!empty($data['desc'])) {
        if (strlen($data['desc']) < 2) {
            $response["status"] = 422;
            $response["statusText"] = $response["statusText"] . "\nDescription should has more 1 symbols!";
        }
    } else {
        $response["status"] = 422;
        $response["statusText"] = $response["statusText"] . "\nDescription require!";
    }
    if (!empty($data['units'])) {
        if (is_numeric($data['units'])) {
            $response["status"] = 422;
            $response["statusText"] = $response["statusText"] . "\nUnit shouldn't be numeric!";
        }
    } else {
        $response["status"] = 422;
        $response["statusText"] = $response["statusText"] . "\nUnit require!";
    }
    if (!empty($data['price'])) {
        if (is_string($data['price'])) {
            $response["status"] = 422;
            $response["statusText"] = $response["statusText"] . "\nPrice should be numeric!";
        }
    } else {
        $response["status"] = 422;
        $response["statusText"] = $response["statusText"] . "\nPrice require!";
    }
    if (!empty($data['count'])) {
        if (!is_int($data['count'])) {
            $response["status"] = 422;
            $response["statusText"] = $response["statusText"] . "\nCount of wares should be numeric!";
        }
    } else {
        $response["status"] = 422;
        $response["statusText"] = $response["statusText"] . "\nCount of wares require!";
    }
    return $response;
}
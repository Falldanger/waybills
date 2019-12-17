<?php

namespace controllers;

use controllers\Connection;
use controllers\connectionController;
use PDO;

class indexController
{
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db->make();
    }

    public function index($dataForView)
    {
        $tableFirstPart = '<tr>
                        <th>№</th>
                        <th>Ware name</th>
                        <th>Units</th>
                        <th>Price</th>
                        <th>Count</th>
                        <th>Sum</th>
                        <th>Edit</th>
                    </tr>';
        $tableMiddlePart = '';
        $total = 0;
        foreach ($dataForView as $data) {
            $id = $data['id'];
            $link = '<a href=' . "/view-or-edit.php?page=$id" . '>Edit</a>';
            $tableMiddlePart .= '<tr><td>' . $data['id'] . '</td><td>' . $data['name'] . '</td><td>' . $data['units'] .
                '</td><td>' . $data['price'] . '</td><td>' . $data['count']
                . '</td><td>' . $data['sum'] . '</td><td><button id=edit' . ' name=' . $id . ">$link</button></td></tr>";
            $total += $data['sum'];
        }
        $nds = $total * 0.18;
        $tableLastPart = "<tr><td></td><td></td><td></td><td></td><td></td><td>NDS(18%)</td><td>$nds</td></tr>" .
            "<tr><td></td><td></td><td></td><td></td><td></td><td>Total:</td><td>$total</td></tr>";
        return $tableFirstPart . $tableMiddlePart . $tableLastPart;
    }

    public function add($data)
    {
        $blackList = ['<', '</', '>', '/', '=', '+', '[', ']', '{', '}', '^', '%', '$', '#', '@', '!', '&', '^', '*', '(', ')'];
        $data = str_replace($blackList, "", $data);
        $sql = "INSERT INTO `merchandise` (`name`, `desc`,`units`,`price`,`count`,`name_doc`,`sum`)
        VALUES (?,?,?,?,?,?,?)";
        $statement = $this->db->prepare($sql);
        $statement->execute([$data['ware_name'], $data['desc'], $data['units'], $data['price'], $data['count'], $data['select'], $data['sum']]);
        return true;
    }

    public function getDataById($id)
    {
        $statement = $this->db->query("SELECT * FROM `merchandise` WHERE id=$id");
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res[0];
    }

    public function edit($data)
    {
        $blackList = ['<', '</', '>', '/', '=', '+', '[', ']', '{', '}', '^', '%', '$', '#', '@', '!', '&', '^', '*', '(', ')'];
        $data = str_replace($blackList, "", $data);
        $sum = $data['price'] * $data['count'];
        $stmt = $this->db->prepare('UPDATE `merchandise` SET `name` = ?,`desc`= ?,`units`= ?,`price`= ?,`count`= ?,`sum`= ? WHERE id = ?');
        $stmt->execute([$data['ware_name'], $data['desc'], $data['units'], $data['price'], $data['count'], $sum, $data['id']]);
        return true;
    }

    public function invoiceAdding($invoiceName)
    {
        $blackList = ['<', '</', '>', '/', '=', '+', '[', ']', '{', '}', '^', '%', '$', '#', '@', '!', '&', '^', '*', '(', ')'];
        $invoiceName = str_replace($blackList, "", $invoiceName);
        $sql = "INSERT INTO `documents` (`name`) values(?);";
        $statement = $this->db->prepare($sql);
        $statement->execute([$invoiceName]);
        return true;
    }

    public function select()
    {
        $statement = $this->db->query("SELECT `name` FROM `documents`");
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function sortByName($name)
    {
        $statement = $this->db->prepare('SELECT * FROM `merchandise` WHERE name_doc= ?');
        $statement->execute([$name]);
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
}
<?php
require_once 'app/models/NganhHocModel.php';
require_once 'app/helpers/SessionHelper.php';

class NganhHocController {
    private $nganhHocModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->nganhHocModel = new NganhHocModel($db);
    }

    public function index() {
        $result = $this->nganhHocModel->getAll();
        $nganhHoc = $result->fetchAll(PDO::FETCH_ASSOC);
        require 'app/views/nganhhoc/index.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->nganhHocModel->MaNganh = $_POST['MaNganh'];
            $this->nganhHocModel->TenNganh = $_POST['TenNganh'];

            if ($this->nganhHocModel->create()) {
                header("Location: index.php?controller=nganhhoc&action=index");
                exit();
            }
        }
        require 'app/views/nganhhoc/add.php';
    }
}

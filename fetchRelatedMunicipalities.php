<?php

    require 'db.php';

    /** check if request is made from ajax */
    if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'){    
        die('Invalid Request');    
    }

    /** check if user is not logged in then redirect to login page */
    if (!isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit;
    }

    $department_id = (int)$_GET['dept_id'];

    /** check if department_id is valid */
    $department = _get_record('departments',$department_id);

    $response = array();

    if(is_array($department)){
        
        /** fetch related municipalities */
        $municipalities = _get_municipalities($department['id']);

        $response = ['success' => true, 'message' => 'Municipalities loaded','data' => $municipalities];

    }else{
        $response = ['success' => false, 'message' => 'Something went wrong, unable to load municipalities'];
    }

    echo json_encode($response);

?>
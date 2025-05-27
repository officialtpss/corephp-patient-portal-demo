<?php

    require 'db.php';

    /** check if user is not logged in then redirect to login page */
    if (!isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit;
    }

    $patient_id = (int)$_GET['patient_id'];

    /** check if patient_id is valid */
    $patient = _get_record('patients',$patient_id);

    $response = array();

    if(is_array($patient)){
        
        try{

            /** 
             * 
             * delete patient record 
             * 
             */
            _delete_patient($patient_id);

            /** 
             * 
             * remove uploaded profile_img 
             * 
             */
            if($patient['profile_img'] != ''){
                unlink($patient['profile_img']);
            }

            $_SESSION['msg_type'] = "success";
            $_SESSION['msg'] = "Patient record deleted successfully";
            header('Location: patients.php');
            exit;                

        }catch(Exception $e){
            exit($e->getMessage());
        }        

    }else{
        exit('Invalid Request');
    }
   

?>
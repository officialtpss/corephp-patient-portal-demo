<?php

if(isset($patient_id)){
    $name1 = $patient['name1'];
    $name2 = $patient['name2'];
    $lastname1 = $patient['lastname1'];
    $lastname2 = $patient['lastname2'];
    $document_type_id = $patient['document_type_id'];
    $document_number = $patient['document_number'];
    $department_id = $patient['department_id'];
    $municipality_id = $patient['municipality_id'];
    $gender_id = $patient['gender_id'];
    $profile_img = $patient['profile_img'];
}else{
    $name1 = $name2 = $lastname1 = $lastname2 = $document_type_id = $document_number = $department_id = $municipality_id = $gender_id = $profile_img = '';
}

$errors = false; 
$name1_error = $name2_error = $lastname1_error = $lastname2_error = $document_type_id_error = $document_number_error = $department_id_error = $municipality_id_error = $gender_id_error = $profile_img_error = '';

/**
 * 
 *  handle form submit 
 * 
 */
if(isset($_POST['submit']) || isset($_POST['update'])){

    /**
     * 
     *  validate submitted data 
     * 
     */
    if (empty($_POST["name1"])) {
        $errors = true;
        $name1_error = "Name1 is required";
    } 
   
    $name1 = (isset($_POST['name1']))?sanitize_input($_POST['name1']):'';
    
    if (empty($_POST["lastname1"])) {
        $errors = true;
        $lastname1_error = "Lastname1 is required";
    } 

    $lastname1 = (isset($_POST['lastname1']))?sanitize_input($_POST['lastname1']):'';
    
    if (empty($_POST["name2"])) {
        $errors = true;
        $name2_error = "Name2 is required";
    }
    
    $name2 = (isset($_POST['name2']))?sanitize_input($_POST['name2']):'';
    
    if (empty($_POST["lastname2"])) {
        $errors = true;
        $lastname2_error = "Lastname2 is required";
    }
    
    $lastname2 = (isset($_POST['lastname2']))?sanitize_input($_POST['lastname2']):'';
   
    
    if (empty($_POST["document_type_id"])) {
        $errors = true;
        $document_type_id_error = "Document Type is required";
    }
    
    $document_type_id = (isset($_POST['document_type_id']))?sanitize_input($_POST['document_type_id']):'';
    
    if (empty($_POST["document_number"])) {
        $errors = true;
        $document_number_error = "Document Number is required";
    }
    
    $document_number = (isset($_POST['document_number']))?sanitize_input($_POST['document_number']):'';
    
    if (empty($_POST["department_id"])) {
        $errors = true;
        $department_id_error = "Department is required";
    }       

    $department_id = (isset($_POST['department_id']))?sanitize_input($_POST['department_id']):'';

    $municipalities = _get_municipalities($department_id);

    if (empty($_POST["municipality_id"])) {
        $errors = true;
        $municipality_id_error = "Municipality is required";
    }
    
    $municipality_id = (isset($_POST['municipality_id']))?sanitize_input($_POST['municipality_id']):'';
   
    if (empty($_POST["gender_id"])) {
        $errors = true;
        $gender_id_error = "Gender is required";
    } 
    
    $gender_id = (isset($_POST['gender_id']))?sanitize_input($_POST['gender_id']):'';
    
    /**
    * 
    * check if profile image is uploaded
    * 
    */
    if(isset($_FILES) && $_FILES['profilepic']['size'] > 0){
        $check = getimagesize($_FILES["profilepic"]["tmp_name"]);
        if($check !== false) {

            $upload_dir = "uploads/";
            $file_name = basename($_FILES["profilepic"]["name"]);
            $imageFileType = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
            $target_file = $upload_dir . md5($file_name.time()).'.'.$imageFileType;

            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"){
                $errors = true;
                $profile_img_error = 'Invalid file format';
            }else{
                if (move_uploaded_file($_FILES["profilepic"]["tmp_name"], $target_file)) {
                    $profile_img = $target_file;
                } else {
                    $errors = true;
                    $profile_img_error = 'Sorry, there was an error uploading your file.';
                }
            }

        }else{
            $errors = true;
            $profile_img_error = 'Invalid file format';
        }
        
    }

    /**
    * 
    * Process form on valid
    * 
    */
    if($errors === false){
        
        $patientData = array(
            'name1' => $name1,
            'lastname1' => $lastname1,
            'name2' => $name2,
            'lastname2' => $lastname2,
            'document_type_id' => $document_type_id,
            'document_number' => $document_number,
            'department_id' => $department_id,
            'municipality_id' => $municipality_id,
            'gender_id' => $gender_id,
            'profile_img' => $profile_img,
        );

        /** 
         * 
         * check if create form is submitted
         * 
         */
        if($_POST['form_type'] == 'create'){

            try{
                _create_new_patient($patientData);

                /** 
                 * 
                 * redirect to patients listing 
                 * 
                 */
                $_SESSION['msg_type'] = "success";
                $_SESSION['msg'] = "Patient record created successfully";
                header('Location: patients.php');
		        exit;                

            }catch(Exception $e){
                exit($e->getMessage());
            }

        }else{
            
            try{
                _update_patient($patientData,$patient['id']);

                if($patientData['profile_img'] != ''){
                    unlink($patient['profile_img']);
                }

                /** 
                 * 
                 * redirect to patients listing 
                 * 
                 */
                $_SESSION['msg_type'] = "success";
                $_SESSION['msg'] = "Patient record updated successfully";
                header('Location: patients.php');
		        exit;                

            }catch(Exception $e){
                exit($e->getMessage());
            }

        }

    }
}

/**
 * 
 * Santize input data
 * 
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
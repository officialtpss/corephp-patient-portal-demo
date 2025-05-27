<?php

    include('config.php');
    
    /**
     * 
     * check if user (admin) record exists with input username and password
     * 
     */
    function _check_login($data){
        global $conn;

        $response = array();

        /** check if username exists */
        $sql = "SELECT * FROM users WHERE username = ? ";
        $stmt = $conn->prepare($sql);         
        $stmt->bind_param("s", $data['username']);
        $stmt->execute();

        $result = $stmt->get_result();
        $record_count = $result->num_rows;
        
        if($record_count > 0){
            /** get record and match passwords */
            $user = $result->fetch_assoc();

            /** verify password */
            if(password_verify($data['password'],$user['password'])){
                
                /** user exists */
                $response = [
                    'success' => true,
                    'message' => 'Login Success', 
                    'data' => [
                        'user_id' => $user['id'],
                        'username' => $user['username']
                    ]
                ];

            }else{
                $response = ['success' => false, 'message' => 'Invalid credentails'];
            }

        }else{

            $response = ['success' => false, 'message' => 'Invalid credentails'];            

        }

        return $response;
    }

    /**
     * 
     *  Get record from id
     * 
     */
    function _get_record($table,$id){
        global $conn;

        $sql = "SELECT * FROM ".$table." WHERE id = ? ";
        $stmt = $conn->prepare($sql);         
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $record = $result->fetch_assoc();

        return $record;
    }

    /**
     * 
     *  Get all patients with foreign key data
     * 
     */
    function _get_patients(){
        global $conn;

        /** check if username exists */
        $sql = "SELECT p.id as p_id, p.document_number, p.name1, p.name2, p.lastname1, p.lastname2, dt.name as document_type, g.name as gender, d.name as department, m.name as municipality, p.profile_img FROM patients as p
            JOIN document_types as dt ON p.document_type_id = dt.id
            JOIN genders as g ON p.gender_id = g.id
            JOIN departments as d ON p.department_id = d.id
            JOIN municipalities as m ON p.municipality_id = m.id";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $patients = $result->fetch_all(MYSQLI_ASSOC);

        return $patients;
    }

    /**
     * 
     *  Get all Document types / Gender / Departments
     * 
     */
    function _get_table_records($table){
        global $conn;

        $sql = "Select * from ".$table;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $records = $result->fetch_all(MYSQLI_ASSOC);

        return $records;
    }

    /**
     * 
     *  Get all Municipalities based on selected Department
     * 
     */
    function _get_municipalities($department_id){
        global $conn;

        $sql = "Select id, name from municipalities where department_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $department_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $municipalities = $result->fetch_all(MYSQLI_ASSOC);

        return $municipalities;
    }

    /**
     * 
     * Create new patient record
     * 
     */
    function _create_new_patient($patient){
        global $conn;

        $sql = "INSERT INTO patients(document_type_id, document_number, name1, name2, lastname1, lastname2, gender_id, department_id, municipality_id, profile_img) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isssssiiis', $patient['document_type_id'], $patient['document_number'], $patient['name1'], $patient['name2'], $patient['lastname1'], $patient['lastname2'], $patient['gender_id'], $patient['department_id'], $patient['municipality_id'],$patient['profile_img']);
        $stmt->execute();
    }

    /**
     * 
     * Update patient record
     * 
     */
    function _update_patient($patient,$id){
        global $conn;

        $sql = "UPDATE patients set document_type_id = ?, document_number = ? ,name1 = ?, name2 = ?, lastname1 = ?, lastname2 = ?,gender_id = ?, department_id = ?, municipality_id = ?, profile_img = ? WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isssssiiisi', $patient['document_type_id'], $patient['document_number'], $patient['name1'], $patient['name2'], $patient['lastname1'], $patient['lastname2'], $patient['gender_id'], $patient['department_id'], $patient['municipality_id'],$patient['profile_img'], $id);
        $stmt->execute();
        
    }

    /**
     * 
     * Delete patient record
     * 
     */
    function _delete_patient($patient_id){
        global $conn;

        $sql = "DELETE FROM `patients` WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $patient_id);
        $stmt->execute();
    }

?>
<?php

    include('layout/header.php'); 

    require 'db.php';

    /** 
     * 
     * check if user is not logged in then redirect to login page 
     * 
     */
    if (!isset($_SESSION['loggedin'])) {
		header('Location: index.php');
		exit;
	}

    $municipalities = array();

    /**
     * 
     * Handle form on submit
     * 
     */
    include('form_validation.php');

    /** 
     * 
     * fetch document types
     * 
     */
    $document_types = _get_table_records('document_types');

    /** 
     * 
     * fetch genders
     * 
     */
    $genders = _get_table_records('genders');

    /** 
     * 
     * fetch departments
     * 
     */
    $departments = _get_table_records('departments');

?>

<?php include('layout/nav.php'); ?>

<div class="content">
    <h2>Add New Patient</h2>
    
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data" > 

        <div class="form-group">
            <div class="row">
                <div class="col">
                    <input type="text" class="form-control" name="name1" placeholder="First Name 1" value="<?php echo $name1;?>">
                    <span class="error"><?php echo $name1_error;?></span>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="lastname1" placeholder="Last Name 1" value="<?php echo $lastname1;?>" >
                    <span class="error"><?php echo $lastname1_error;?></span>
                </div>
            </div>        	
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col">
                    <input type="text" class="form-control" name="name2" placeholder="First Name 2" value="<?php echo $name2;?>">
                    <span class="error"><?php echo $name2_error;?></span>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="lastname2" placeholder="Last Name 2" value="<?php echo $lastname2;?>">
                    <span class="error"><?php echo $lastname2_error;?></span>
                </div>
            </div>        	
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col">
                    <select class="form-select" name="document_type_id" >
                        <option selected disabled>Select Document Type</option>
                        <?php foreach($document_types as $document_type):?>
                            <option value="<?php echo $document_type['id'];?>" <?php if($document_type['id'] == $document_type_id){ echo "selected";}?>><?php echo $document_type['name'];?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="error"><?php echo $document_type_id_error;?></span>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="document_number" placeholder="Document Number" value="<?php echo $document_number;?>">
                    <span class="error"><?php echo $document_number_error;?></span>
                </div>
            </div>        	
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col">
                    <select class="form-select" name="department_id"  >
                        <option selected disabled>Select Department</option>
                        <?php foreach($departments as $department):?>
                            <option value="<?php echo $department['id'];?>" <?php if($department['id'] == $department_id){ echo "selected";}?>><?php echo $department['name'];?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="error"><?php echo $department_id_error;?></span>
                </div>
                <div class="col">
                    <select class="form-select" name="municipality_id" >
                        <option selected disabled>Select Municipality</option>
                        <?php foreach($municipalities as $municipality): ?>
                            <option value="<?php echo $municipality['id']?>" <?php if($municipality['id'] == $municipality_id){ echo "selected";}?>><?php echo $municipality['name']?></option>
                        <?php endforeach;?>
                    </select>
                    <span class="error"><?php echo $municipality_id_error;?></span>
                </div>
            </div>        	
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col">
                    <select class="form-select" name="gender_id" >
                        <option selected disabled>Select Gender</option>
                        <?php foreach($genders as $gender):?>
                            <option value="<?php echo $gender['id'];?>" <?php if($gender['id'] == $gender_id){ echo "selected";}?>><?php echo $gender['name'];?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="error"><?php echo $gender_id_error;?></span>
                </div>
                <div class="col">
                    <input type="file" class="form-control" name="profilepic"  >
                    <span style="color:grey; font-size:12px;">Only jpg / jpeg/ png format allowed.</span>
                    <span class="error" style="float:right;"><?php echo $profile_img_error;?></span>
                </div>
            </div>        	
        </div>
            
        <div class="form-group">
            <div class="row">
                <div class="col"></div>
                
                <div class="col">
                    <input type="hidden" name="form_type" value="create" />
                    <a href="patients.php" class="btn btn-default btn-block pull-right">Cancel</a>
                    <input type="submit" class="btn btn-success btn-block pull-right" name="submit" value="Submit">
                </div>
            </div>
        </div>

    </form>

</div>

<script>
    /** on department dropdown change fetch related municipalities */
    $('select[name="department_id"]').change(function(){
        let selected_department_id = $('select[name="department_id"]').val();

        $.ajax({
            url: "fetchRelatedMunicipalities.php?dept_id="+selected_department_id,
            method: "get",

            success:function(data){
                var json = $.parseJSON(data);
                if(json.success == true){
                    $('select[name="municipality_id"]').html('');
                    $.each(json.data, function(){
                        $('select[name="municipality_id"]').append('<option value="'+ this.id +'">'+ this.name +'</option>')
                    });
                }else{
                    alert(json.message);
                }
            }
            
        });

    })
</script>

<?php include('layout/footer.php'); ?>
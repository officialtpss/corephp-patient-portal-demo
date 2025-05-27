<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    include('layout/header.php'); 

    require 'db.php';

    /** check if user is not logged in then redirect to login page */
    if (!isset($_SESSION['loggedin'])) {
		header('Location: index.php');
		exit;
	}

    /** get patients listing */
    $patients = _get_patients();

?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css"/>

<?php include('layout/nav.php'); ?>

<div class="content">
    <h2>Patient Management <a href="addPatient.php" class="btn btn-success pull-right" > Add New Patient</a> </h2>

    <?php include('layout/message.php'); ?>

    <table id="patientTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pic</th>
                <th>Name1</th>
                <th>Name2</th>
                <th>Gender</th>
                <th>Document Type</th>
                <th>Document Number</th>
                <th>Department</th>
                <th>Municipality</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach($patients as $patient):?>
                <tr>
                    <td><?php echo $patient['p_id']; ?></td>
                    <td>
                        <?php 
                            if($patient['profile_img']): 
                                echo "<img src='".$patient['profile_img']."' width='80px' />";
                            endif; 
                        ?>
                    </td>
                    <td><?php echo $patient['name1'].' '.$patient['lastname1']; ?></td>
                    <td><?php echo $patient['name2'].' '.$patient['lastname2']; ?></td>
                    <td><?php echo $patient['gender']; ?></td>
                    <td><?php echo $patient['document_type']; ?></td>
                    <td><?php echo $patient['document_number']; ?></td>
                    <td><?php echo $patient['department']; ?></td>
                    <td><?php echo $patient['municipality']; ?></td>
                    <td>
                        <a href="editPatient.php?patient_id=<?php echo $patient['p_id'];?>" title="Edit"><i class="fas fa-edit"></i></a>&nbsp; | &nbsp;
                        <a href="deletePatient.php?patient_id=<?php echo $patient['p_id'];?>" onClick="return confirm('Are you sure you want to delete this record?');" title="Delete"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
                
        </tbody>
        
    </table>

</div>

<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready( function () {
        $('#patientTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "aaSorting" : [[0, 'desc']],
            "responsive": true,
            'columnDefs': []  
        });
    } );
</script>
<?php include('layout/footer.php'); ?>
<?php if(isset($_SESSION['msg_type']) && $_SESSION['msg_type'] == 'success'): ?>

    <div class="alert alert-success" role="alert">
        <?php echo $_SESSION['msg']; ?>
    </div>

<?php elseif(isset($_SESSION['msg_type']) && $_SESSION['msg_type'] == 'error'):?>

    <div class="alert alert-danger" role="alert">
        <?php echo $_SESSION['msg']; ?>
    </div>

<?php endif;

    if(isset($_SESSION['msg_type'])){
        unset($_SESSION['msg']);
        unset($_SESSION['msg_type']);
    }
    
?>
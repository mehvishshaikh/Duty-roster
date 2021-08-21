<?php if (isFlashSet("success")) { ?>
    <div class="alert alert-success alert-dismissible fade show text-left" role="alert">
        <?php echo getFlashDelete("success"); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
<?php }
if (isFlashSet("warning")) { ?>
    <div class="alert alert-warning alert-dismissible fade show text-left" role="alert">
        <?php echo getFlashDelete("warning"); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
<?php }
if (isFlashSet("danger")) { ?>
    <div class="alert alert-danger alert-dismissible fade show text-left" role="alert">
        <?php echo getFlashDelete("danger"); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
<?php } ?>
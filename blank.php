<?php include 'includes/header.php';?>
<!-- Page content -->
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-3">
                    <div
                        class="card-header d-flex flex-column flex-md-row align-tiems-center justify-content-md-between">
                        <h3 class="h1 mb-md-0 mb-sm-3 text-center">Blank Page</h3>
                        <div class="actions text-center">
                            <a href="#" class="btn btn-primary mr-1 mr-md-0" data-toggle="modal"
                                data-target="#formName"><i class="fas fa-plus"></i><span
                                    class="d-none d-lg-inline">&nbsp;Add</span></a>
                            <a href="#" class="btn btn-slack mr-1 mr-md-0"><i class="fas fa-file-csv"></i><span
                                    class="d-none d-lg-inline">&nbsp;Upload CSV</span></a>
                        </div>
                        <div class="modal fade" id="formName" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary p-3">
                                        <h2 id="exampleModalLabel" class="text-white">Modal title</h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" class="text-white">&times;</span>
                                        </button>
                                    </div>
                                    <form action="">
                                        <div class="modal-body">
                                            ...
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary"><i
                                                    class="fas fa-plus"></i>&nbsp; Add Something</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php';?>
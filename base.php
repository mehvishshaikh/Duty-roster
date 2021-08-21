<?php include 'includes/header.php';?>
<!-- Page content -->
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-3">
                    <div
                        class="card-header d-flex flex-column flex-md-row align-tiems-center justify-content-md-between">
                        <h3 class="h1 mb-md-0 mb-sm-3 text-center">Base Page</h3>
                        <div class="actions text-center">
                            <a href="#" class="btn btn-primary mr-1 mr-md-0" data-toggle="modal"
                                data-target="#addForm"><i class="fas fa-plus"></i><span
                                    class="d-none d-lg-inline">&nbsp;Add</span></a>
                            <a href="#" class="btn btn-slack mr-1 mr-md-0" data-toggle="modal"
                                data-target="#csvUpload"><i class="fas fa-file-csv"></i><span
                                    class="d-none d-lg-inline">&nbsp;Upload CSV</span></a>
                            <a href="#" class="btn btn-default mr-1 mr-md-0" data-toggle="modal"
                                data-target="#sortData"><i class="fas fa-sort"></i><span
                                    class="d-none d-lg-inline">&nbsp;Sort Data</span></a>
                        </div>
                        <!-- Add Modal -->
                        <div class="modal fade" id="addForm" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h2 id="exampleModalLabel" class="text-white">Add Data</h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" class="text-white">&times;</span>
                                        </button>
                                    </div>
                                    <form action="">
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                    <label for="example-text-input"
                                                        class="form-control-label">Text</label>
                                                    <input class="form-control" type="text" placeholder="John Snow"
                                                        id="example-text-input">
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                    <label for="example-search-input"
                                                        class="form-control-label">Search</label>
                                                    <input class="form-control" type="search"
                                                        placeholder="Tell me your secret ..." id="example-search-input">
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                    <label for="example-email-input"
                                                        class="form-control-label">Email</label>
                                                    <input class="form-control" type="email" placeholder="@example.com"
                                                        id="example-email-input">
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                    <label for="example-url-input"
                                                        class="form-control-label">URL</label>
                                                    <input class="form-control" type="url"
                                                        placeholder="https://www.google.com" id="example-url-input">
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                    <label for="example-tel-input"
                                                        class="form-control-label">Phone</label>
                                                    <input class="form-control" type="tel"
                                                        placeholder="40-(770)-888-444" id="example-tel-input">
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                    <label for="example-password-input"
                                                        class="form-control-label">Password</label>
                                                    <input class="form-control" type="password" placeholder="password"
                                                        id="example-password-input">
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                    <label for="example-number-input"
                                                        class="form-control-label">Number</label>
                                                    <input class="form-control" type="number" placeholder="23"
                                                        id="example-number-input">
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                    <label for="example-color-input"
                                                        class="form-control-label">Color</label>
                                                    <input class="form-control" type="color" placeholder="#5e72e4"
                                                        id="example-color-input">
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                    <label for="datepicker"
                                                        class="form-control-label">DatePicker</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input class="form-control datepicker" type="text" placeholder="Select date" id="datepicker" data-toggle="datepicker">
                                                    </div>
                                                </div>
                                                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                    <label for="ckeditor" class="form-control-label">CK
                                                        Editor</label>
                                                    <textarea name="" id="ckeditor" rows="10" class="form-control"
                                                        placeholder="Enter Long Text"></textarea>
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                    <label for="selectSimple" class="form-control-label">Select 2
                                                        Simple</label>
                                                    <select class="form-control" data-toggle="select"
                                                        title="Simple select" data-live-search="true"
                                                        data-live-search-placeholder="Search ..."
                                                        data-placeholder="Select 2" id="selectSimple">
                                                        <option>Alerts</option>
                                                        <option>Badges</option>
                                                        <option>Buttons</option>
                                                        <option>Cards</option>
                                                        <option>Forms</option>
                                                        <option>Modals</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                    <label for="selectWSearch" class="form-control-label">Select 2
                                                        without
                                                        Search</label>
                                                    <select class="form-control" data-toggle="select"
                                                        data-placeholder="Select without Seacrh" id="selectWSearch"
                                                        data-minimum-results-for-search="Infinity">
                                                        <option>Alerts</option>
                                                        <option>Badges</option>
                                                        <option>Buttons</option>
                                                        <option>Cards</option>
                                                        <option>Forms</option>
                                                        <option>Modals</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                    <label for="selectMultiple" class="form-control-label">Select 2
                                                        Multiple</label>
                                                    <select class="form-control" data-toggle="select" multiple
                                                        data-placeholder="Select multiple options"
                                                        data-live-search="true"
                                                        data-live-search-placeholder="Search ..."
                                                        data-minimum-results-for-search="8" id="selectMultiple">
                                                        <option>Alerts</option>
                                                        <option>Badges</option>
                                                        <option>Buttons</option>
                                                        <option>Cards</option>
                                                        <option>Forms</option>
                                                        <option>Modals</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6 col-md-12 col-sm-12 croppie-container">
                                                    <label for="croppie" class="form-control-label">Croppie</label>
                                                    <div class="croppie-container">
                                                        <input type="text" name="imageData" id="imageData" hidden>
                                                        <a class="btn btn-primary upload mb-3">
                                                            <input type="file" name="imageFile" id="imageFile"
                                                                accept="image/*">
                                                            <span class="text-white">Select Image</span>
                                                        </a>
                                                        <button class="btn btn-danger delete mb-3">Remove Image</button>
                                                        <div id="croppie"></div>
                                                    </div>
                                                </div>
                                            </div>
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
                        <!-- CSV Upload Modal -->
                        <div class="modal fade" id="csvUpload" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h2 id="exampleModalLabel" class="text-white">Upload CSV File</h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" class="text-white">&times;</span>
                                        </button>
                                    </div>
                                    <form action="">
                                        <div class="modal-body">
                                            <div class="form-row justify-content-center">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="customFile">
                                                        <label class="custom-file-label" for="customFile">Choose
                                                            file</label>
                                                    </div>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-slack" type="submit"><i
                                                                class="fas fa-upload"></i> Upload</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Sort Data Modal -->
                        <div class="modal fade" id="sortData" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h2 id="exampleModalLabel" class="text-white">Sort Data</h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" class="text-white">&times;</span>
                                        </button>
                                    </div>
                                    <form action="">
                                        <div class="modal-body">
                                            <table class="table table-stripped table-hover table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>List Title</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="sortTable">
                                                    <tr data-index="1" data-position="1"><td>List Item 1</td></tr>
                                                    <tr data-index="2" data-position="2"><td>List Item 2</td></tr>
                                                    <tr data-index="3" data-position="3"><td>List Item 3</td></tr>
                                                    <tr data-index="4" data-position="4"><td>List Item 4</td></tr>
                                                    <tr data-index="5" data-position="5"><td>List Item 5</td></tr>
                                                    <tr data-index="6" data-position="6"><td>List Item 6</td></tr>
                                                    <tr data-index="7" data-position="7"><td>List Item 7</td></tr>
                                                    <tr data-index="8" data-position="8"><td>List Item 8</td></tr>
                                                    <tr data-index="9" data-position="9"><td>List Item 9</td></tr>
                                                    <tr data-index="10" data-position="10"><td>List Item 10</td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-default"><i class="fas fa-sort"></i>&nbsp; Save Positions</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form
                            class="form-inline d-flex flex-md-row flex-sm-column align-items-center justify-content-between">
                            <div class="mb-0 d-flex align-items-center">
                                <label class="mb-3" for="entries">Show</label>
                                <select class="form-control form-control-sm mx-2 mb-3" id="entries">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <label class="mb-3" for="entries">Entries</label>
                            </div>
                            <div class="form-group d-flex align-items-center mb-3">
                                <label for="search">Search</label>
                                <input type="search" class="form-control form-control-sm ml-2" id="search">
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover align-items-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Project</th>
                                        <th>Budget</th>
                                        <th>Status</th>
                                        <th>Completion</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">
                                            <div class="media align-items-center">
                                                <a href="assets/img/placeholder.jpg"
                                                    class="avatar rounded-circle mr-3 popup-image">
                                                    <img alt="Image placeholder" src="assets/img/placeholder.jpg">
                                                </a>
                                                <div class="media-body">
                                                    <span class="name mb-0 text-sm">Duty Roaster Design System</span>
                                                </div>
                                            </div>
                                        </th>
                                        <td class="budget">
                                            $2500 USD
                                        </td>
                                        <td>
                                            <span class="badge badge-dot mr-4">
                                                <i class="bg-warning"></i>
                                                <span class="status">pending</span>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="completion mr-2">60%</span>
                                                <div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-warning" role="progressbar"
                                                            aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                                                            style="width: 60%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-info btn-sm mx-1" data-toggle="tooltip"
                                                data-placement="top" title="View"><i class="fas fa-expand"></i></a>
                                            <a href="#" class="btn btn-warning btn-sm mx-1" data-toggle="tooltip"
                                                data-placement="top" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                            <a href="#" class="btn btn-danger btn-sm mx-1" data-toggle="tooltip"
                                                data-placement="top" title="Delete"><i class="fas fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <nav class="mt-4 d-flex justify-content-end">
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link" href="#" tabindex="-1"><i
                                            class="fa fa-angle-left"></i></a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item active"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#"><i
                                            class="fa fa-angle-right"></i></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php';?>

<script>
    CKEDITOR.replace(
        'ckeditor', {
            toolbar: [{
                    name: 'basicstyles',
                    items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
                },
                {
                    name: 'paragraph',
                    items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-',
                        'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'
                    ]
                },
                {
                    name: 'links',
                    items: ['Link', 'Unlink']
                },
                {
                    name: 'insert',
                    items: ['Image', 'Table', 'HorizontalRule']
                },
                {
                    name: 'styles',
                    items: ['Format']
                },
                {
                    name: 'colors',
                    items: ['TextColor', 'BGColor']
                },
                {
                    name: 'tools',
                    items: ['Maximize']
                },
            ],
        });

    function readFile(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $("#croppie").croppie("bind", {
                    url: e.target.result
                });

            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(".upload input").on("change", function () {
        readFile(this);
    });

    $('.delete').click(function (e) {
        e.preventDefault();
        $("#croppie").croppie("bind", {
            url: "assets/img/placeholder.jpg"
        });
    });

    $('#addForm').on('shown.bs.modal', function (e) {
        var width = $('.croppie-container').width();
        var bscWidth = width - 5;

        // Calculating Aspect Ratio
        var initialWidth = 16,
            initialHeight = 9,
            newWidth = bscWidth,
            newHeight;
        initialWidth = initialWidth;
        initialHeight = initialHeight;
        newWidth = newWidth;
        newHeight = newHeight;
        aspectRatio = initialWidth / initialHeight;
        newHeight = Math.round(newWidth / aspectRatio);
        var Height = newHeight;
        newWidth = Math.round(newHeight * aspectRatio);
        var Width = newWidth;


        $image_crop = $('#croppie').croppie({
            enableExif: true,
            viewport: {
                width: 300,
                height: 300,
                type: 'square'
            },
            boundary: {
                width: Width,
                height: Height,
            },
            url: "assets/img/placeholder.jpg",
        });
    });

    $("#blogForm").submit(function (event) {
        $image_crop.croppie('result', {
            type: 'canvas',
            size: {
                width: 300,
                height: 300,
            },
        }).then(function (response) {
            $("#imageData").val(response);
        })
    });

    $(document).ready(function () {
           $('#sortTable').sortable({
               update: function (event, ui) {
                   $(this).children().each(function (index) {
                        if ($(this).attr('data-position') != (index+1)) {
                            $(this).attr('data-position', (index+1)).addClass('updated');
                        }
                   });

                //    saveNewPositions();
               }
           });
        });

        // function saveNewPositions() {
        //     var positions = [];
        //     $('.updated').each(function () {
        //        positions.push([$(this).attr('data-index'), $(this).attr('data-position')]);
        //        $(this).removeClass('updated');
        //     });

        //     $.ajax({
        //        url: 'index.php',
        //        method: 'POST',
        //        dataType: 'text',
        //        data: {
        //            update: 1,
        //            positions: positions
        //        }, success: function (response) {
        //             console.log(response);
        //        }
        //     });
        // }
</script>
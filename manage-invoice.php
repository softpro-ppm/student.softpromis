<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SOFTPRO | ADMIN</title>

    <!-- <link rel="stylesheet" href="css/bootstrap.min.css" media="screen"> -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
    <link rel="stylesheet" href="css/prism/prism.css" media="screen">
    <link rel="stylesheet" href="css/select2/select2.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="css/main.css" media="screen">
    <link rel="stylesheet" href="css/mystyle.css"> 
    <script src="js/modernizr/modernizr.min.js"></script>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="includes/style.css">



    

    
    <style>
        .card { border: none; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-radius: 10px; }
        .table-responsive { border-radius: 10px; overflow: hidden; }
        .btn-action { padding: 5px 10px; margin: 0 2px; }
        .thead-dark { background: #212529; color: white; }
        .dt-buttons { margin-bottom: 15px; }
    </style>
</head>

<body class="bg-light">
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <?php include('includes/topbar-new.php'); ?>

        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <?php include('includes/left-sidebar-new.php'); ?>
                <?php include('includes/leftbar.php'); ?>

                <!-- Main Content -->
                <main class="col-md-9 col-lg-10 px-md-4">
                    <!-- Page Title -->
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                        <h1 class="h2">Manage Invoices</h1>
                    </div>

                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
                            <li class="breadcrumb-item">Invoice</li>
                            <li class="breadcrumb-item active" aria-current="page">Manage Invoice</li>
                        </ol>
                    </nav>

                    <!-- Messages -->
                    <?php if ($msg) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Well done!</strong> <?php echo htmlentities($msg); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php } else if ($error) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php } ?>

                    <!-- Invoices Table -->
                    <div class="card">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Invoice Information</h5>
                            <a href="add-invoice.php" class="btn btn-success">
                                <i class="fas fa-plus"></i> Add Invoice
                            </a>
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table id="example" class="table table-hover table-bordered" style="width:100%">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Invoice No</th>
                                            <th>Invoice Date</th>
                                            <th>Batch ID</th>
                                            <th>Training Center</th>
                                            <th>Scheme</th>
                                            <th>Sector</th>
                                            <th>Job Roll</th>
                                            <th>Batch Name</th>
                                            <th>Tranche</th>
                                            <th>Amount</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql = "SELECT tblinvoice.*, tbltrainingcenter.trainingcentername, tblscheme.SchemeName, 
                                                tblsector.SectorName, tbljobroll.jobrollname, tblbatch.batch_name 
                                                FROM tblinvoice 
                                                JOIN tbltrainingcenter ON tblinvoice.trainingcenterID = tbltrainingcenter.TrainingcenterId 
                                                JOIN tblscheme ON tblinvoice.schemeID = tblscheme.SchemeId 
                                                JOIN tblsector ON tblinvoice.sectorID = tblsector.SectorId 
                                                JOIN tbljobroll ON tblinvoice.jobrollID = tbljobroll.JobrollId 
                                                JOIN tblbatch ON tblinvoice.batchID = tblbatch.id";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) { ?>
                                                <tr>
                                                    <td><?php echo htmlentities($cnt); ?></td>
                                                    <td><?php echo htmlentities($result->invoiceNo); ?></td>
                                                    <td><?php echo htmlentities($result->invoiceDate); ?></td>
                                                    <td><?php echo htmlentities($result->manualbatchID); ?></td>
                                                    <td><?php echo htmlentities($result->trainingcentername); ?></td>
                                                    <td><?php echo htmlentities($result->SchemeName); ?></td>
                                                    <td><?php echo htmlentities($result->SectorName); ?></td>
                                                    <td><?php echo htmlentities($result->jobrollname); ?></td>
                                                    <td><?php echo htmlentities($result->batch_name); ?></td>
                                                    <td><?php echo htmlentities($result->tranche); ?></td>
                                                    <td><?php echo htmlentities($result->invoiceAmount); ?></td>
                                                    <td>
                                                        <a href="edit-invoice.php?invoiceid=<?php echo htmlentities($result->invoiceID); ?>" 
                                                           class="btn btn-info btn-xs btn-action" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button class="btn btn-danger btn-xs btn-action delete" 
                                                                id="del_<?php echo htmlentities($result->invoiceID); ?>" 
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                        <?php $cnt++; }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
      <script src="js/pace/pace.min.js"></script>
      <script src="js/lobipanel/lobipanel.min.js"></script>
      <script src="js/iscroll/iscroll.js"></script>
      <script src="js/prism/prism.js"></script>
    <script src="js/main.js"></script>




    <script>
    $(document).ready(function() {
        var table = $('#example').DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [[10, 20, 50, 100, 500], [10, 20, 50, 100, 500]],
            order: [[2, 'desc']], // Order by Invoice Date
            /*
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10] } // Exclude Actions column
                },
                {
                    extend: 'csv',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10] }
                },
                {
                    extend: 'excel',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10] }
                },
                {
                    extend: 'pdf',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10] }
                },
                {
                    extend: 'print',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10] }
                },
                //'colvis'
            ],
            */
            //dom: 'Bfrtip'
        });

        table.buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');

        // Delete functionality
        $('#example tbody').on('click', '.delete', function() {
            var el = this;
            var id = this.id.split("_")[1];
            if (confirm("Are you sure you want to delete this invoice?")) {
                $.ajax({
                    url: 'action.php',
                    type: 'POST',
                    data: { id: id, action: "Delete invoice" },
                    success: function(response) {
                        if (response == 4) {
                            $(el).closest('tr').css('background', '#ffcccc').fadeOut(800, function() {
                                $(this).remove();
                            });
                        } else {
                            alert('Error deleting invoice.');
                        }
                    }
                });
            }
        });
    });
    </script>
</body>
</html>
<?php } ?>
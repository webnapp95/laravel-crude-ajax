<!DOCTYPE html>
<html>
<head>
    <title>Movies List</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="<?=url('/')?>/public/css/bootstrap.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Movies List</h2>
                </div>
                <div class="pull-right">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addModal">Create New Post</button>
                </div>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                <th>Title</th>
                <th>Year</th>
                <th>Description</th>
                <th>Image</th>
                <th width="200px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <ul id="pagination" class="pagination-sm"></ul>
        <!-- Create Item Modal -->
        @include('create')
        <!-- Edit Item Modal -->
        @include('edit')

    </div>
    <link href="<?=url('/')?>/public/css/toastr.min.css" rel="stylesheet">
    <script type="text/javascript" src="<?=url('/')?>/public/js/jquery.js"></script>
    <script type="text/javascript" src="<?=url('/')?>/public/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?=url('/')?>/public/js/jquery.twbsPagination.min.js"></script>
    <script src="<?=url('/')?>/public/js/validator.min.js"></script>
    <script type="text/javascript" src="<?=url('/')?>/public/js/toastr.min.js"></script>
    <script src="<?=url('/')?>/public/js/postsAjax.js"></script> 
</body>
</html>

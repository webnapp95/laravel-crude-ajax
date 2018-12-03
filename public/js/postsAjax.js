var page = 1;
var current_page = 1;
var total_page = 0;
var is_ajax_fire = 0;

manageData();

/* manage data list */
function manageData() {
    var base_url = 'http://localhost/movies';
    var page = getUrlParam('page', 1);
    $.ajax({
        dataType: 'json',
        url: base_url+"/api/movies/list",
        data: {page:page}
    }).done(function(data) {
        total_page = data.moviesData.total;
        last_page = data.moviesData.last_page;
        current_page = data.moviesData.current_page;
        $('#pagination').twbsPagination({
            startPage: current_page,
            totalPages: last_page,
            visiblePages: 3,
            onPageClick: function (event, pageL) {
                page = pageL;
                if(is_ajax_fire != 0){
                  getPageData(page);
                }
            }
        });
        manageRow(data.moviesData.data);
        is_ajax_fire = 1;
    });
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

/* Get Page Data*/
function getPageData(page = 1) {
    var base_url = 'http://localhost/movies';
    //var page = getUrlParam('page', 1);
    $.ajax({
        dataType: 'json',
        url: base_url+"/api/movies/list",
        data: {page:page}
    }).done(function(data) {
        manageRow(data.moviesData.data);
    });
}

/* Add new Post table row */
function manageRow(data) {
    var rows = '';
    $.each( data, function( key, value ) {
        rows = rows + '<tr>';
        rows = rows + '<td>'+value.title+'</td>';
        rows = rows + '<td>'+value.year+'</td>';
        rows = rows + '<td>'+value.description+'</td>';
        rows = rows + '<td data-src="'+value.image+'"><img src ="'+value.image+'" height="100px" width="150px"></td>';
        rows = rows + '<td data-id="'+value.id+'">';
        rows = rows + '<button data-toggle="modal" data-target="#editModal" class="btn btn-primary edit-item">Edit</button> ';
        rows = rows + '<button class="btn btn-danger remove-item">Delete</button>';
        rows = rows + '</td>';
        rows = rows + '</tr>';
    });
    $("tbody").html(rows);
}

/* Create new Post */
$(".crud-submit").click(function(e) {
    e.preventDefault();
    var form_action = $("#addModal").find("form").attr("action");
    var title = $("#addModal").find("input[name='title']").val();
    var year = $("#addModal").find("input[name='year']").val();
    var description = $("#addModal").find("textarea[name='description']").val();
    var image = $("#blah").attr('src');
    $.ajax({
        dataType: 'json',
        type:'POST',
        url: form_action,
        data:{title:title, description:description,year:year,image:image}
    }).done(function(data){
        getPageData();
        $(".modal").modal('hide');
        if (data.msg == 'Error') {
            toastr.error(data.data.join(','), 'Error Alert', {timeOut: 5000});
        } else {
            toastr.success('Movies Created Successfully.', 'Success Alert', {timeOut: 5000});
        }
    });
});

/* Remove Post */
$("body").on("click",".remove-item",function() {
    var id = $(this).parent("td").data('id');
    var base_url = 'http://localhost/movies/api/movies';
    var c_obj = $(this).parents("tr");
    $.ajax({
        dataType: 'json',
        type:'delete',
        url: base_url + '/' + id,
    }).done(function(data) {
        c_obj.remove();
        toastr.success('Post Deleted Successfully.', 'Success Alert', {timeOut: 5000});
        getPageData();
    });
});

/* Edit Post */
$("body").on("click",".edit-item",function() {
    var base_url = 'http://localhost/movies/api/movies/updateMovies';
    var id = $(this).parent("td").data('id');
    var title = $(this).parent("td").prev("td").prev("td").text();
    var year = $(this).parent("td").prev("td").prev("td").prev("td").text();
    var description = $(this).parent("td").prev("td").prev("td").text();
    var image = $(this).parent("td").prev("td").data('src');
    $('#blah').hide();
    $('#blah').fadeIn(650);
    $("img").attr("src", image);
    $("#editModal").find("input[name='id']").val(id);
    $("#editModal").find("input[name='title']").val(title);
    $("#editModal").find("input[name='year']").val(year);
    $("#editModal").find("textarea[name='description']").val(description);
    $("#editModal").find("form").attr("action",base_url + '/' + id);
});

/* Updated new Post */
$(".crud-submit-edit").click(function(e) {
    e.preventDefault();
    var form_action = $("#editModal").find("form").attr("action");
    var title = $("#editModal").find("input[name='title']").val();
    var year = $("#editModal").find("input[name='year']").val();
    var description = $("#editModal").find("textarea[name='description']").val();
    var image = $("#blah").attr('src');
    $.ajax({
        dataType: 'json',
        type:'POST',
        url: form_action,
        data:{title:title, description:description,year:year,image:image}
    }).done(function(data){
        getPageData();
        $(".modal").modal('hide');
        if (data.msg == 'Error') {
            toastr.error(data.data.join(','), 'Error Alert', {timeOut: 5000});
        }
        toastr.success('Post Updated Successfully.', 'Success Alert', {timeOut: 5000});
    });
});

function getUrlParam(parameter, defaultvalue){
    var urlparameter = defaultvalue;
    if(window.location.href.indexOf(parameter) > -1){
        urlparameter = getUrlVars()[parameter];
        }
    return urlparameter;
}


$("#imgInp").change(function() {
    //console.log("upload");
    readURL(this);
});


$("#imgInp2").change(function() {
    //console.log("upload");
    readURL(this);
});

function readURL(input) {

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {

    }
    var base_url = 'http://localhost/movies/api/imageUpload';
    var fdata = new FormData();
    fdata.append("image", input.files[0]);
    $.ajax({
        dataType: 'json',
        type:'POST',
        url: base_url,
        data:fdata,
        processData: false,
        contentType: false,
    }).done(function(data){
      $('#blah').attr("src",data.imageName);
      $("img").attr("src",data.imageName);
      $('#blah').hide();
      $('#blah').fadeIn(650);
    });
    reader.readAsDataURL(input.files[0]);
  }
}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Todo List</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body class="antialiased">
    <div class="container-fluid">
        <!-- Trigger the modal with a button -->
        <button type="button" class="btn btn-info btn-lg" id="add_todo">Add Todo</button>
        <div id="response"></div>
        <table class="table table-bordered table-striped table-hover">
            <thead style="background-color:skyblue;">
                <th class="text-center">Id</th>
                <th class="text-center">Name</th>
                <th class="text-center">Actions</th>
            </thead>
            <tbody id="list_todo">
                @foreach ($todos as $todo )
                <tr id="row_todo_{{ $todo->id }}">
                    <td>{{ $todo->id }}</td>
                    <td>{{ $todo->Name }}</td>
                    <td>
                        <button type="button" class="btn btn-success btn-sm mr-1" data-id="{{ $todo->id }}"
                            id="edit_todo">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm" data-id="{{ $todo->id }}"
                            id="delete_todo">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Modal -->
        <div class="modal fade" id="modal_todo" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <form id="form_todo">
                        <div class="modal-header">
                            <h4 class="modal-title text-center" id="modal_title"></h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="id" name="id">
                            <input style="width:75%;" type="text" id="name_todo" name="name"
                                placeholder="Enter Todo Name" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success " id="submit">Submit</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(
        function(){
            $("table").css('margin-top','5px');

            $.ajaxSetup({
                         headers:{
                         'x-csrf-token':$('meta[name="csrf-token"]').attr('content')
                         }
                     })
         });
            $("#add_todo").on('click',function(){
            $("#form_todo").trigger('reset');
            $("#modal_title").html('Add Todo'); 
            $("#modal_todo").modal('show'); 
            });
    // edit todos
            $("body").on('click','#edit_todo',function(){
                var id=$(this).data('id');
            $.get("todo/"+id+"/edit_todo",function(res){
                    $("#modal_title").html('Edit Todo'); 
                    $("#id").val(res.id); 
                    $("#name_todo").val(res.Name);  
                    $("#modal_todo").modal('show'); 
                });
            });

            //save edited data
            
        $("#form_todo").on("submit",function(e){
            e.preventDefault();
            $.ajax({
                url:'todos/store',
                 data:$("#form_todo").serialize(),
                 type:'POST'
            }).done(function(res) {
            var row = '<tr id="row_todo_'+res.id+'">';
            row += '<td>'+res.id+'</td>';
            row += '<td>'+res.Name+'</td>';
            row += '<td><button type="button" class="btn btn-success btn-sm mr-auto" data-id="'+res.id+'" id="edit_todo">Edit</button> <button type="button" class="btn btn-danger btn-sm" data-id="'+res.id+'" id="delete_todo">Delete</button></td></tr>';
            if ($("#id").val()) {
                $("#row_todo_"+res.id).replaceWith(row);
                $("#response").fadeIn(5000);
            // $("#response").addClass('d-inline flo');
         $("#response").css('color','green');
         $("#response").css('background-color','gray');
         $("#response").css('text-align','center');
         $("#response").css('border-radius','10px');
         $("#response").css('font-size','24px');
         $("#response").html('Edit Todo Successfully!');
         setTimeout(() => {
             $("#response").fadeOut(2000);
            }, 3000);
            $("#id").val("");

     } else {
        $("#list_todo").prepend(row);
         $("#response").fadeIn(5000);
         $("#response").css('background-color','gray');
         $("#response").css('border-radius','10px');
         $("#response").css('color','green');
         $("#response").css('text-align','center');
         $("#response").css('font-size','24px');
                    $("#response").html('Add Todo Successfully!');
                    setTimeout(() => {
                        $("#response").fadeOut(2000);
                    }, 3000);
     }
        $("#form_todo").trigger("reset");
        $("#modal_todo").modal('hide');
        });

        });

    // Delete todos
    $("body").on('click','#delete_todo',function(){
                var id=$(this).data('id');
                if(confirm("Confirm to Delete?")){
                    $.ajax({
                url:'todos/delete/'+id,
                 type:'POST'
            }).done(function(res) {
                       $("#row_todo_"+id).remove();
                    $("#response").fadeIn(5000);
                    $("#response").css('background-color','black');
                    $("#response").css('border-radius','10px');
                    $("#response").css('text-align','center');
                    $("#response").css('font-size','24px');   
                    $("#response").css('color','red');
                    $("#response").html('Deleted Todo Successfully!');
                    setTimeout(() => {
                        $("#response").fadeOut(2000);
                    }, 3000);
            });
                }
               
            });
    </script>
</body>

</html>
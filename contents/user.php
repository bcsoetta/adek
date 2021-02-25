<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath. '/../lib/Session.php');
Session::init();

$user_id = Session::get("id");

if ($user_id == 0 OR $user_id == null OR $user_id == false) { 
    Session::redirect();
}

?>

<style type="text/css">
    #user-wrap-feature {
        //border: 1px solid red;
        margin-bottom: 20px;
    }
    .user-feature {
        border: 1px solid #ddd;
        display: table-cell;
        padding: 1.5px 4.5px;
        cursor: pointer;
        font-size: 1.5em;
        color: #000;
    }
    .status_active {
        color: green;
    }
    .status_inactive {
        color: red;
    }
</style>

<p id="pageTitle" hidden>&nbsp;&nbsp;~ User</p>

<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
<div class="apps-data">
    <div id="user-wrap-feature">
        <a class="link-load-back" href="user_create">
            <div class="user-feature" title="Create"><i class="fa fa-plus-circle" aria-hidden="true"></i></div>
        </a>
    </div>
    <table id="app-tb1" class="display" width="100%" cellspacing="0" style="text-transform: uppercase;">
        <thead>
            <tr>
                <th>Username</th>
                <th>Nama</th>
                <th>NIP</th>
                <th>Role</th>
                <th>Unit</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>

<script src="js/jquery.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        var t = $('#app-tb1').DataTable( {
            "ajax": {
            	"url": "ajax/ajax_user.php",
            	"type": "POST"
            },
            oLanguage: {
                sProcessing: "<img src='images/loading_1.gif'>",
                "sSearch": "Search",
                "sLengthMenu": "_MENU_",
                //"sInfo": "Showing page _PAGE_ of _PAGES_ records",
                "sInfo": "Showing _START_ to _END_ of _TOTAL_ records"
            },

            // serverSide: true,
            "processing": true,
            "order": [[ 4, 'asc' ]],
            "columns": [
                { "data": "username" },
                { "data": "name" },
                { "data": "nip" },
                { "data": "level" },
                {
                	data: null,
                	render: function ( data, type, row, full, meta ) {
                        var unit_short = data.unit_short;
                        var unit_long = data.unit_long;
         				return unit_long + " (" + unit_short + ")";
         			}
                },
                {
                    data: null,
                    render: function ( data, type, row, full, meta ) {
                        var status = data.status;
                        if (status === 'ACTIVE') {
                            return '<div class="status_active">'+status+'</div>';
                        } else if (status === 'INACTIVE') {
                            return '<div class="status_inactive">'+status+'</div>';
                        } else {
                            return '<div class="status_unknown">'+status+'</div>';
                        }    
                    }
                },
                {
                    data: null,
                    render: function ( data, type, row, full, meta ) {
                        var user_id = data.user_id;
                        return '<a class="link1" href="user_update.php?id='+user_id+'">UPDATE</a>';
                        
                    }
                },
            ]
        });
    });

</script>


<script type="text/javascript">
    $(document).ready(function() {
        
        $('.link-load-back').click(function() {
            var page = $(this).attr('href');
            $('#apps-contents').load('contents/' + page + '.php');
            return false;
        });
    });   
</script>

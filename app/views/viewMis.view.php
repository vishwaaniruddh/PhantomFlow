<?php  require 'partials/head.php'; ?>


<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="card">
                        <div class="card-block">
                        <form id="misFilter" method="POST">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>ATMID</label>
                                    <input type="text" name="atmid" class="form-control" value="<? echo $_REQUEST['atmid']; ?>">
                                </div>
                                
                                <div class="col-md-3">
                                    <label>From Call Login Date</label>
                                    <input type="date" name="fromdt" class="form-control" value="<? if ($_REQUEST['fromdt']) { echo  $_REQUEST['fromdt']; } else { echo '2023-01-01'; } ?>">
                                </div>
                                <div class="col-md-3">
                                    <label>To Call Login Date</label>
                                    <input type="date" name="todt" class="form-control" value="<? if ($_REQUEST['todt']) { echo  $_REQUEST['todt']; } else { echo date('Y-m-d'); } ?>">
                                </div>

                                <div class="col-md-3">
                                    <label>Status</label>
                                    <select id="multiselect_status" class="form-control" name="status[]" multiple="multiple">
                                        <?
                                        $i = 0;
                                        $status_sql = $pdo->prepare("select status_code,status_name from mis_status where status='1'");
                                        $status_sql->execute();

                                        while ($status_sql_result = $status_sql->fetch(PDO::FETCH_ASSOC)) { 
                                            if($status_sql_result['status_code']=="material_pending"){ 
                                                $status_sql_result['status_code'] = "MRS";
                                            }
                                        ?>
                                            <option value="<? echo $status_sql_result['status_code']; ?>" <?  if(isset($_REQUEST['status'])) { 
                                            
                                            if(in_array($status_sql_result['status_code'],$_REQUEST['status'])){
                                                echo 'selected'; 
                                                
                                            }
                                            }else{
                                                if($status_sql_result['status_name']!='Closed'){
                                                    echo 'selected';
                                                }
                                            }
                                            ?>
                                            
                                            >
                                                <? echo $status_sql_result['status_name']; ?>
                                            </option>
                                        <?
                                            $i++;
                                        } ?>
                                    </select>
                                </div>                                
                            </div>
                            <br><br>
                            <div class="col" style="display:flex;justify-content:center;">
                                <input type="submit" name="submit" value="Filter" class="btn btn-primary">
                                <a class="btn btn-warning" id="hide_filter" style="color:white;margin:auto 10px;">Hide Filters</a>
                            </div>

                            </form>

                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-block">
                            <div id="misRecords" class="overflow_auto"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#multiselect_status').multiselect({
            buttonWidth: '100%',
            includeSelectAllOption: true,
            nonSelectedText: 'Select an Option'
        });




    });


    $("#show_filter").css('display', 'none');

    $("#hide_filter").on('click', function() {
        $("#filter").css('display', 'none');
        $("#show_filter").css('display', 'block');
    });
    $("#show_filter").on('click', function() {
        $("#filter").css('display', 'block');
        $("#show_filter").css('display', 'none');
    });



</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js">
</script>

<script>
    document.getElementById('misFilter').addEventListener('submit', function(e) {
        e.preventDefault();

        // Create a FormData object from the form
        var formData = new FormData(this);

        // Assuming you're sending the form data via AJAX
        $.ajax({
            url: './get_mis',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $("#misRecords").html(response);
            }
        });
    });

</script>

<?php  require 'partials/footer.php'; ?>

<?php 
include('includes/config.php');
include('includes/session_check.php');
if(isset($_POST['submit'])){
    
    
    if($count[0] == 0 ){
        if(count($_FILES['file_import']['name']) > 0){
            $TableNameArr = 'aruba_backlog_raw';
            for($i=0; $i<count($_FILES['file_import']['name']); $i++) {
                $tmpFilePath = $_FILES['file_import']['tmp_name'][$i];
                if($tmpFilePath != ""){
                   $shortname = explode(".",$_FILES['file_import']['name'][$i]);
                    $filename=$shortname[0];
                    $filePath = CSV_ROOT_PATH."csv/" . $_FILES['file_import']['name'][$i];
                    if(move_uploaded_file($tmpFilePath,$filePath)) {
                        try {
                            $conn = new PDO("mysql:host=".SERVER.";dbname=".DATABASE, DBUSER, DBPASS);
                            $conn->exec('LOAD DATA '.$localkeyword.' INFILE "'.$filePath.'" INTO TABLE  '. $TableNameArr. ' FIELDS TERMINATED BY ","   OPTIONALLY ENCLOSED BY """" LINES TERMINATED BY "\n" IGNORE 1 LINES');
                        }catch(PDOException $e){  
                            echo $e->getMessage(); 
                        }
                       unlink($filePath);
                    }else{
                        echo $_FILES['file_import']['name'][$i]." - not upload";
                    }
                }
            }
        }
        unset($_FILES['file_import']);
    }else{
        $ErrorMsg = $count[0];
    }
    // $_POST['date']);
    
}
include("includes/header.php");
?>          

        <!-- PAGE CONTENT WRAPPER -->
        <div class="page-content-wrap">
        
            <div class="row">
                <div class="col-md-12">
                    
                    <form class="form-horizontal" method="POST" id="upload"  enctype="multipart/form-data">
                    <input type="hidden" value='submit' name='submit'>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><strong>Backlog Raw Data Upload</strong></h3>
                                <ul class="panel-controls">
                                    <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                                </ul>
                            </div>
                            <div class="panel-body"></p></div>
                             <!-- form-group-separated -->
                            <div class="panel-body">                                                                        
                                
                                <div class="row">
                                    <div class="col-md-4">

                                        <div class="widget widget-primary">
                                            <div class="widget-title">TOTAL Backlog Upto</div>
                                            <div class="widget-subtitle"><?php 
                                            $date = $commonobj->getQry("SELECT count(*) as count,calendar_date From aruba_backlog_raw order by id ASC limit 0,1");
                                            echo $date[0]['calendar_date'];
                                            ?></div>
                                            <div class="widget-int"><span data-toggle="counter" data-to="<?=$date[0][count]?>"><?=$date[0]['count']?></span></div>
                                            <div class="widget-controls">
                                                <a href="#" class="widget-control-left"><span class="fa fa-upload"></span></a>
                                                <a href="#" class="widget-control-right"><span class="fa fa-times"></span></a>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <div class="widget widget-success widget-no-subtitle">
                                            <div class="widget-big-int"><span class="num-count"><?php echo $ErrorMsg ==''?'-':$ErrorMsg ?></span></div>                            
                                            <div class="widget-subtitle">Total Records</div>
                                            <div class="widget-controls">
                                                <a href="#" class="widget-control-left"><span class="fa fa-cloud"></span></a>
                                                <a href="#" class="widget-control-right"><span class="fa fa-times"></span></a>
                                            </div>                            
                                        </div>                        

                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="widget widget-danger widget-no-subtitle">
                                            <div class="widget-big-int"><span class="num-count"><?php echo $ErrorMsg ==''?'-':$ErrorMsg ?></span></div>                            
                                            <div class="widget-subtitle">Already Upload Backlog Your Select Date</div>
                                            <div class="widget-controls">
                                                <a href="#" class="widget-control-left"><span class="fa fa-cloud"></span></a>
                                                <a href="#" class="widget-control-right"><span class="fa fa-times"></span></a>
                                            </div>                            
                                        </div>                        

                                    </div>

                                </div>
                                <div class="form-group">                                        
                                    <label class="col-md-6 control-label">Date</label>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" value="<?php echo date('m/d/Y')?>" data-date-format="mm/dd/yyyy" data-date-viewmode="years" id='date' name='date'>
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                        </div>
                                        <span class="help-block font-red"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-6 col-xs-12 control-label">Backlog File Upload:</label>
                                    <div class="col-md-6 col-xs-12">                                             
                                        
                                            <input type="file" class="fileinput btn-primary" name="file_import[]" id="file_import">
                                       
                                        <label id="file_import-error" class="error" for="file_import"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer text-center" >
                                <a href="upload.php"><input type='button' class="btn btn-danger" formnovalidate value='Cancel'></a>
                                <button class="btn btn-info ">Upload</button>
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>                    
            
        </div>

<?php include("includes/footer.php"); ?>
<script type='text/javascript' src='js/plugins/jquery-validation/additional/additional-methods.min.js'></script> 
<script>
$( "#upload" ).validate({
    rules: {
        file_import: {
            required: true,
            extension: "csv"
        },
    }
});
$('.datepicker').datepicker({
            format: 'mm/dd/yyyy',
            //startDate: '-0d'
        });
</script>
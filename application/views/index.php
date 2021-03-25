<!doctype html>
<html>
 <head>
  <title>Simple Directory listing Application</title>
  <link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrap.min.css"); ?>" />
  <link rel="stylesheet" href="<?php echo base_url("assets/css/fa/all.css"); ?>" />

  
  <link rel="stylesheet" href="<?php echo base_url("assets/dt/datatables.min.css"); ?>" />
  <!-- <script src="<?php echo base_url('assets/jquery/jquery-2.2.3.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script> -->
 
  <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.6.0.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.min.js"); ?>"></script>

<script>var base_url = "<?php echo base_url();?>";
var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
    csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

</script>
<script type="text/javascript" src="<?php echo base_url("assets/dt/datatables.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/custom.js"); ?>"></script>
<script>
// for  name of the file appear on select
$(document).ready(function(){

    $(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

})

</script>
<style>
    .delete{
        color:red
    }
</style>
 </head>
<body>
    <div class="container-fluid">
        <!-- file upload card !-->
        <div class="card" style="margin-top:10px">
            <div class="card-header">
                <h5>Simple Directory listing Application  <i class="fas fa-upload"></i></h5>
            </div>
            <div class="card-body">
                <form method='post' action='<?php echo base_url("upload"); ?>' enctype='multipart/form-data'>
                    <input name="<?php echo $this->security->get_csrf_token_name(); ?>" type="hidden" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                    <div class="row"> 
                        <div class="col-md-6">
                           
                            <div class="custom-file">
                                <input type="file" name='user_file' class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <input type='submit'  class="btn btn-primary btn-block" value='Upload' name='upload' />
                        </div>
                    </div>
                </form>

                <?php 
                
                if(!empty($this->session->flashdata('message'))){
                ?>
                    <div  style="margin-top:10px"class="alert alert-success alert-dismissible fade show" role="alert">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        
                        <?php echo $this->session->flashdata('message');?>
                    </div>
                <?php } 

                if(!empty($this->session->flashdata('error'))){
                ?>
                    <div  style="margin-top:10px"class="alert alert-danger alert-dismissible fade show" role="alert">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <?php print_r($this->session->flashdata('error'));?>
                    </div>
                <?php } ?>

                
            </div>
        </div>
        <!-- file upload card end !-->
        <!-- table listing card !-->
        <div class="card text-center">
            <div class="card-header">
              
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#active">Uploaded File List <i class="fas fa-upload"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#deleted" id="del"> History Of  Files<i class="fas fa-trash"></i></a>
                    </li>

                 
                </ul>
            </div>

            <div class="tab-content">
                <div class="tab-pane  card-body active" id="active">
                    <table id="mytable" class="table table-striped table-bordered" style="width:100%" data-url="<?php echo base_url('welcome/ajax_list');?>"> 
                        <thead>
                            <tr>
                            <th data-index="slno">Sl No</th>
                            <th data-index="name">File Name</th>
                            <th data-index="type">File Type</th>
                            <th data-index="created"> Created on</th>
                            <th data-index="option">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane  card-body" id="deleted">
                <table id="history_table" class="table table-striped table-bordered" style="width:100%" data-url="<?php echo base_url('welcome/delete_history');?>"> 
                        <thead>
                            <tr>
                            <th data-index="slno">Sl No</th>
                            <th data-index="name">File Name</th>
                            <th data-index="type">File Type</th>
                            <th data-index="action"> Action</th>
                            <th data-index="ip"> Client Ip</th>
                            <th data-index="os"> Client Os</th>
                            <th data-index="browser"> Browser</th>
                            <th data-index="created">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
  
        </div>
        </div>
        <!-- table listing card end !-->
    </div>
</body>
</html>
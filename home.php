<?php include('db_connect.php'); ?>
<!-- Info boxes -->

<script>
    var welcomeMessage = 'Welcome, <?php echo isset($_SESSION['login_name']) ? $_SESSION['login_name'] : "User"; ?>!';
    alert(welcomeMessage);
</script>

<div class="row">
    <!-- Total Categories -->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-folder"></i></span>
            <a href="./index.php?page=categories">
                <div class="info-box-content">
                    <span class="info-box-text">Total Categories</span>
                    <span class="info-box-number">
                        <?php echo $conn->query("SELECT * FROM categories")->num_rows; ?>
                    </span>
                </div>
            </a>
        </div>
    </div>
    <!-- /.info-box -->

    <!-- Total Users -->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
            <a href="./index.php?page=user_list">
                <div class="info-box-content">
                    <span class="info-box-text">Total Users</span>
                    <span class="info-box-number">
                        <?php echo $conn->query("SELECT * FROM tbl_users")->num_rows; ?>
                    </span>
                </div>
            </a>
        </div>
    </div>
    <!-- /.info-box -->

    <!-- Total Surveys -->
    <div class="col-12 col-sm-6 col-md-3">
        <a href="./index.php?page=survey_templates">
            <div class="info-box">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-poll-h"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Surveys</span>
                    <span class="info-box-number">
                        <?php echo $conn->query("SELECT * FROM survey_set")->num_rows; ?>
                    </span>
                </div>
            </div>
        </a>
    </div>
    <!-- /.info-box -->

    <!-- Total Messages -->
    <div class="col-12 col-sm-6 col-md-3">
        <a href="./index.php?page=inbox">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-envelope"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Messages</span>
                    <span class="info-box-number">
                        <?php echo $conn->query("SELECT * FROM contact")->num_rows; ?>
                    </span>
                </div>
            </div>
        </a>
    </div>
    <!-- /.info-box -->
</div>

<div class="col-12">
    <div class="card">
        <div class="card-body">
            Welcome <?php echo isset($_SESSION['login_name']) ? $_SESSION['login_name'] : "User"; ?>!
        </div>
    </div>
</div>
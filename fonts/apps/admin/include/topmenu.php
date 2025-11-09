
<section class="content-header" >
      
      </section>
  
    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
      <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-12">
      <a href="movieSearch.php">
        <div class="info-box" style="cursor: pointer;">
          <span class="info-box-icon bg-gray"><i class="fa fa-film" aria-hidden="true"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">ADD MOVIES</span>
          </div>
        </div>
      </a>
    </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
      <a href="movielist.php">
        <div class="info-box">
          <span class="info-box-icon bg-maroon"><i class="fa fa-bookmark-o" aria-hidden="true"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">BOOKINGS</span>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <a href="feedback.php">
        <div class="info-box">
          <span class="info-box-icon bg-blue"><i class="fa fa-comments-o" aria-hidden="true"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">FEEDBACK</span>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <a href="add_admin.php">
        <div class="info-box">
          <span class="info-box-icon bg-blue"><i class="fa fa-bell" aria-hidden="true"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">MANAGE UNITS</span>
          </div>
        </div>
      </a>
    </div>
  
<br>       

      <!-- /.row -->
     
      
<!-- Custom CSS -->
<style>
  /* General Info Box styling */
  .info-box {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    min-height: 120px;
    margin-bottom: 10px;
    background-color: #f7f7f7;
    padding: 10px;
    border-radius: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
  }

  .info-box-icon {
    font-size: 2rem;
    width: 60px;
    height: 60px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-shrink: 0;
    border-radius: 50%;
  }

  .info-box-content {
    margin-left: 10px;
    text-align: left;
    flex-grow: 1;
  }

  .info-box-text {
    font-size: 1.8rem;
    font-weight: 600;
    color: #333;
  }

  /* Hover effect */
  .info-box:hover {
    background-color: #e6e6e6;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
  }

  /* Responsive for small screens (max-width: 576px) */
  @media (max-width: 576px) {
    .info-box {
      min-height: 90px;
      margin-bottom: 5px; /* Reduce space between elements */
    }

    .info-box-icon {
      font-size: 1.5rem;
      width: 50px;
      height: 50px;
    }

    .info-box-content {
      margin-left: 8px;
    }

    .info-box-text {
      font-size: 1.2rem; /* Smaller text for mobile */
    }
  }
</style>
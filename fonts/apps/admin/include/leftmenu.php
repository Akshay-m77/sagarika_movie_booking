<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
  <!-- Sidebar user panel -->
  <div class="user-panel">
    <div class="pull-left image"></div>
    <div class="pull-left info"></div>
  </div>
  
  <!-- Sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu" data-widget="tree">
    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
      <a href="index.php">
        <i class="fa fa-dashcube" aria-hidden="true"></i>
        <span>Dashboard</span>
      </a>
    </li>
    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
      <a href="profile.php">
        <i class="fa fa-ticket" aria-hidden="true"></i>
        <span>Profile</span>
      </a>
    </li>
    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'upcomingmovies.php' ? 'active' : ''; ?>">
      <a href="upcomingmovies.php">
        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
        <span>Edit movies</span>
      </a>
    </li>
    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'userlist.php' ? 'active' : ''; ?>">
      <a href="userlist.php">
        <i class="fa fa-list-alt" aria-hidden="true"></i>
        <span>User List</span>
      </a>
    </li>
    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'addUser.php' ? 'active' : ''; ?>">
      <a href="addUser.php">
        <i class="fa fa-list-alt" aria-hidden="true"></i>
        <span>Add users</span>
      </a>
    </li>
                     
    <li class="treeview">
          <a href="logout.php">
          <i class="fa fa-sign-out" aria-hidden="true"></i>
            <span>Logout</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             <li><a href="logout.php"><i class="fa fa-laptop"></i> Logout</a></li>  
          </ul>
        </li>
  </ul>
</section>

<style>
/* CSS for highlighting the active menu item */
.sidebar-menu .active > a {
  background-color: #3c8dbc; /* Change this to your desired color */
  color: white; /* Change this to your desired text color */
}

.sidebar-menu > li > a {
  color: #333; /* Default text color for menu items */
}

.sidebar-menu > li > a:hover {
  background-color: #f4f4f4; /* Background color on hover */
  color: #333; /* Text color on hover */
}
</style>

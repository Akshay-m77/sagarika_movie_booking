<div class="row">	    
    <div class="col-md-12">
    	<div class="box box-info">
            <div class="box-header with-border">
            <a href="upcomingmovies.php">              
              <h3 class="box-title" style="color:black; font-weight:bold;">LATEST MOVIES</h3></a>
              </a>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">              
                   <table id="example1" class="table table-bordered table-striped table-responsive" style="border-collapse: collapse;width: 100%;margin-top: 2px;">
                   <thead>
                    <tr>
                     <th style="background-color: #7393B3;text-align:center;">Movie</th>
                     <th style="background-color: #7393B3;text-align:center;">Name</th>
                     <th style="background-color: #7393B3;text-align:center;">Description</th>
                     <th style="background-color: #7393B3;text-align:center;">Rating</th>
                     <th style="background-color: #7393B3;text-align:center;">Starring</th>
                     <th style="background-color: #7393B3;text-align:center;">Date</th>
                   </tr>
                  </thead>
                  <tbody>	                
                    <?php
							$result = $db->prepare("
              SELECT *
FROM movies m
JOIN schedules s ON m.M_ID = s.M_ID
WHERE m.STATUS = 0  -- Filter to show only movies with status 0
AND (
    s.DATE > CURRENT_DATE 
    OR (s.DATE = CURRENT_DATE AND s.SHOW_TIME > CURRENT_TIME)
)
GROUP BY m.M_ID
LIMIT 3;

");
							$result->execute();
							for($i=1; $row = $result->fetch(); $i++)
							{
                $mid=$row["M_ID"];
                $imgpath=$row["IMAGE"];
							echo"<tr>";
								echo"<td style='text-align:center;'><img src='$imgpath' alt='image'  ' style='max-width: 200px; max-height: 300px; display: inline-block; border-radius: 8px; margin-bottom: 10px;'></td>";	
								echo"<td style='text-align:center;'>".$row["NAME"]."</td>";	
								echo"<td style='text-align:center;'>".$row["DESCRIPTION"]."</td>";
                
								// echo"<td>".$row["RUNTIME"]."</td>";
								echo"<td style='text-align:center;'>".$row["RATING"]."</td>";	
								echo"<td style='text-align:center;'>".$row["STARRING"]."</td>";	
                echo "<td style='text-align:center;'>";
                $result2 = $db->prepare("SELECT *
                FROM schedules where M_ID=$mid AND ((DATE > CURRENT_DATE) OR 
                    (DATE = CURRENT_DATE AND SHOW_TIME > CURRENT_TIME) )
                ");
                $result2->execute();
                for($i=1; $row = $result2->fetch(); $i++)
                {
                 
								echo $row["DATE"]."&nbsp"."&nbsp"."&nbsp"."&nbsp".$row["SHOW_TIME"]."<br><br>";
                }
						   echo"</td></tr>";
						   }						
				 	?>				
				</tbody>
              </table>
                </div>
            </div>
            <!--
            <div class="box-footer clearfix">
                <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">All Booking</a>
            </div>
            -->
    	</div>  
	</div>           
</div>  

<div class="row">	    
    <div class="col-md-12">
    	<div class="box box-info">
            <div class="box-header with-border">
              <a href="feedback.php"><h3 class="box-title" style="color:black; font-weight:bold;">FEEDBACK</h3></a>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">              
                   <table id="example1" class="table table-bordered table-striped table-responsive">
                   <thead>
                    <tr>
                     <th style="background-color: #7393B3;text-align:center;">Sl No</th>
                     <th style="background-color: #7393B3;text-align:center;">SERVICE</th>
                     <th style="background-color: #7393B3;text-align:center;">RATING</th>
                     <th style="background-color: #7393B3;text-align:center;">Description</th>
                     
                   </tr>
                  </thead>
                  <tbody>	                
                    <?php


                    // Function to convert numerical rating to star icons
                    function displayRatingStars($rating) {
                    $stars = '';
                    for ($i = 1; $i <= 5; $i++) {
                      $stars .= ($i <= $rating) ? '<i class="fa fa-star text-yellow"></i>' : '<i class="fa fa-star text-gray"></i>';
                      }
                      return $stars;
                    }

							$result = $db->prepare("SELECT *
              FROM feedback
              ORDER BY DATE DESC
              LIMIT 3;
              ");
							$result->execute();
							for($i=1; $row = $result->fetch(); $i++)
							{
							echo"<tr style='text-align:center;'>";
								echo"<td>".$i."</td>";	
								echo"<td>".$row["SERVICES"]."</td>";	
								echo"<td>".displayRatingStars($row["RATING"])."</td>";
								echo"<td>".$row["DESCRIPTION"]."</td>";
								
						   echo"</tr>";
						   }						
				 	?>				
				</tbody>
              </table>
                </div>
            </div>
            <!--
            <div class="box-footer clearfix">
                <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">All Booking</a>
            </div>
            -->
    	</div>  
	</div>           
</div>  

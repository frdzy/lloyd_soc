<?php
session_start();
?>
<html>
<head>
<title>Lloyd Ski Trip 2010</title>
<style type="text/css">
body {
    margin: 0px;
}

.title {
    padding: 5px 5px 5px 5px;
    margin: 0px;
}

.imageBox {
    float: left;
    padding: 5px 5px 5px 5px;
}

.list {
    width: 600px;
    float: left;
    padding: 5px 5px 5px 5px;
    margin: 0;
    font-size: 12pt;
}

.content {
    width: 650px;
    float: left;
    padding: 5px 5px 5px 5px;
    margin: 0;
    font-size: 12pt;
    clear: left;
}

.inputForm {
    width: 800px;
    float: left;
    padding: 5px 5px 5px 5px;
    font-size: 12pt;
    clear: both;
}
</style>
<link rel="stylesheet" href="ll_default.css">
<link rel="stylesheet" href="ll_houselist.css">
</head>

<body>
    <div class='title'>
        <h1><a href="http://www.youtube.com/watch?v=-_HjUpQQyqY">"D'oh!"</a> - Homer Simpson, skiing</h1>
    </div>

    <!-- --> <!-- --> <!-- -->
    <!-- Event Details -->
    <!-- --> <!-- --> <!-- -->
    <div class='content'>
        <h2>Event Details</h2>
        <b>Date</b><br />
        January 7th - January 9th, 2011<br />
        <font color="red">Sign up</font> by Sunday, December 5th, 2010<br />
        <font color="red">Pay</font> by Thursday, December 9th, 2010<br />

        <br>
        <b>Location</b><br />
        Golden Bear Cottages<br />
        39367 Big Bear Blvd. (Hwy 18)<br />
        Big Bear Lake, CA 92315<br />
        <iframe width="425" height="350" frameborder="0" scrolling="no" 
        marginheight="0" marginwidth="0" 
        src="http://maps.google.com/maps?f=d&amp;source=s_d&amp;saddr=1200+E.+California+Blvd.,+Pasadena,+CA&amp;daddr=34.2321278,-117.2053796+to:39367+Big+Bear+Boulevard,+Big+Bear+Lake,+CA+92315+(Golden+Bear+Cottages)&amp;hl=en&amp;geocode=FTPjCAId3Y_1-Cmx5mzbp8TCgDG--RbS-nFWYA%3BFT9XCgIdfZYD-Snx3X6yT1bDgDFIGToVlg-w6w%3BFetvCgIdxJcH-SFTRQpd1nfcIQ&amp;mra=ls&amp;via=1&amp;sll=34.181702,-117.189102&amp;sspn=0.339684,0.727158&amp;ie=UTF8&amp;t=h&amp;ll=34.175453,-117.155457&amp;spn=0.397653,0.583649&amp;z=10&amp;output=embed"></iframe><br 
        /><small><a 
        href="http://maps.google.com/maps?f=d&amp;source=embed&amp;saddr=1200+E.+California+Blvd.,+Pasadena,+CA&amp;daddr=34.2321278,-117.2053796+to:39367+Big+Bear+Boulevard,+Big+Bear+Lake,+CA+92315+(Golden+Bear+Cottages)&amp;hl=en&amp;geocode=FTPjCAId3Y_1-Cmx5mzbp8TCgDG--RbS-nFWYA%3BFT9XCgIdfZYD-Snx3X6yT1bDgDFIGToVlg-w6w%3BFetvCgIdxJcH-SFTRQpd1nfcIQ&amp;mra=ls&amp;via=1&amp;sll=34.181702,-117.189102&amp;sspn=0.339684,0.727158&amp;ie=UTF8&amp;t=h&amp;ll=34.175453,-117.155457&amp;spn=0.397653,0.583649&amp;z=10" 
        style="color:#0000FF;text-align:left">View Larger Map</a></small>
        <br />
        Snow Summit<br />
        880 Summit Blvd.<br />
        Big Bear Lake, CA 92315<br />
        <a href="http://www.snowsummit.com/itrailmap/">Interactive Trail Map</a><br />

        <br>
        <b>Cost</b><br />
        Check should be payable to Lloyd House.<br />
        Cabin: <font color=red>$60</font> Full/Social Lloydies, <font color=red>$80</font> Alums/Guests*<br />
        Lift ticket: <font color=red>$40</font> Full/Social Lloydies, <font color=red>$55</font> Alums/Guests*<br />
        <font color=red>Note:</font> Ticket prices have gone up since last year because January 8th is a Peak Saturday this year. :(<br />
        Equipment rental: $20-$25 (pay at resort)<br />
        Drivers: <font color=red>gas cost</font> will be reimbursed!<br />
        <br />
        *Guests accomodated based on available vehicle seats<br />
    </div>

    <div class='imageBox'>
        <img src="/lloyd/lloyd2012.jpg">
    </div>

    <!-- --> <!-- --> <!-- -->
    <!-- Admin Panel -->
    <!-- --> <!-- --> <!-- -->

    <div class='content'>
        <!-- Login Panel -->
        <?php

        if(!isset($_SESSION['llogged_in'])) {
            echo "<a href=/ll_login.php>Admin</a><br />";
        }
        
        $con = mysql_connect("localhost","USERNAME","PASSWORD");

        if( !$con )
        {
            die('Could not connect: '.mysql_error());
        }

        mysql_select_db("lloyd", $con);

        if($_SESSION['llogged_in']) {
            if($_POST['participant'] && $_POST['amount'] && $_POST['paid_to']) {
                if( mysql_query("
                    UPDATE lloyd.ll_participate SET paid_amount = paid_amount + "
                        .$_POST['amount'].", paid_to_id = ".$_POST['paid_to']."
                        WHERE participant_id = (SELECT id FROM names WHERE name LIKE '%"
                        .$_POST['participant']."%') AND ll_event_id = 2;") ) {
                    echo "Payment update successful!";
                }
                else {
                    echo "Error: Fred sucks at coding payments: ". mysql_error();
                }
            }
            else if($_POST['drivee'] && $_POST['driver'] ) {

                if( mysql_query("
                    UPDATE lloyd.ll_participate SET driver_id =
                        (SELECT id FROM names WHERE name LIKE '%"
                            .$_POST['driver']."%')
                        WHERE participant_id = (SELECT id FROM names WHERE name LIKE '%"
                        .$_POST['drivee']."%') AND ll_event_id = 2;") ) {
                    echo "Driver update successful!";
                }
                else {
                    echo "Error: Fred sucks at coding drivers: ". mysql_error();
                }
            }
        ?>
        <h2>Admin</h2>
        <a href=/ll_logout.php>Logout</a><br />
        <h3>Driver Assignment</h3>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <table border='0'>
        <tr>
            <th>Name</th>
            <th>Driver</th>
            <th></th>
        </tr>
        <tr>
            <td><input type="text" name="drivee"></td>
            <td><input type="text" name="driver"></td>
            <td><input type="submit" value="Submit"></td>
        </tr>
        </table></form>
        <h3>Payment Record</h3>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <table border='0'>
        <tr>
            <th>Name</th>
            <th>Amount Paid</th>
            <th>Paid To</th>
            <th></th>
        </tr>
        <tr>
            <td><input type="text" name="participant"></td>
            <td><input type="text" name="amount"></td>
            <td><select name="paid_to" id="paid_to">
                <option value="360">Elisa</option>
                <option value="367">Fred</option>
                </select>
            </td>
            <td><input type="submit" value="Submit"></td>
        </tr>
        </table></form>
        <?php
        }

        mysql_close($con);
        ?>
    
    </div>

<?php
$con = mysql_connect("localhost","USERNAME","PASSWORD");
if( !$con ) {
    die( 'Could not connect: '.mysql_error() );
}
else {
    
    mysql_select_db( "lloyd", $con );

    $cabin_results = mysql_query("
        SELECT * FROM ski2010_cabins;
    ");

    $driver_results = mysql_query("
        SELECT COUNT(*) AS attendees, COUNT(DISTINCT driver_id) AS drivers,
            SUM(driver_seats + 1) AS seats
        FROM ll_participate
        WHERE ll_event_id = 2;
    ");

    $lifts = mysql_query("
        SELECT COUNT(*) AS count
        FROM ski_participant
        WHERE lift_ticket = 1;
    ");

    if( isset($_GET['sort'] ) ) {
        if( $_GET['sort'] == 'name' ) {
            $sort = 'lastname';
            $order = '';
        }
        else if( $_GET['sort'] == 'cabin' ) {
            $sort = 'cabin_name, lol';
            $order = '';
        }
        else if( $_GET['sort'] == 'paid' ) {
            $sort = 'paid_amount';
            $order = '';
        }
        else if( $_GET['sort'] == 'remaining' ) {
            $sort = 'owe';
            $order = ' DESC';
        }
        else {
            $sort = 'lastname';
            $order = '';
        }
    }
    else
        $sort = 'lastname';

    $results= mysql_query("
        SELECT *, price - paid_amount AS owe FROM ski2010_3 ORDER BY ".$sort.$order.", lastname, name;
    ");

    $classes = mysql_query("
        SELECT class, COUNT(*) AS count, SUM(lift_ticket) AS skiiers
        FROM houselist JOIN ll_participate
                ON id = participant_id
            JOIN ski_participant
                ON id = occupant_id
        WHERE ll_event_id = 2
        GROUP BY class;
    ");
}
?>

    <!-- --> <!-- --> <!-- -->
    <!-- Information Summary -->
    <!-- --> <!-- --> <!-- -->


    <div class='content'>
        <h2>List of Attendees</h2>
    <?php
        echo "
        <table border='0'>
        <tr>
            <th><a href=\"/ski2010.php?sort=name\">Name</a></th>
            <th><a href=\"/ski2010.php?sort=cabin\">Cabin</a></th>
            <th>Lift Ticket</th>
            <th>Seats</th>
            <th><a href=\"/ski2010.php?sort=paid\">Paid</a></th>
            <th><a href=\"/ski2010.php?sort=remaining\">Owe</a></th>
            <th>Paid To</th>
        </tr>";

        $dark = true;
        while($row = mysql_fetch_array($results))
        {
            if( $dark ) {
                $color="dark";
            }
            else {
                $color="light";
            }
            $dark = !$dark;
            echo "
        <tr>
            <td class = \"".$color."\">" . $row['name'] . "</td>
            <td class = \"".$color."\">" . $row['cabin_name'] . "</td>
            <td class = \"".$color."\">" . $row['lift'] . "</td>
            <td class = \"".$color."\">";
            if( $row['seats'] > 0 )
            {
                echo $row['seats'];
            }
                echo "  </td>
            <td class = \"".$color."\">" . $row['paid_amount'] . "</td>
            <td class = \"".$color."\">" . $row['owe'] . "</td>
            <td class = \"".$color."\">" . $row['paid_to'] . "</td>
        </tr>";
        }
        echo "
        </table>";
        mysql_close($con);
    ?> 
    </div>
    
    <div class='list'>
        <h2>Overview</h2>
    <?php

        $liftcount = mysql_fetch_array($lifts);
        echo "
        <table border='0'>
        <tr>
            <th>Attendees</th>
            <th>Skiiers</th>
            <th>Drivers</th>
            <th>Seats</th>
            <th>Sad Lloydies</th>
        </tr>";

        $color= "dark";
        while($row = mysql_fetch_array($driver_results)) {
        echo "
        <tr>
            <td class = \"".$color."\">" . $row['attendees'] . "</td>
            <td class = \"".$color."\">" . $liftcount['count'] . "</td>
            <td class = \"".$color."\">" . $row['drivers'] . "</td>
            <td class = \"".$color."\">" . $row['seats'] . "</td>
            <td class = \"".$color."\"><font color=#ff0000>" . ($row['attendees'] - $row['seats']) . "</font></td>
        </tr>";
        }
        echo "
        </table>";
    ?>

    </div>
    <div class='list'>
        <h2>List of Cabins</h2>
    <?php
        echo "
        <table border='0'>
        <tr>
            <th>Cabin</th>
            <th>Total Spaces</th>
            <th>Occupants</th>
        </tr>";

        $dark = true;
        while($row = mysql_fetch_array($cabin_results))
        {
            if( $dark ) {
                $color="dark";
            }
            else {
                $color="light";
            }
            $dark = !$dark;
            echo "
        <tr>
            <td class = \"".$color."\">" . $row['cabin_name'] . "</td>
            <td class = \"".$color."\">" . $row['cabin_space'] . "</td>
            <td class = \"".$color."\">" . $row['occupants'] . "</td>
        </tr>";
        }
        echo "
        </table>";
    ?>

    </div>
    <div class='list'>
        <h2>Attendees by Class</h2>
        
    <?php
        echo "
        <table border='0'>
        <tr>
            <th>Class</th>
            <th>Attendees</th>
            <th>Skiiers</th>
        </tr>";

        $dark = true;
        while($row = mysql_fetch_array($classes))
        {
            if( $dark ) {
                $color="dark";
            }
            else {
                $color="light";
            }
            $dark = !$dark;
            echo "
        <tr>";
            if( $row['class'] == '' ) {
                echo "
                <td class = \"".$color."\">Alum</td>";
            }
            else {
                echo "
            <td class = \"".$color."\">" . $row['class'] . "</td>";
            }
            echo "
            <td class = \"".$color."\">" . $row['count'] . "</td>
            <td class = \"".$color."\">" . $row['skiiers'] . "</td>
        </tr>";
        }
        echo "</table>";
    ?>

    </div>

</body>
</html>

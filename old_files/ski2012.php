<?php
session_start();
require_once('init.php');
require_once('pdo_lloyd_db_connect.php');
require_once('post_get.php');

if (!extension_loaded('xhp')) {
  echo '<a href="https://github.com/facebook/xhp">XHP</a> extension not found.\n';
}
$db = lloyd_db_connect();

$this_page = $_SERVER['PHP_SELF'];
$ll_event_id = 5;

$head =
  <head>
    <title>Lloyd Ski Trip 2012</title>
    <link rel="stylesheet" href="ll_ski.css" />
    <link rel="stylesheet" href="ll_default.css" />
    <link rel="stylesheet" href="ll_houselist.css" />
    <link rel="stylesheet" type="text/css" href="tab-view.css" />
    <link rel="shortcut icon"
      href="http://lloyd.caltech.edu/gfx/lloydicon.ico" />
    <script type="text/javascript" src="ll_signup.js"></script>
  </head>;


$nav =
  <x:frag>
    <div class="tabBar">
      <hr />
      <span>
        <span class="tab">
          <a href={$this_page}>Home</a>
        </span>
        <span class="tab">
          <a href={$this_page.'?action=signup'}>Profile</a>
        </span>
        <span class="tab">
          <a href="http://www.youtube.com/watch?v=-_HjUpQQyqY">D{"'"}oh!</a>
        </span>
      </span>
      <span style="position:absolute; right: 0px; padding: 0px 10px 0px 0px;">
        <img
          src="http://arun.caltech.edu/img/lloydhouse_sm.jpg" />
      </span>
      <hr />
    </div>
  </x:frag>;

$action = get_param('action');
$status = <div class="success" />;
$admin = null;

$content = <div />;

if ($action === 'admin') {
  if(!isset($_SESSION['llogged_in'])) {
    $content->appendChild(
      <div style="padding: 50px 0px 0px 0px;">
        <a href="/ll_login.php">Login</a><br />
      </div>
    );
  }
  else {
    $admin = $content;
  }
}

else if ($action === 'signup') {
  $trip = post_param('trip');
  $fname = post_param('fname');
  $lname = post_param('lname');
  $lift = post_param('lift');
  $drive = post_param('drive');
  $cabin = post_param('cabin');

  if ($trip && $fname && $lname) {
    $fname = str_replace('\'','', $fname);
    $lname = str_replace('\'','', $lname);
    $driver = 'false';
    $driver_seats = -1;

    if ($lift === 'yes') {
      $lift = 1;
    }
    else {
      $lift = 0;
    }

    if ($drive === 'can_drive') {
      $driver_seats = $_POST['drive_seats'] - 1;
      $driver = 'true';
    }

    $signup_exec = '
      REPLACE INTO lloyd.ll_participate
        (ll_event_id, participant_id, driver_id, driver_seats)
      VALUE ('.
        $ll_event_id.',
        (SELECT id FROM names
          WHERE name LIKE \''.$fname.' '.$lname.'\'),
        IF('.$driver.', participant_id, NULL),'.$driver_seats.');';

    $ski_exec = '
      REPLACE INTO lloyd.ski_participant
      VALUE ('.
        $ll_event_id.',
        (SELECT id FROM names
          WHERE name LIKE \''.$fname.' '.$lname.'\'),'.
        $cabin.','.
        $lift.');';

    try {
      $db->beginTransaction();
      $db->exec($signup_exec);
      $db->commit();
      $db->beginTransaction();
      $db->exec($ski_exec);
      $db->commit();
      $status->appendChild(
        <label>
          Signed up. Fill out this form again to change cabins/ski option.
        </label>
      );
    }
    catch (Exception $e) {
      //$db->rollBack();
      if( $e->getMessage() == "Column 'participant_id' cannot be null" ) {
        $content->appendChild(
          <x:frag>
            <label class="warning">Error:</label>
            Name not found. Please use your name as it appears on the
            <a href="http://lloyd.caltech.edu/houselist.php">house list</a>.
          </x:frag>
        );
      }
      else {
        $content->appendChild(
        $e->getMessage()
        );
      }
    }
  }

  $content->appendChild(
    <div class="inputForm">
      <h2>Registration (closes Thursday Dec. 1st)</h2>

      <form method="post" action={$this_page.'?action=signup'}>
        Type your name as it appears in <a href="http://lloyd.caltech.edu/houselist.php">this list</a>.<br />
        First name: <input type="text" name="fname" /><br />
        Last name: <input type="text" name="lname" /><br />
        <input id="trip1" type="radio" name="trip" value="yes"
            onclick="javascript:
            enableGroup(this.form.elements['lift']);
            enableGroup(this.form.elements['cabin']);
            this.form.elements['drive'].disabled=false;" />
        <label for="trip1">
          I will attend Lloyd ski trip (+$60)
        </label>
        <input id="trip2" type="radio" name="trip" value="no"
            onclick="javascript:
            disableGroup(this.form.elements['lift']);
            disableGroup(this.form.elements['cabin']);
            this.form.elements['drive'].disabled=true;" />
        <label for="trip2">
          I dunno why I{"'"}m on this page :{"("}
        </label>
        <br />
        <input type="radio" name="cabin" value="6" disabled="true" />
        <a href="/lloyd/map.gif">Pooh{"'"}s Corner</a>
        <input type="radio" name="cabin" value="7" disabled="true" />
        <a href="/lloyd/map.gif">Knotty Pine</a>
        <input type="radio" name="cabin" value="8" disabled="true" />
        <a href="/lloyd/map.gif">Bearskin Hollow</a>
        <input type="radio" name="cabin" value="9" disabled="true" />
        <a href="/lloyd/map.gif">Bear{"'"}s Den</a>
        <br />
        <input type="radio" name="cabin" value="10" disabled="true" />
        <a href="/lloyd/map.gif">Lazy Days</a>
        <input type="radio" name="cabin" value="11" disabled="true" />
        <a href="/lloyd/map.gif">Racket Club</a>
        <input type="radio" name="cabin" value="17" disabled="true" />
        <a href="/lloyd/map.gif">Pine Cone Cottage</a>
        <br />
        <input type="radio" name="cabin" value="25" disabled="true" />
        <a href="/lloyd/map.gif">Bear Berry Manor</a>
        <input type="radio" name="cabin" value="26" disabled="true" />
        <a href="/lloyd/map.gif">Snuggle Bear Manor</a>
        <input type="radio" name="cabin" value="28" checked="true" disabled="true" />
        <a href="/lloyd/map.gif">Golden Bear Manor</a>
        <br />
        (Note: Golden Bear Manor is the traditional Frosh cabin)
        <br />
        <br />
        <input id="lift1" type="radio" name="lift" value="yes" disabled="true" />
        <label for="lift1">
          I want a lift ticket (+$40)
        </label>
        <input id="lift2" type="radio" name="lift" value="no" checked="true" disabled="true" />
        <label for="lift2">
          Imma just chill in the cabin or under the mountain
        </label>
        <br />
        <br />
        <input id="drive" type="checkbox" name="drive" value="can_drive" disabled="true" onchange="this.form.elements['drive_seats'].disabled=!this.form.elements['drive_seats'].disabled;" />
        <label for="lift2">
          I can drive
        </label> 
        <input type="text" name="drive_seats" disabled="true" />
        <label>
          people (including myself)
        </label>
        <br />
        <input type="submit" value="Submit" />
        Note to alumni: please <a href="mailto:ssiyer@caltech.edu,vzhang@caltech.edu?subject=Ski%20Trip%20alum%20signup">email Supriya and Vivian</a> instead of filling out this form<br />
      </form>
      {$status}
    </div>
  );

  $cabin_query = '
    SELECT * FROM ski2012_cabins;
  ';

  $driver_query = '
    SELECT COUNT(*) AS attendees, COUNT(DISTINCT driver_id) AS drivers,
    SUM(driver_seats + 1) AS seats
    FROM ll_participate
    WHERE ll_event_id = '.$ll_event_id.';
  ';

  $lift_query = '
    SELECT COUNT(*) AS count
    FROM ski_participant
    WHERE lift_ticket = 1 AND ll_event_id = '.$ll_event_id.';
  ';

  $sort = get_param('sort');
  if ($sort) {
    if ($sort === 'name') {
      $sort = 'lastname';
    }
    else if ($sort === 'cabin') {
      $sort = 'cabin_name';
    }
    else
      $sort = 'lastname';
  }
  else {
    $sort = 'lastname';
  }

  $sort_query = '
    SELECT * FROM ski2012 ORDER BY '.$sort.', lastname, name;
  ';

  $classes_query = '
    SELECT class, COUNT(*) AS count, SUM(lift_ticket) AS skiiers
    FROM houselist JOIN ll_participate
        ON id = participant_id
      JOIN ski_participant
        ON id = occupant_id AND
          ll_participate.ll_event_id = ski_participant.ll_event_id
    WHERE ll_participate.ll_event_id = '.$ll_event_id.'
    GROUP BY class;
  ';

  $overview_table =
    <table border="0">
      <tr>
        <th>Attendees</th>
        <th>Skiiers</th>
        <th>Seats</th>
        <th>Sad Lloydies</th>
      </tr>
    </table>;

  $overview_list =
    <div>
      <h2>Overview</h2>
      {$overview_table}
    </div>;

  foreach ($db->query($lift_query) as $row) {
    $liftcount = $row['count'];
  }

  $color= "dark";
  foreach ($db->query($driver_query) as $row) {
    $overview_table->appendChild(
      <tr>
        <td class={$color}>{$row['attendees']}</td>
        <td class={$color}>{$liftcount}</td>
        <td class={$color}>{$row['seats']}</td>
        <td class={$color}>{$row['attendees'] - $row['seats']}</td>
      </tr>
    );
  }

  $cabin_table =
    <table border="0">
      <tr>
        <th>Cabin</th>
        <th>Total Spaces</th>
        <th>Occupants</th>
      </tr>
    </table>;

  $dark = false;
  foreach ($db->query($cabin_query) as $row) {
    if ($dark = !$dark) {
      $color='dark';
    }
    else {
      $color='light';
    }
    $cabin_table->appendChild(
      <tr>
        <td class={$color}>{$row['cabin_name']}</td>
        <td class={$color}>{$row['cabin_space']}</td>
        <td class={$color}>{$row['occupants']}</td>
      </tr>
    );
  }

  $cabin_list =
    <div>
      <h2>List of Cabins</h2>
      {$cabin_table}
    </div>;

  $classes_table =
    <table border="0">
      <tr>
        <th>Class</th>
        <th>Attendees</th>
        <th>Skiiers</th>
      </tr>
    </table>;

  $classes_list =
    <div>
      <h2>Attendees by Class</h2>
      {$classes_table}
    </div>;

  $dark = true;
  foreach($db->query($classes_query) as $row) {
    if( $dark ) {
      $color="dark";
    }
    else {
      $color="light";
    }
    $dark = !$dark;
    $list_row = <tr />;

    if ($row['class'] == '') {
      $list_row->appendChild(
        <td class={$color}>Alum</td>
      );
    }
    else {
      $list_row->appendChild(
        <td class={$color}>{$row['class']}</td>
      );
    }

    $list_row->appendChild(
      <td class={$color}>{$row['count']}</td>
    );
    $list_row->appendChild(
      <td class={$color}>{$row['skiiers']}</td>
    );
    $classes_table->appendChild($list_row);
  }

  $attendees_table =
    <table border="0">
      <tr>
        <th><a href={$this_page.'?action=signup&sort=name'}>Name</a></th>
        <th><a href={$this_page.'?action=signup&sort=cabin'}>Cabin</a></th>
        <th>Lift Ticket</th>
        <th>Seats (If Driving)</th>
        <th>Paid Amount</th>
        <th>Paid To</th>
      </tr>
    </table>;

  $attendees_list =
    <div class="content">
      <h2>List of Attendees</h2>
      {$attendees_table}
    </div>;

  $dark = true;
  foreach ($db->query($sort_query) as $row) {
    if( $dark ) {
      $color='dark';
    }
    else {
      $color='light';
    }
    $dark = !$dark;
    $attendees_table->appendChild(
      <tr>
        <td class={$color}>{$row['name']}</td>
        <td class={$color}>{$row['cabin_name']}</td>
        <td class={$color}>{$row['lift']}</td>
        <td class={$color}>{$row['seats'] > 0 ? $row['seats'] : ''}</td>
        <td class={$color}>{$row['paid_amount']}</td>
        <td class={$color}>{$row['paid_to']}</td>
      </tr>
    );
  }

  $content->appendChild(
  <x:frag>
    <div class="listBar">
      {$overview_list}
      {$classes_list}
    </div>
    <div class="listBar">
      {$cabin_list}
    </div>
    </x:frag>
  );

  $content->appendChild($attendees_list);
}

else {
  $event_details =
    <div class="content">
      <h2>Event Details</h2>
      <b>Date</b><br />
      January 6th - January 8th, 2012<br />
      <a style="color:red;" href={$this_page.'?action=signup'}>Sign up</a>
      <label class="warning"> and pay</label>
      by Thursday, December 1st, 2011<br />
      <br />
      <b>Location</b><br />
      Golden Bear Cottages<br />
      39367 Big Bear Blvd. (Hwy 18)<br />
      Big Bear Lake, CA 92315<br />
      <br />
      <iframe
        width="425"
        height="350"
        frameborder="0"
        scrolling="no"
        marginheight="0"
        marginwidth="0"
        src="http://maps.google.com/maps?f=d&amp;source=s_d&amp;saddr=1200+E.+California+Blvd.,+Pasadena,+CA&amp;daddr=39367+Big+Bear+Boulevard,+Big+Bear+Lake,+CA+92315+(Golden+Bear+Cottages)&amp;hl=en&amp;geocode=FTPjCAId3Y_1-Cmx5mzbp8TCgDG--RbS-nFWYA%3BFfl7CgId_e8H-SFTRQpd1nfcIQ&amp;sll=34.175453,-117.155457&amp;sspn=0.81121,1.385651&amp;vpsrc=0&amp;mra=prv&amp;ie=UTF8&amp;t=h&amp;ll=34.175453,-117.155457&amp;spn=0.81121,1.385651&amp;output=embed">
      </iframe><br />
      <small>
        <a href="http://maps.google.com/maps?f=d&amp;source=embed&amp;saddr=1200+E.+California+Blvd.,+Pasadena,+CA&amp;daddr=39367+Big+Bear+Boulevard,+Big+Bear+Lake,+CA+92315+(Golden+Bear+Cottages)&amp;hl=en&amp;geocode=FTPjCAId3Y_1-Cmx5mzbp8TCgDG--RbS-nFWYA%3BFfl7CgId_e8H-SFTRQpd1nfcIQ&amp;sll=34.175453,-117.155457&amp;sspn=0.81121,1.385651&amp;vpsrc=0&amp;mra=prv&amp;ie=UTF8&amp;t=h&amp;ll=34.175453,-117.155457&amp;spn=0.81121,1.385651" style="color:#0000FF;text-align:left">
          View Larger Map
        </a>
      </small>
      <br />
      Snow Summit<br />
      880 Summit Blvd.<br />
      Big Bear Lake, CA 92315<br />

      <a href="http://www.snowsummit.com/itrailmap/">Interactive Trail Map</a><br />

      <br />
      <b>Cost</b><br />
      Check should be payable to Lloyd House.<br />
      Cabin: <label class="warning">$60</label> Full/Social Lloydies, <label class="warning">$80</label> Alums/Guests*<br />
      Lift ticket: <label class="warning">$40</label> Full/Social Lloydies, <label class="warning">$55</label> Alums/Guests*<br />
      Equipment rental: $20-$25 (pay at resort)<br />
      Drivers: <label class="warning">gas cost</label> will be reimbursed!<br />
      <br />
      *Guests accomodated based on available vehicle seats<br />
    </div>;

  $pic =
    <div class="imageBox">
      <img src="/lloyd/lloyd2012.jpg" />
    </div>;

  $content->appendChild(
    <x:frag>
      {$event_details}
      {$pic}
    </x:frag>
  );
}

$body =
  <body>
    {$nav}
    {$content}
  </body>;


if ($admin) {
  $admin->appendChild(
    <x:frag>
      <h2>Admin</h2>
      <a href="/ll_logout.php">Logout</a><br />
    </x:frag>
  );

  $amount = post_param('amount');
  $paid_to = post_param('paid_to');
  $participant = post_param('participant');
  $drivee = post_param('drivee');
  $driver = post_param('driver');
  if ($participant && $amount && $paid_to) {

    $payment_exec =
      'UPDATE lloyd.ll_participate SET paid_amount = paid_amount + '
      .$amount.', paid_to_id = '.$paid_to.'
      WHERE participant_id = (SELECT id FROM names WHERE name LIKE \'%'
      .$participant.'%\') AND ll_event_id = '.$ll_event_id.';';
    try {
      $db->beginTransaction();
      $db->exec($payment_exec);
      $db->commit();
      $admin->appendChild(
        <label>Payment update successful!</label>
      );
    }
    catch (Exception $e) {
      $db->rollBack();
      $admin->appendChild(
        <label>Error: Fred sucks at coding payments: {$e->getMessage()}</label>
      );
    }
  }
  else if ($drivee && $driver) {
    $driver_exec =
      'UPDATE lloyd.ll_participate SET driver_id =
      (SELECT id FROM names WHERE name LIKE \'%'
      .$driver.'%\')
      WHERE participant_id = (SELECT id FROM names WHERE name LIKE \'%'
      .$drivee.'%\') AND ll_event_id = '.$ll_event_id.';';

    try {
      $db->beginTransaction();
      $db->exec($driver_exec);
      $db->commit();
      $admin->appendchild(
        <label>Driver update successful!</label>
      );
    }
    catch (Exception $e) {
      $admin->appendchild(
        <label>Error: Fred sucks at coding drivers: {$e->getMessage()}</label>
      );
    }
  }

  $admin->appendChild(
    <x:frag>
      <h3>Driver Assignment</h3>
      <form method="post" action={$this_page.'?action=admin'}>
        <table border="0">
        <tr>
          <th>Name</th>
          <th>Driver</th>
          <th></th>
        </tr>
        <tr>
          <td><input type="text" name="drivee" /></td>
          <td><input type="text" name="driver" /></td>
          <td><input type="submit" value="Submit" /></td>
        </tr>
        </table>
      </form>
      <h3>Payment Record</h3>
      <form method="post" action={$this_page.'?action=admin'}>
        <table border="0">
        <tr>
          <th>Name</th>
          <th>Amount Paid</th>
          <th>Paid To</th>
          <th></th>
        </tr>
        <tr>
          <td><input type="text" name="participant" /></td>
          <td><input type="text" name="amount" /></td>
          <td><select name="paid_to" id="paid_to">
                <option value="384">Supriya</option>
              </select>
          </td>
          <td><input type="submit" value="Submit" /></td>
        </tr>
      </table></form>
    </x:frag>
  );
}

echo
  <html>
    {$head}
    {$body}
  </html>;

$db = null;
?>

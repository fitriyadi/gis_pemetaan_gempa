<?php 
$server   = 'localhost';
$user   = 'root';
$pass   = '';
$db     = 'db_reza';

$con    = mysql_connect($server, $user, $pass) or die('gagal koneksi');
mysql_select_db($db, $con) or die('gagal database');

?>
<script>
  function initMap() {
    var lokasi = [
    <?php
    session_start();
    $_SESSION['bulan']=$_POST['bulan'];
    $_SESSION['tahun']=$_POST['tahun'];

    $bulan=$_POST['bulan'];
    $tahun=$_POST['tahun'];

    $sql = "SELECT * from gempa where month(tanggal)='$bulan' and year(tanggal)='$tahun'";

    $a = mysql_query($sql);
    while ($data = mysql_fetch_object($a)) {
      echo "['$data->idgempa',";
      echo "'$data->tanggal',";
      echo "$data->lat,"; 
      echo "$data->longi,"; 
      echo "'$data->detail',"; 
      echo "$data->kedalaman,"; 
      echo "'$data->kekuatan'],"; 
    }
    ?>
    ];

    var icons = {
      k1: {
        name: 'Terasa',
        icon: 'http://www.clker.com/cliparts/o/t/F/J/B/k/google-maps-th.png'
      },
      k2: {
        name: 'Tidak Terasa',
        icon: 'http://www.clker.com/cliparts/o/t/F/J/B/k/google-maps-th.png'
      }
    };

    var infowindow = new google.maps.InfoWindow();
    var mapOptions = {
     center:new google.maps.LatLng(-8.583333, 117.516667), 
     zoom:8.5,
     mapTypeId:google.maps.MapTypeId.ROADMAP
   };

   var map = new google.maps.Map(document.getElementById("map"),mapOptions);
   var marker,i;

   for (i = 0; i<lokasi.length; i++) {
    if (lokasi[i][6] < 3) {
      url = 'http://maps.google.com/mapfiles/ms/icons/green-dot.png';
    } else if (lokasi[i][6] < 6) {
      url = 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png'
    } else {
      url = 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
    }

    marker = new google.maps.Marker({
      icon: {
        url: url,
      },
      position: new google.maps.LatLng(lokasi[i][2],lokasi[i][3]),
      map:map
    });
    google.maps.event.addListener(marker, 'click', (function(marker, i) {
      return function() {
        infowindow.setContent('<a <b>'+lokasi[i][1]+'</b></a><br><div class="panel"> Kedalaman: '+lokasi[i][5]+' KM<br> Kekuatan: '+lokasi[i][6]+' SR<br>Keterangan: '+lokasi[i][4]+'</div>');
        infowindow.open(map, marker);
      }
    })(marker, i));
  }

  map.data.loadGeoJson('../json/Kabupaten Lombok Utara.geojson');
  map.data.loadGeoJson('../json/Kabupaten Lombok Barat.geojson');
  map.data.loadGeoJson('../json/Kabupaten Lombok Tengah.geojson');
  map.data.loadGeoJson('../json/Kabupaten Lombok Timur.geojson');
  map.data.loadGeoJson('../json/Kabupaten Sumbawa.geojson');
  map.data.loadGeoJson('../json/Kabupaten Dompu.geojson');
  map.data.loadGeoJson('../json/Kabupaten Bima.geojson');
  map.data.loadGeoJson('../json/Kabupaten Sumbawa Barat.geojson');
  map.data.loadGeoJson('../json/Kota Mataram.geojson');
  map.data.loadGeoJson('../json/Kota Bima.geojson');

  map.data.setStyle(function(feature) {
    return {
      fillColor: '#000000',
      strokeColor: '#000000',
      strokeWeight: 1
    };
  });

  map.data.addListener('mouseover', function(event) {
    map.data.revertStyle();
    map.data.overrideStyle(event.feature, {strokeWeight: 3});
  });

  map.data.addListener('mouseout', function(event) {
    map.data.revertStyle();
  });


  map.data.addListener('click', function(event) {
    var feature = event.feature
    var html = '<span><b>' + event.feature.getProperty('KABKOT') + '</b></span>'

    infowindow.setContent(html)
    infowindow.setPosition(event.latLng)
    infowindow.open(map)
  })
  
}
</script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDWfzKm2hI-mFjdQdHqRzMDFc5svKXBwUg&callback=initMap">
</script> 
<?php

function intPart($floatNum) {
    return($floatNum<-0.0000001 ? ceil($floatNum-0.0000001) : floor($floatNum+0.0000001));
}

function hdate($day,$month,$year) {
    $julian = GregorianToJD($month, $day, $year);
    if ($julian >= 1937808 && $julian <= 536838867) {
        $date = cal_from_jd($julian, CAL_GREGORIAN);
        $d = $date['day'];
        $m = $date['month'] - 1;
        $y = $date['year'];
    
        $mPart = ($m-13)/12;
        $jd = intPart((1461*($y+4800+intPart($mPart)))/4)+
        intPart((367*($m-1-12*(intPart($mPart))))/12)-
        intPart((3*(intPart(($y+4900+intPart($mPart))/100)))/4)+$d-32075;
    
        $l = $jd-1948440+10632;
        $n = intPart(($l-1)/10631);
        $l = $l-10631*$n+354;
        $j = (intPart((10985-$l)/5316))*(intPart((50*$l)/17719))+(intPart($l/5670))*(intPart((43*$l)/15238));
        $l = $l-(intPart((30-$j)/15))*(intPart((17719*$j)/50))-(intPart($j/16))*(intPart((15238*$j)/43))+29;
    
        $m = intPart((24*$l)/709);
        $d = $l-intPart((709*$m)/24);
        $y = 30*$n+$j-30;
        $yj = $y+512;
        $h = ($julian+3)%5;

        if($julian<=1948439) $y–;
    
        return array(
            'day' => $date['day'],
            'month' => $date['month'],
            'year' => $date['year'],
            'dow' => $date['dow'],
            'hijriday' => $d,
            'hijrimonth' => $m, 
            'hijriyear' => $y,
            'javayear' => $yj,
            'javadow' => $h
        );
    } else {
        return false;
    }
}

$imonth = Array(
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
);

$amonth = Array(
    'Muharram',
    'Safar',
    'Rabi\'ul Awal',
    'Rabi\'ul Akhir',
    'Jumadil Awal',
    'Jumadil Akhir',
    'Rajab',
    'Sya\'ban',
    'Ramadhan',
    'Syawal',
    'Dzul Qa\'dah',
    'Dzul Hijjah'
);

$jmonth = Array(
    'Suro',
    'Sapar',
    'Mulud',
    'Ba\'da Mulud',
    'Jumadil Awal',
    'Jumadil Akhir',
    'Rejeb',
    'Ruwah',
    'Poso',
    'Syawal',
    'Dulkaidah',
    'Besar'
);

$aday = Array(
    'Al-Ahad',
    'Al-Itsnayna',
    'Ats-Tsalatsa',
    "Al-Arba'a",
    "Al-Hamis",
    "Al-Jum'a",
    "As-Sabt"
);

$iday = Array(
    'Minggu',
    'Senin',
    'Selasa',
    'Rabu',
    'Kamis',
    'Jumat',
    'Sabtu'
);

$jday = Array(
    'Pon',
    'Wage',
    'Kliwon',
    'Legi',
    'Pahing'
);

if (isset($_POST['submit'])) {
    
    $date1 = explode("-", $_POST['date1']);
    $date2 = explode("-", $_POST['date2']);

    $date1 = hdate($date1[2], $date1[1], $date1[0]);
    $date2 = hdate($date2[2], $date2[1], $date2[0]);

    $p1['hari'] = $iday[$date1['dow']];
    $p1['pasaran'] = $jday[$date1['javadow']];
    $p2['hari'] = $iday[$date2['dow']];
    $p2['pasaran'] = $jday[$date2['javadow']];

    $hari = [
        'Minggu' => 5,
        'Senin' => 4,
        'Selasa' => 3,
        'Rabu' => 7,
        'Kamis' => 8,
        'Jumat' => 6,
        'Sabtu' => 9
    ];

    $neptu = [
        'Legi' => 5,
        'Pahing' => 9,
        'Pon' => 7,
        'Wage' => 4,
        'Kliwon' => 8,
    ];

    foreach ($hari as $key => $value) {
        if ($p1['hari'] == $key) {
            $p1['hari_val'] = $value;
            break;
        }
    }

    foreach ($neptu as $key => $value) {
        if ($p1['pasaran'] == $key) {
            $p1['pasaran_val'] = $value;
            break;
        }
    }

    foreach ($hari as $key => $value) {
        if ($p2['hari'] == $key) {
            $p2['hari_val'] = $value;
            break;
        }
    }

    foreach ($neptu as $key => $value) {
        if ($p2['pasaran'] == $key) {
            $p2['pasaran_val'] = $value;
            break;
        }
    }

    $jumlahWeton = [
        1 => 'Pegat',
        2 => 'Ratu',
        3 => 'Jodoh',
        4 => 'Topo',
        5 => 'Tinari',
        6 => 'Padu',
        7 => 'Sujanan',
        8 => 'Pesthi',
        9 => 'Pegat'
    ];

    $i = 1;
    $c = 1;

    $weton = "";

    while ($i <= 36) {
        if (in_array($i, array(10, 19, 28)) and $i <= 36) {
            $c = 1;
        }
        if ($p1['hari_val']+$p1['pasaran_val']+$p2['hari_val']+$p2['pasaran_val'] == $i) {
            $weton = $jumlahWeton[$c];
            break;
        } else {
            $i++;
            $c++;
        }
    }

}

?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Font GoogleApis -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">

    <title>Weton</title>

    <style>
        * { 
            font-family: 'Quicksand', sans-serif;
            font-size: 15px;
        }

        html {
            position: relative;
            min-height: 100%;
        }

        body {
            margin-bottom: 60px;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 60px;
            line-height: 60px;
            background-color: #f5f5f5;
            text-align: center;
        }

        .container-fluid {
            /* width: 500px; */
            width: auto;
            max-width: 680px;
            padding: 0 15px;
        }

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <span class="navbar-brand">Hitung Weton</span>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="">Index <span class="sr-only">(current)</span></a>
                    </li>
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="x" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">x
                        </a>
                        <div class="dropdown-menu" aria-labelledby="x">
                            <a class="dropdown-item" href=""></a>
                        </div>
                    </li> -->
                </ul>
            </div>
    </nav>

    <div class="container-fluid">
        <div class="row mt-2">
            <div class="col justify-content-center">

                <?php if(isset($_POST['submit']) and !empty(isset(($weton)))){ ?>
                    <?php

                    switch ($weton) {
                        case 'Pegat':
                            $x = "Pegat / Pegatan";
                            $y = "Dalam bahasa jawa berarti bercerai. Pasangan ini kemungkinan akan sering menghadapi masalah dikemudian hari. Masalah itu bisa dari masalah ekonomi, perselingkuhan, kekuasaan yang bisa menyebabkan perceraian.";
                            $z = "alert-danger";
                            break;
                        case 'Ratu':
                            $x = "Ratu";
                            $y = "Identik dengan sosok yang dihormati. Pasangan ini bisa dikatakan sudah cocok dan berjodoh. Sangat dihargai dan disegani oleh tetangga maupun lingkungan sekitar. Bahkan tak sedikit orang sekitar yang iri dengan keharmonisannya dalam membina rumah tangga.";
                            $z = "alert-success";
                            break;
                        case 'Jodoh':
                            $x = "Jodoh";
                            $y = "Pasangan ini memang ditakdirkan berjodoh. Mereka bisa saling menerima segala kekurangan maupun kelebihan masing2. Nasib rumah tangga dapat harmonis sampai tua.";
                            $z = "alert-success";
                            break;
                        case 'Topo':
                            $x = "Topo";
                            $y = "Dalam bahasa jawa bisa diartikan bertirakat. Pasangan ini akan sering mengalami kesusahan di awal-awal membina rumah tangga, namun pada akhirnya akan bahagia. Persoalan rumah tangga bisa dari ekonomi dan lain sebagainya. Tapi setelah mempunyai anak dan cukup lama berumah tangga, hidupnya akan sukses serta bahagia.";
                            $z = "alert-warning";
                            break;
                        case 'Tinari':
                            $x = "Tinari";
                            $y = "Pasangan ini akan mendapatkan kebahagiaan. Kemudahan dalam mencari rezeki dan tidak akan hidup berkekurangan. Hidupnya juga diliputi keberuntungan.";
                            $z = "alert-success";
                            break;
                        case 'Padu':
                            $x = "Padu";
                            $y = "Padu dalam bahasa jawa berarti cekcok atau pertengkaran. Rumah tangga pasangan ini akan sering mengalami pertikaian atau pertengkaran. Meski sering terjadi pertengkaran, nasib rumah tangga tidak sampai bercerai. Pertengkaran ini bahkan dipicu dari hal-hal yang bersifat sepele.";
                            $z = "alert-danger";
                            break;
                        case 'Sujanan':
                            $x = "Sujanan";
                            $y = "Rumah tangga ini akan sering mengalami percekcokan & masalah perselingkuhan.";
                            $z = "alert-danger";
                            break;
                        case 'Pesthi':
                            $x = "Pesthi";
                            $y = "Rumah tangga akan berjalan dgn sgt harmonis, rukun, adem, ayem, tenteram & sejahtera sampai tua. Bisa dikatakan jika ada sedikit masalah namun tidak megganggu keharmonisan.";
                            $z = "alert-success";
                            break;
                    }

                    ?>
                <div class="alert <?php echo $z ?> alert-dismissible fade show" role="alert">
                    <strong><?php echo $x ?></strong>. <?php echo $y ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php } else { ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong><i class="fa fa-exclamation-circle fa-md"></i> Baca!</strong> Pemahaman ini tidak bisa dijadikan patokan utama dalam kehidupan sehari-hari. Tergantung dari sisi kepercayaan masing-masing.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php } ?>

                <div class="card">
                    <div class="card-body">
                        <form action="" method="post" autocomplete="off">
                            <div class="form-group">
                                <label><i class="fa fa-user-circle fa-md"></i> Orang Pertama</label>
                                <input type="date" name="date1" class="form-control" required>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label><i class="fa fa-user-circle fa-md"></i> Orang Kedua</label>
                                <input type="date" name="date2" class="form-control" required>
                            </div>
                            <hr>
                            <button type="submit" name="submit" class="btn btn-primary btn-block"><i class="fa fa-paper-plane"></i> Hitung</button>
                        </form>
                    </div>
                    <div class="card-footer">
                        <small><i>Source: <a href="https://borobudurnews.com/tradisi-weton-jawa-untuk-cek-pasangan-dan-rejekimu-begini-caranya/">borobudurnews.com</a></i></small>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <footer class="footer">
        <div class="container">
          <span class="text-muted">Made with <span style="color: #e25555;">&#9829;</span> <a href="https://naufalist.com" target="_blank" title="Naufalist">naufalist</a></span>
        </div>
    </footer>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>

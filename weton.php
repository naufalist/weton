<?php

function clear(){    
    return chr(27).chr(91).'H'.chr(27).chr(91).'J';
}

function konversi($date) {
    if (preg_match("/^[0-9]{2} [0-9]{2} [0-9]{4}$/", $date)) {
        $imonth = Array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
        $amonth = Array('Muharram','Safar','Rabi\'ul Awal','Rabi\'ul Akhir','Jumadil Awal','Jumadil Akhir','Rajab','Sya\'ban','Ramadhan','Syawal','Dzul Qa\'dah','Dzul Hijjah');
        $jmonth = Array('Suro','Sapar','Mulud','Ba\'da Mulud','Jumadil Awal','Jumadil Akhir','Rejeb','Ruwah','Poso','Syawal','Dulkaidah','Besar');
        $aday = Array('Al-Ahad','Al-Itsnayna','Ats-Tsalatsa',"Al-Arba'a","Al-Hamis","Al-Jum'a","As-Sabt");
        $iday = Array('Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu');
        $jday = Array('Pon','Wage','Kliwon','Legi','Pahing');
        $hari = ['Minggu' => 5,'Senin' => 4,'Selasa' => 3,'Rabu' => 7,'Kamis' => 8,'Jumat' => 6,'Sabtu' => 9];
        $pasaran = ['Legi' => 5,'Pahing' => 9,'Pon' => 7,'Wage' => 4,'Kliwon' => 8];

        $date = explode(" ", $date);
        $date = hdate($date[0], $date[1], $date[2]);

        $r['hari'] = $iday[$date['dow']];
        $r['pasaran'] = $jday[$date['javadow']];

        foreach ($hari as $key => $val) {
            if ($r['hari'] == $key) {
                $r['hari_val'] = $val;
                break;
            }
        }

        foreach ($pasaran as $key => $val) {
            if ($r['pasaran'] == $key) {
                $r['pasaran_val'] = $val;
                break;
            }
        }

        return $r;

    } else {
        echo "Inputan salah.\n";
        exit();
    };
}

function hitungWeton($satu, $dua){

    $jumlahWeton = [1 => 'Pegat',2 => 'Ratu',3 => 'Jodoh',4 => 'Topo',5 => 'Tinari',6 => 'Padu',7 => 'Sujanan',8 => 'Pesthi',9 => 'Pegat'];

    $i = 1; $c = 1;

    while ($i <= 36) {
        if (in_array($i, array(10, 19, 28)) and $i <= 36) {
            $c = 1;
        }
        if ($satu['hari_val']+$satu['pasaran_val']+$dua['hari_val']+$dua['pasaran_val'] == $i) {
            return $jumlahWeton[$c];
            break;
        } else {
            $i++; $c++;
        }
    }
}

function keterangan($weton){
    $keterangan = [
        "Pegat" => "Dalam bahasa jawa berarti bercerai. Pasangan ini kemungkinan akan sering menghadapi masalah dikemudian hari. Masalah itu bisa dari masalah ekonomi, perselingkuhan, kekuasaan yang bisa menyebabkan perceraian.",

        "Ratu" => "Identik dengan sosok yang dihormati. Pasangan ini bisa dikatakan sudah cocok dan berjodoh. Sangat dihargai dan disegani oleh tetangga maupun lingkungan sekitar. Bahkan tak sedikit orang sekitar yang iri dengan keharmonisannya dalam membina rumah tangga.",

        "Jodoh" => "Pasangan ini memang ditakdirkan berjodoh. Mereka bisa saling menerima segala kekurangan maupun kelebihan masing2. Nasib rumah tangga dapat harmonis sampai tua.",

        "Topo" => "Dalam bahasa jawa bisa diartikan bertirakat. Pasangan ini akan sering mengalami kesusahan di awal-awal membina rumah tangga, namun pada akhirnya akan bahagia. Persoalan rumah tangga bisa dari ekonomi dan lain sebagainya. Tapi setelah mempunyai anak dan cukup lama berumah tangga, hidupnya akan sukses serta bahagia.",
        
        "Tinari" => "Pasangan ini akan mendapatkan kebahagiaan. Kemudahan dalam mencari rezeki dan tidak akan hidup berkekurangan. Hidupnya juga diliputi keberuntungan.",
        
        "Padu" => "Padu dalam bahasa jawa berarti cekcok atau pertengkaran. Rumah tangga pasangan ini akan sering mengalami pertikaian atau pertengkaran. Meski sering terjadi pertengkaran, nasib rumah tangga tidak sampai bercerai. Pertengkaran ini bahkan dipicu dari hal-hal yang bersifat sepele.",
        
        "Sujanan" => "Rumah tangga ini akan sering mengalami percekcokan & masalah perselingkuhan.",

        "Pesthi" => "Rumah tangga akan berjalan dgn sgt harmonis, rukun, adem, ayem, tenteram & sejahtera sampai tua. Bisa dikatakan jika ada sedikit masalah namun tidak megganggu keharmonisan.",
    ];

    return $keterangan[$weton];
}

function intPart($floatNum) {
    return ($floatNum<-0.0000001 ? ceil($floatNum-0.0000001) : floor($floatNum+0.0000001));
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
    }
}

echo clear();
echo "Masukkan tanggal lahir. Contoh: 17 08 1945"."\n";
echo "----------\n";
echo "Orang ke-1: ";
$date1 = konversi(trim(fgets(STDIN)));
echo "Orang ke-1: ";
$date2 = konversi(trim(fgets(STDIN)));
echo "----------\n";
echo "Orang ke-1: ".$date1['hari']." (".$date1['hari_val'].") + ".$date1['pasaran']." (".$date1['pasaran_val'].") = ".($date1['hari_val']+$date1['pasaran_val'])."\n";
echo "Orang ke-2: ".$date2['hari']." (".$date2['hari_val'].") + ".$date2['pasaran']." (".$date2['pasaran_val'].") = ".($date2['hari_val']+$date2['pasaran_val'])."\n";
echo "----------\n";
echo "Hasil     : ".hitungWeton($date1, $date2)." (".($date1['hari_val']+$date1['pasaran_val']+$date2['hari_val']+$date2['pasaran_val']).")\n";
echo "----------\n";
echo "Keterangan: ".keterangan(hitungWeton($date1, $date2))."\n";
exit();

?>

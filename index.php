<?php


require 'fpdf/fpdf.php';

class PDF extends FPDF
{

    public $data_header,$body_data,$footer_data;

    function getWeekday($date) {

        switch(date('w', strtotime($date))){

            case 0:
                return "Dimanche";

            case 1:
                return "Lundi";
                
            case 2:
                return "Mardi";
            
            case 3:
                return "Mercredi";
            
            case 4:
                return "Jeudi";
            
            case 5:
                return "Vendredi";
            
            case 6:
                return "Samedi";

        };

    }

    function getMonthOfYear($month_number){
       

        switch($month_number){
    
            case 1:
                return  "Janvier";
            case 2:
                return  "Février";
            case 3:
                return  "Mars";
            case 4 :
                return  "Avril";
            case 5 :
                return  "Mai";
            case 6 :
                return  "Juin";
            case 7 :
                return  "Juillet";
            case 8 :
                return  "Août";
            case 9 :
                return  "Septembre";
            case 10:
                return  "Octobre";
            case 11 :
                return  "Novembre";
            case 12 :
                return  "Décembre";
        
        };
    
    }

    


// +---------------- Header Of The Page ---------------+

function Header()
{

    $this->SetFont('Arial','B',13);
    $w_org = $this->GetStringWidth($this->data_header['org']);
    $w_em = $this->GetStringWidth($this->data_header['email']);
    $w_tele = $this->GetStringWidth($this->data_header['tele']);

    $this->Cell($w_org,15,$this->data_header['org'],0,0,'C');
    $this->Image($this->data_header['logo'],150,13,60);
    $this->SetFont('Arial','',8);
    $this->Ln(6);

    $this->SetY(20);
    $this->Cell($w_em,5,"Email : ".$this->data_header['email'],0,1,'');
    $this->SetY(24);
    $this->Cell($w_tele,5,"Tel : ".$this->data_header['tele'],0,1,'');

    $this->Ln(15);

    $this->SetFont('Arial','B',16);
    $this->Cell(0,5,utf8_decode("Feuille d'émargement".$this->data_header["mois"]),0,0,'C');
    $this->Ln(7);

    $this->SetFont('Arial','B',11);
    $this->cMargin = 3;
    $this->Cell(0,10,"Nom du stagiaire : ".$this->data_header['nom_stagi'],'LTR',0,'');
    $this->Ln(4);

    $this->SetFont('Arial','B',8);
    $this->Cell(0,10,"Nom de la formation : ".$this->data_header['nom_form'],'LR',0,'');
    $this->Ln(4);

    $this->SetFont('Arial','',8);

    $date_start = explode("-",$this->data_header['date_debut']);
    $day_start = $date_start[0];

    $month_start = $this->getMonthOfYear($date_start[1]);
    $year_start = $date_start[2];

    $date_end = explode("-",$this->data_header['date_fin']);
    $day_end = $date_end[0];
    $month_end = $this->getMonthOfYear($date_end[1]);
    $year_end = $date_end[2];

    $this->Cell(0,10,"Date de la formation : du ".$day_start." ".utf8_decode($month_start)." ".$year_start." au ".$day_end." ".utf8_decode($month_end)." ".$year_end,'LR',0,'');
    $this->Ln(4);
    $this->Cell(0,10,utf8_decode("Durée : ").$this->data_header['duree_form']." heures",'LR',0,'');
    $this->SetFont('Arial','B',8);
    $this->Ln(4);
    $this->Cell(0,10,"Prestataire de la formation : ".$this->data_header['org'],'LR',0,'');
    $this->Ln(4);
    $this->Cell(0,10,"Lieu de la formation : ".$this->data_header['address']." ".$this->data_header['ville'],'LR',0,'');
    $this->Ln(4);
    $this->Cell(0,10," ",'LRB',0,'');

    $this->Sety(91);

    $w = array(13, 43.05, 25, 54,0);
    $head = array(utf8_decode("N°"),"Date",utf8_decode("Durée"),"Signature stagiaire","Signature formateur");
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('Arial','B',9);

    for($i=0;$i<5;$i++){
        $this->Cell($w[$i],10,$head[$i],"LRBT",0,'C',true);
    }

    $this->Ln();

}



// +-------------- Body of The Page ---------------+

function bodyy($with_month){


    $w = array(13, 43, 25, 54,0);
    
    // show Data 
    
    if($with_month == false){
    $i = 0;
    $p=1;

    $this->SetTextColor(0);
    $this->SetFont('');
    foreach($this->body_data as $row){
        $i++;

        $date_start = explode("-",$row[0]);
        $day_start = $date_start[0];
        $month_start = $date_start[1];
        $year_start = $date_start[2];

        $this->Cell($w[0],15,$i,'LRB',0,"C");

        $current_y = $this->GetY();
        $current_x = $this->GetX();

        $this->cMargin = 1.5;

        $txt_day = $this->getWeekday($row[0]);
        $rest_day = $day_start." ".utf8_decode($this->getMonthOfYear($month_start))." ".$year_start;
        $x = $this->GetX();
        $this->Cell($w[1],11,$txt_day,'',"");
        $this->SetX($x);
        $this->Cell($w[1],20,$rest_day,'',"");
        $this->SetX($x);
        $this->Cell($w[1],15,"",'B',0,'L','');

        $this->SetXY($current_x + $w[1], $current_y);

        
        $this->Cell($w[2],15,$row[1]."h",'LRB',0,"C");
        $this->Cell($w[3],15,"",'LRB',0,"C");
        $this->Cell($w[4],15,"",'LRB',0,"C");
        $this->Ln();
        $p++;
        
    }
    
    }

    if($with_month == true){
        $i = 0;
        $p=0;
    
        $this->SetTextColor(0);
        $this->SetFont('');
        $first_row = explode("-",$this->data_header['date_debut']);
        $month_verif = $first_row[1];

        foreach($this->body_data as $row){
            
            $date_start = explode("-",$row[0]);
            $day_start = $date_start[0];
            $month_start = $date_start[1];
            $year_start = $date_start[2];

            if($month_start == $month_verif){
                $i++;

                if($p>=10){
                    $this->AddPage();
                    $p = 0;
                }
                
                $this->data_header['mois'] = " - ".ucfirst($this->getMonthOfYear($month_start))." ".$year_start;   
                
                $this->Cell($w[0],15,$i,'LRB',0,"C");

                $current_y = $this->GetY();
                $current_x = $this->GetX();

                $this->cMargin = 1.5;

                $txt_day = $this->getWeekday($row[0]);
                $rest_day = $day_start." ".utf8_decode($this->getMonthOfYear($month_start))." ".$year_start;
                $x = $this->GetX();
                $this->Cell($w[1],11,$txt_day,'',"");
                $this->SetX($x);
                $this->Cell($w[1],20,$rest_day,'',"");
                $this->SetX($x);
                $this->Cell($w[1],15,"",'B',0,'L','');
        
                $this->SetXY($current_x + $w[1], $current_y);
        

                $this->Cell($w[2],15,$row[1]."h",'LRB',0,"C");
                $this->Cell($w[3],15,"",'LRB',0,"C");
                $this->Cell($w[4],15,"",'LRB',0,"C");
                $this->Ln();
                
                $p++;
                
        }else{
            
            $month_verif = $month_start;
            $this->data_header['mois'] = " - ".ucfirst($this->getMonthOfYear($month_start))." ".$year_start;   
            $i++;
            $p = 0;
            $this->AddPage();
            $this->Cell($w[0],15,$i,'LRB',0,"C");
            $current_y = $this->GetY();
            $current_x = $this->GetX();

            $this->cMargin = 1.5;
            $txt_day = $this->getWeekday($row[0]);
            $rest_day = $day_start." ".utf8_decode($this->getMonthOfYear($month_start))." ".$year_start;
            $x = $this->GetX();
            $this->Cell($w[1],11,$txt_day,'',"");
            $this->SetX($x);
            $this->Cell($w[1],20,$rest_day,'',"");
            $this->SetX($x);
            $this->Cell($w[1],15,"",'B',0,'L','');

            $this->SetXY($current_x + $w[1], $current_y);


            $this->Cell($w[2],15,$row[1]."h",'LRB',0,"C");
            $this->Cell($w[3],15,"",'LRB',0,"C");
            $this->Cell($w[4],15,"",'LRB',0,"C");
            $this->Ln();
            $this->data_header['date_signature'] =  $row[0];
        } 
        }
    
        }


}

// +---------------- Footer Of the Page --------------+

function Footer()
{
    
    $date_sig = explode("-",$this->data_header['date_signature']);
    $day_sig = $date_sig[0];
    $month_sig = $date_sig[1];
    $year_sig = $date_sig[2];


    $this->SetFont('Arial','',12);
    $w_org = $this->GetStringWidth("Signature et tampon de l'organisme");
    $fait = utf8_decode(" Fait à ").$this->data_header['ville'].", le ".$day_sig." ".utf8_decode($this->getMonthOfYear($month_sig))." ".$year_sig;
    $w_fait = $this->GetStringWidth($fait);
    $this->Cell($w_org,15,"Signature et tampon de l'organisme",0,0,'L');
    $this->Ln(5);
    $this->SetFont('Arial','',9);
    $this->Cell($w_fait,15,$fait,0,0,'L');
    $this->Ln(0);
    $this->Image($this->footer_data['cachet'],74,null,70);
    $this->SetY(-10);
    $this->SetFont('Arial','B',8);
    $this->Cell(0,10,'PAGE '.$this->PageNo().'/{nb}',0,0,'R');
    
}


}


// +-------------- Generate PDF Function --------------------+


function generate_pdf($header,$data,$footer){

    $pdf = new PDF('P','mm','A4');
    $pdf->AliasNbPages();
    
    $pdf->data_header['org'] = $header[0];
    $pdf->data_header['logo'] = $header[1];
    $pdf->data_header['email'] = $header[2];
    $pdf->data_header['tele'] = $header[3];
    $pdf->data_header['nom_stagi'] = $header[4];
    $pdf->data_header['nom_form'] = $header[5];
    $pdf->data_header['date_debut'] = $header[6];
    $pdf->data_header['date_fin'] = $header[7];
    $pdf->data_header['address'] = $header[8];
    $pdf->data_header['ville'] = $header[9];
    $pdf->data_header['date_signature'] = $header[6];

    $s = 0;
    foreach($data as $row){
        $s+=$row[1];
    }

    $date_start = explode("-",$pdf->data_header['date_debut']);
    $month_start = $date_start[1];
    $year_start = $date_start[2];

    $pdf->data_header['duree_form'] = $s;   
    $pdf->data_header['mois'] = " - ".ucfirst($pdf->getMonthOfYear($month_start))." ".$year_start;   
    $pdf->body_data = $data;
    $pdf->footer_data["cachet"] = $footer[0];
    
    $pdf->AddPage();


    $date_end = explode("-",$pdf->data_header['date_fin']);

    $month_end = $date_end[1];

    if(count($data) >10 && $month_start == $month_end){
        $with_month = false;
        $pdf->bodyy($with_month);
    }

    if(count($data) <=10){
        $with_month = false;
        $pdf->bodyy($with_month);
    }

    if(count($data)>10 && $month_start != $month_end){
        $with_month = true;
        
        $pdf->bodyy($with_month);
    }


    
    $pdf->Output();


}
        // +----------------------- EnD Class PDF ----------------------------+




// +---------------------------- Data that will be uploaded from DataBase  ---------------------------+
// +-------------------- !! The data from the database must be arranged by date !! -------------------+
    


        $org = 'STUDIO CAPITALE ENSEIGNEMENT';
        $email = 'info.studcap@gmail.com';
        $tel = '0782 997 860';
        $logo = 'logo.PNG';
        $nom_stagi = 'Natacha MOGET';
        $nom_form = 'Technique de doublage';
        $date_debut = '01-01-2022';
        $date_fin = '08-04-2022';
        $address = '9 Rue Lakanal - 75015';
        $ville = 'PARIS';
                        
                        
        $header = [$org,$logo,$email,$tel,$nom_stagi,$nom_form,$date_debut,$date_fin,$address,$ville];
        $data = [
                    ["01-01-2022",2],
                    ["03-01-2022",2],
                    ["04-01-2022 ",2],
                    ["05-01-2022 ",2],
                    ["07-01-2022",2],
                    ["09-01-2022",2],
                    ["12-01-2022",2],
                    ["15-01-2022",2],
                    ["19-01-2022 ",2],
                    ["22-01-2022 ",2],
                    ["24-01-2022 ",2],
                    ["26-02-2022",2],
                    ["29-02-2022",2],
                    ["03-4-2022",2],
                    ["06-4-2022 ",2],
                    ["08-4-2022 ",2],
                    
                                
        ];
        $footer = ["Signature-FP.PNG"];


// +------------------------------------ Call Function To Genrate PDF File ----------------------------------------------+

    generate_pdf($header,$data,$footer);




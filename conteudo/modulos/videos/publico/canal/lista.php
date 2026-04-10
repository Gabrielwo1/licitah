<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/zpOULjyy-n8?rel=0" title="YouTube video" allowfullscreen></iframe>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="gradeInteligente d-flex flex-column gap-2">
                 <?
        $i = 0;
        while($i < 12){
            include __DIR__."/../componentes/card.php";
            $i++;
        }
        
        ?>
            </div>
           
        </div>
    </div>
</div>
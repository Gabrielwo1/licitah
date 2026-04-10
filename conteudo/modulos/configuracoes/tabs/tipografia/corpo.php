<div class="preview-fonte">
    <h6>Visualização da Fonte:</h6>
    <div id="previewFont" class="text-dark">
        <div class="w-100 bg-carregando" style="height: 30px;"></div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-xl-6 mt-3">
        <label class="form-label fs-14 fw-700 text-uppercase">Font Family</label>
        <select class="form-select" id="fontFamily"></select>
    </div>
    <div class="col-12 col-xl-6 mt-3">
         <label class="form-label fs-14 fw-700 text-uppercase">Peso e Estilo da Fonte</label>
         <select class="form-select" id="pesos"></select>
    </div>
      <div class="col-12 col-xl-4 mt-3">
        <label class="form-label fs-14 fw-700 text-uppercase">Sub Conjunto de Fonte</label>
        <select class="form-select" id="subs"></select>
    </div> 
    <div class="col-12 col-xl-4 mt-3">
         <label class="form-label fs-14 fw-700 text-uppercase">Alinhamento de Texto</label>
         <select class="form-select" id="alinhamento"
         <option value="none" selected="">Normal</option>
         <option value="left">Esquerda</option>
         <option value="right">Direita</option>
         <option value="center">Centro</option>
         <option value="justify">Justificado</option> 
        </select>
    </div>
      <div class="col-12 col-xl-4 mt-3">
        <label class="form-label fs-14 fw-700 text-uppercase">Transformação de Texto</label>
         <select class="form-select" id="transformacao"> 
         <option value="none" selected="">Normal</option>
         <option value="uppercase">Primeras Letas Maiusculas</option>
         <option value="uppercase">Tudo Maisuculo</option>
         <option value="lowercase">Tudo Minusculo</option> 
        </select>
    </div>
</div>
<?
foreach(["Mobile", "Tablet", "Pc", "WideScreen"] as $item){
    ?>
    <div class="d-flex justify-content-between align-items-center gap-3 mt-4">
    <h2 class="fs-18 fw-700 m-0 text-uppercase"><?=$item?></h2>
    <div class="bg-danger w-100" style="height: 2px"></div>
</div>
<div class="row">
    <div class="col-12 col-xl-3 mt-3">
        <label class="form-label fs-14 fw-700 text-uppercase">Tamanho da Fonte</label>
        <input class="form-control sizers" data-dispositivo="<?=$item?>" data-prop="font-size">
    </div>
    <div class="col-12 col-xl-3 mt-3">
         <label class="form-label fs-14 fw-700 text-uppercase">Altura da Linha</label>
       <input class="form-control sizers" data-dispositivo="<?=$item?>" data-prop="altura-linha">
    </div>
      <div class="col-12 col-xl-3 mt-3">
        <label class="form-label fs-14 fw-700 text-uppercase">Esp. de Palavra</label>
        <input class="form-control sizers" data-dispositivo="<?=$item?>" data-prop="espaco-palavra">
    </div>
    <div class="col-12 col-xl-3 mt-3">
         <label class="form-label fs-14 fw-700 text-uppercase">Esp. de Letras</label>
        <input class="form-control sizers" data-dispositivo="<?=$item?>"  data-prop="espaco-letra">
    </div>
 
</div>
    <?
}

?>
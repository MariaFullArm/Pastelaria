<?php

$loader = require __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Respect\Validation\Validator as v;

$app = new Silex\Application();
$conexao = mysqli_connect('localhost', 'root', '', 'pastelaria');

function createErrorMessage($msg){
    return ["erro" => $msg];
}

//PRODUTO
$app->get('/produto', function () use ($conexao){

   $merc = array();
   $resultado  = mysqli_query($conexao,"select * from produto ");

   while ($produto = mysqli_fetch_assoc($resultado)){
       array_push($merc, $produto);
   }

  if ($resultado){
       return new JsonResponse($merc,200);
  }

   return new JsonResponse(createErrorMessage("Não há produto cadastrado!"), 400);
});


$app->post('/produto', function (\Symfony\Component\HttpFoundation\Request $request) use ($conexao){

   $produto = json_decode($request->getContent(),true);


   //Valida se json valido
   if($produto == NULL){
       return new JsonResponse(createErrorMessage ("Erro"), 400);
   };

   //Valida Nome do produto
   if (is_string($produto['nome'])) {
       $produto['nome'] = trim($produto['nome']);
   }

   if(empty($produto['nome'])){
       return new JsonResponse(createErrorMessage("Nome não pode ser branco!"), 400);
   }

   //Valida o valor do produto
   if(filter_var($produto['valor'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION) <= 0 ){
       return new JsonResponse(createErrorMessage("O valor não pode ser null!"), 400);
   }

   //Valida o tipo
   if (is_string($produto['tipo'])) {
       $produto['tipo'] = trim($produto['tipo']);
   }

   if(empty($produto['tipo'])){
       return new JsonResponse(createErrorMessage("Tipo não pode ser branco!"), 400);
   }

   //Salva no banco
   $nome      = $produto['nome'];
   $valor     = $produto['valor'];
   $tipo      = $produto['tipo'];
   $descricao = $produto['descricao'];

    $query = "select nome from produto where nome = '{$nome}'";
    $resultado = mysqli_query($conexao,$query);

   if ($resultado->num_rows > 0){

       return new JsonResponse(createErrorMessage("Não foi possivel realizar o cadastro!"), 400);

   }
else {

    $inserindo = "Insert into  produto (nome,valor,tipo,descricao) values ('{$nome}',{$valor},'{$tipo}','{$descricao}')";
    $result = mysqli_query($conexao,$inserindo);

    return new JsonResponse($produto, 200);

}
});

$app->put('/produto/{id}', function(Request $request,$id) use ($conexao){

    $produto = json_decode($request->getContent(), true);

    if($produto == NULL){
        return new JsonResponse(createErrorMessage("Erro"), 400);
    };

    if (is_string($produto['nome'])) {
        $produto['nome'] = trim($produto['nome']);
    }

    if(empty($produto['nome'])){
        return new JsonResponse(createErrorMessage("Nome não pode ser branco!"), 400);
    }

    if(filter_var($produto['valor'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION) <= 0 ){
        return new JsonResponse(createErrorMessage("O valor não pode ser null!"), 400);
    }

    if (is_string($produto['tipo'])) {
        $produto['tipo'] = trim($produto['tipo']);
    }

    if(empty($produto['tipo'])){
        return new JsonResponse(createErrorMessage("Tipo não pode ser branco!"), 400);
    }

    $nome      = $produto['nome'];
    $valor     = $produto['valor'];
    $tipo      = $produto['tipo'];
    $descricao = $produto['descricao'];

    $query = "select id from produto where id = {$id}";
    $resultado = mysqli_query($conexao,$query);

    if ($resultado->num_rows == 0){

        return new JsonResponse(createErrorMessage("Não foi possível alterar o produto!"), 400);
    }
    else {
        $alterando = "update produto set nome = '{$nome}', valor = {$valor}, descricao = '{$descricao}', tipo = '{$tipo}' where id = {$id}";
        $result = mysqli_query($conexao, $alterando);

        return new JsonResponse($produto, 200);
    }
})->assert('id','\d+');


$app->delete('/produto/{id}', function ($id) use ($conexao){

    $resultado = mysqli_query($conexao,"select id from produto where id = {$id}");

    if($resultado->num_rows == 0) {
        return new JsonResponse(createErrorMessage("Produto com id {$id} não encontrado para exclusão!"), 404);
    }
    else {
        $query = "delete from produto where id = {$id}";
        $result = mysqli_query($conexao, $query);

        return new JsonResponse(["aviso" => "Produto excluído com sucesso!"], 200);
    }

})->assert('id', '\d+');




//VENDEDOR
$app->get('/vendedor', function () use ($conexao){

    $func = array();
    $resultado  = mysqli_query($conexao,"select * from vendedor ");

    while ($vendedor = mysqli_fetch_assoc($resultado)){
        array_push($func, $vendedor);
    }

    if ($resultado){
        return new JsonResponse($func,200);
    }

    return new JsonResponse(createErrorMessage("Não há vendedor cadastrado!"), 400);
});

$app->post('/vendedor', function (\Symfony\Component\HttpFoundation\Request $request) use ($conexao){

    $vendedor = json_decode($request->getContent(),true);

    if($vendedor == NULL){
        return new JsonResponse(createErrorMessage("Erro"), 400);
    };

    if (is_string($vendedor['nome'])) {
        $vendedor['nome'] = trim($vendedor['nome']);
    }

    if(empty($vendedor['nome'])){
        return new JsonResponse(createErrorMessage("Nome não pode ser branco!"), 400);
    }

    $cpfValidacao = v::cpf()->notEmpty()->validate($vendedor['cpf']);

    if (!$cpfValidacao){
        return new JsonResponse(createErrorMessage("CPF Invalido"), 400);
    }

    $nome      = $vendedor['nome'];
    $cpf       = $vendedor['cpf'];

    $query = "select cpf from vendedor where cpf='{$cpf}')";
    $resultado = mysqli_query($conexao,$query);

    if ($resultado->num_rows > 0){
        return new JsonResponse(createErrorMessage("Não foi possivel realizar o cadastro!"), 400);
    }
    $inserindo = "Insert into  vendedor (nome,cpf) values ('{$nome}','{$cpf}')";
    $result = mysqli_query($conexao,$inserindo);

    return new JsonResponse($vendedor,200);

});

$app->put('/vendedor/{id}', function(Request $request,$id) use ($conexao){

    $vendedor = json_decode($request->getContent(), true);

    if($vendedor == NULL){
        return new JsonResponse(createErrorMessage("Erro"), 400);
    };

    if (is_string($vendedor['nome'])) {
        $vendedor['nome'] = trim($vendedor['nome']);
    }

    if(empty($vendedor['nome'])){
        return new JsonResponse(createErrorMessage("Nome não pode ser branco!"), 400);
    }

    $cpfValidacao = v::cpf()->notEmpty()->validate($vendedor['cpf']);

    if (!$cpfValidacao){
        return new JsonResponse(createErrorMessage("CPF Inválido ou nulo!"), 400);
    }

    $nome      = $vendedor['nome'];
    $cpf       = $vendedor['cpf'];

    $query = "select id from vendedor where id = {$id}";
    $resultado  = mysqli_query($conexao,$query);

    if ($resultado->num_rows == 0){
        return new JsonResponse(createErrorMessage("Não foi possível alterar o vendedor!"), 400);
    }

    $alterando = "update vendedor set nome = '{$nome}', cpf = '{$cpf}' where id = {$id}";
    $result  = mysqli_query($conexao,$alterando);

    return new JsonResponse($vendedor,200);

})->assert('id','\d+');


$app->delete('/vendedor/{id}', function ($id) use ($conexao){

    $resultado = mysqli_query($conexao,"select id from vendedor where id = {$id}");

    if($resultado->num_rows == 0) {
        return new JsonResponse(createErrorMessage("Vendedor com id {$id} não encontrado para exclusão!"), 404);
    }
    $result = mysqli_query($conexao,"delete from vendedor where id = {$id}");

    return new JsonResponse(["aviso" => "Vendedor excluído com sucesso!"], 200);

})->assert('id', '\d+');




//VENDA
$app->get('/venda', function () use ($conexao){

    $ven = array();
    $resultado  = mysqli_query($conexao,"select * from venda");

    while ($venda = mysqli_fetch_assoc($resultado)){
        array_push($ven, $venda);
    }

    if ($resultado){
        return new JsonResponse($ven,200);
    }
    else {

        return new JsonResponse(createErrorMessage("Não há venda cadastrada!"), 400);
    }
});

$app->get('/venda/finalizada', function () use ($conexao){
    $ven = array();

    $resultado  = mysqli_query($conexao,"select * from venda where status = false");

    while ($venda = mysqli_fetch_assoc($resultado)){
        array_push($ven, $venda);
    }

    if ($resultado){
        return new JsonResponse($ven,200);
    }
    else {

    return new JsonResponse(createErrorMessage("Não há vendas finalizadas!"), 400);
}
});

$app->get('/venda/pendente', function () use ($conexao){
    $ven = array();

    $resultado  = mysqli_query($conexao,"select * from venda where status = true");

    while ($venda = mysqli_fetch_assoc($resultado)){
        array_push($ven, $venda);
    }

    if ($resultado){
        return new JsonResponse($ven,200);
    }
    else {

        return new JsonResponse(createErrorMessage("Não há vendas pendentes!"), 400);
    }
});

$app->post('/venda', function (\Symfony\Component\HttpFoundation\Request $request) use ($conexao){

    $venda = json_decode($request->getContent(),true);

    if($venda == NULL){
        return new JsonResponse(createErrorMessage("Erro"), 400);
    };

    if(filter_var($venda['total'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION) <= 0 ){
        return new JsonResponse(createErrorMessage("O total não pode ser null!"), 400);
    }

    if (is_bool($venda['status'])) {
        $venda['status'] = trim($venda['status']);
    }

    if(empty($venda['status'])){
        return new JsonResponse(createErrorMessage("Status não pode ser branco!"), 400);
    }

    if (is_int($venda['data_venda'])) {
        $venda['data_venda'] = trim($venda['data_venda']);
    }

    if(empty($venda['data_venda'])){
        return new JsonResponse(createErrorMessage("Data não pode ser branco!"), 400);
    }

    $id_vendedor = $venda['id_vendedor'];
    $total       = $venda['total'];
    $observacoes = $venda['observacoes'];
    $status      = $venda['status'];
    $data_venda  = $venda['data_venda'];


    $query = "Insert into venda (total,id_vendedor,observacoes,status,data_venda) values ({$total},{$id_vendedor}, '{$observacoes}',{$status},{$data_venda})";
    $resultado = mysqli_query($conexao,$query);

    if ($resultado){
        return new JsonResponse($venda,200);
    }

    return new JsonResponse(createErrorMessage("Não foi possivel realizar o cadastro!"), 400);

});

$app->put('/venda/{id}', function(Request $request,$id) use ($conexao){

    $venda = json_decode($request->getContent(), true);

    if($venda == NULL){
        return new JsonResponse(createErrorMessage("Erro"), 400);
    };

    if(filter_var($venda['total'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION) <= 0 ){
        return new JsonResponse(createErrorMessage("O total não pode ser null!"), 400);
    }

    if (is_int($venda['data_venda'])) {
        $venda['data_venda'] = trim($venda['data_venda']);
    }

    if(empty($venda['data_venda'])){
        return new JsonResponse(createErrorMessage("Data não pode ser branco!"), 400);
    }

    $id_vendedor = $venda['id_vendedor'];
    $total       = $venda['total'];
    $observacoes = $venda['observacoes'];
    $status      = $venda['status'];
    $data_venda  = $venda['data_venda'];

    $query = "select id from venda  where id = {$id}";
    $resultado  = mysqli_query($conexao,$query);

    if ($resultado->num_rows == 0){
        return new JsonResponse(createErrorMessage("Não foi possível alterar a venda!"), 400);
    }
    else {
        $alterando = "update venda set total = {$total},id_vendedor= {$id_vendedor}, observacoes = '{$observacoes}', status = {$status}, data_venda = {$data_venda}  where id = {$id}";
        $result = mysqli_query($conexao, $alterando);

        return new JsonResponse($venda, 200);
    }
})->assert('id','\d+');


$app->delete('/venda/{id}', function ($id) use ($conexao){

    $resultado = mysqli_query($conexao,"select id from venda where id = {$id}");

    if ($resultado->num_rows == 0){
        return new JsonResponse(createErrorMessage("Venda com id {$id} não encontrada para exclusão!"), 404);
    }
    else {
        $result = mysqli_query($conexao, "delete from venda where id = {$id}");

        return new JsonResponse(["aviso" => "Venda excluída com sucesso!"], 200);
    }
})->assert('id', '\d+');




//ITENS VENDIDOS
$app->get('/itensvendidos', function () use ($conexao){

    $itens = array();
    $resultado  = mysqli_query($conexao,"select * from itens_vendidos");

    while ($itensvendidos = mysqli_fetch_assoc($resultado)){
        array_push($itens, $itensvendidos);
    }

    if ($resultado){
        return new JsonResponse($itens,200);
    }

    return new JsonResponse(createErrorMessage("Não há itens vendidos cadastrados!"), 400);
});


$app->post('/itensvendidos', function (\Symfony\Component\HttpFoundation\Request $request) use ($conexao){

    $itensvendidos = json_decode($request->getContent(),true);

    if($itensvendidos == NULL){
        return new JsonResponse(createErrorMessage("Erro"), 400);
    };

    $id_produto  = $itensvendidos['id_produto'];
    $id_venda    = $itensvendidos['id_venda'];
    $quantidade  = $itensvendidos['quantidade'];


    $query = "Insert into itens_vendidos (id_venda,id_produto,quantidade) values ({$id_venda},{$id_produto},{$quantidade})";
    $resultado = mysqli_query($conexao,$query);

    if ($resultado){
        return new JsonResponse($itensvendidos,200);
    }

    return new JsonResponse(createErrorMessage("Não foi possivel realizar o cadastro!"), 400);

});

$app->put('/itensvendidos/{id}', function(Request $request,$id) use ($conexao){

    $itensvendidos = json_decode($request->getContent(), true);

    if($itensvendidos == NULL){
        return new JsonResponse(createErrorMessage("Erro"), 400);
    };

    $id_produto  = $itensvendidos['id_produto'];
    $id_venda    = $itensvendidos['id_venda'];
    $quantidade  = $itensvendidos['quantidade'];

    $query = "select id from itens_vendidos where id = {$id}";
    $resultado  = mysqli_query($conexao,$query);

    if ($resultado->num_rows == 0){
        return new JsonResponse(createErrorMessage("Não foi possível alterar o item vendido!"), 400);
    }
    else {
        $alterando = "update itens_vendidos set id_produto = {$id_produto},id_venda= {$id_venda}, quantidade = {$quantidade} where id = {$id}";
        $result = mysqli_query($conexao, $alterando);

        return new JsonResponse($itensvendidos, 200);
    }
})->assert('id','\d+');


$app->delete('/itensvendidos/{id}', function ($id) use ($conexao){

    $resultado = mysqli_query($conexao,"select id from itens_vendidos where id = {$id}");

    if ($resultado->num_rows == 0){
        return new JsonResponse(createErrorMessage("Item vendido com id {$id} não encontrado para exclusão!"), 404);
    }

    else {
        $result = mysqli_query($conexao, "delete from itens_vendidos where id = {$id}");

        return new JsonResponse(["aviso" => "Item vendido excluído com sucesso!"], 200);
    }
})->assert('id', '\d+');

$app->run();

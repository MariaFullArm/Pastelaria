<?php

$loader = require __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Respect\Validation\Validator as v;

$app = new Silex\Application();

$app->get('/produto', function (){

   $conexao = mysqli_connect('localhost', 'root', '', 'pastelaria');

   $merc = array();
   $resultado  = mysqli_query($conexao,"select * from produto ");

   while ($produto = mysqli_fetch_assoc($resultado)){
       array_push($merc, $produto);
   }

  if ($resultado){
       return new JsonResponse($merc,200);
  }

   return new Response("Não há produto cadastrado!", 400);
});


$app->post('/produto', function (\Symfony\Component\HttpFoundation\Request $request){

   $produto = json_decode($request->getContent(),true);


   //Valida se json valido
   if($produto == NULL){
       return new Response("Erro",400);
   };

   //Valida Nome do produto
   if (is_string($produto['nome'])) {
       $produto['nome'] = trim($produto['nome']);
   }

   if(empty($produto['nome'])){
       return new Response("Nome não pode ser branco!",400);
   }

   //Valida o valor do produto
   if(filter_var($produto['valor'], FILTER_VALIDATE_FLOAT) < 1 ){
       return new Response("O valor não pode ser null!", 400);
   }

   //Valida o tipo
   if (is_string($produto['tipo'])) {
       $produto['tipo'] = trim($produto['tipo']);
   }

   if(empty($produto['tipo'])){
       return new Response("Tipo não pode ser branco!", 400);
   }

   //Salva no banco
   $nome      = $produto['nome'];
   $valor     = $produto['valor'];
   $tipo      = $produto['tipo'];
   $descricao = $produto['descricao'];

   $conexao = mysqli_connect('localhost', 'root', '', 'pastelaria');

   $query = "Insert into  produto (nome,valor,tipo,descricao) values ('{$nome}',{$valor},'{$tipo}','{$descricao}')";
   $resultado = mysqli_query($conexao,$query);

   if (mysqli_num_rows($resultado) == 0){
       return new Response("Não foi possivel realizar o cadastro!", 400);
   }
else {
    return new JsonResponse($produto, 200);
}
});


$app->put('/produto/{id}', function(Request $request,$id){

    $conexao = mysqli_connect('localhost', 'root', '', 'pastelaria');
    $produto = json_decode($request->getContent(), true);

    if($produto == NULL){
        return new Response("Erro",400);
    };

    if (is_string($produto['nome'])) {
        $produto['nome'] = trim($produto['nome']);
    }

    if(empty($produto['nome'])){
        return new Response("Nome não pode ser branco!",400);
    }

    if(filter_var($produto['valor'], FILTER_VALIDATE_FLOAT) < 1 ){
        return new Response("O valor não pode ser null!", 400);
    }

    if (is_string($produto['tipo'])) {
        $produto['tipo'] = trim($produto['tipo']);
    }

    if(empty($produto['tipo'])){
        return new Response("Tipo não pode ser branco!", 400);
    }

    $nome      = $produto['nome'];
    $valor     = $produto['valor'];
    $tipo      = $produto['tipo'];
    $descricao = $produto['descricao'];

    $query = "update produto set nome = '{$nome}', valor = {$valor}, descricao = '{$descricao}', tipo = '{$tipo}' where id = {$id}";

    $resultado  = mysqli_query($conexao,$query);

    if ($resultado){
        return new JsonResponse($produto,200);
    }

    return new Response("Não foi possível alterar o produto!", 400);
})->assert('id','\d+');


$app->delete('/produto/{id}', function ($id){
    $conexao = mysqli_connect('localhost', 'root', '', 'pastelaria');

    $resultado = mysqli_query($conexao,"delete from produto where id = {$id}");

    if($resultado->rowCount() < 1) {
        return new Response("Produto com id {$id} não encontrado para exclusão!", 404);
    }

    return new Response("Produto excluído com sucesso!", 200);

})->assert('id', '\d+');





$app->get('/vendedor', function (){

    $conexao = mysqli_connect('localhost', 'root', '', 'pastelaria');

    $func = array();
    $resultado  = mysqli_query($conexao,"select * from vendedor ");

    while ($vendedor = mysqli_fetch_assoc($resultado)){
        array_push($func, $vendedor);
    }

    if ($resultado){
        return new JsonResponse($func,200);
    }

    return new Response("Não há vendedor cadastrado!", 400);
});

$app->post('/vendedor', function (\Symfony\Component\HttpFoundation\Request $request){

    $vendedor = json_decode($request->getContent(),true);

    if($vendedor == NULL){
        return new JsonResponse("Erro",400);
    };

    if (is_string($vendedor['nome'])) {
        $vendedor['nome'] = trim($vendedor['nome']);
    }

    if(empty($vendedor['nome'])){
        return new JsonResponse("Nome não pode ser branco!",400);
    }

   /*if (v::cpf()->validate($vendedor['cpf'])){
        $vendedor['cpf'] = ($vendedor['cpf']);
    }

    if(empty($vendedor['cpf'])){
        return new Response("Cpf não pode ser branco!", 400);
    }*/

    $nome      = $vendedor['nome'];
    $cpf       = $vendedor['cpf'];

    $conexao = mysqli_connect('localhost', 'root', '', 'pastelaria');

    $query = "Insert into  vendedor (nome,cpf) values ('{$nome}','{$cpf}')";
    $resultado = mysqli_query($conexao,$query);

    if ($resultado){
        return new JsonResponse($vendedor,200);
    }

    return new JsonResponse("Não foi possivel realizar o cadastro!", 400);

});

$app->put('/vendedor/{id}', function(Request $request,$id){

    $conexao = mysqli_connect('localhost', 'root', '', 'pastelaria');
    $vendedor = json_decode($request->getContent(), true);

    if($vendedor == NULL){
        return new Response("Erro",400);
    };

    if (is_string($vendedor['nome'])) {
        $vendedor['nome'] = trim($vendedor['nome']);
    }

    if(empty($vendedor['nome'])){
        return new Response("Nome não pode ser branco!",400);
    }

    /*if (v::cpf()->validate($vendedor['cpf'])){
       $vendedor['cpf'] = ($vendedor['cpf']);
   }

   if(empty($vendedor['cpf'])){
       return new Response("Cpf não pode ser branco!", 400);
   }*/

    $nome      = $vendedor['nome'];
    $cpf       = $vendedor['cpf'];

    $query = "update vendedor set nome = '{$nome}', cpf = '{$cpf}' where id = {$id}";

    $resultado  = mysqli_query($conexao,$query);

    if ($resultado){
        return new JsonResponse($vendedor,200);
    }

    return new JsonResponse("Não foi possível alterar o vendedor!", 400);

})->assert('id','\d+');


$app->delete('/vendedor/{id}', function ($id){
    $conexao = mysqli_connect('localhost', 'root', '', 'pastelaria');

    $resultado = mysqli_query($conexao,"delete from vendedor where id = {$id}");

    $hello = "Não foi possivel excluir o vendedor!";
    if (mysqli_num_rows($resultado)<1){
        return new JsonResponse($hello, 400);
    }

    return new JsonResponse("Vendedor excluído com sucesso!",200);

})->assert('id', '\d+');




$app->get('/venda', function (){

    $conexao = mysqli_connect('localhost', 'root', '', 'pastelaria');

    $ven = array();
    $resultado  = mysqli_query($conexao,"select * from venda as v2 inner join vendedor as v on v2.id_vendedor = v.id");

    while ($venda = mysqli_fetch_assoc($resultado)){
        array_push($ven, $venda);
    }

    if ($resultado){
        return new JsonResponse($ven,200);
    }

    return new Response("Não há venda cadastrada!", 400);
});


$app->post('/venda', function (\Symfony\Component\HttpFoundation\Request $request){

    $venda = json_decode($request->getContent(),true);

    if($venda == NULL){
        return new Response("Erro",400);
    };

    if(filter_var($venda['total'], FILTER_VALIDATE_FLOAT) < 1 ){
        return new Response("O total não pode ser null!", 400);
    }

    if (is_int($venda['data_venda'])) {
        $venda['data_venda'] = trim($venda['data_venda']);
    }

    if(empty($venda['data_venda'])){
        return new Response("Data não pode ser branco!",400);
    }

    $id_vendedor = $venda['id_vendedor'];
    $total       = $venda['total'];
    $observacoes = $venda['observacoes'];
    $status      = $venda['status'];
    $data_venda  = $venda['data_venda'];

    $conexao = mysqli_connect('localhost', 'root', '', 'pastelaria');

    $query = "Insert into venda (total,id_vendedor,observacoes,status,data_venda) values ({$total},{$id_vendedor}, '{$observacoes}',{$status},{$data_venda}) SELECT nome from vendedor as v join venda v2 ON v2.id_vendedor = v.id";
    $resultado = mysqli_query($conexao,$query);



});

$app->put('/venda/{id}', function(Request $request,$id){

    $conexao = mysqli_connect('localhost', 'root', '', 'pastelaria');
    $venda = json_decode($request->getContent(), true);

    if($venda == NULL){
        return new Response("Erro",400);
    };

    if(filter_var($venda['total'], FILTER_VALIDATE_FLOAT) < 1 ){
        return new Response("O total não pode ser null!", 400);
    }

    if (is_int($venda['data_venda'])) {
        $venda['data_venda'] = trim($venda['data_venda']);
    }

    if(empty($venda['data_venda'])){
        return new Response("Data não pode ser branco!",400);
    }

    $id_vendedor = $venda['id_vendedor'];
    $total       = $venda['total'];
    $observacoes = $venda['observacoes'];
    $status      = $venda['status'];
    $data_venda  = $venda['data_venda'];

    $query = "update venda set total = {$total},id_vendedor= {$id_vendedor}, observacoes = '{$observacoes}', status = {$status}, data_venda = {$data_venda} SELECT nome from vendedor as v join venda v2 ON v2.id_vendedor = v.id";

    $resultado  = mysqli_query($conexao,$query);

    if ($resultado){
        return new JsonResponse($venda,200);
    }

    return new Response("Não foi possível alterar a venda!", 400);

})->assert('id','\d+');


$app->delete('/venda/{id}', function ($id){
    $conexao = mysqli_connect('localhost', 'root', '', 'pastelaria');

    $resultado = mysqli_query($conexao,"delete from venda where id = {$id}");

    if (mysqli_num_rows($resultado)>0){
        return new Response("Não foi possivel excluir a venda!", 400);
    }
    return new Response("Venda apagada com sucesso!",200);

})->assert('id', '\d+');







$app->get('/itensvendidos', function (){

    $conexao = mysqli_connect('localhost', 'root', '', 'pastelaria');

    $itens = array();
    $resultado  = mysqli_query($conexao,"select * from itens_vendidos as i inner join produto as p on i.produto = p.id");

    while ($itensvendidos = mysqli_fetch_assoc($resultado)){
        array_push($itens, $itensvendidos);
    }

    if ($resultado){
        return new JsonResponse($itens,200);
    }

    return new Response("Não há itens vendidos cadastrados!", 400);
});


$app->post('/itensvendidos', function (\Symfony\Component\HttpFoundation\Request $request){

    $itensvendidos = json_decode($request->getContent(),true);

    if($itensvendidos == NULL){
        return new Response("Erro",400);
    };

    $id_produto  = $itensvendidos['id_produto'];
    $id_venda    = $itensvendidos['id_venda'];
    $quantidade  = $itensvendidos['quantidade'];

    $conexao = mysqli_connect('localhost', 'root', '', 'pastelaria');

    $query = "Insert into itens_vendidos (id_venda,id_produto,quantidade) values ({$id_venda},{$id_produto},{$quantidade}) SELECT id from venda as v join itens_vendidos i ON v.id_vendedor = i.id";
    $resultado = mysqli_query($conexao,$query);

    if ($resultado){
        return new JsonResponse($itensvendidos,200);
    }

    return new Response("Não foi possivel realizar o cadastro!", 400);

});

$app->put('/itensvendidos/{id}', function(Request $request,$id){

    $conexao = mysqli_connect('localhost', 'root', '', 'pastelaria');
    $itensvendidos = json_decode($request->getContent(), true);

    if($itensvendidos == NULL){
        return new Response("Erro",400);
    };

    $id_produto  = $itensvendidos['id_produto'];
    $id_venda    = $itensvendidos['id_venda'];
    $quantidade  = $itensvendidos['quantidade'];

    $query = "update itens_vendidos set id_produto = {$id_produto},id_venda= {$id_venda}, quantidade = {$quantidade} SELECT id from venda as v join itens_vendidos i ON v.id_vendedor = i.id";

    $resultado  = mysqli_query($conexao,$query);

    if ($resultado){
        return new JsonResponse($itensvendidos,200);
    }

    return new Response("Não foi possível alterar o item vendido!", 400);

})->assert('id','\d+');


$app->delete('/itensvendidos/{id}', function ($id){
    $conexao = mysqli_connect('localhost', 'root', '', 'pastelaria');

    $resultado = mysqli_query($conexao,"delete from itens_vendidos where id = {$id}");

    if (mysqli_num_rows($resultado)>0){
        return new Response("Não foi possivel excluir o item vendido!", 400);
    }
    return new Response("Item vendido apagado com sucesso!",200);

})->assert('id', '\d+');

$app->run();


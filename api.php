<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);

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
       return new Response("O valor nao pode ser null!", 400);
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

   if ($resultado){
       return new JsonResponse($produto,200);
   }

   return new Response("Não foi possivel realizar o cadastro!", 400);

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
        return new Response("O valor nao pode ser null!", 400);
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

    return new Response("Produto excluido com sucesso", 200);

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

    return new Response("Não há produto cadastrado!", 400);
});


$app->post('/vendedor', function (\Symfony\Component\HttpFoundation\Request $request){

    $vendedor = json_decode($request->getContent(),true);

    if($vendedor == NULL){
        return new Response("Erro",400);
    };

    if (is_string($vendedor['nome'])) {
        $vendedor['nome'] = trim($vendedor['nome']);
    }

    if(empty($vendedor['nome'])){
        return new Response("Nome não pode ser branco!",400);
    }

    if (v::cpf()->validate($vendedor['cpf'])){
        $vendedor['cpf'] = ($vendedor['cpf']);
    }

    if(empty($vendedor['cpf'])){
        return new Response("Cpf não pode ser branco!", 400);
    }

    $nome      = $vendedor['nome'];
    $cpf       = $vendedor['cpf'];

    $conexao = mysqli_connect('localhost', 'root', '', 'pastelaria');

    $query = "Insert into  vendedor (nome,cpf) values ('{$nome}',{$cpf})";
    $resultado = mysqli_query($conexao,$query);

    if ($resultado){
        return new JsonResponse($vendedor,200);
    }

    return new Response("Não foi possivel realizar o cadastro!", 400);

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

    if (v::cpf()->validate($vendedor['cpf'])){
        $vendedor['cpf'] = ($vendedor['cpf']);
    }

    if(empty($vendedor['cpf'])){
        return new Response("Cpf não pode ser branco!", 400);
    }

    $nome      = $vendedor['nome'];
    $cpf       = $vendedor['cpf'];

    $query = "update vendedor set nome = '{$nome}', cpf = {$cpf} where id = {$id}";

    $resultado  = mysqli_query($conexao,$query);

    if ($resultado){
        return new JsonResponse($vendedor,200);
    }

    return new Response("Não foi possível alterar o produto!", 400);

})->assert('id','\d+');


$app->delete('/vendedor/{id}', function ($id){
    $conexao = mysqli_connect('localhost', 'root', '', 'pastelaria');

    $resultado = mysqli_query($conexao,"delete from vendedor where id = {$id}");

    if (mysqli_num_rows($resultado)>0){
        return new Response("Não foi possivel excluir o vendedor!", 400);
    }
    return new Response("Apagado com sucesso!",200);

})->assert('id', '\d+');

$app->run();


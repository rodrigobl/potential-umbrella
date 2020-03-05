<!doctype html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Shift</title>

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="js/jquery-3.4.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>

	<script src="js/principal.js"></script>
</head>

<body class="p-2">
<?php
	$num_tabela = (isset($_REQUEST["t"]))? $_REQUEST["t"] : "0";
	include_once("estrutura.php");
?>

<div  class="container">
	<form method="get">
		<div class="row" style="display: flex;align-items: flex-end;">
			<div class="form-group col-md-6">
				<label for="selecionarTabela">Selecione a tabela</label>
				<select name="t" id="selecionarTabela" class="form-control form-control-lg">
				<?php
					foreach($headers as $key => $value){
						$sel = ($num_tabela == $key)?"selected":"";
						echo '<option value="'.$key.'" '.$sel.'>'.$value["tabela"].'</option>';
					}
				?>
				</select>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary">Carregar</button>
			</div>
		</div>
	</form>
</div>

<div class="row p-2">
    <div class="col-md-12 text-right">
    	<input id="btnInserir" class="btn btn-primary btn-lg" type="button" value="Inserir" data-toggle="modal" data-target="#modalInserirEditar">
    </div>
</div>

<table id="tabela-dados" class="table table-hover">
  <thead>
    <tr>
<?php
	foreach($headers[$num_tabela] as $key => $value){
		if($key != "tabela")
			echo "<th scope='col'>$value</th>";
	}

	if($num_tabela == "0"){
		echo "<th scope='col'>Exame(s)</th>";
	}
?>
    </tr>
  </thead>
  <tbody>
<?php
	include_once("banco.php");

	$conexao = new BaseDados($num_tabela);
	$conexao->ConfigurarSelectTabela();
	$resultado = $conexao->Procurar();
	$conexao->Desconectar();

	foreach($resultado as $res){
		echo "<tr>";

		foreach($estrutura[$num_tabela] as $key => $value){
			echo "<td>".$res[$value]."</td>";
		}

		//no caso da tabela 0 é necessário carregar e listar os exames
		if($num_tabela == "0"){
			$conexao = new BaseDados($num_tabela);
			$conexao->CarregarTabelaPorChave("idOrdemServicoExame");
			$r = $conexao->Procurar("idOrdemServico = ?", array($res["idOrdemServico"]));
			$conexao->Desconectar();

			echo "<td>";

			foreach($r as $exames){
				echo "<div>(".$exames["idExame"].")".$exames["legenda"]."</div>";
			}

			echo "</td>";
		}

		echo "<td>";
		echo '<button class="btn btn-primary btn-sm btnEditar" type="button" value="'.$res[$estrutura[$num_tabela]["id"]].'" data-toggle="modal" data-target="#modalInserirEditar">Editar</button> ';
        echo '<button class="btn btn-danger btn-sm btnExcluir" type="button" value="'.$res[$estrutura[$num_tabela]["id"]].'" data-toggle="modal" data-target="#modalExcluir">Excluir</button>';
		echo "<td>";

		echo "</tr>";
	}
?>
  </tbody>
</table>


<div class="modal fade" id="modalInserirEditar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloAdicionarEditar">"<?= $headers[$num_tabela]["tabela"] ?>"</h5>
        <button type="button" class="close btnCancelar" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-cadastro">
        	<?php
        		foreach ($estrutura[$num_tabela] as $key => $value) {
        			if($key != "id"){
	        			echo '<div class="form-group">';
	        			echo '<label for="'.$key.'">'.$headers[$num_tabela][$key].'</label>';

	        			if(substr( $value, 0, 2 ) !== "id"){
	        				echo '<input name="'.$key.'" type="text" class="form-control enviar" id="'.$key.'" />';
	        			}else{

	        				//caso possua id significa que é um select então os options são carregados
	        				echo '<select name="'.$key.'" id="'.$key.'" class="form-control enviar">';

	        				$conexao = new BaseDados($num_tabela);
	        				$conexao->CarregarTabelaPorChave($value);
							$r = $conexao->Procurar();
							$conexao->Desconectar();

							foreach($r as $val){
								echo '<option value="'.$val[$value].'">('.$val[$value].') '.$val["legenda"].'</option>';
							}

							echo '</select>';

	        			}

	        			echo '</div>';
	        		}
        		}

        		if($num_tabela == "0"){
        			//no caso da tabela 0 é necessário carregar os exames
	        		$conexao = new BaseDados($num_tabela);
	        		$conexao->CarregarTabelaPorChave("idExame");
	        		$r = $conexao->Procurar();
	        		$conexao->Desconectar();

	    			echo '<div class="form-group">';
	    			echo '<label for="idExame">Exames</label>';
	        		echo '<select id="idExame" class="form-control">';
	        		foreach($r as $exame){
	        			echo '<option value="'.$exame["idExame"].'">('.$exame["idExame"].') '.$exame["legenda"].'</option>';
	        		}
	        		echo '</select>';
	        		echo '</div>';
	        		echo '<button type="button" id="btnAdicionarExame" class="btn btn-success">Adicionar Exame</button>';
        	?>
        	<div class="form-group">
        		<table id="tabela-exames" class="table table-hover">
					<thead>
						<tr>
							<th scope='col'>idExame</th>
							<th scope='col'>Descricao</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		<?php } ?>
		  <div class="modal-footer">
	        <button type="button" class="btn btn-secondary btnCancelar" data-dismiss="modal">Cancelar</button>
	        <button type="button" id="btnSalvar" class="btn btn-primary">Salvar</button>
	      </div>
		</form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalExcluir" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloExcluir">Excluir</h5>
        <button type="button" class="close btnCancelar" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Deseja realmente excluir este registro?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btnCancelar" data-dismiss="modal">Cancelar</button>
        <button type="button" id="btnConfirmarExcluir" class="btn btn-danger" data-dismiss="modal">EXCLUIR</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>
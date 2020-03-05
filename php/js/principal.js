//variavel utilizada para saber qual item será manipulado
var id_selecionado=0;
$(document).ready(function(){

    $("#btnAdicionarExame").click(AdicionarExame);

    $("#btnSalvar").click(SalvarFormulario);

    $(".btnCancelar").click(function(){
        id_selecionado=0;
        Recarregar();
    });

    $(".btnExcluir").click(function(){
        id_selecionado = $(this).val();
    });

    $(".btnEditar").click(function(){
        id_selecionado = $(this).val();

        //busca as informações do banco para preencher no formulário
        if(id_selecionado > 0){
            $.ajax({
                method:"POST",
                url: "requisicao.php",
                data:{t:PegarParametro('t'), r:'5',id:id_selecionado},
                dataType:"json"
            }).always(CarregarFormulario);
        }
    });

    //no caso de clicar no botão inserir é necessário limpar o formulário
    $("#btnInserir").click(function(){
        id_selecionado=0;

        $("form .form-control").each(function(){
            $(this).val("");
        });
    });

    $("#btnConfirmarExcluir").click(ExcluirRegistro);
});



////////////////////////
//APENAS FUNÇÕES ABAIXO
////////////////////////

function AdicionarExame()
{
    AdicionarItem_TabelaExame($("#idExame").val(), $("#idExame option:selected").text().split(")")[1]);  
}

//Constroi e envia a requisição de salvar o formulário (Inserir ou Editar)
function SalvarFormulario()
{
    //coleta todos os atributos name dos inputs no formulário
    var nomes = $("#form-cadastro .enviar").map(function(){
        return $.trim($(this).attr("name"));
    }).get();

    //coleta todos os valures dos inputs no formulário
    var valores = $("#form-cadastro .enviar").map(function(){
        return $.trim($(this).val());
    }).get();

    var conteudo = new Object();
    conteudo["t"]=PegarParametro('t');

    for(i in nomes)
        conteudo[nomes[i]]=valores[i];

    //cria um array de todos os exames adicionados na tabela
    var exames = $("#tabela-exames .exames").map(function(){
        return $.trim($(this).text());
    }).get();

    //para facilitar o envio agrupa o array em uma string, com os elementos separados por -
    if(exames)
        conteudo["ex"]=exames.join("-");

    if(id_selecionado > 0){
        conteudo["id"] = id_selecionado;
    }

    $.ajax({
        method:"POST",
        url: "requisicao.php",

        data:JSON.parse(JSON.stringify(conteudo)),

        dataType:"json"
    }).always(Recarregar);

}

//A partir do parametro data (que está formatado para essa operação), insere os valores no formulário
function CarregarFormulario(data)
{
    for(d in data){
        if(d != "id"){
            $("#"+d).val(data[d]);
        }
    }

    //para o caso da tabela 0, é necessário carregar cada exame
    if(PegarParametro('t') == "0"){

        var conteudo = new Object();
        conteudo["t"]="5";
        conteudo["r"]='6';
        conteudo["id"]=id_selecionado;

        //busca as informações no banco
        $.ajax({
            method:"POST",
            url: "requisicao.php",

            data:JSON.parse(JSON.stringify(conteudo)),

            dataType:"json"
        }).always(CarregarExamesFormulario);

    }
}

//Constroi e envia a requisição de excluir um registro
function ExcluirRegistro()
{
    var conteudo = new Object();

    conteudo["t"]=PegarParametro('t');
    conteudo["r"]="3";
    conteudo["id"]=id_selecionado;

    $.ajax({
        method:"POST",
        url: "requisicao.php",

        data:JSON.parse(JSON.stringify(conteudo)),

        dataType:"json"
    }).always(Recarregar);
}

//Insere na tabela de exames o array formatado data
function CarregarExamesFormulario(data)
{
    for(d in data){
        AdicionarItem_TabelaExame(data[d].id, data[d].legenda);
    }
}

//Função apenas para recarregar a página a fim de reduzir a complexidade do código
function Recarregar(data)
{
    location.reload(true);
}

//Função para obter o parametro get recebido
function PegarParametro(str)
{
    let searchParams = new URLSearchParams(window.location.search);

    if(searchParams.has(str))
        return searchParams.get(str);
    else
        return "0";
}

//adiciona o item com id e conteudo na tabela de exames
function AdicionarItem_TabelaExame(id, conteudo)
{
    $("#tabela-exames tbody").append('<tr>'+
        '<td class="exames">'+
            id+
        '</td>'+
        '<td>'+
            conteudo+
        '</td>'+
        '<td class="text-right">'+
            '<button type="button" class="btn btn-danger btnExcluirExame">Excluir</button>'+
        '</td>'+
        '</tr>'
    );

    $(".btnExcluirExame").click(function(){
        $(this).parent().parent().remove();
    });
}

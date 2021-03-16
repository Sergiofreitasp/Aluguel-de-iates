

$(document).ready(function() {
    loadchanges();
});

function savechanges(){
    obj = {};

    obj.id = $("#id").val();
    obj.nome = $("#nome").val();
    obj.cpf = $("#cpf").val();
    obj.idade = $("#idade").val();
    obj.endereco = $("#endereco").val();
    obj.numero = $("#numero").val();

    if(obj.nome == ""){
        alert("por favor, digite um nome");
    }else if(obj.cpf ==""){
        alert("Digita um cpf ai valeu");
    }else if(obj.idade ==""){
        alert("Digite sua idade");
    }else if(obj.endereco ==""){
        alert("digite um endereco");
    }else if(obj.numero ==""){
        alert("digite um numero");
    }else{
        
        $.post('../api/add.php', obj, function(resultado){
            console.log("ola1")
            alert(resultado.msg)
            limparcampos();
            loadchanges();
        }, 'json');
    }
    
    
}

function limparcampos(){

    $("#id").val("");
    $("#nome").val("");
    $("#cpf").val("");
    $("#idade").val("");
    $("#endereco").val("");
    $("#numero").val("");
}

function edit(){

  var l = $('.scheck:checked');

  if(l.length>1){
    alert("Selecione apenas uma linha")
  }else if(l.length == 0){
    alert("Por favor, selecione uma linha")
  }else{
    l.each(function(){
      var s = this.id;
      s=s.split("-");
      editpopup(s[1]);
    });
  }

}


var global_ar="";

function loadchanges(){
    
    $.post('../api/return.php', {}, function(data){
        
        global_ar= data;
        montartbl(data);
    }, 'json')
}

function editpopup(id){

  $.each(global_ar, function(index, value){
    if(value.id == id){
      $('#id').val(value.id);
      $('#nome').val(value.nome);
      $('#cpf').val(value.cpf);
      $('#idade').val(value.idade);
      $('#endereco').val(value.endereco);
      $('#numero').val(value.numero);
    }
  });
  
}

function del(){
  var l = $('.scheck:checked');

  if (l.length == 0){
    alert("Selecione uma ou mais linhas");
  }else{
    var x = "";
    var c="";
    l.each(function(){
      var s = this.id;
      c = this.cpf;
      s=s.split("-");
      if (x != 0){
        x+=',';

      }
      x+=s[1];
    })
    deletepopup(x);
  }
}

function deletepopup(x){

  if(confirm("Voce tem certeza que deseja excluir essa linha?")){
    console.log("ola");
    $.post('../api/delete.php', {id:x}, function(data){
      console.log("ola2");
      loadchanges();
      alert("Linha deletada!");
    }, 'json');
  }
}

function montartbl(data){
    var title = ''; // nao precisa
    var s = "<thead><tr><th onclick=\"selall();\">all</th> <th>IDid</th> <th>NOMEno</th> <th>IDADEidd</th><th>CPF</th><th>ENDERECO</th><th>NUMERO</th></tr></thead><tbody>";
    $.each(data,function(index,value){
      s+="<tr class=\"odd gradeX\" onclick=\"abredetalerta('"+value.id+"');\"><td><input type=\"checkbox\" id=\"vl-"+value.id+"\"  name=\"vl-"+value.id+"\" class=\"scheck\"/></td>"+
      "<td><a href=\"#\">"+value.id+"</a></td><td>"+value.nome+"</td><td>"+value.idade+"</td><td>"+value.cpf+"</td><td>"+value.endereco+"</td><td>"+value.numero+"</td></tr>";
    });
    s+="</tbody>";
   
   $('#dataTables-example').html(s); 
  
   
   setTimeout(function(){
    deftable = $('#dataTables-example').DataTable({
     responsive: true,
     "bDestroy": true,
     orderCellsTop: true,
     fixedHeader: true,
     dom: 'Blfrtip',
     buttons: [
      'csv', 'excel', 'pdf'
     ],
     "order": [[ 1, "desc" ]],
     "language": {
      "sEmptyTable": "Nenhum registro encontrado",
      "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
      "sInfoFiltered": "(Filtrados de _MAX_ registros)",
      "sInfoPostFix": "",
      "sInfoThousands": ".",
      "sLengthMenu": "_MENU_ resultados por página",
      "sLoadingRecords": "Carregando...",
      "sProcessing": "Processando...",
      "sZeroRecords": "Nenhum registro encontrado",
      "sSearch": "Pesquisar",
      "oPaginate": {
        "sNext": "Próximo",
        "sPrevious": "Anterior",
        "sFirst": "Primeiro",
        "sLast": "Último"
      },
      "oAria": {
        "sSortAscending": ": Ordenar colunas de forma ascendente",
        "sSortDescending": ": Ordenar colunas de forma descendente"
      },
      "select": {
        "rows": {
          "_": "Selecionado %d linhas",
          "0": "Nenhuma linha selecionada",
          "1": "Selecionado 1 linha"
        }
      },
      "buttons": {
        "copy": "Copiar para a área de transferência",
        "copyTitle": "Cópia bem sucedida",
        "copySuccess": {
          "1": "Uma linha copiada com sucesso",
          "_": "%d linhas copiadas com sucesso"
        }
      }
    }
    });
    },800);
  }
  
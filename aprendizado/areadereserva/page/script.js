

$(document).ready(function() {
    loadchangesc();
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
    obj.condominio_id = $('#condominio_drop').val();

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
        
        $.post('../api_cadastrousuario/add.php', obj, function(resultado){
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
    var obj = {};
    obj.condominio_id = $('#condominio_drop').val();
    $.post('../api_cadastrousuario/return.php', obj, function(data){
        
        global_ar= data;
        montartbl(data);
        
    }, 'json')
}

function editpopup(id){
  console.log(global_ar);
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
    
    l.each(function(){
      var s = this.id;
      
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
    $.post('../api_cadastrousuario/delete.php', {id:x}, function(data){
      console.log("ola2");
      loadchanges();
      alert("Linha deletada!");
    }, 'json');
  }
}

function montartbl(data){
    var title = ''; // nao precisa
    var s = "<thead><tr><th onclick=\"selall();\">all</th> <th>ID</th> <th>NOME</th> <th>IDADE</th><th>CPF</th><th>ENDERECO</th><th>NUMERO</th><th>CONDOMINIO</th></tr></thead><tbody>";
    console.log(data);
    $.each(data,function(index,value){
      s+="<tr class=\"odd gradeX\" onclick=\"abredetalerta('"+value.id+"');\"><td><input type=\"checkbox\" id=\"vl-"+value.id+"\"  name=\"vl-"+value.id+"\" class=\"scheck\"/></td>"+
      "<td><a href=\"#\">"+value.id+"</a></td><td>"+value.nome+"</td><td>"+value.idade+"</td><td>"+value.cpf+"</td><td>"+value.endereco+"</td><td>"+value.numero+"</td><td>"+value.condominio+"</td></tr>";
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
    


function montarDropdown(data){
  /*var d = "<button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\">Selecione ..."+
  "<span class=\"caret\"></span></button><ul class=\"dropdown-menu\"><span class=\"caret\"></span></button><ul class=\"dropdown-menu\">";
  */

  var d = "<option>Selecione um condominio</option>"
  $.each(data, function(index, value){
    console.log(value.nome);
    d+="<option value="+value.id+">"+value.nome+"</option>";
  });
  console.log(d);
  $('#condominio_drop').html(d);
}

function savechangesc(){
  obj = {};

  obj.id = $("#id").val();
  obj.nome = $("#nome").val();
  

  if(obj.nome == ""){
      alert("por favor, digite um nome");
  }else{
      
      $.post('../api_db_condominio/add.php', obj, function(resultado){
          console.log("ola1")
          alert(resultado.msg)
          limparcamposc();
          loadchangesc();
      }, 'json');
  }
}

function limparcamposc(){

  $("#id").val("");
  $("#nome").val("");
}

var datadrop = "";

function loadchangesc(){
  console.log("1");
  $.post('../api_db_condominio/return.php', {}, function(datacondominio){
    datadrop=datacondominio;
    montartblcondominio(datadrop);
    console.log("2");
    montarDropdown(datadrop);
    console.log("3");
  }, 'json');
}


function editc(){

  var l = $('.scheck:checked');

  if(l.length>1){
    alert("Selecione apenas uma linha")
  }else if(l.length == 0){
    alert("Por favor, selecione uma linha")
  }else{
    l.each(function(){
      var s = this.id;
      s=s.split("-");
      editpopupc(s[1]);
    });
  }

}

function editpopupc(id){
  
  $.each(datadrop, function(index, value){
    if(value.id == id){
      $('#id').val(value.id);
      $('#nome').val(value.nome);
    }
  });
}

function delc(){
  var l = $('.scheck:checked');

  if (l.length == 0){
    alert("Selecione uma ou mais linhas");
  }else{
    var x = "";
    
    l.each(function(){
      var s = this.id;
      
      s=s.split("-");
      if (x != 0){
        x+=',';
      }
      x+=s[1];
    })
    deletepopupc(x);
  }
}

function deletepopupc(x){

  if(confirm("Voce tem certeza que deseja excluir essa linha?")){
    
    $.post('../api_db_condominio/delete.php', {id:x}, function(data){
      
      loadchangesc();
      alert("Linha deletada!");
    }, 'json');
  }
}

function montartblcondominio(data){
  console.log("entrou no monta tabela");
  
  var s = "<thead><tr><th onclick=\"selall();\">all</th> <th>ID</th> <th>NOME</th> </tr></thead><tbody>";
  console.log(data)
  $.each(data,function(index,value){
    s+="<tr class=\"odd gradeX\" ><td><input type=\"checkbox\" id=\"vl-"+value.id+"\"  name=\"vl-"+value.id+"\" class=\"scheck\"/></td>"+
    "<td><a href=\"#\">"+value.id+"</a></td><td>"+value.nome+"</td></tr>";
  });
  console.log("aqui");
  s+="</tbody>";
 
 $('#dataTables-c').html(s); 

 
 
  
}
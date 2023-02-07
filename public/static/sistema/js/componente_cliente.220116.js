var componente_cliente = (function($, pub){

  var o = pub.deepClone(pub);

  o.create_row_contact_table = function($table)
  {
    cht = '';

    var bEdit  = $table.data("active-edit") == 1;
    var bQuit  = $table.data("active-quit") == 1;
    var cInput = $table.data("input-name");

    $table.find("tbody tr").each(function(index, element){
      $tr = $( element );

      cht = '';

      cin = '<input type="hidden" name="'+cInput+'['+ $tr.data('id') +'][clave]" value="'+ $tr.data('clave') +'" />';
      cht+='<td class="col-clave">'+ $tr.data('clave') +''+ cin +'</td>'; // clave

      cin = '<input type="hidden" name="'+cInput+'['+ $tr.data('id') +'][descripcion]" value="'+ $tr.data('descripcion') +'" />';
      cht+='<td class="col-descripcion">'+ $tr.data('descripcion') +''+ cin +'</td>'; // descripcion

      cht+='<td class="col-opciones"><div class="table-data-feature">';

      if(bEdit)
      {
        cht+='<a href="#" class="item btnForm" data-id="'+ $tr.data('id') +'" data-title="Editar T&eacute;lefono"><i class="zmdi zmdi-edit"></i></a>';
      }

      if(bQuit)
      {
        cht+='<a href="#" class="item btnQuit"><i class="zmdi zmdi-delete"></i></a>';
      }

      cht+='</div></td>';

      $tr.html(cht);
    });
  }

  o.create_row_email_table = function($table)
  {
    cht = '';

    var bEdit  = $table.data("active-edit") == 1;
    var bQuit  = $table.data("active-quit") == 1;
    var cInput = $table.data("input-name");

    $table.find("tbody tr").each(function(index, element){
      $tr = $( element );

      cht = '';

      cin = '<input type="hidden" name="'+cInput+'['+ $tr.data('id') +'][clave]" value="'+ $tr.data('clave') +'" />';
      /*cht+='<td class="col-clave">'+ $tr.data('clave') +''+ cin +'</td>'; // clave */

      cin+= '<input type="hidden" name="'+cInput+'['+ $tr.data('id') +'][descripcion]" value="'+ $tr.data('descripcion') +'" />';
      cht+='<td class="col-descripcion">'+ $tr.data('descripcion') +''+ cin +'</td>'; // descripcion

      cht+='<td class="col-opciones"><div class="table-data-feature">';

      if(bEdit)
      {
        cht+='<a href="#" class="item btnForm" data-id="'+ $tr.data('id') +'" data-title="Editar T&eacute;lefono"><i class="zmdi zmdi-edit"></i></a>';
      }

      if(bQuit)
      {
        cht+='<a href="#" class="item btnQuit"><i class="zmdi zmdi-delete"></i></a>';
      }

      cht+='</div></td>';

      $tr.html(cht);
    });
  }

  o.create_row_symptom_table = function($table)
  {
    cht = '';

    var bEdit  = $table.data("active-edit") == 1;
    var bQuit  = $table.data("active-quit") == 1;
    var cInput = $table.data("input-name");

    $table.find("tbody tr").each(function(index, element){
      $tr = $( element );

      cht = '';

      cin = '<input type="hidden" name="'+cInput+'['+ $tr.data('id') +'][clave]" value="'+ $tr.data('clave') +'" />';
      cht+='<td class="col-clave">'+ $tr.data('clave') +''+ cin +'</td>'; // clave

      cin = '<input type="hidden" name="'+cInput+'['+ $tr.data('id') +'][descripcion]" value="'+ $tr.data('descripcion') +'" />';
      cht+='<td class="col-descripcion">'+ $tr.data('descripcion') +''+ cin +'</td>'; // descripcion

      cht+='<td class="col-opciones"><div class="table-data-feature">';

      if(bEdit)
      {
        cht+='<a href="#" class="item btnForm" data-id="'+ $tr.data('id') +'" data-title="Editar Sintoma"><i class="zmdi zmdi-edit"></i></a>';
      }

      if(bQuit)
      {
        cht+='<a href="#" class="item btnQuit"><i class="zmdi zmdi-delete"></i></a>';
      }

      cht+='</div></td>';

      $tr.html(cht);
    });
  }

  o.create_row_family_table = function($table)
  {
    cht = '';

    var bEdit  = $table.data("active-edit") == 1;
    var bQuit  = $table.data("active-quit") == 1;
    var cInput = $table.data("input-name");

    $table.find("tbody tr").each(function(index, element){
      $tr = $( element );

      cht = '';

      cin = '<input class="idpariente" type="hidden" name="'+cInput+'['+ $tr.data('id') +'][idpariente]" value="'+ $tr.data('idpariente') +'" />';
      cht+='<td class="col-clave">'+ $tr.data('clave') +''+ cin +'</td>';

      cin = '<input class="idparentesco" type="hidden" name="'+cInput+'['+ $tr.data('id') +'][idparentesco]" value="'+ $tr.data('idparentesco') +'" />';
      cht+='<td class="col-descripcion">'+ $tr.data('descripcion') +''+ cin +'</td>';

      cht+='<td class="col-opciones"><div class="table-data-feature">';

      if(bEdit)
      {
        cht+='<a href="#" class="item btnForm" data-id="'+ $tr.data('id') +'" data-title="Editar Familiar"><i class="zmdi zmdi-edit"></i></a>';
      }

      if(bQuit)
      {
        cht+='<a href="#" class="item btnQuit"><i class="zmdi zmdi-delete"></i></a>';
      }

      cht+='</div></td>';

      $tr.html(cht);
    });
  }


return o;
})(jQuery, componente || {});
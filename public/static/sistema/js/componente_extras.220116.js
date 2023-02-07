var componente_extras = (function($, pub){
  var o = pub.deepClone(pub);

  var _icons_multi_upload = {
    pdf: "/static/sistema/images/icon/pdf.png",
    word: "/static/sistema/images/icon/word.png",
    excel:"/static/sistema/images/icon/excel.png",
    pic: "/static/sistema/images/icon/pic.png",
    unknown: "/static/sistema/images/icon/unknown.png",
  };

  function _get_square_multi_upload(objInput, objValue)
  {
    objValue.input                = (objValue.input || '').replace("None", "");
    objValue.input_name           = (objValue.input_name || '').replace("None", "");
    objValue.input_name_en        = (objValue.input_name_en || '').replace("None", "");
    objValue.input_description    = (objValue.input_description || '').replace("None", "");
    objValue.input_description_en = (objValue.input_description_en || '').replace("None", "");

    objValue.url = objValue.url || '';

    var cht   = '';
    var cUrl  = objValue.url;
    var cIcon = _icons_multi_upload.unknown;

    if(cUrl != "")
    {
      cUrl = cUrl.toLowerCase()

      if(cUrl.indexOf(".pdf") >= 0)
      {
        cIcon = _icons_multi_upload.pdf;
      }
      else if(cUrl.indexOf(".doc") >= 0 || cUrl.indexOf(".docx") >= 0)
      {
        cIcon = _icons_multi_upload.word;
      }
      else if(cUrl.indexOf(".xls") >= 0 || cUrl.indexOf(".xlsx") >= 0)
      {
        cIcon = _icons_multi_upload.excel;
      }
      else if(cUrl.indexOf(".jpg") >= 0 || cUrl.indexOf(".jpeg") >= 0
                        || cUrl.indexOf(".png") >= 0 || cUrl.indexOf(".gif") >= 0
                        || cUrl.indexOf(".webp") >= 0 || cUrl.indexOf(".bmp") >= 0
                        || cUrl.indexOf(".tiff") >= 0)
      {
        cIcon = objValue.url;
      }

      cht+= '<div class="brick small" style="position: relative; height: auto !important;">';
      cht+= '<div title="Eliminar" class="btnDelete">X</div>';
      cht+= '<div style="width: 60px; margin: 10px auto; text-align: center">';
      cht+= '<a href="'+ objValue.url +'" title="Descargar" target="_blank" download>';
      cht+= '<img src="'+ cIcon +'" style="margin-top: 10px; max-height:50px;" />';
      cht+= '</a>';
      cht+= '</div>';

      cht+= '<center>';

      cht+= '<input type="text" name="'+ objInput.input +'['+ objValue.id +']['+ objInput.input_name +']" value="'+ objValue.input_name +'" placeholder="Nombre" style="width: 90%; margin-bottom:10px;" />';

      if(objInput.input_name_en.length > 0)
        cht+= '<input type="text" name="'+ objInput.input +'['+ objValue.id  +']['+ objInput.input_name_en +']" value="'+ objValue.input_name_en +'" placeholder="Nombre En" style="width: 90%; margin-bottom:10px;" />';

      cht+= '<textarea name="'+ objInput.input +'['+ objValue.id  +']['+ objInput.input_description +']" placeholder="Descripción" style="width: 90%; margin-bottom:10px;">'+ objValue.input_description +'</textarea>';

      if(objInput.input_description_en.length > 0)
        cht+= '<textarea name="'+ objInput.input +'['+ objValue.id  +']['+ objInput.input_description_en +']" placeholder="Description En" style="width: 90%; margin-bottom:10px;">'+ objValue.input_description_en +'</textarea>';

      cht+= '</center>';
      cht+= '</div>';

      /*cht+= '<div class="brick small" style="position: relative;">';
      cht+= '<div title="Eliminar" class="btnDelete">X</div>';
      cht+= '<div style="width: 60px; height: 60px; margin: 10px auto; text-align: center">';
      cht+= '<a href="'+ objValue.input_document +'" title="Descargar" target="_blank" download>';
      cht+= '<img src="'+ cIcon +'" style="margin-top: 10px;" />';
      cht+= '</a>';
      cht+= '</div>';

      cht+= '<center><input type="hidden" name="'+ objInput.input_id +'" value="'+ objValue.input_id +'" style="width: 90%;" />';
      cht+= '<input type="text" name="'+ objInput.input_name +'" value="'+ objValue.input_name +'" placeholder="Nombre"  style="width: 90%;" /><br><br>';
      cht+= '<textarea name="'+ objInput.input_description +'" placeholder="Descripción" style="width: 90%;">'+ objValue.input_description +'</textarea></center>';
      cht+= '</div>';*/
    }

    return cht;
  }

  function _sendFileSummerNoteEditor(file, el)
  {
    data = new FormData();

    data.append("file", file);
    data.append("keep", "yes");
    data.append("_token", window.laravel.token);

    $.ajax({
      data: data,
      type: "post",
      url: "/uploader/",
      cache: false,
      contentType: false,
      processData: false,
      dataType: 'json',
      success: function(response) {
        $(el).summernote('editor.insertImage', response.data.url);
      }
    });
  }

  o.load_plugin_multi_upload = function($area)
  {
    $area.each(function(index, element){
      let $area = $(element);

      let $filelist = $area.find('.filelist');

      let _objInput = {
        input : $filelist.attr("data-input"),
        input_name : $filelist.attr("data-input-name") || "",
        input_name_en : $filelist.attr("data-input-name-en") || "",
        input_description : $filelist.attr("data-input-description") || "",
        input_description_en : $filelist.attr("data-input-description-en") || "",
      };

      $filelist.find(".item").each(function(index, element){
        var $item = $(element);

        var cAux = '';

        var _objValue = {
          id : $item.attr("data-id"),
          input_name : $item.attr("data-value-name") || '',
          input_name_en : $item.attr("data-value-name-en") || '',
          input_description : $item.attr("data-value-description") || '',
          input_description_en : $item.attr("data-value-description-en") || '',
          url : $item.attr("data-url") || '',
        };

        cAux = _get_square_multi_upload(_objInput, _objValue);

        $filelist.append(cAux);

        $item.remove();
      });

      $filelist.dad();

      $area.find(".upload_input").upload({
        headers: { "X-CSRF-TOKEN": window.laravel.token },
        action: "/uploader/",
        postKey: "file",
        maxConcurrent: 1,
        label: "<b>Arrastra y suelta los archivos, o <u>haz click aquí</u> para cargarlos.</b>",
      })
      .on("start.upload", function(evt, files){
        var cError = '';
        var bError = false;

        var $filelist = $(this).closest(".area_multi_upload").find(".filelist");

        var iMaxItem = $filelist.data("max-item") || 0;

        var iTotalItems = $filelist.find(".brick").length;

        var cExt = $filelist.attr("data-extension-valid");

        var arr_ext = cExt.split(",");

        bError = false;

        files.forEach(function(file, i){
          if(bError == false)
          {
            if(!(arr_ext.some(ext => file.name.toLowerCase().includes("." + ext.toLowerCase()))))
            {
              cError += "<li>Uno o m&aacute;s archivos no tienen las extensiones permitidas: <b>" + arr_ext.join(", ") + "</b>";
              bError = true;
            }
          }
        });

        if(iMaxItem > 0)
        {
          if(iTotalItems >= iMaxItem)
          {
            cError += "<li>El limite de archivos permitido es:&nbsp;<b>" + iMaxItem + "</b>, borra archivos para poder cargar nuevos.";
          }
        }

        if(cError.length > 0)
        {
          var cHtml = '<div style="text-align: left; padding-left:40px;"><ul>'+ cError +'</ul></div>';

          swal({
            type: 'error',
            html: cHtml,
          });

          $(this).closest(".area_multi_upload").find(".upload_input").upload("abort");
        }

      })
      .on("filestart.upload", function(evt, file){
        $(this).closest(".area_multi_upload").find(".progress").show().find(".progress-bar").css({'width':'0%'});
      })
      .on("fileprogress.upload", function(evt, file, percent){
        $(this).closest(".area_multi_upload").find(".progress").find(".progress-bar").css({'width':percent + "%"});
      })
      .on("filecomplete.upload", function(evt, file, response){
        $(this).closest(".area_multi_upload").find(".progress").hide().find(".progress-bar").css({'width':'0%'});

        response = JSON.parse(response);

        if(response.success)
        {
          let cError = '';

          let $filelist = $(this).closest(".area_multi_upload").find(".filelist");

          /*let iMaxItem = $filelist.data("max-item") || 0;*/

          /*let iTotalItems = $filelist.find(".brick").length;

          if(iMaxItem > 0)
          {
            if(iTotalItems >= iMaxItem)
            {
              cError += "<li>El limite de archivos permitidos es:&nbsp;<b>" + iMaxItem + "</b>, borra archivos para poder cargar nuevos.";
            }
          }*/

          if(cError.length > 0)
          {
            var cHtml = '<div style="text-align: left; padding-left:40px;"><ul>'+ cError +'</ul></div>';

            swal({
              type: 'error',
              html: cHtml,
            });

            $(this).closest(".area_multi_upload").find(".upload_input").upload("abort");
          }
          else
          {
            let _objInput = {
              input : $filelist.attr("data-input") || '',
              input_name : $filelist.attr("data-input-name")|| '',
              input_name_en : $filelist.attr("data-input-name-en") || '',
              input_description : $filelist.attr("data-input-description") || '',
              input_description_en : $filelist.attr("data-input-description-en") || '',
            };

            let _objValue = {
              id : response.data.id,
              input_name : (response.data.nombre || response.data.nombre_es || response.data.nombre_en || ''),
              input_description : '',
              url : response.data.url,
            };

            cAux = _get_square_multi_upload(_objInput, _objValue);

            $filelist.append(cAux);
          }
        }
        else
        {
          swal({
            type: 'error',
            text: "Sucedio un error al cargar archivo, favor de volver a intentar.",
          });
        }
      })
      .on("fileerror.upload", function(evt, file, error){
        $(this).parents("form").find(".progress").hide().find(".progress-bar").css({'width':'0%'});

        if(error != "abort")
        {
          alert("Sucedio un error al cargar archivo, favor de volver a intentar.");
        }
      });

      $filelist.on("click", ".btnDelete", function(evt){
        evt.preventDefault();

        let $self = $(this);

        let $brick = $self.closest("div.brick");

        $brick.remove();
      });
    });
  }

  o.load_plugin_summernote = function(cClass)
  {
    cClass = cClass || "summernote";
    cClass = "." + cClass;

    $cSelector = $(cClass);

    $cSelector.each(function(index, element) {
      $elem = $(element);

      $elem.summernote({
        tabsize: 2,
        height: 400,
        callbacks: {
          onInit: function()
          {
            $area_summernote = $elem.closest(".area_summernote");
            $note_codable    = $area_summernote.find(".note-codable").first();

            $note_codable.first().off("change keyup").on("change keyup", function(evt){
              evt.preventDefault();

              $note_codable = $(this);
              $area_summernote = $note_codable.closest(".area_summernote");

              $input = $area_summernote.find("textarea").first();

              $input.html($note_codable.val());
            });
          },
          onImageUpload: function(files, editor, welEditable)
          {
            for(var i = files.length - 1; i >= 0; --i)
            {
              _sendFileSummerNoteEditor(files[i], this);
            }
          },
        },
      });
    });




  }

  return o;
})(jQuery, componente || {});
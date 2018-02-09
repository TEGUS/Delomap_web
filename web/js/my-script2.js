$(function () {
    //Exportation des termes de reference dans le tableau
    $('#table-terme-reference').DataTable({
        "language": {
            "url": Routing.getBaseUrl() + "/plugins/jquery-datatable/i18n/French.json",
            buttons: {
                copy: 'Copier',
                print: 'Imprimer'
            }
        },
        "ajax": {
            "url": Routing.generate('list_datatable_tdrs'),
            "type": "POST"
        },
        dom: 'Bfrtip',
        responsive: true,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "columnDefs": [
            {
                "targets": [ 3 ],
                "class": "hide_me"
            }
        ]
    });

    //click sur le bouton nouveau TDR
    $('#block-table-terme-reference .button-new').click(function () {

        $('#form-terme-reference input.nom').val('');
        $('#form-terme-reference textarea.description').html('');

        $('#block-table-terme-reference').addClass('hidden');
        $('#block-form-terme-reference').removeClass('hidden');
    });

    //click recherce tdr
    //$('#select-tdr').searchableOptionList();

    //click sur le bouton editer TP
    $('#table-terme-reference').on("click", ".edit", function () {

        var id;
        var idtypeprestation;
        var nom;
        var description;

        var row = jQuery(this).closest('tr');

        var i = 0;
        row.find("td").each(function (cellIndex) {
            if (i === 0) {
                id = $(this).html();
            } else if (i === 1) {
                nom = $(this).html();
            } else if (i === 2) {
                description = $(this).html();
            } else if (i === 3) {
                idtypeprestation = $(this).html();
            }
            i++;
        });
        
        $.ajax({
                'type': 'POST',
                'url': Routing.generate('find_all_tps'),
                'dataType': 'JSON',
                'success': function(result) {
                    var types_prestations = "";
                    
                    for (var key in result) {
                        var tp = result[key];
                        types_prestations += '<option value="' + tp.id + '">' + tp.libelle + '</option>';
                    }
                    //console.log(types_prestations);
                    $('#select-tdr').html(types_prestations);
                    $('#select-tdr').selectpicker('refresh');
                    console.log(idtypeprestation);
                    console.log('fin');
                    $('#select-tdr').val(idtypeprestation);
                    $('#select-tdr').selectpicker('refresh');
                },
                'error': function(xhr, status, error) {
                    var err = eval(xhr.responseText );
                    console.log(err);
                    console.log(error);
                }
            });

        $('#id_tdr').val(id);
        $('#form-terme-reference input.nom').val(nom);
        $('#form-terme-reference textarea.description').html(description);

        $('#block-table-terme-reference').addClass('hidden');
        $('#block-form-terme-reference').removeClass('hidden');

    });
    $("#form-terme-reference").validate({
        rules: {
            tdselect: "required",
            nom: "required"
        },
        messages: {
            tdselect: "Veuillez sélectionner le type de prestation",
            nom: "Veuillez entrer un nom"
        }
    });
      //click sur le bouton Enregistrer tdr
    $('#block-form-terme-reference .save').click(function () {
        
        if ($("#form-terme-reference").valid()) {
            var id = $('#id_tdr').val();
            var url = "";
            var msg_reussite = "";
            var msg_echec = "";
            if (id){
                //console.log('modification')
                url = Routing.generate('update_tdr', { 'tdr': id });
                msg_reussite = "Terme de référence modifiée avec succès";
                msg_echec = "Problème de modification du nouveau terme de référence";
            } else{
                //console.log('enregistrement')
                url = Routing.generate('add_tdr');
                msg_reussite = "Terme de référence enregistrée avec succès";
                msg_echec = "Problème d'enregistrement du nouveau terme de référence";
            }
            $.ajax({
                'type': 'POST',
                'url': url,
                'dataType': 'JSON',
                'data': {
                    libelle: $('#nom_tdr').val(),
                    description: $('#description_tdr').val(),
                    tp: $('#select-tdr').val()
                },
                'success': function (result) {
                    if (result.data) {
                        $('#block-form-terme-reference').addClass('hidden');
                        $('#block-table-terme-reference').removeClass('hidden');
                        swal("Réussi!", msg_reussite, "success");

                        table_tdr.ajax.reload();
                    } else {
                        swal("Erreur!", msg_echec, "error");
                    }
                },
                'error': function () {
                    swal("Erreur!", msg_echec, "error");
                },
                'beforeSend': function() {
                    $('#block-form-terme-reference .save i').removeClass('hidden');
                },
                'complete': function() {
                    $('#block-form-terme-reference .save i').addClass('hidden');
                }
            });
        }
    });
    

});

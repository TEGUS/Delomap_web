var table_tp;

$(function () {
    //Exportable table
    table_tp = $('#table-type-prestation').DataTable({
        "language": {
            "url": Routing.getBaseUrl() + "/plugins/jquery-datatable/i18n/French.json",
            buttons: {
                copy: 'Copier',
                print: 'Imprimer'
            }
        },
        "ajax": {
            "url": Routing.generate('list_datatable_tps'),
            "type": "POST"
        },
        dom: 'Bfrtip',
        responsive: true,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    //click sur le bouton nouveau TP
    $('#block-table-type-prestation .button-new').click(function () {

        $('#form-type-prestation input.nom').val('');
        $('#form-type-prestation textarea.description').html('');

        $('#block-table-type-prestation').addClass('hidden');
        $('#block-form-type-prestation').removeClass('hidden');
    });
    //click sur le bouton editer TP
    $('#table-type-prestation').on("click", ".edit", function () {

        var id;
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
            }
            i++;
        });

        $('#id_tp').val(id);
        $('#form-type-prestation input.nom').val(nom);
        $('#form-type-prestation textarea.description').html(description);

        $('#block-table-type-prestation').addClass('hidden');
        $('#block-form-type-prestation').removeClass('hidden');
    });
    //click sur le bouton supprimer TP
    $('#table-type-prestation').on("click", ".remove", function () {
        //console.log('ici');

        var id;

        var row = jQuery(this).closest('tr');

        var i = 0;
        row.find("td").each(function (cellIndex) {
            if (i === 0) {
                id = $(this).html();
            }
            i++;
        });

        swal({
            title: "Attention !",
            text: "Voulez-vous vraiment Supprimer cet élément ? ",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Oui",
            cancelButtonText: "Non",
            closeOnConfirm: false
        }, function () {

            $.ajax({
                'type': 'POST',
                'url': Routing.generate('delete_tp', { 'tp': id }),
                'dataType': 'JSON',
                'data': {
                },
                'success': function (result) {
                    if (result.data) {
                        swal("Réussi!", "Type de prestation supprimée avec succès", "success");

                        table_tp.ajax.reload();
                    } else {
                        swal("Erreur!", "Erreur lors de la suppression du type de prestation", "error");
                    }
                },
                'error': function () {
                    swal("Erreur!", "Erreur lors de la suppression du type de prestation", "error");
                },
                'beforeSend': function() {
                    $('.sweet-alert button.confirm').html("<i class=\"fa fa-spinner fa-spin\"></i> Oui");
                },
                'complete': function() {
                    $('.sweet-alert button.confirm').html("Oui");
                }
            });
        });
    });

    //click sur le bouton annuler TP
    $('#block-form-type-prestation .cancel').click(function () {
        $('#block-form-type-prestation').addClass('hidden');
        $('#block-table-type-prestation').removeClass('hidden');
    });

    //gestion des champs obligatoires sur le formulaire TP
    $("#form-type-prestation").validate({
        rules: {
            nom: "required"
        },
        messages: {
            nom: "Veuillez entrer un nom"
        }
    });

    //click sur le bouton Enregistrer TP
    $('#block-form-type-prestation .save').click(function () {

        if ($("#form-type-prestation").valid()) {
            var id = $('#id_tp').val();
            var url = "";
            var msg_reussite = "";
            var msg_echec = "";
            if (id) {
                //console.log('modification')
                url = Routing.generate('update_tp', { 'tp': id });
                msg_reussite = "Type de prestation modifiée avec succès";
                msg_echec = "Problème de modification du nouveau type de prestation";
            } else {
                //console.log('enregistrement')
                url = Routing.generate('add_tp');
                msg_reussite = "Type de prestation enregistrée avec succès";
                msg_echec = "Problème d'enregistrement du nouveau type de prestation";
            }

            $.ajax({
                'type': 'POST',
                'url': url,
                'dataType': 'JSON',
                'data': {
                    libelle: $('#nom_tp').val(),
                    description: $('#description_tp').val()
                },
                'success': function (result) {
                    if (result.data) {
                        $('#block-form-type-prestation').addClass('hidden');
                        $('#block-table-type-prestation').removeClass('hidden');
                        swal("Réussi!", msg_reussite, "success");

                        table_tp.ajax.reload();
                    } else {
                        swal("Erreur!", msg_echec, "error");
                    }
                },
                'error': function () {
                    swal("Erreur!", msg_echec, "error");
                },
                'beforeSend': function() {
                    $('#block-form-type-prestation .save i').removeClass('hidden');
                },
                'complete': function() {
                    $('#block-form-type-prestation .save i').addClass('hidden');
                }
            });
        }
    });

});
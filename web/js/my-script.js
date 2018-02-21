var table_tp;
var table_projet;

$(function () {

    if ($('.datepicker').length) {
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',//'DD MMMM YYYY',
            clearButton: true,
            weekStart: 1,
            time: false,
            lang: 'fr'
        });
    }

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
        ],
        "columnDefs": [
            {
                "targets": [0, 3],
                "class": "hide_me"
            }
        ]
    });

    //click sur le bouton nouveau TP
    $('#block-table-type-prestation .button-new').click(function () {

        $('#id_tp').val('');
        $('#form-type-prestation input.nom').val('');
        $('#form-type-prestation textarea.description').val('');

        $('#block-table-type-prestation').addClass('hidden');
        $('#block-form-type-prestation').removeClass('hidden');

        $('#block-form-type-prestation .header h2').html('Nouveau type de prestation');
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
        //console.log(description);
        $('#id_tp').val(id);
        $('#form-type-prestation input.nom').val(nom);
        $('#form-type-prestation textarea.description').val(description);

        $('#block-table-type-prestation').addClass('hidden');
        $('#block-form-type-prestation').removeClass('hidden');

        $('#block-form-type-prestation .header h2').html('Editer type de prestation');
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
                'beforeSend': function () {
                    $('.sweet-alert button.confirm').html("<i class=\"fa fa-spinner fa-spin\"></i> Oui");
                },
                'complete': function () {
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
                'beforeSend': function () {
                    $('#block-form-type-prestation .save i').removeClass('hidden');
                },
                'complete': function () {
                    $('#block-form-type-prestation .save i').addClass('hidden');
                }
            });
        }
    });


    // validate projet form
    $('#form-projet').validate({
        rules: {
            nom: "required",
            date_lanc: "required",
            date_attr: "required",
            cout: "required"
        },
        messages: {
            nom: "Veuillez entrer un nom",
            date_lanc: "Veuillez entrer la date de lancement du projet",
            date_attr: "Veuillez entrer la date d'attribution du projet",
            cout: "Veuillez entrer le coût du projet"
        }
    });

    //click sur le bouton nouveau projet
    $('#block-table-projet .button-new').click(function () {

        $('#id_projet').val('');
        $('#form-projet input.nom').val('');
        $('#form-projet input.date_lanc').val('');
        $('#form-projet input.date_attr').val('');
        $('#form-projet input.date_sign').val('');
        $('#form-projet input.date_dem').val('');
        $('#form-projet input.date_recep').val('');
        $('#form-projet input.cout').val('');
        $('#list_tp_projet').val('');

        $('#block-table-projet').addClass('hidden');
        $('#block-form-projet').removeClass('hidden');

        $.ajax({
            'type': 'POST',
            'url': Routing.generate('find_all_tps'),
            'dataType': 'JSON',
            'success': function (result) {

                var types_prestation = "";

                for (var key in result) {
                    var tp = result[key];
                    types_prestation += '<option value="' + tp.id + '">' + tp.libelle + '</option>';
                }

                $('#list_tp_projet').html(types_prestation);
                $('#list_tp_projet').selectpicker('refresh');
            },
            'error': function (xhr, status, error) {
                var err = eval(xhr.responseText);
                console.log(err);
                console.log(error);
            }
        });

        $('#block-form-projet .header h2').html('Nouveau projet');
    });

    //Annuler fenetre projet
    $('#form-projet .cancel').click(function () {
        $('#block-form-projet').addClass('hidden');
        $('#block-table-projet').removeClass('hidden');
    });

    //Enregistrer fenetre projet
    $('#form-projet .save').click(function () {
        if ($("#form-projet").valid()) {
            var id = $('#id_projet').val();
            var url = "";
            var msg_reussite = "";
            var msg_echec = "";
            if (id) {
                //console.log('modification')
                url = Routing.generate('update_projet', { 'projet': id });
                msg_reussite = "Projet modifié avec succes";
                msg_echec = "Problème de modification du nouveau projet";
            } else {
                //console.log('enregistrement')
                url = Routing.generate('add_projet');
                msg_reussite = "Nouveau projet enregistré avec succes";
                msg_echec = "Problème d'enregistrement du nouveau projet";
            }

            $.ajax({
                'type': 'POST',
                'url': url,
                'data': {
                    libelle: $('#form-projet input.nom').val(),
                    dateLancement: $('#form-projet input.date_lanc').val(),
                    dateAttribution: $('#form-projet input.date_attr').val(),
                    dateSignature: $('#form-projet input.date_sign').val(),
                    dateDemarrage: $('#form-projet input.date_dem').val(),
                    dateReception: $('#form-projet input.date_recep').val(),
                    dateArret: $('#form-projet input.date_arret').val(),
                    montant: $('#form-projet input.cout').val(),
                    tp: $('#list_tp_projet').val()
                },
                'dataType': 'JSON',
                'success': function (result) {
                    if (result.data) {
                        $('#block-form-projet').addClass('hidden');
                        $('#block-table-projet').removeClass('hidden');

                        swal("Réussi!", msg_reussite, "success");

                        table_projet.ajax.reload();
                    } else {
                        swal("Erreur!", msg_echec, "error");
                    }
                },
                'error': function () {
                    swal("Erreur!", "Veuillez contacter un administrateur", "error");
                },
                'beforeSend': function () {
                    $('#block-form-projet .save i').removeClass('hidden');
                },
                'complete': function () {
                    $('#block-form-projet .save i').addClass('hidden');
                }
            });
        }
    });

    //click sur le bouton editer projet
    $('#table-projet').on("click", ".edit", function () {
        var id;
        var nom;
        var date_lanc;
        var date_attr;
        var date_sign;
        var date_dem;
        var date_recep;
        var cout;
        var tp;

        var row = jQuery(this).closest('tr');

        var i = 0;
        row.find("td").each(function (cellIndex) {
            if (i === 0) {
                id = $(this).html();
            } else if (i === 1) {
                nom = $(this).html();
            } else if (i === 3) {
                cout = $(this).html();
            } else if (i === 4) {
                date_lanc = $(this).html();
            } else if (i === 5) {
                date_attr = $(this).html();
            } else if (i === 6) {
                date_sign = $(this).html();
            } else if (i === 7) {
                date_dem = $(this).html();
            } else if (i === 8) {
                date_recep = $(this).html();
            } else if (i === 13) {
                tp = $(this).html();
            }
            i++;
        });


        $('#id_projet').val(id);
        $('#form-projet input.nom').val(nom);
        $('#form-projet input.date_lanc').val(date_lanc);
        $('#form-projet input.date_attr').val(date_attr);
        $('#form-projet input.date_sign').val(date_sign);
        $('#form-projet input.date_dem').val(date_dem);
        $('#form-projet input.date_recep').val(date_recep);
        $('#form-projet input.cout').val(cout);

        $('#block-table-projet').addClass('hidden');
        $('#block-form-projet').removeClass('hidden');

        $.ajax({
            'type': 'POST',
            'url': Routing.generate('find_all_tps'),
            'dataType': 'JSON',
            'success': function (result) {

                var types_prestation = "";

                for (var key in result) {
                    var tp_obj = result[key];
                    types_prestation += '<option value="' + tp_obj.id + '">' + tp_obj.libelle + '</option>';
                }

                $('#list_tp_projet').html(types_prestation);
                $('#list_tp_projet').selectpicker('refresh');

                $('#list_tp_projet').val(tp);
                $('#list_tp_projet').selectpicker('refresh');
            },
            'error': function (xhr, status, error) {
                var err = eval(xhr.responseText);
                console.log(err);
                console.log(error);
            }
        });

        $('#block-form-projet .header h2').html('Editer le projet');
    });
    //Supprimer un projet
    $('#table-projet').on("click", ".remove", function () {
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
            text: "Voulez-vous vraiment Supprimer ce projet ? ",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Oui",
            cancelButtonText: "Non",
            closeOnConfirm: false
        }, function () {

            $.ajax({
                'type': 'POST',
                'url': Routing.generate('delete_projet', { 'projet': id }),
                'dataType': 'JSON',
                'data': {
                },
                'success': function (result) {
                    if (result.data) {
                        swal("Réussi!", "Projet supprimé avec succès", "success");

                        table_projet.ajax.reload();
                    } else {
                        swal("Erreur!", "Erreur lors de la suppression du projet", "error");
                    }
                },
                'error': function () {
                    swal("Erreur!", "Erreur, veuillez contacter l'administrateur", "error");
                },
                'beforeSend': function () {
                    $('.sweet-alert button.confirm').html("<i class=\"fa fa-spinner fa-spin\"></i> Oui");
                },
                'complete': function () {
                    $('.sweet-alert button.confirm').html("Oui");
                }
            });
        });
    });
    //ajouter une procédure au projet
    $('#table-projet').on("click", ".proc", function () {
        var id;
        var nom;
        var tp;

        var row = jQuery(this).closest('tr');

        var i = 0;
        row.find("td").each(function (cellIndex) {
            if (i === 0) {
                id = $(this).html();
            } else if (i === 1) {
                nom = $(this).html();
            } else if (i === 13) {
                tp = $(this).html();
            }
            i++;
        });

        $('#id_projet_form_proc').val(id);
        $('#form-add-proc-projet input.nom').val(nom);

        $.ajax({
            'type': 'POST',
            'url': Routing.generate('find_all_procs_by_tp', { 'tp': tp }),
            'dataType': 'JSON',
            'data': {
            },
            'success': function (result) {
                var procedures = "";

                for (var key in result) {
                    var proc_obj = result[key];
                    procedures += '<option value="' + proc_obj.id + '">' + proc_obj.libelle + '</option>';
                }
                $('#list_proc_tp_projet').html(procedures);
                $('#list_proc_tp_projet').selectpicker('refresh');
            }
        });

        $('#block-table-projet').addClass('hidden');
        $('#block-form-add-proc-projet').removeClass('hidden');
    });

    //chargement du datatable projet
    if ($('#table-projet').length) {
        table_projet = $('#table-projet').DataTable({
            "language": {
                "url": Routing.getBaseUrl() + "/plugins/jquery-datatable/i18n/French.json",
                buttons: {
                    copy: 'Copier',
                    print: 'Imprimer'
                }
            },
            "ajax": {
                "url": Routing.generate('list_datatable_projets'),
                "type": "POST"
            },
            dom: 'Bfrtip',
            responsive: true,
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "columnDefs": [
                {
                    "targets": [0, 2, 5, 6, 7, 8, 9, 10, 11, 12, 13, 15],
                    "class": "hide_me"
                }
            ]
        });
    }

});
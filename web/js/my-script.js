var table_tp;
var table_projet;
var table_acteurs;

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
            "url": "../plugins/jquery-datatable/i18n/French.json",
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
                'url': Routing.generate('delete_tp', {'tp': id}),
                'dataType': 'JSON',
                'data': {},
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
    //click sur le bouton ajouter procedure TP
    $('#table-type-prestation').on("click", ".add_proc", function () {

        var id;
        var nom;
        var liste_procs;

        var row = jQuery(this).closest('tr');
        var i = 0;
        row.find("td").each(function (cellIndex) {
            if (i === 0) {
                id = $(this).html();
            } else if (i === 1) {
                nom = $(this).html();
            } else if (i === 3) {
                liste_procs = JSON.parse($(this).html());
            }
            i++;
        });

        var liste_procedures = "";
        for (key in liste_procs) {
            var doc = liste_procs[key];
            liste_procedures += '<tr><td>' + doc.id + '</td><td>' + doc.libelle + '</td><td><a href="#" class="remove" title="Supprimer"><i class="fa fa-times fa-lg fa-red"></i></a></td></tr>';
        }
        $('#table-prestation-procedure tbody').html(liste_procedures);

        $('#modal-tp-proc h4').html('Liste des procédures [' + nom + ']');

        $('#modal-tp-proc').modal('show');

        $.ajax({
            'type': 'POST',
            'url': Routing.generate('find_all_procs'),
            'dataType': 'JSON',
            'success': function (result) {

                var procedures = "";
                //console.log(result);
                for (var key in result.data) {
                    var proc = result.data[key];
                    procedures += '<option value="' + proc.id + '">' + proc.libelle + '</option>';
                }

                $('#list_procs').html(procedures);
                $('#list_procs').selectpicker('refresh');

                //click sur ajout procedure
                $('#modal-tp-proc button.add').click(function () {
                    //console.log('entree');
                    var idtp = id;
                    var idproc = $('#list_procs').val();
                    var nomproc = $("#list_procs option:selected").text();

                    $.ajax({
                        'type': 'POST',
                        'url': Routing.generate('update_proc_add_tp', {'proc': idproc}),
                        'data': {
                            tp: idtp
                        },
                        'dataType': 'JSON',
                        'success': function (result) {
                            //
                            //console.log('oui');
                            if (result.data === true) {
                                $('#table-prestation-procedure tbody').append('<tr><td>' + idproc + '</td><td>' + nomproc + '</td><td><a href="#" class="remove" title="Supprimer"><i class="fa fa-times fa-lg fa-red"></i></a></td></tr>');
                            } else {
                                swal({
                                    title: "Erreur!",
                                    text: "Verifiez si la procedure n'existe pas deja dans le tableau",
                                    type: "error",
                                    timer: 3000
                                });
                            }
                        },
                        'error': function () {
                            //
                            //console.log('non');
                            swal({
                                title: "Erreur!",
                                text: "Verifiez si la procedure n'existe pas deja dans le tableau",
                                type: "error",
                                timer: 3000
                            });
                        },
                        'beforeSend': function () {
                            $('#modal-tp-proc button.add').prop('disabled', true);
                            $('#modal-tp-proc button.add i').removeClass('hidden');
                        },
                        'complete': function () {
                            $('#modal-tp-proc button.add').prop('disabled', false);
                            $('#modal-tp-proc button.add i').addClass('hidden');
                        }
                    });
                });

                //click sur le bouton du tableau document
                $('#table-prestation-procedure .remove').click(function () {
                    //console.log('ici');

                    var idtp = id;
                    var idproc;

                    var row = jQuery(this).closest('tr');

                    var i = 0;
                    row.find("td").each(function (cellIndex) {
                        if (i === 0) {
                            idproc = $(this).html();
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
                            'url': Routing.generate('update_proc_remove_tp', {'proc': idproc}),
                            'dataType': 'JSON',
                            'data': {
                                tp: idtp
                            },
                            'success': function (result) {
                                if (result.data === true) {
                                    swal.close();
                                    $(row).remove();
                                } else {
                                    swal("Erreur!", "Erreur", "error");
                                }
                            },
                            'error': function () {
                                swal("Erreur!", "Erreur", "error");
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
            },
            'error': function (xhr, status, error) {
                var err = eval(xhr.responseText);
                console.log(err);
                console.log(error);
            }
        });

    });

    //fermeture du modal d'ajout de la procedure au type de prestation
    $('#modal-tp-proc .close_btn').click(function () {
        table_tp.ajax.reload();
        $('#modal-tp-proc').modal('hide');
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
                url = Routing.generate('update_tp', {'tp': id});
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
            cout: "required",
            tp: "required"
        },
        messages: {
            nom: "Veuillez entrer un nom",
            date_lanc: "Veuillez entrer la date de lancement du projet",
            date_attr: "Veuillez entrer la date d'attribution du projet",
            cout: "Veuillez entrer le coût du projet",
            tp: "Veuillez sélectionner un type de prestation"
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
                url = Routing.generate('update_projet', {'projet': id});
                msg_reussite = "Projet modifié avec succes";
                msg_echec = "Problème de modification du projet";
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
                'url': Routing.generate('delete_projet', {'projet': id}),
                'dataType': 'JSON',
                'data': {},
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
            'url': Routing.generate('find_all_procs_by_tp', {'tp': tp}),
            'dataType': 'JSON',
            'success': function (result) {
                //console.log(result);
                var procedures = "";

                for (var key in result) {
                    var proc_obj = result[key];
                    procedures += '<option value="' + proc_obj.id + '">' + proc_obj.libelle + '</option>';
                }
                $('#list_proc_projet').html(procedures);
                $('#list_proc_projet').selectpicker('refresh');
            }
        });

        $('#block-table-projet').addClass('hidden');
        $('#block-form-add-proc-projet').removeClass('hidden');
    });

    //Annuler fenetre ajout de procédure au projet
    $('#form-add-proc-projet .cancel').click(function () {
        $('#block-form-add-proc-projet').addClass('hidden');
        $('#block-table-projet').removeClass('hidden');
    });
    //Enregistrer la fenetre d'ajout de procédure au projet
    $('#form-add-proc-projet .save').click(function () {

        var id = $('#id_projet_form_proc').val();

        var url = Routing.generate('update_projet_choose_proc', {'projet': id});
        var msg_reussite = "Ajout de procedure réussie";
        var msg_echec = "Problème de l'ajout de la procedure";

        $.ajax({
            'type': 'POST',
            'url': url,
            'dataType': 'JSON',
            'data': {
                proc: $('#list_proc_projet').val()
            },
            'success': function (result) {
                if (result.data) {
                    $('#block-form-add-proc-projet').addClass('hidden');
                    $('#block-table-projet').removeClass('hidden');
                    swal("Réussi!", msg_reussite, "success");

                    table_projet.ajax.reload();
                } else {
                    swal("Erreur!", msg_echec, "error");
                }
            },
            'error': function () {
                swal("Erreur!", msg_echec, "error");
            },
            'beforeSend': function () {
                $('#block-form-add-proc-projet .save i').removeClass('hidden');
            },
            'complete': function () {
                $('#block-form-add-proc-projet .save i').addClass('hidden');
            }
        });

    });

    //chargement du datatable projet
    if ($('#table-projet').length) {
        table_projet = $('#table-projet').DataTable({
            "language": {
                "url": "../plugins/jquery-datatable/i18n/French.json",
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


    //ouvrir la fenêtre de gestion des documents projets
    $('#table-projet').on("click", ".docs", function () {
        var id;
        var nom;

        var row = jQuery(this).closest('tr');

        var i = 0;
        row.find("td").each(function (cellIndex) {
            if (i === 0) {
                id = $(this).html();
            } else if (i === 1) {
                nom = $(this).html();
            }
            i++;
        });

        $('#block-gestion-docs h2 span').html(nom);
        $('#id_projet_pour_doc').val(id);

        $.ajax({
            type: "POST",
            url: Routing.generate('list_documents_projet', {'projet': id}),
            dataType: "JSON",
            success: function (result) {
                //console.log(result);
                var idproj_pour_doc = id;

                var str = "";
                for (key in result.data) {
                    str += '<tr>';
                    str += '<td>' + result.data[key][0] + '</td>';
                    str += '<td>' + result.data[key][1] + '</td>';
                    var statut = '<span class="label label-danger">en attente</span>';
                    if (result.data[key][8] != null) {
                        statut = '<span class="label label-success">Prêt</span>';
                    }
                    str += '<td>' + statut + '</td>';
                    str += '<td><a  type="button" class="btn btn-default waves-effect" href="../uploads/docs/' + result.data[key][2] + '" title="Enregistré le ' + result.data[key][5] + '" target="_blank"><i class="material-icons">file_download</i> <b>Télécharger</b></a></td>';
                    var doc_modif = "";
                    if (result.data[key][7] != null) {
                        doc_modif += '<a type="button" class="btn btn-default waves-effect" href="../uploads/docs/' + result.data[key][7] + '" target="_blank"><i class="material-icons">file_download</i> <b>Télécharger</b></a>';
                    }
                    var doc_signe = "";
                    if (result.data[key][8] != null) {
                        doc_signe += '<a type="button" class="btn btn-default waves-effect" href="../uploads/docs/' + result.data[key][8] + '" title="Signature enregistrée le ' + result.data[key][3] + '" target="_blank"><i class="material-icons">file_download</i> <b>Télécharger</b></a>';
                    }
                    var date_sign = "";
                    if (result.data[key][3] != null) {
                        date_sign += result.data[key][3];
                    }
                    str += '<td>' + doc_modif + '</td>';
                    str += '<td>' + doc_signe + '</td>';
                    str += '<td>' + date_sign + '</td>';
                    str += '<td>';
                    str += '<a href="' + Routing.getBaseUrl() + '/new/document/modifie/' + result.data[key][0] + '" type="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float edit" title="Charger le document modifié"><i class="material-icons">file_upload</i></a>';
                    str += '<span class="space-button2"></span>';
                    str += '<a href="' + Routing.getBaseUrl() + '/new/document/signe/' + result.data[key][0] + '" type="button" class="btn btn-success btn-circle waves-effect waves-circle waves-float remove" title="Charger le document signé"><i class="material-icons">file_upload</i></a>';
                    if (doc_signe != "") {
                        str += '<span class="space-button2"></span>';
                        str += '<a href="#" type="button" class="btn btn-default btn-circle waves-effect waves-circle waves-float send" title="Envoyer mail"><i class="material-icons">send</i></a>';
                    }
                    str += '</td></tr>';
                }
                $('#table-gestion-docs tbody').html(str);
            },
            error: function () {
                $('#table-gestion-docs tbody').html('');
            },
            beforeSend: function () {
                $('#table-gestion-docs tbody').html('<tr><td colspan="7">Chargement en cours...</td></tr>');
            }
        });

        $('#block-gestion-docs').removeClass('hidden');
        $('#block-table-projet').addClass('hidden');
    });
    //fermer la fenêtre de gestion des documents du projets
    $('#block-gestion-docs button.cancel').click(function () {
        $('#block-gestion-docs').addClass('hidden');
        $('#block-table-projet').removeClass('hidden');
    });


    //*************************************************
    // Gestion des administations
    //*************************************************

    //chargement du datatable administrations
    if ($('#table-administrations').length) {
        table_administrations = $('#table-administrations').DataTable({
            "language": {
                "url": "../plugins/jquery-datatable/i18n/French.json",
                buttons: {
                    copy: 'Copier',
                    print: 'Imprimer'
                }
            },
            "ajax": {
                "url": Routing.generate('list_administration'),
                "type": "POST"
            },
            dom: 'Bfrtip',
            responsive: true,
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "class": "hide_me"
                }
            ]
        });
    }

    //click sur le bouton nouvelle administration
    $('#block-table-administrations .button-new').click(function () {

        $('#id_administration').val('');
        $('#form-administration input.nom').val('');
        $('#form-administration input.email').val('');
        $('#form-administration input.telephone').val('');

        $('#block-table-administrations').addClass('hidden');
        $('#block-form-administrations').removeClass('hidden');

        $('#block-form-administrations .header h2').html('Nouvelle Administration');
    });

    //click sur le bouton editer projet
    $('#table-administrations').on("click", ".edit", function () {

        var row = jQuery(this).closest('tr');

        var i = 0;
        row.find("td").each(function (cellIndex) {
            if (i === 0) {
                $('#id_administration').val($(this).text());
            } else if (i === 1) {
                $('#form-administration input.nom').val($(this).text());
            } else if (i === 2) {
                $('#form-administration input.email').val($(this).text());
            } else if (i === 3) {
                $('#form-administration input.telephone').val($(this).text());
            }
            i++;
        });

        $('#block-table-administrations').addClass('hidden');
        $('#block-form-administrations').removeClass('hidden');

        $('#block-form-administrations .header h2').html('Modifier l\'administration');
    });

    //Supprimer une administration
    $('#table-administrations').on("click", ".remove", function () {
        var id;

        var row = jQuery(this).closest('tr');

        var i = 0;
        row.find("td").each(function (cellIndex) {
            if (i === 0) {
                id = $(this).text();
            }
            i++;
        });

        swal({
            title: "Attention !",
            text: "Voulez-vous vraiment Supprimer cette administration ? ",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Oui",
            cancelButtonText: "Non",
            closeOnConfirm: false
        }, function () {

            $.ajax({
                'type': 'POST',
                'url': Routing.generate('delete_contractant', {'contractant': id}),
                'dataType': 'JSON',
                'data': {},
                'success': function (result) {
                    if (result.data) {
                        swal("Réussi!", "Administration supprimée avec succès", "success");

                        table_administrations.ajax.reload();
                    } else {
                        swal("Erreur!", "Erreur lors de la suppression de l'administration", "error");
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

    //Annuler fenetre de nouvelle administration
    $('#form-administration .cancel').click(function () {
        $('#block-form-administrations').addClass('hidden');
        $('#block-table-administrations').removeClass('hidden');
    });

    //Enregistrer administration
    $('#form-administration .save').click(function () {
        if ($("#form-administration").valid()) {
            var id = $('#id_administration').val();
            var url = "";
            var msg_reussite = "";
            var msg_echec = "";
            if (id) {
                //console.log('modification')
                url = Routing.generate('update_contractant', {'contractant': id});
                msg_reussite = "Administration modifiée avec succès";
                msg_echec = "Problème de modification de l'administration";
            } else {
                //console.log('enregistrement')
                url = Routing.generate('add_contractant');
                msg_reussite = "Nouvelle administration enregistrée avec succès";
                msg_echec = "Problème d'enregistrement de l'administration";
            }

            $.ajax({
                'type': 'POST',
                'url': url,
                'data': {
                    nom: $('#form-administration input.nom').val(),
                    email: $('#form-administration input.email').val(),
                    tel: $('#form-administration input.telephone').val(),
                    type: 'administration',
                },
                'dataType': 'JSON',
                'success': function (result) {
                    if (result.data) {
                        $('#block-form-administrations').addClass('hidden');
                        $('#block-table-administrations').removeClass('hidden');

                        swal("Réussi!", msg_reussite, "success");

                        table_administrations.ajax.reload();
                    } else {
                        swal("Erreur!", msg_echec, "error");
                    }
                },
                'error': function () {
                    swal("Erreur!", "Veuillez contacter un administrateur", "error");
                },
                'beforeSend': function () {
                    $('#block-form-administrations .save i').removeClass('hidden');
                },
                'complete': function () {
                    $('#block-form-administrations .save i').addClass('hidden');
                }
            });
        }
    });

    //*************************************************
    // Fin de gestion des administations
    //*************************************************


    //*************************************************
    // Gestion des acteurs
    //*************************************************

    //chargement du datatable acteurs
    if ($('#table-acteurs').length) {
        table_acteurs = $('#table-acteurs').DataTable({
            "language": {
                "url": "../plugins/jquery-datatable/i18n/French.json",
                buttons: {
                    copy: 'Copier',
                    print: 'Imprimer'
                }
            },
            "ajax": {
                "url": Routing.generate('list_acteur'),
                "type": "POST"
            },
            dom: 'Bfrtip',
            responsive: true,
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "class": "hide_me"
                }
            ]
        });
    }

    //click sur le bouton nouvelle acteur
    $('#block-table-acteurs .button-new').click(function () {

        $('#id_acteur').val('');
        $('#form-acteur input.nom').val('');
        $('#form-acteur input.email').val('');
        $('#form-acteur input.telephone').val('');

        $('#block-table-acteurs').addClass('hidden');
        $('#block-form-acteurs').removeClass('hidden');

        $('#block-form-acteurs .header h2').html('Nouvel Acteur');
    });

    //click sur le bouton editer projet
    $('#table-acteurs').on("click", ".edit", function () {

        var row = jQuery(this).closest('tr');

        var i = 0;
        row.find("td").each(function (cellIndex) {
            if (i === 0) {
                $('#id_acteur').val($(this).text());
            } else if (i === 1) {
                $('#form-acteur input.nom').val($(this).text());
            } else if (i === 2) {
                $('#form-acteur input.email').val($(this).text());
            } else if (i === 3) {
                $('#form-acteur input.telephone').val($(this).text());
            }
            i++;
        });

        $('#block-table-acteurs').addClass('hidden');
        $('#block-form-acteurs').removeClass('hidden');

        $('#block-form-acteurs .header h2').html('Modifier l\'acteur');
    });

    //Supprimer une acteur
    $('#table-acteurs').on("click", ".remove", function () {
        var id;

        var row = jQuery(this).closest('tr');

        var i = 0;
        row.find("td").each(function (cellIndex) {
            if (i === 0) {
                id = $(this).text();
            }
            i++;
        });

        swal({
            title: "Attention !",
            text: "Voulez-vous vraiment Supprimer cet acteur ? ",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Oui",
            cancelButtonText: "Non",
            closeOnConfirm: false
        }, function () {

            $.ajax({
                'type': 'POST',
                'url': Routing.generate('delete_contractant', {'contractant': id}),
                'dataType': 'JSON',
                'data': {},
                'success': function (result) {
                    if (result.data) {
                        swal("Réussi!", "Acteur supprimé avec succès", "success");

                        table_acteurs.ajax.reload();
                    } else {
                        swal("Erreur!", "Erreur lors de la suppression de l'acteur", "error");
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

    //Annuler fenetre de nouvelle acteur
    $('#form-acteur .cancel').click(function () {
        $('#block-form-acteurs').addClass('hidden');
        $('#block-table-acteurs').removeClass('hidden');
    });

    //Enregistrer acteur
    $('#form-acteur .save').click(function () {
        if ($("#form-acteur").valid()) {
            var id = $('#id_acteur').val();
            var url = "";
            var msg_reussite = "";
            var msg_echec = "";
            if (id) {
                //console.log('modification')
                url = Routing.generate('update_contractant', {'contractant': id});
                msg_reussite = "Acteur modifié avec succès";
                msg_echec = "Problème de modification de l'acteur";
            } else {
                //console.log('enregistrement')
                url = Routing.generate('add_contractant');
                msg_reussite = "Nouvel acteur enregistré avec succès";
                msg_echec = "Problème d'enregistrement de l'acteur";
            }

            $.ajax({
                'type': 'POST',
                'url': url,
                'data': {
                    nom: $('#form-acteur input.nom').val(),
                    email: $('#form-acteur input.email').val(),
                    tel: $('#form-acteur input.telephone').val(),
                    type: 'acteur',
                },
                'dataType': 'JSON',
                'success': function (result) {
                    if (result.data) {
                        $('#block-form-acteurs').addClass('hidden');
                        $('#block-table-acteurs').removeClass('hidden');

                        swal("Réussi!", msg_reussite, "success");

                        table_acteurs.ajax.reload();
                    } else {
                        swal("Erreur!", msg_echec, "error");
                    }
                },
                'error': function () {
                    swal("Erreur!", "Veuillez contacter un administrateur", "error");
                },
                'beforeSend': function () {
                    $('#block-form-acteurs .save i').removeClass('hidden');
                },
                'complete': function () {
                    $('#block-form-acteurs .save i').addClass('hidden');
                }
            });
        }
    });

    //*************************************************
    // Fin de gestion des acteurs
    //*************************************************


    //*************************************************
    // Fin d'envoi des docs
    //*************************************************
    //
    $('#table-gestion-docs').on('click', '.send', function (event) {
        $('#modal-send-mail').modal('show');

        var doc = "";

        var row = jQuery(this).closest('tr');

        var i = 0;
        row.find("td").each(function (cellIndex) {
            if (i === 5) {
                doc = $(this).html();
            }
            i++;
        });
        console.log(doc);
        $('#form-send-mail liste_docs tbody').html("<tr><td>" + doc + "</td></tr>");

        $.ajax({
            url: Routing.generate('list_administration'),
            dataType: "JSON",
            success: function (return_datas) {

                var select_option = '';
                var rets = return_datas.data;
                for (ret in rets) {
                    select_option += '<option val="' + rets[ret][2] + '">' + rets[ret][1] + '</option>';
                }

                $('#list_administrations_send_mail').html(select_option);
                $('#list_administrations_send_mail').selectpicker('refresh');
            },
            error: function (err) {
                $('#list_administrations_send_mail').html('');
            }
        })
    });

    $('#modal-send-mail').on('click', 'button.close_btn', function (event) {
        $('#modal-send-mail').modal('hide');
    })

    $('#modal-send-mail').on('click', 'button.send', function (event) {
        window.open('mailto:user@example.com?subject=Transfert%20des%20docs&body=bien%20vouloir%20accuser%20reception%20de%20ces%20documents', '_blank');
    })

    //*************************************************
    // Fin de gestion d'envoi de docs
    //*************************************************

});
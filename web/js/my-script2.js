var table_proc;

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
        $('#form-terme-reference textarea.description').val('');

        $('#block-table-terme-reference').addClass('hidden');
        $('#block-form-terme-reference').removeClass('hidden');
    });


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
        $('#form-terme-reference textarea.description').val(description);

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
    
    //click sur le bouton supprimer TDR
    $('#table-terme-reference').on("click", ".remove", function () {
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
                'url': Routing.generate('delete_tdr', { 'tdr': id }),
                'dataType': 'JSON',
                'data': {
                },
                'success': function (result) {
                    if (result.data) {
                        swal("Réussi!", "Terme de référence supprimée avec succès", "success");

                        table_tdr.ajax.reload();
                    } else {
                        swal("Erreur!", "Erreur lors de la suppression du terme de référence", "error");
                    }
                },
                'error': function () {
                    swal("Erreur!", "Erreur lors de la suppression du terme de référence", "error");
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
    
    //click sur le bouton annuler TDR
    $('#block-form-terme-reference .cancel').click(function () {
        $('#block-form-terme-reference').addClass('hidden');
        $('#block-table-terme-reference').removeClass('hidden');
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
    
    
    
    //Exportation des procedures dans le tableau
    table_proc = $('#table-procedure').DataTable({
        "language": {
            "url": Routing.getBaseUrl() + "/plugins/jquery-datatable/i18n/French.json",
            buttons: {
                copy: 'Copier',
                print: 'Imprimer'
            }
        },
        "ajax": {
            "url": Routing.generate('list_datatable_procs'),
            "type": "POST"
        },
        dom: 'Bfrtip',
        responsive: true,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "columnDefs": [
            {
                "targets": [ 0, 3 ],
                "class": "hide_me"
            }
        ]
    });
    
    //click sur le bouton nouveau (procedure)
    $('#block-table-procedure .button-new').click(function () {

        $('#form-procedure input.nom').val('');
        $('#form-procedure textarea.description').html('');

        $('#block-table-procedure').addClass('hidden');
        $('#block-form-procedure').removeClass('hidden');
    });
    
    //click sur le bouton Enregistrer Procedure
    $('#block-form-procedure .save').click(function () {

        if ($("#form-procedure").valid()) {
            var id = $('#id_proc').val();
            var url = "";
            var msg_reussite = "";
            var msg_echec = "";
            if (id) {
                //console.log('modification')
                url = Routing.generate('update_proc', { 'proc': id });
                msg_reussite = "Procédure modifiée avec succès";
                msg_echec = "Problème de modification de la nouvelle procédure";
            } else {
                //console.log('enregistrement')
                url = Routing.generate('add_proc');
                msg_reussite = "Procédure enregistrée avec succès";
                msg_echec = "Problème d'enregistrement de la nouvelle procédure";
            }

            $.ajax({
                'type': 'POST',
                'url': url,
                'dataType': 'JSON',
                'data': {
                    libelle: $('#nom_proc').val(),
                    description: $('#description_proc').val()
                },
                'success': function (result) {
                    if (result.data) {
                        $('#block-form-procedure').addClass('hidden');
                        $('#block-table-procedure').removeClass('hidden');
                        swal("Réussi!", msg_reussite, "success");

                        table_proc.ajax.reload();
                    } else {
                        swal("Erreur!", msg_echec, "error");
                    }
                },
                'error': function () {
                    swal("Erreur!", msg_echec, "error");
                },
                'beforeSend': function() {
                    $('#block-form-procedure .save i').removeClass('hidden');
                },
                'complete': function() {
                    $('#block-form-procedure .save i').addClass('hidden');
                }
            });
        }
    });
    
    //click sur le bouton annuler TDR
    $('#block-form-procedure .cancel').click(function () {
        $('#block-form-procedure').addClass('hidden');
        $('#block-table-procedure').removeClass('hidden');
    });
    
    //click sur le bouton editer proc
    $('#table-procedure').on("click", ".edit", function () {

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

        $('#id_proc').val(id);
        $('#form-procedure input.nom').val(nom);
        $('#form-procedure textarea.description').html(description);

        $('#block-table-procedure').addClass('hidden');
        $('#block-form-procedure').removeClass('hidden');
    });
    //click sur le bouton supprimer proc
    $('#table-procedure').on("click", ".remove", function () {
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
                'url': Routing.generate('delete_proc', { 'proc': id }),
                'dataType': 'JSON',
                'data': {
                },
                'success': function (result) {
                    if (result.data) {
                        swal("Réussi!", "Procédure supprimée avec succès", "success");

                        table_proc.ajax.reload();
                    } else {
                        swal("Erreur!", "Erreur lors de la suppression de la procédure", "error");
                    }
                },
                'error': function () {
                    swal("Erreur!", "Erreur lors de la suppression de la procédure", "error");
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
    //click sur le bouton list documents proc
    $('#table-procedure').on("click", ".add_doc", function () {
        
        var id;
        var nom;
        var liste_docs;

        var row = jQuery(this).closest('tr');
        var i = 0;
        row.find("td").each(function (cellIndex) {
            if (i === 0) {
                id = $(this).html();
            } else if  (i === 1) {
                nom = $(this).html();
            } else if(i === 3) {
                liste_docs = JSON.parse($(this).html());
            }
            i++;
        });

        var liste_documents = "";
        for(key in liste_docs) {
            var doc = liste_docs[key];
            liste_documents += '<tr><td>'+doc.id+'</td><td>'+doc.libelle+'</td><td><a href="#" class="remove" title="Supprimer"><i class="fa fa-times fa-lg fa-red"></i></a></td></tr>';
        }
        $('#table-procedure-documents tbody').html(liste_documents);

        $('#modal-procedure-doc h4').html('Liste des documents ['+nom+']');

        $('#modal-procedure-doc').modal('show');

        $.ajax({
            'type': 'POST',
            'url': Routing.generate('find_all_dags'),
            'dataType': 'JSON',
            'success': function(result) {

                var documents = "";
                
                for (var key in result.data) {
                    var doc = result.data[key];
                    documents += '<option value="' + doc.id + '">' + doc.libelle + '</option>';
                }
                
                $('#list_docs').html(documents);
                $('#list_docs').selectpicker('refresh');

                //click sur ajout document
                $('#modal-procedure-doc .add').click( function() {
                    
                    var idproc = id;
                    var iddag = $('#list_docs').val();
                    var nomdag = $("#list_docs option:selected" ).text();
                    //console.log(idproc);
                    //console.log(iddag);
                    $.ajax({
                        'type': 'POST',
                        'url': Routing.generate('update_proc_add_dag', { 'proc': idproc }),
                        'data': {
                            dag: iddag
                        },
                        'dataType': 'JSON',
                        'success': function(result) {
                            //
                            if (result.data === true) {
                                $('#table-procedure-documents tbody').append('<tr><td>'+iddag+'</td><td>'+nomdag+'</td><td><a href="#" class="remove" title="Supprimer"><i class="fa fa-times fa-lg fa-red"></i></a></td></tr>');
                            } else {
                                swal({
                                    title: "Erreur!",
                                    text: "Verifiez si le document n'existe pas deja dans le tableau",
                                    type: "error",
                                    timer: 3000
                                });
                            }
                        },
                        'error': function() {
                            //
                            swal({
                                title: "Erreur!",
                                text: "Verifiez si le document n'existe pas deja dans le tableau",
                                type: "error",
                                timer: 3000
                             });
                        },
                        'beforeSend': function() {
                            $('#modal-procedure-doc button.add i').removeClass('hidden');
                        },
                        'complete': function() {
                            $('#modal-procedure-doc button.add i').addClass('hidden');
                        }
                    });
                });
    
                //click sur le bouton du tableau document
                $('#table-procedure-documents .remove').click( function () {
                    //console.log('ici');
            
                    var iddag;
                    var idproc = id;
            
                    var row = jQuery(this).closest('tr');
            
                    var i = 0;
                    row.find("td").each(function (cellIndex) {
                        if (i === 0) {
                            iddag = $(this).html();
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
                            'url': Routing.generate('update_proc_remove_dag', { 'proc': idproc }),
                            'dataType': 'JSON',
                            'data': {
                                dag: iddag
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
                            'beforeSend': function() {
                                $('.sweet-alert button.confirm').html("<i class=\"fa fa-spinner fa-spin\"></i> Oui");
                            },
                            'complete': function() {
                                $('.sweet-alert button.confirm').html("Oui");
                            }
                        });
                    });
                });
            },
            'error': function(xhr, status, error) {
                var err = eval(xhr.responseText );
                console.log(err);
                console.log(error);
            }
        });

    });
    //fermeture du modal d'ajout de documents a la procedure
    $('#modal-procedure-doc .close_btn').click( function() {
        table_proc.ajax.reload();
    });

    
    
    //Exportation des DAG dans le tableau
    $('#table-document-genere').DataTable({
        "language": {
            "url": Routing.getBaseUrl() + "/plugins/jquery-datatable/i18n/French.json",
            buttons: {
                copy: 'Copier',
                print: 'Imprimer'
            }
        },
        "ajax": {
            "url": Routing.generate('list_datatable_dags'),
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
    
    //click sur le bouton nouveau DAG
    $('#block-document-genere .button-new').click(function () {

        $('#form-document-genere input.nom').val('');
        $('#form-document-genere textarea.description').html('');
        $('#form-document-genere input.delais').val('');
        $('#form-document-genere input.statut').val('');

        $('#block-table-document-genere').addClass('hidden');
        $('#block-form-document-genere').removeClass('hidden');
    });
    
    //click sur le bouton annuler DAG
    $('#block-form-document-genere .cancel').click(function () {
        $('#block-form-document-genere').addClass('hidden');
        $('#block-table-document-genere').removeClass('hidden');
    });
    

});

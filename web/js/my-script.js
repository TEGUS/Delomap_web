
$(function () {
    //Exportable table
    $('#table-type-prestation').DataTable({
        "language": {
            "url": Routing.getBaseUrl() + "/plugins/jquery-datatable/i18n/French.json",
            buttons: {
                copy: 'Copier',
                print: 'Imprimer'
            }
        },
        processing: true,
        serverSide: true,
        "ajax": {
         "url": Routing.generate('list_tps'),
         "type": "GET"
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
    $('#table-type-prestation .edit').click(function () {

        var nom;
        var description;

        var row = jQuery(this).closest('tr');

        var i = 0;
        row.find("td").each(function (cellIndex) {
            if (i == 1) {
                nom = $(this).html();
            } else if (i == 2) {
                description = $(this).html();
            }
            i++;
        });

        $('#form-type-prestation input.nom').val(nom);
        $('#form-type-prestation textarea.description').html(description);

        $('#block-table-type-prestation').addClass('hidden');
        $('#block-form-type-prestation').removeClass('hidden');
    });
    //click sur le bouton supprimer TP
    $('#table-type-prestation .remove').click(function () {
        console.log('ici');
        swal({
            title: "Attention !",
            text: "Voulez-vous vraiment Supprimer cet élément ? ",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Oui",
            closeOnConfirm: false
        }, function () {

            swal("Réussi!", "Type de prestation supprimée avec succès", "success");
        });
    });

    //click sur le bouton annuler TP
    $('#block-form-type-prestation .cancel').click(function () {
        $('#block-form-type-prestation').addClass('hidden');
        $('#block-table-type-prestation').removeClass('hidden');
    });

});
$(function (){
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
    $('#select-tdr').searchableOptionList();
    
     //click sur le bouton editer TP
    $('#table-terme-reference').on("click", ".edit", function () {
        
        console.log('bonjour le code');

        var id;
        var select;
        var nom;
        var description;

        var row = jQuery(this).closest('tr');

        var i = 0;
        row.find("td").each(function (cellIndex) {
            if (i === 0) {
                id = $(this).html();
            } else if (i === 1) {
                select = $(this).html();
            } else if (i === 2) {
                nom = $(this).html();
            }
            else if (i === 3) {
                description = $(this).html();
            }
            i++;
        });

        $('#id_tdr').val(id);
        $('#form-terme-reference select.select').val(select);
        $('#form-terme-reference input.nom').val(nom);
        $('#form-terme-reference textarea.description').html(description);

        $('#block-table-terme-reference').addClass('hidden');
        $('#block-form-terme-reference').removeClass('hidden');
    });
    
});



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
    
});



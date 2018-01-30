/* 
 * Copyright 2017 Oscar.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

$(document).ready(function () {
    var tblAlumnes = initDatatables();
    
    tblAlumnes.on( 'select deselect', function () {
        var selectedRows = tblAlumnes.rows( { selected: true } ).count();
        
        enableButtons(tblAlumnes, (selectedRows === 1));
    });
    
    tblAlumnes.on('page', function () {
        enableButtons(tblAlumnes, false);
    });    
});

function enableButtons(table, enabled){   
    table.button(1).enable( enabled );
    table.button(2).enable( enabled );
}

function initDatatables() {
    return $('#alumnes').DataTable({
        language: {"url": "plugins/dataTables/Datatable.Catalan.json"},
        processing: true,
        serverSide: true,
        ajax: "?page=alumnesList",
        select: true,
        "columnDefs": [
            {
            "targets": [0],
            "visible": false,
            "searchable": false
            }],
        aaSorting: [[1,"asc"]],
        dom: "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>" +
                "<'row'<'col-sm-12'B>>",
        buttons: [
            {
                text: '<i class="fa fa-plus" ></i>',
                enabled: true,
                action: function ( e, dt, node, config ) {
                    var uri = "?page=alumnesEdit";
                    window.location.href = uri;
                }
            },
            {
                text: '<i class="fa fa-edit" ></i>',
                enabled: false,
                action: function ( e, dt, node, config ) {
                    var data = dt.row( { selected: true } ).data();
                    var uri = "?page=alumnesEdit&id=" + data[0];
                    window.location.href = uri;
                }
            },
            {
                text: '<i class="fa fa-trash" ></i>',
                enabled: false,
                action: function ( e, dt, node, config ) {
                    var data = dt.row( { selected: true } ).data();
                    var nomAlumne = "";
                    if (data[1] !== "") { 
                        nomAlumne = data[1]; 
                    }
                    if (data[2] !== "") { 
                        if (nomAlumne !== "") { nomAlumne = nomAlumne + " " }
                        nomAlumne = nomAlumne + data[2]; 
                    }
                    if (data[3] !== "") { 
                        if (nomAlumne !== "") { nomAlumne = nomAlumne + ", " }
                        nomAlumne = nomAlumne + data[3]; 
                    }
                    showModal({
                        title: "Confirma l'acció",
                        body: "Estàs segur que vols esborrar l'alumne " + nomAlumne + "?",
                        actions: [{
                            label: 'Cancel·lar',
                            cssClass: 'btn-success',
                            onClick: function(e){
                                $(e.target).parents('.modal').modal('hide');
                            }
                        },{
                            label: 'Esborrar',
                            cssClass: 'btn-danger',
                            onClick: function(e){
                                var uri = "?page=alumnesDelete&id=" + data[0];
                                $(e.target).parents('.modal').modal('hide');
                                $.post(uri, function() {
                                    dt.draw(false); //use false to stay at the same page
                                    enableButtons(dt, false);
                                })
                                  .fail(function() {
                                    alert( "error" );
                                  })
                                  .always(function() {
                                    $(e.target).parents('.modal').modal('hide');
                                  });
                            }
                        }]
                    });
                }
            },
            {
               extend:  'excelHtml5',
               text:    '<i class="fa fa-file-excel-o"></i>'
            },
            {
               extend:  'pdfHtml5',
               text:    '<i class="fa fa-file-pdf-o"></i>'
            }
        ]
    });
}
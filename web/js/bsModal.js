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

function showModal(options){
    
    var options = $.extend({
        title : '',
        body : '',
        remote : false,
        backdrop : 'static',
        size : false,
        onShow : false,
        onHide : false,
        actions : false,
        autoHide : 0 
    }, options);
    
    self.onShow = typeof options.onShow == 'function' ? options.onShow : function () {};
    self.onHide = typeof options.onHide == 'function' ? options.onHide : function () {};

    if (self.$modal == undefined) {
        self.$modal = $('<div class="modal fade"><div class="modal-dialog"><div class="modal-content"></div></div></div>').appendTo('body');
        self.$modal.on('shown.bs.modal', function (e) {
            self.onShow.call(this, e);
        });
        self.$modal.on('hidden.bs.modal', function (e) {
            self.onHide.call(this, e);
        });
    }

    var modalClass = {
        small : "modal-sm",
        large : "modal-lg"
    };

    self.$modal.data('bs.modal', false);
    self.$modal.find('.modal-dialog').removeClass().addClass('modal-dialog ' + (modalClass[options.size] || ''));
    self.$modal.find('.modal-content').html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button><h4 class="modal-title">${title}</h4></div><div class="modal-body">${body}</div><div class="modal-footer"></div>'.replace('${title}', options.title).replace('${body}', options.body));

    var footer = self.$modal.find('.modal-footer');
    //$('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>').appendTo(footer);
    if (Object.prototype.toString.call(options.actions) == "[object Array]") {
        for (var i = 0, l = options.actions.length; i < l; i++) {
            options.actions[i].onClick = typeof options.actions[i].onClick == 'function' ? options.actions[i].onClick : function () {};
            $('<button type="button" class="btn ' + (options.actions[i].cssClass || '') + '">' + 
                    (options.actions[i].label || '{Label Missing!}') + '</button>').appendTo(footer).on('click', options.actions[i].onClick);
        }
    } else {
        $('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>').appendTo(footer);
    }    

    self.$modal.modal({backdrop: 'static'});
    if (options.autoHide > 0) {
        setTimeout(function() {$('.modal').modal('hide');}, options.autoHide);
    }
}

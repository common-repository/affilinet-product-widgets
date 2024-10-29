if (typeof(tinymce) != 'undefined') {

    if (tinymce.majorVersion == 4) {
        (function () {
            tinymce.PluginManager.add('affilinet_product_widgets_mce_button', function (editor, url) {
                function _show_image(co) {
                    return co.replace(/\[affilinet_widget([^\]]*)\]/g, function (a, b) {
                        var image = b.split('=');
                        return '<img src="'+ affilinet_product_widgets_mce_variables.image_path + 'dummy.png" class="affilinet_widget" title="affilinet Widget" data-id="affilinet_widget' + tinymce.DOM.encode(b) + '" />';
                    });
                }

                function _remove_image(co) {
                    function getAttr(s, n) {
                        n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
                        return n ? tinymce.DOM.decode(n[1]) : '';
                    }

                    return co.replace(/(?:<p[^>]*>)*(<img[^>]+>)(?:<\/p>)*/g, function (a, im) {
                        var cls = getAttr(im, 'class');

                        if (cls.indexOf('affilinet_widget') != -1)
                            return '<p>[' + tinymce.trim(getAttr(im, 'data-id')) + ']</p>';

                        return a;
                    });
                }

                editor.on('mousedown', function (ed, o) {


                    if (ed.target.className === 'affilinet_widget') {
                        this.preventDefault();
                        this.stopPropagation();
                        this.stopImmediatePropagation();

                        var selected = ed.target.getAttribute('data-id').replace('affilinet_widget id=', '');

                        var values = JSON.parse(JSON.stringify(affilinet_product_widgets_mce_variables.widgets));


                        var selectedText = '';
                        values.forEach(function(val) {
                            console.log(val, selected);
                            if (val.value.toString() === selected.toString()) {
                                selectedText = val.text;
                            }
                        });

                        editor.windowManager.open({
                            title: affilinet_product_widgets_mce_variables.choose_widget,
                            body: [
                                {
                                    type: 'listbox',
                                    name: 'id',
                                    label: 'Widget',
                                    values: values,
                                    value: selected,
                                    text: selectedText,
                                }
                            ],

                            onsubmit: function (e) {
                                // Insert content when the window form is submitted
                                editor.selection.select(ed.target);
                                editor.selection.setContent('[affilinet_widget id=' + e.data.id + ']');
                                ed.stopImmediatePropagation();
                                ed.stopPropagation();
                                ed.preventDefault();
                                console.log('stoped')
                                this.hide()

                                return false
                            }

                        });
                        return true;
                    }
                });


                editor.on('BeforeSetcontent', function (event) {
                    event.content = _show_image(event.content);
                });


                //replace shortcode as its inserted into editor (which uses the exec command)
                editor.on('ExecCommand', function (event) {
                    if (event.command === 'mceInsertContent') {
                        tinyMCE.activeEditor.setContent(_show_image(tinyMCE.activeEditor.getContent()));
                    }
                });


                //replace the image back to shortcode on save
                editor.on('PostProcess', function (event, o) {
                    event.content = _remove_image(event.content);
                });

                // clone the object
                var menu = JSON.parse(JSON.stringify(affilinet_product_widgets_mce_variables.widgets));

                menu.forEach(function(elem){
                    elem.onclick =  function(){
                        editor.insertContent('[affilinet_widget id=' + elem.value.toString() + ']');
                    }
                });



                console.log(menu);
                editor.addButton('affilinet_product_widgets_mce_button', {
                    icon: true,
                    image: affilinet_product_widgets_mce_variables.image_path  + 'affilinet_icon.png',
                    type: 'menubutton',
                    text: 'affilinet Widgets',
                    menu: menu
                });

            });
        })();
    }
    else if (tinymce.majorVersion == 3) {


        // no support for tiny mce 3


    }
}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/lib/wp/toastr.min.css">
    <link rel="stylesheet" href="/assets/lib/wp/grapes.min.css">
    <link rel="stylesheet" href="/assets/lib/wp/grapesjs-preset-webpage.min.css">
    <link rel="stylesheet" href="/assets/lib/wp/tooltip.css">
    <link rel="stylesheet" href="/assets/lib/wp/demos.css">
    <link href="/assets/lib/wp/grapick.min.css" rel="stylesheet">

    <script src="/assets/lib/wp/jquery.min.js"></script>
    <script src="/assets/lib/wp/toastr.min.js"></script>
    <script src="/assets/lib/wp/grapes.min.js"></script>

    <script src="/assets/lib/wp/grapesjs-preset-webpage.js"></script>

    <script src="/assets/lib/wp/grapesjs-blocks-basic.js"></script>
    <script src="/assets/lib/wp/grapesjs-plugin-forms.js"></script>
    <script src="/assets/lib/wp/grapesjs-component-countdown.js"></script>
    <script src="/assets/lib/wp/grapesjs-plugin-export.js"></script>
    <script src="/assets/lib/wp/grapesjs-tabs.js"></script>
    <script src="/assets/lib/wp/grapesjs-custom-code.js"></script>
    <script src="/assets/lib/wp/grapesjs-touch.js"></script>
    <script src="/assets/lib/wp/grapesjs-parser-postcss.js"></script>
    <script src="/assets/lib/wp/grapesjs-tooltip.js"></script>
    <script src="/assets/lib/wp/grapesjs-tui-image-editor.js"></script>
    <script src="/assets/lib/wp/grapesjs-typed.js"></script>
    <script src="/assets/lib/wp/grapesjs-style-bg.js"></script>
    <script src="<?= asset('js/jquery-3.3.1.min.js') ?>"></script>
    <title>Editing</title>
</head>

<body>
    <style>
        .ad-cont {
            display: none;
        }

        .gjs-selected {
            overflow: hidden;
        }

        .save_btn,
        .save_btn_edit {
            background-color: #f71;
            padding: 0.2rem 0.5rem;
            border-radius: 2px;
        }

        #gjs iframe {
            height: 100vh;
        }
    </style>
    <div style="display: none">
        <div class="gjs-logo-cont">
            <a href="#" style="color: #fff !important; text-decoration: none; font-size: 14px">Nocode Editor</a>
            <a href="" style="color: #fff !important; text-decoration: none; font-size: 14px" class="save_btn_edit">Salvar</a>
            <div class="gjs-logo-version" style="display: none"></div>
        </div>
    </div>

    <div id="editor">
        <iframe id="uniqiframe" src="<?= __template($red) ?>" frameborder="0"></iframe>
    </div>


</body>

</html>

<div class="rotas" style="display: none">
    <input type="hidden" id="rota-salvar-edicoes" value="<?= route('salvar_edit') ?>">
    <input type="hidden" id="id_template" value=<?= $template->template_id ?>>
    <input type="hidden" id="__dominio" value=<?= $dominio ?>>
</div>

<script type="text/javascript">
    var images = [];



    var editor = grapesjs.init({
        height: '100%',
        container: '#editor',
        fromElement: true,
        showOffsets: true,
        storageManager: false,
        assetManager: {
            embedAsBase64: true,
            assets: images
        },
        selectorManager: {
            componentFirst: true
        },
        styleManager: {
            sectors: [{
                name: 'Geral',
                properties: [{
                        extend: 'float',
                        type: 'radio',
                        default: 'none',
                        options: [{
                                value: 'none',
                                className: 'fa fa-times'
                            },
                            {
                                value: 'left',
                                className: 'fa fa-align-left'
                            },
                            {
                                value: 'right',
                                className: 'fa fa-align-right'
                            }
                        ],
                    },
                    'display',
                    {
                        extend: 'position',
                        type: 'select'
                    },
                    'top',
                    'right',
                    'left',
                    'bottom',
                ],
            }, {
                name: 'Dimension',
                open: false,
                properties: [
                    'width',
                    {
                        id: 'flex-width',
                        type: 'integer',
                        name: 'Width',
                        units: ['px', '%'],
                        property: 'flex-basis',
                        toRequire: 1,
                    },
                    'height',
                    'max-width',
                    'min-height',
                    'margin',
                    'padding'
                ],
            }, {
                name: 'Typography',
                open: false,
                properties: [
                    'font-family',
                    'font-size',
                    'font-weight',
                    'letter-spacing',
                    'color',
                    'line-height',
                    {
                        extend: 'text-align',
                        options: [{
                                id: 'left',
                                label: 'Left',
                                className: 'fa fa-align-left'
                            },
                            {
                                id: 'center',
                                label: 'Center',
                                className: 'fa fa-align-center'
                            },
                            {
                                id: 'right',
                                label: 'Right',
                                className: 'fa fa-align-right'
                            },
                            {
                                id: 'justify',
                                label: 'Justify',
                                className: 'fa fa-align-justify'
                            }
                        ],
                    },
                    {
                        property: 'text-decoration',
                        type: 'radio',
                        default: 'none',
                        options: [{
                                id: 'none',
                                label: 'None',
                                className: 'fa fa-times'
                            },
                            {
                                id: 'underline',
                                label: 'underline',
                                className: 'fa fa-underline'
                            },
                            {
                                id: 'line-through',
                                label: 'Line-through',
                                className: 'fa fa-strikethrough'
                            }
                        ],
                    },
                    'text-shadow'
                ],
            }, {
                name: 'Decorations',
                open: false,
                properties: [
                    'opacity',
                    'border-radius',
                    'border',
                    'box-shadow',
                    'background', // { id: 'background-bg', property: 'background', type: 'bg' }
                ],
            }, {
                name: 'Extra',
                open: false,
                buildProps: [
                    'transition',
                    'perspective',
                    'transform'
                ],
            }, {
                name: 'Flex',
                open: false,
                properties: [{
                    name: 'Flex Container',
                    property: 'display',
                    type: 'select',
                    defaults: 'block',
                    list: [{
                            value: 'block',
                            name: 'Disable'
                        },
                        {
                            value: 'flex',
                            name: 'Enable'
                        }
                    ],
                }, {
                    name: 'Flex Parent',
                    property: 'label-parent-flex',
                    type: 'integer',
                }, {
                    name: 'Direction',
                    property: 'flex-direction',
                    type: 'radio',
                    defaults: 'row',
                    list: [{
                        value: 'row',
                        name: 'Row',
                        className: 'icons-flex icon-dir-row',
                        title: 'Row',
                    }, {
                        value: 'row-reverse',
                        name: 'Row reverse',
                        className: 'icons-flex icon-dir-row-rev',
                        title: 'Row reverse',
                    }, {
                        value: 'column',
                        name: 'Column',
                        title: 'Column',
                        className: 'icons-flex icon-dir-col',
                    }, {
                        value: 'column-reverse',
                        name: 'Column reverse',
                        title: 'Column reverse',
                        className: 'icons-flex icon-dir-col-rev',
                    }],
                }, {
                    name: 'Justify',
                    property: 'justify-content',
                    type: 'radio',
                    defaults: 'flex-start',
                    list: [{
                        value: 'flex-start',
                        className: 'icons-flex icon-just-start',
                        title: 'Start',
                    }, {
                        value: 'flex-end',
                        title: 'End',
                        className: 'icons-flex icon-just-end',
                    }, {
                        value: 'space-between',
                        title: 'Space between',
                        className: 'icons-flex icon-just-sp-bet',
                    }, {
                        value: 'space-around',
                        title: 'Space around',
                        className: 'icons-flex icon-just-sp-ar',
                    }, {
                        value: 'center',
                        title: 'Center',
                        className: 'icons-flex icon-just-sp-cent',
                    }],
                }, {
                    name: 'Align',
                    property: 'align-items',
                    type: 'radio',
                    defaults: 'center',
                    list: [{
                        value: 'flex-start',
                        title: 'Start',
                        className: 'icons-flex icon-al-start',
                    }, {
                        value: 'flex-end',
                        title: 'End',
                        className: 'icons-flex icon-al-end',
                    }, {
                        value: 'stretch',
                        title: 'Stretch',
                        className: 'icons-flex icon-al-str',
                    }, {
                        value: 'center',
                        title: 'Center',
                        className: 'icons-flex icon-al-center',
                    }],
                }, {
                    name: 'Flex Children',
                    property: 'label-parent-flex',
                    type: 'integer',
                }, {
                    name: 'Order',
                    property: 'order',
                    type: 'integer',
                    defaults: 0,
                    min: 0
                }, {
                    name: 'Flex',
                    property: 'flex',
                    type: 'composite',
                    properties: [{
                        name: 'Grow',
                        property: 'flex-grow',
                        type: 'integer',
                        defaults: 0,
                        min: 0
                    }, {
                        name: 'Shrink',
                        property: 'flex-shrink',
                        type: 'integer',
                        defaults: 0,
                        min: 0
                    }, {
                        name: 'Basis',
                        property: 'flex-basis',
                        type: 'integer',
                        units: ['px', '%', ''],
                        unit: '',
                        defaults: 'auto',
                    }],
                }, {
                    name: 'Align',
                    property: 'align-self',
                    type: 'radio',
                    defaults: 'auto',
                    list: [{
                        value: 'auto',
                        name: 'Auto',
                    }, {
                        value: 'flex-start',
                        title: 'Start',
                        className: 'icons-flex icon-al-start',
                    }, {
                        value: 'flex-end',
                        title: 'End',
                        className: 'icons-flex icon-al-end',
                    }, {
                        value: 'stretch',
                        title: 'Stretch',
                        className: 'icons-flex icon-al-str',
                    }, {
                        value: 'center',
                        title: 'Center',
                        className: 'icons-flex icon-al-center',
                    }],
                }]
            }],
        },
        plugins: [
            'gjs-blocks-basic',
            'grapesjs-plugin-forms',
            'grapesjs-component-countdown',
            'grapesjs-plugin-export',
            'grapesjs-tabs',
            'grapesjs-custom-code',
            'grapesjs-touch',
            'grapesjs-parser-postcss',
            'grapesjs-tooltip',
            'grapesjs-tui-image-editor',
            'grapesjs-typed',
            'grapesjs-style-bg',
            'grapesjs-preset-webpage',
        ],
        pluginsOpts: {
            'gjs-blocks-basic': {
                flexGrid: true
            },
            'grapesjs-tui-image-editor': {
                script: [
                    // 'https://cdnjs.cloudflare.com/ajax/libs/fabric.js/1.6.7/fabric.min.js',
                    'https://uicdn.toast.com/tui.code-snippet/v1.5.2/tui-code-snippet.min.js',
                    'https://uicdn.toast.com/tui-color-picker/v2.2.7/tui-color-picker.min.js',
                    'https://uicdn.toast.com/tui-image-editor/v3.15.2/tui-image-editor.min.js'
                ],
                style: [
                    'https://uicdn.toast.com/tui-color-picker/v2.2.7/tui-color-picker.min.css',
                    'https://uicdn.toast.com/tui-image-editor/v3.15.2/tui-image-editor.min.css',
                ],
            },
            'grapesjs-tabs': {
                tabsBlock: {
                    category: 'Extra'
                }
            },
            'grapesjs-typed': {
                block: {
                    category: 'Extra',
                    content: {
                        type: 'typed',
                        'type-speed': 40,
                        strings: [
                            'Text row one',
                            'Text row two',
                            'Text row three',
                        ],
                    }
                }
            },
            'grapesjs-preset-webpage': {
                modalImportTitle: 'Import Template',
                modalImportLabel: '<div style="margin-bottom: 10px; font-size: 13px;">Paste here your HTML/CSS and click Import</div>',
                modalImportContent: function(editor) {
                    return editor.getHtml() + '<style>' + editor.getCss() + '</style>'
                },
            },
        },
    });

    editor.I18n.addMessages({
        en: {
            styleManager: {
                properties: {
                    'background-repeat': 'Repeat',
                    'background-position': 'Position',
                    'background-attachment': 'Attachment',
                    'background-size': 'Size',
                }
            },
        }
    });

    var pn = editor.Panels;
    var modal = editor.Modal;
    var cmdm = editor.Commands;

    // Update canvas-clear command
    cmdm.add('canvas-clear', function() {
        if (confirm('Are you sure to clean the canvas?')) {
            editor.runCommand('core:canvas-clear')
            setTimeout(function() {
                localStorage.clear()
            }, 0)
        }
    });

    // Add info command
    var mdlClass = 'gjs-mdl-dialog-sm';
    var infoContainer = document.getElementById('info-panel');

    cmdm.add('open-info', function() {
        var mdlDialog = document.querySelector('.gjs-mdl-dialog');
        mdlDialog.className += ' ' + mdlClass;
        infoContainer.style.display = 'block';
        modal.setTitle('About this demo');
        modal.setContent(infoContainer);
        modal.open();
        modal.getModel().once('change:open', function() {
            mdlDialog.className = mdlDialog.className.replace(mdlClass, '');
        })
    });

    pn.addButton('options', {
        id: 'open-info',
        className: 'fa fa-question-circle',
        command: function() {
            editor.runCommand('open-info')
        },
        attributes: {
            'title': 'About',
            'data-tooltip-pos': 'bottom',
        },
    });


    // Simple warn notifier
    var origWarn = console.warn;
    toastr.options = {
        closeButton: true,
        preventDuplicates: true,
        showDuration: 250,
        hideDuration: 150
    };
    console.warn = function(msg) {
        if (msg.indexOf('[undefined]') == -1) {
            toastr.warning(msg);
        }
        origWarn(msg);
    };


    // Add and beautify tooltips
    [
        ['sw-visibility', 'Show Borders'],
        ['preview', 'Preview'],
        ['fullscreen', 'Fullscreen'],
        ['export-template', 'Export'],
        ['undo', 'Undo'],
        ['redo', 'Redo'],
        ['gjs-open-import-webpage', 'Import'],
        ['canvas-clear', 'Clear canvas']
    ]
    .forEach(function(item) {
        pn.getButton('options', item[0]).set('attributes', {
            title: item[1],
            'data-tooltip-pos': 'bottom'
        });
    });
    [
        ['open-sm', 'Style Manager'],
        ['open-layers', 'Layers'],
        ['open-blocks', 'Blocks']
    ]
    .forEach(function(item) {
        pn.getButton('views', item[0]).set('attributes', {
            title: item[1],
            'data-tooltip-pos': 'bottom'
        });
    });
    var titles = document.querySelectorAll('*[title]');

    for (var i = 0; i < titles.length; i++) {
        var el = titles[i];
        var title = el.getAttribute('title');
        title = title ? title.trim() : '';
        if (!title)
            break;
        el.setAttribute('data-tooltip', title);
        el.setAttribute('title', '');
    }


    // Store and load events
    editor.on('storage:load', function(e) {
        console.log('Loaded ', e)
    });
    editor.on('storage:store', function(e) {
        console.log('Stored ', e)
    });


    // Do stuff on load
    editor.on('load', function() {
        var $ = grapesjs.$;

        // Show borders by default
        pn.getButton('options', 'sw-visibility').set('active', 1);

        // Show logo with the version
        var logoCont = document.querySelector('.gjs-logo-cont');
        document.querySelector('.gjs-logo-version').innerHTML = 'v' + grapesjs.version;
        var logoPanel = document.querySelector('.gjs-pn-commands');
        logoPanel.appendChild(logoCont);


        // Load and show settings and style manager
        var openTmBtn = pn.getButton('views', 'open-tm');
        openTmBtn && openTmBtn.set('active', 1);
        var openSm = pn.getButton('views', 'open-sm');
        openSm && openSm.set('active', 1);

        // Remove trait view
        pn.removeButton('views', 'open-tm');

        // Add Settings Sector
        var traitsSector = $('<div class="gjs-sm-sector no-select">' +
            '<div class="gjs-sm-sector-title"><span class="icon-settings fa fa-cog"></span> <span class="gjs-sm-sector-label">Settings</span></div>' +
            '<div class="gjs-sm-properties" style="display: none;"></div></div>');
        var traitsProps = traitsSector.find('.gjs-sm-properties');
        traitsProps.append($('.gjs-trt-traits'));
        $('.gjs-sm-sectors').before(traitsSector);
        traitsSector.find('.gjs-sm-sector-title').on('click', function() {
            var traitStyle = traitsProps.get(0).style;
            var hidden = traitStyle.display == 'none';
            if (hidden) {
                traitStyle.display = 'block';
            } else {
                traitStyle.display = 'none';
            }
        });

        // Open block manager
        var openBlocksBtn = editor.Panels.getButton('views', 'open-blocks');
        openBlocksBtn && openBlocksBtn.set('active', 1);

        // Move Ad
        $('#gjs').append($('.ad-cont'));
    });

    (function(i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function() {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-74284223-1', 'auto');
    ga('send', 'pageview');
</script>
<script>
    // Função para ajustar a altura do iframe
    function ajustarAlturaIframe() {
        // Seleciona o iframe dentro do elemento com a classe .gjs-frame
        var iframe = document.querySelector('[data-gjs-type="iframe"]');
        if (iframe) {
            // Define a altura do iframe como 100% da janela de visualização
            iframe.style.height = '100%';
            // Se o iframe foi encontrado e a altura ajustada, podemos parar de verificar
            clearInterval(verificarIntervalo);
        }
        console.log(iframe)
    }

    // Chama a função para ajustar a altura do iframe repetidamente com um intervalo de 500ms
    //var verificarIntervalo = setInterval(ajustarAlturaIframe, 500);

    // Chama a função para ajustar a altura do iframe quando o editor é carregado
    editor.on('load', function() {
        // Se o iframe já estiver disponível quando o editor é carregado, chama a função imediatamente
        //ajustarAlturaIframe();
        var s = document.querySelector('.frame');
        s.parentNode.style.height = '100vh'
        s.style.height = '100vh !important';
        console.log(s.parentElement);
        
    });

    // Chama a função para ajustar a altura do iframe quando a janela é redimensionada
    window.addEventListener('resize', function() {
        //ajustarAlturaIframe();
    });
</script>
<script src="<?= asset('js/editor/save.js') ?>"></script>
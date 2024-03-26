const editor = grapesjs.init({
  container: "#own-editor",
  fromElement: true,
  width: "auto",
  storageManager: false,
  plugins: [
    'gjs-blocks-basic',
    //'grapesjs-preset-webpage'
  ],
  pluginsOpts: {
    'gjs-blocks-basic': {
      //flexGrid: true
    },/*
        'grapesjs-preset-webpage': {
            modalImportTitle: 'Import Template',
            modalImportLabel: '<div style="margin-bottom: 10px; font-size: 13px;">Paste here your HTML/CSS and click Import</div>',
            modalImportContent: function (editor) {
                return editor.getHtml() + '<style>' + editor.getCss() + '</style>'
            },
        },*/
  },
  blockManager: {
    appendTo: "#blocks",
  },
  layerManager: {
    appendTo: "#layer-container"
  },
  styleManager: {
    appendTo: "#style-view"
  },
  panels: {
    defaults: [
      {
        id: "basic-actions",
        el: ".panel__basic-actions",
        buttons: [
          {
            id: "visibility",
            active: true,
            className: "btn-toggle-borders",
            label: "<i class=\"bi bi-border\"></i>",
            command: "sw-visibility"
          },
        ]
      },
      {
        id: "panel-devices",
        el: ".panel__devices",
        buttons: [
          {
            id: "device-desktop",
            label: "<i class=\"bi bi-laptop\"></i>",
            command: "set-device-desktop",
            active: true,
            togglable: false,
          },
          {
            id: "device-mobile",
            label: "<i class=\"bi bi-phone\"></i>",
            command: "set-device-mobile",
          },
        ]
      }
    ],
  },
  deviceManager: {
    devices: [
      {
        name: "Desktop",
        width: ""
      },
      {
        name: "Mobile",
        width: "320px",
        //widthMedia: "480px",
        widthMedia: "576px",
      }
    ]
  }

});
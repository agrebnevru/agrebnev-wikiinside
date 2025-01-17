/* eslint-disable */
(function (exports) {
    'use strict';

    var Icon = /*#__PURE__*/function () {
      function Icon() {
        babelHelpers.classCallCheck(this, Icon);
        this.init();
      }
      babelHelpers.createClass(Icon, [{
        key: "init",
        value: function init() {
          this.injectIcon();
          this.addEventListener();
        }
      }, {
        key: "injectIcon",
        value: function injectIcon() {
          var html = "<span class=\"adm-header-help-btn\" id=\"bx_top_panel_agrebnev_wikiinside_header_btn\" style=\"padding-left:0;\">\n\t\t   <span class=\"adm-header-help-btn-text\"><span style=\"color:#2fc6f6;\">Wiki</span>Inside</span>\n\t\t</span>";
          var $headerRightBlock = document.querySelector('.adm-header-right-block');
          if (!$headerRightBlock) {
            return;
          }
          $headerRightBlock.insertAdjacentHTML('beforeEnd', html);
        }
      }, {
        key: "addEventListener",
        value: function addEventListener() {
          var $btn = document.getElementById('bx_top_panel_agrebnev_wikiinside_header_btn');
          if (!$btn) {
            return;
          }
          $btn.addEventListener('click', function () {
            var url = '/bitrix/admin/agrebnev_wikiinside.php?';
            url += 'location[pathname]=' + encodeURIComponent(window.location.pathname);
            url += '&location[search]=' + encodeURIComponent(window.location.search);
            BX.SidePanel.Instance.open(url, {
              width: 850,
              allowChangeHistory: false,
              allowChangeTitle: false,
              cacheable: false
            });
          });
        }
      }]);
      return Icon;
    }();
    document.addEventListener('DOMContentLoaded', function () {
      new AgrebnevWikiInside.Icon();
    }, {
      once: true
    });

    exports.Icon = Icon;

}((this.AgrebnevWikiInside = this.AgrebnevWikiInside || {})));
//# sourceMappingURL=app.bundle.js.map

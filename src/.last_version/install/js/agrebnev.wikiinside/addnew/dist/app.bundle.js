/* eslint-disable */
(function (exports) {
    'use strict';

    var AddNew = /*#__PURE__*/function () {
      function AddNew() {
        babelHelpers.classCallCheck(this, AddNew);
        this.init();
      }
      babelHelpers.createClass(AddNew, [{
        key: "init",
        value: function init() {
          this.fillLocationPathname();
          this.fillLocationSearch();
        }
      }, {
        key: "fillLocationPathname",
        value: function fillLocationPathname() {
          var $propRow = document.getElementById("tr_PROPERTY_".concat(BX.message('AGREBNEV_WI_OPTIONS_PROP_ID_PATHNAME')));
          if (!$propRow) {
            return;
          }
          var $input = $propRow.querySelector("[name=\"PROP[".concat(BX.message('AGREBNEV_WI_OPTIONS_PROP_ID_PATHNAME'), "][n0]\"]"));
          if (!$input) {
            return;
          }
          var valueFromRequest = BX.message('AGREBNEV_WI_ADD_PARAMS_LOCATION_PATHNAME');
          if (!valueFromRequest) {
            return;
          }
          $input.value = valueFromRequest;
        }
      }, {
        key: "fillLocationSearch",
        value: function fillLocationSearch() {
          var $propRow = document.getElementById("tr_PROPERTY_".concat(BX.message('AGREBNEV_WI_OPTIONS_PROP_ID_SEARCH')));
          if (!$propRow) {
            return;
          }
          var valueFromRequest = BX.message('AGREBNEV_WI_ADD_PARAMS_LOCATION_SEARCH');
          if (!valueFromRequest) {
            return;
          }
          var params = valueFromRequest.split('&');
          if (0 === Object.values(params)) {
            return;
          }
          var $inputValue = null,
            $inputDescription = null;
          for (var key in params) {
            var param = params[key];
            if (!param) {
              continue;
            }
            var data = param.split('=');
            var paramName = data[0];
            var paramValue = data[1];
            if (!paramName || !paramValue) {
              continue;
            }
            $inputValue = $propRow.querySelector("[name=\"PROP[".concat(BX.message('AGREBNEV_WI_OPTIONS_PROP_ID_SEARCH'), "][n").concat(key, "][VALUE]\"]"));
            $inputDescription = $propRow.querySelector("[name=\"PROP[".concat(BX.message('AGREBNEV_WI_OPTIONS_PROP_ID_SEARCH'), "][n").concat(key, "][DESCRIPTION]\"]"));
            if (!$inputValue || '' !== $inputValue.value || !$inputDescription || '' !== $inputDescription.value) {
              continue;
            }
            $inputValue.value = paramName;
            $inputDescription.value = paramValue;
          }
        }
      }]);
      return AddNew;
    }();
    document.addEventListener('DOMContentLoaded', function () {
      new AgrebnevWikiInside.AddNew();
    }, {
      once: true
    });

    exports.AddNew = AddNew;

}((this.AgrebnevWikiInside = this.AgrebnevWikiInside || {})));
//# sourceMappingURL=app.bundle.js.map

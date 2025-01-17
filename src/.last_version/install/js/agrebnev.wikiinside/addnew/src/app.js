export class AddNew {

    constructor() {
        this.init()
    }

    init() {
        this.fillLocationPathname()
        this.fillLocationSearch()
    }

    fillLocationPathname() {
        let $propRow = document.getElementById(`tr_PROPERTY_${BX.message('AGREBNEV_WI_OPTIONS_PROP_ID_PATHNAME')}`)
        if (!$propRow) {
            return
        }

        let $input = $propRow.querySelector(`[name="PROP[${BX.message('AGREBNEV_WI_OPTIONS_PROP_ID_PATHNAME')}][n0]"]`)
        if (!$input) {
            return
        }

        let valueFromRequest = BX.message('AGREBNEV_WI_ADD_PARAMS_LOCATION_PATHNAME')
        if (!valueFromRequest) {
            return
        }

        $input.value = valueFromRequest
    }

    fillLocationSearch() {
        let $propRow = document.getElementById(`tr_PROPERTY_${BX.message('AGREBNEV_WI_OPTIONS_PROP_ID_SEARCH')}`)
        if (!$propRow) {
            return
        }

        let valueFromRequest = BX.message('AGREBNEV_WI_ADD_PARAMS_LOCATION_SEARCH')
        if (!valueFromRequest) {
            return
        }

        let params = valueFromRequest.split('&')
        if (0 === Object.values(params)) {
            return
        }

        let $inputValue = null,
            $inputDescription = null

        for (let key in params) {
            let param = params[key]
            if (!param) {
                continue
            }

            let data = param.split('=')
            let paramName = data[0]
            let paramValue = data[1]
            if (!paramName || !paramValue) {
                continue
            }

            $inputValue = $propRow.querySelector(`[name="PROP[${BX.message('AGREBNEV_WI_OPTIONS_PROP_ID_SEARCH')}][n${key}][VALUE]"]`)
            $inputDescription = $propRow.querySelector(`[name="PROP[${BX.message('AGREBNEV_WI_OPTIONS_PROP_ID_SEARCH')}][n${key}][DESCRIPTION]"]`)

            if (
                !$inputValue
                || '' !== $inputValue.value
                || !$inputDescription
                || '' !== $inputDescription.value
            ) {
                continue
            }

            $inputValue.value = paramName
            $inputDescription.value = paramValue
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    new AgrebnevWikiInside.AddNew()
}, {
    once: true,
})

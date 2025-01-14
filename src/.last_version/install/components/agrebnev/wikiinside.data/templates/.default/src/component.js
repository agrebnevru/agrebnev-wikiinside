import Mustache from 'mustache';

import Base from './base';

import './component.scss';

export class Data extends Base {

    constructor(id, params = {}, result = {}, options = {}) {
        super(id, params, result, options)

        this.activeNavigationId = 0

        this.init()
    }

    init() {
        this.initDocument()
        this.addEventListeners()
        this.fillStartData()
        this.render()
    }

    initDocument() {
        document.documentElement.classList.add('agrebnev-wikiinside--inited')

        if ('Y' !== this.params.IFRAME_REQUEST) {
            return
        }

        document.documentElement.classList.add('agrebnev-wikiinside--iframe')
    }

    addEventListeners() {
        this.$component.on('click', '.js-agrebnev-wikiinside-sec', event => {
            let id = Number.parseInt(event.target.dataset.id)
            this.showSectionById(id)
            this.render()
        })

        this.$component.on('click', '.js-agrebnev-wikiinside-elem', event => {
            let id = Number.parseInt(event.target.dataset.id)
            this.showElementById(id)
            this.render()
        })
    }

    showSectionById(id) {
        let entity = this.getSectionById(id)

        if (!entity) {
            this.fillStartData()
            return
        }

        this.result.JS_DATA.CURRENT_DATA.ID = entity.ID
        this.result.JS_DATA.CURRENT_DATA.TITLE = entity.NAME
        this.result.JS_DATA.CURRENT_DATA.DESCRIPTION = entity.DESCRIPTION

        this.activeNavigationId = entity.ID
    }

    showElementById(id) {
        let entity = this.getElementById(id)

        if (!entity) {
            return
        }

        this.result.JS_DATA.CURRENT_DATA.ID = entity.ID
        this.result.JS_DATA.CURRENT_DATA.TITLE = entity.NAME
        this.result.JS_DATA.CURRENT_DATA.DESCRIPTION = entity.DETAIL_TEXT

        this.activeNavigationId = entity.IBLOCK_SECTION_ID
    }

    fillStartData() {
        this.result.JS_DATA.CURRENT_DATA = {
            ID: null,
            TITLE: this.result.JS_DATA.IBLOCK.NAME,
            DESCRIPTION: this.result.JS_DATA.IBLOCK.DESCRIPTION,
        }
        this.activeNavigationId = 0

        let currentData = this.getCurrentDataByUrl()
        if (null !== currentData) {
            this.result.JS_DATA.CURRENT_DATA.ID = currentData.ID
            this.result.JS_DATA.CURRENT_DATA.TITLE = currentData.NAME
            this.result.JS_DATA.CURRENT_DATA.DESCRIPTION = currentData.DETAIL_TEXT

            this.activeNavigationId = currentData.IBLOCK_SECTION_ID
        }
    }

    getCurrentDataByUrl() {
        if (
            0 === this.result.JS_DATA.ITEMS.length
            || '' === this.params.LOCATION_PATHNAME
        ) {
            return null
        }

        let finedItem = null
        for (let item of this.result.JS_DATA.ITEMS) {

            let approve = false;
            let approveParamsCount = 0;
            if (this.params.LOCATION_PATHNAME !== item.PATHNAME) {
                continue;
            }

            if (0 < Object.values(item.SEARCH).length) {
                approve = false;
                approveParamsCount = 0;
                for (let name in item.SEARCH) {
                    let param = `${name}=${item.SEARCH[name]}`
                    if (0 < this.params.LOCATION_SEARCH.indexOf(param)) {
                        approveParamsCount++
                    }
                }

                if (Object.values(item.SEARCH).length === approveParamsCount) {
                    approve = true;
                }
            } else {
                approve = true;
            }

            if (false === approve) {
                continue
            }

            finedItem = item

            break;
        }

        return finedItem
    }

    render() {
        this.renderNavigation()
        this.renderBody()
    }

    renderNavigation() {
        let blockId = `${this.id}-menu`,
            startItem = {
                ID: 0,
                NAME: BX.message('AGREBNEV_WI_COMPONENT_TMPL_JS__GO_MAIN'),
                CODE: 'agrebnev-wikiinside-zero-navi-item',
            },
            data = {
                SECTIONS: [startItem].concat(this.result.JS_DATA.NAVIGATION)
            }


        let funcSetActive = function (items, activeId) {
            if (!items || 0 === items.length) {
                return items
            }

            for (let item of items) {
                item.ACTIVE = Number.parseInt(activeId) === Number.parseInt(item.ID)

                if (item.SUB_ITEMS && 0 < item.SUB_ITEMS.length) {
                    item.SUB_ITEMS = funcSetActive(item.SUB_ITEMS, activeId);
                }
            }

            return items;
        }

        data.SECTIONS = funcSetActive(data.SECTIONS, Number.parseInt(this.activeNavigationId))

        this.renderMustache(`${blockId}-template`, `${blockId}-target`, data)
    }

    renderBody() {
        let blockId = `${this.id}-body`,
            data = Object.assign({}, {HAS_ELEMENTS: false, ELEMENTS: []}, this.result.JS_DATA.CURRENT_DATA)

        data.ELEMENTS = this.result.JS_DATA.ITEMS.filter(item => {
            return Number.parseInt(this.activeNavigationId) === Number.parseInt(item.IBLOCK_SECTION_ID)
        })
        data.HAS_ELEMENTS = 0 < data.ELEMENTS.length

        data.ELEMENTS = data.ELEMENTS.map(item => {
            let rewriteFields = {
                ACTIVE: Number.parseInt(data.ID) === Number.parseInt(item.ID)
            }
            return Object.assign({}, item, rewriteFields)
        })

        this.renderMustache(`${blockId}-template`, `${blockId}-target`, data)
    }
}

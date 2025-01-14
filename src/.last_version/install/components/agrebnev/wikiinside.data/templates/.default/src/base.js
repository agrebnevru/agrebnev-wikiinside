import Mustache from 'mustache';

import './component.scss';

export default class Base {

    constructor(id, params = {}, result = {}, options = {}) {
        this.id = id
        this.params = params
        this.result = result
        this.options = options

        this.$component = document.getElementById(this.id)
    }

    getSectionById(id) {
        return this.getEntityById(id, this.result.JS_DATA.SECTIONS)
    }

    getElementById(id) {
        return this.getEntityById(id, this.result.JS_DATA.ITEMS)
    }

    getEntityById(id, entities) {
        let entity = null

        if (0 < entities.length) {
            for (let row of entities) {
                if (Number.parseInt(id) !== Number.parseInt(row.ID)) {
                    continue;
                }

                entity = row

                break
            }
        }

        return entity
    }

    renderMustache(templateId, targetId, data) {
        let mixId = `${templateId}_${targetId}`,
            templateHtml = null,
            $target = null

        if (this.hasOwnProperty('mustacheCache') === false) {
            this.mustacheCache = {}
        }

        if (this.mustacheCache.hasOwnProperty(mixId) === true) {
            templateHtml = this.mustacheCache[mixId].templateHtml
            $target = this.mustacheCache[mixId].$target
        } else {
            let $template = document.getElementById(templateId)
            $target = document.getElementById(targetId)

            if (!$template || !$target) {
                return
            }

            templateHtml = $template.innerHTML

            this.mustacheCache[mixId] = {
                templateHtml: templateHtml,
                $target: $target
            }
        }

        $target.innerHTML = Mustache.render(templateHtml, data)
    }
}

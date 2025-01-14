export class Icon {

    constructor() {
        this.init()
    }

    init() {
        this.injectIcon()
        this.addEventListener()
    }

    injectIcon() {
        let html = `<span class="adm-header-help-btn" id="bx_top_panel_agrebnev_wikiinside_header_btn" style="padding-left:0;">
		   <span class="adm-header-help-btn-text"><span style="color:#2fc6f6;">Wiki</span>Inside</span>
		</span>`

        let $headerRightBlock = document.querySelector('.adm-header-right-block')
        if (!$headerRightBlock) {
            console.warn('Cant find header right block')
            return
        }

        $headerRightBlock.insertAdjacentHTML('beforeEnd', html)
    }

    addEventListener() {
        let $btn = document.getElementById('bx_top_panel_agrebnev_wikiinside_header_btn')
        if (!$btn) {
            return
        }

        $btn.addEventListener('click', function () {
            let url = '/bitrix/admin/agrebnev_wikiinside.php?';
            url += 'location[pathname]=' + encodeURIComponent(window.location.pathname);
            url += '&location[search]=' + encodeURIComponent(window.location.search);

            BX.SidePanel.Instance.open(
                url,
                {
                    width: 850,
                    allowChangeHistory: false,
                    allowChangeTitle: false,
                    cacheable: false,
                }
            );
        })
    }
}

document.addEventListener('DOMContentLoaded', function () {
    new AgrebnevWikiInside.Icon()
}, {
    once: true,
})

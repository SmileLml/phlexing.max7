function getDropdown()
{
    return zui.Dropdown.get('#versionDropdown-toggle');
}

window.afterRenderMenu = function(firstRender)
{
    if(!firstRender) return;
    const $exchangeBtn = $('#exchangeDiffBtn');
    if(!$exchangeBtn.length) return;
    const newVersion = $exchangeBtn.prev().find('.text').text().replace('#', '');
    const oldVersion = $exchangeBtn.next().find('.text').text().replace('#', '');
    const checked = {};
    checked[newVersion] = true;
    checked[oldVersion] = true;
    this.setState({showCheckbox: true, checked: checked});
};

window.getVersionHeader = function()
{
    if(this.props.items.length < 2) return;

    const dropdown = getDropdown();
    const actions =
    [
        {icon: 'exchange', text: dropdown.options.diffLang, className: this.state.showCheckbox ? 'invisible pointer-events-none' : 'text-primary', onClick: () => this.setState({showCheckbox: true})},
    ];

    return {
        component: 'Listitem',
        style: {marginBottom: -6},
        className: 'not-hide-menu',
        props:
        {
            text: dropdown.options.allVersion,
            titleClass: 'text-gray',
            actions: actions,
        },
    };
}

window.getVersionFooter = function()
{
    if(this.props.items.length < 2) return;

    const dropdown = getDropdown();
    return {
        component: 'Toolbar',
        props: {
            gap: 4,
            className: `p-1 pt-0${this.state.showCheckbox ? '' : ' hidden'}`,
            items: [
                {text: dropdown.options.confirmLang, size: 'sm', disabled: this.getChecks().length < 2, type: 'primary', onClick: () =>
                    {
                        const versions = this.getChecks();
                        const oldVersion = Math.min(...versions);
                        const newVersion = Math.max(...versions);
                        reloadView(newVersion, oldVersion);
                        const $toggle = $('#versionDropdown-toggle').removeClass('gray-pale').addClass('primary-pale rounded-r-none');
                        $('#docHeader .is-extra-version').remove();
                        $toggle.find('.text').text('#' + newVersion);
                        $toggle.after(
                        [
                            '<button class="rounded-none size-xs gap-1 btn primary-pale is-extra-version" id="exchangeDiffBtn" onClick="flipVersion(this)" style="margin-left:-6px"><span class="icon icon-exchange"></span></button>',
                            `<button class='rounded-full rounded-l-none size-xs gap-1 btn primary-pale is-extra-version pointer-events-none' style="margin-left:-6px;min-width:${$toggle.outerWidth()}px"><span class='text'>#${oldVersion}</span></button>`
                        ].join(''));
                        this.setState({showCheckbox: true});
                    }
                },
                {text: dropdown.options.cancelDiff, size: 'sm', className: 'not-hide-menu', type: 'default', onClick: () => {this.setState({showCheckbox: false});}},
            ],
        },
    };
}

window.getDropdownItem = function(item)
{
    if (!this.state.showCheckbox) return item;

    const checked = !!this.state.checked[item.key];
    item = $.extend({checked: checked}, item, {active: false, selected: item.checked});

    if (!item.checked && item.disabled === undefined) {
        const disabled = this.getChecks().length >= 2;
        item = $.extend({disabled: disabled}, item);
    }

    return item;
}

window.onClickDropdownItem = function(info)
{
    if(!this.state.showCheckbox) return;
    info.event.stopPropagation();
    info.event.preventDefault();
}

window.reloadView = function(newV, oldV)
{
    const dropdown = getDropdown();
    const param = 'objectType=' + dropdown.options.objectType + '&docID=' + dropdown.options.docID + '&newVersion=' + newV + '&oldVersion=' + oldV;
    loadTarget($.createLink('doc', 'diff', param), '#docEditor');
}

window.flipVersion = function()
{
    const $exchangeBtn = $('#exchangeDiffBtn');
    $exchangeBtn.toggleClass('primary').toggleClass('primary-pale');

    const newVersion = $exchangeBtn.prev().find('.text').text().replace('#', '');
    const oldVersion = $exchangeBtn.next().find('.text').text().replace('#', '');

    $exchangeBtn.prev().find('.text').text('#' + oldVersion);
    $exchangeBtn.next().find('.text').text('#' + newVersion);

    reloadView(oldVersion, newVersion);
}

// 添加 Holder 插件
KindEditor.plugin('holder', function(K)
{
    var self = this;

    if (!self.options.htmlTags)
    {
        self.options.htmlTags = {};
    }
    if (!self.options.htmlTags.div)
    {
        self.options.htmlTags.div = [];
    }
    if (!self.options.htmlTags.span)
    {
        self.options.htmlTags.span = [];
    }

    self.options.htmlTags.div.push('data-holder', 'data-holder-edit', 'contenteditable');
    self.options.htmlTags.span.push('data-holder', 'data-holder-edit', 'contenteditable');

    self.afterCreate(function()
    {
        $(self.edit.doc.head).append([
            '<style>',
            '.holder-element {border: 1px solid #ea644a; background: #ffe5e0; border-radius: 4px; padding: 0 5px; cursor: pointer; color: #ea644a; position: relative; min-width: 50px; text-align: center}',
            '.holder-value {width: 20px; font-size: 0; position: absolute; top: 0; right: 0; bottom: 0;}',
            '.holder-element:before {content: attr(data-holder)}',
            '.holder-block {display: block; text-align: center}',
            '.holder-span {display: inline-block; line-height: 1}',
            '.holder-value:after {content: "X"; display: block; position: absolute; right: 0; top: 0; bottom: 0; border-radius: 0 3px 3px 0; background: #ffe5e0; text-align: center; font-size: 14px; display: flex; justify-content: center; align-items: center; width: 20px; border-left: 1px solid #ea644a; opacity: 0}',
            '.holder-element:hover .holder-value:after {opacity: 1}',
            '.holder-value:hover:after {background: #ea644a; color: #fff}',
            '</style>'
        ].join('\n'));

        $(self.edit.doc).find('.holder-element[contenteditable="true"]').attr('contenteditable', false);

        if (self.options.holderEdit)
        {
            $(self.edit.doc).on('click', '.holder-element', function(e)
            {
                var $ele = $(this);
                if($(e.target).is('.holder-value'))
                {
                    $ele.remove();
                    self.sync();
                    return;
                }
                showHolderEditModal($ele.attr('data-holder'), $ele.find('.holder-value').text(), $ele.hasClass('holder-block'), function(text, value, asBlock)
                {
                    var $holderElement = $(asBlock ? '<div>' : '<span>').attr('data-holder', text).attr('contenteditable', false).addClass(asBlock ? 'holder-block' : 'holder-span').addClass('holder-element');
                    $holderElement.append($(asBlock ? '<div>' : '<span>').addClass('holder-value').text(value));
                    if (self.options.holderEditText)
                    {
                        $holderElement.attr('data-holder-edit', self.options.holderEditText);
                    }
                    $ele.replaceWith($holderElement);
                    self.edit.focus();
                    self.sync();
                });
            });
        }
    });

    // 点击图标时执行
    self.clickToolbar('holder', function()
    {
        showHolderEditModal('', '', false, function(text, value, asBlock)
        {
            if(text)
            {
                var $holderElement = $(asBlock ? '<div>' : '<span>').attr('data-holder', text).attr('contenteditable', false).addClass(asBlock ? 'holder-block' : 'holder-span').addClass('holder-element');
                $holderElement.append($(asBlock ? '<div>' : '<span>').addClass('holder-value').text(value));
                if(self.options.holderEditText)
                {
                    $holderElement.attr('data-holder-edit', self.options.holderEditText);
                }
                self.insertHtml($holderElement[0].outerHTML + '&nbsp;' + (asBlock ? '<br>' : ''));
            }
        });
    });
});

function showHolderEditModal(placeholder, value, asBlock, callback)
{
    if(typeof placeholder === 'function')
    {
        callback = placeholder;
        placeholder = '';
        value = '';
        asBlock = false;
    }
    placeholder = placeholder || '';
    value = value || '';
    $('#holderText').val(placeholder);
    $('#holderValue').val(value);
    $('#holderAsBlock').prop('checked', !!asBlock);
    $('#holderModal').modal('show');
    if(typeof afterHolderShow == 'function') afterHolderShow(value);
    $('#holderSaveBtn').off('click').on('click', function()
    {
        if(callback)
        {
            callback($('#holderText').val(), $('#holderValue').val(), $('#holderAsBlock').is(':checked'))
        }
        $('#holderModal').modal('hide');
    });
};

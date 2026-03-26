$(document).ready(function()
{
    /* Set action to up when in last actions. */
    $(".moreActions").click(function()
    {
        var height = $(this).offset().top - $('#actionListTable').offset().top + $(this).parent().parent().height() + $(this).next(".dropdown-menu").height();
        var maxHeight = $('#actionListTable').parent().css('max-height').replace("px", "");
        if(height > maxHeight)
        {
            $(this).next(".dropdown-menu").addClass('dropup');
        }
    });

    $(document).on('hide.zui.modal', '#triggerModal.layout-modal', function()
    {
        var action = $(this).data('action');

        $('#actionList tr[data-action=' + action + '] .select-action' ).click();
    });

    $('#actionList').on('sort.sortable', function(e, data)
    {
        $.post(createLink('workflowaction', 'sort'), data.orders, function(response)
        {
            if(response.result == 'success')
            {
                return location.reload();
            }
            else
            {
                bootbox.alert(response.message);
            }
        }, 'json');
    });

    $('#actionList .select-action').click(function()
    {
        $('#actionList tr.checked').removeClass('checked row-check-begin row-check-end');

        var $tr  = $(this).parents('tr').addClass('checked row-check-begin row-check-end');
        var data = $tr.data();

        $('#previewArea .panel-heading').html('<strong>' + data.name + '</strong>');

        if(data.buildin && data.extensiontype != 'override')
        {
            $('.layout-buildin-tip').show();
            $('.layout-empty-tip').hide();
            $('.layout-no-tip').hide();
            $('.layout-preview').hide();
        }
        else if(data.open != 'none')
        {
            if(data.virtual)
            {
                var nextModule  = data.action.substring(0, data.action.lastIndexOf('_'));
                var nextAction  = data.action.substring(data.action.lastIndexOf('_') + 1);
                var previewLink = createLink('workflowaction', 'ajaxPreview', 'module=' + nextModule + '&action=' + nextAction);
            }
            else
            {
                var previewLink = createLink('workflowaction', 'ajaxPreview', 'module=' + window.moduleName + '&action=' + data.action);
            }

            $('#previewArea .layout-preview').load(previewLink, function(response)
            {
                if(!response)
                {
                    var setLayoutLink   = createLink('workflowlayout', 'admin', 'module=' + window.moduleName + '&action=' + data.action);
                    var setLayoutButton = "<a href='" + setLayoutLink + "' class='btn btn-secondary setLayout' data-toggle='modal'>" + window.setLayout + '</a>';
                    $('.layout-buildin-tip').hide();
                    $('.layout-empty-tip').find('.setLayout').remove();
                    $('.layout-empty-tip').append(setLayoutButton).show();
                    $('.layout-no-tip').hide();
                    $('.layout-preview').hide();
                    return false;
                }

                $('.preview-content .chosen').chosen();

                $('.example-text-holder').each(function()
                {
                    $(this).attr('data-size', Math.floor(Math.random() * 8) + 2);
                });

                $('.btn-group').fixInputGroup();
            });

            $('.layout-buildin-tip').hide();
            $('.layout-empty-tip').hide();
            $('.layout-no-tip').hide();
            $('.layout-preview').show();
        }
        else
        {
            $('.layout-buildin-tip').hide();
            $('.layout-empty-tip').hide();
            $('.layout-no-tip').show();
            $('.layout-preview').hide();
        }
    });

    $('#actionList .select-action:first').click();

    /* Action */
    $(document).on('change', '#actionTable #type', function()
    {
        $('#batchMode').parents('tr').toggle($(this).val() == 'batch');
        $('#position, #show').parents('tr').toggle($(this).val() == 'single');
        $('#open option[value=modal]').toggle($(this).val() == 'single');
        if($(this).val() == 'batch' && $('#open').val() == 'modal')
        {
            $('#open').val('normal');
        }
    });

    /* Condition */
    $(document).on('change', '#conditionTable #conditionType', function()
    {
        $('.sqlTR').toggle($(this).val() == 'sql');
        $('.dataTR').toggle($(this).val() == 'data');
    });

    $(document).on('click', '#conditionTable .addCondition', function()
    {
        $(this).parents('tr').after(window.itemRow.replace(/KEY/g, window.conditionKey));
        $(this).parents('tr').next().find('[name*=field]').chosen();

        window.conditionKey++;
    });

    $(document).on('click', '#conditionTable .delCondition', function()
    {
        $(this).parents('tr').remove();
    });

    $(document).on('change', '#conditionTable [name*=field]', function()
    {
        var $tr  = $(this).parents('tr');
        var key  = $tr.data('key');
        var name = window.btoa(encodeURI('param[' + key + ']'));
        var link = createLink('workflowfield', 'ajaxGetFieldControl', 'module=' + window.moduleName + '&field=' + $(this).val() + '&value=&elementName=' + name);
        $tr.find('#paramTD').load(link, function()
        {
            $tr.find('select.chosen').chosen();
            $tr.find('.form-date').datetimepicker(dateOptions);
            $tr.find('.form-datetime').datetimepicker(datetimeOptions);

            initSelect($tr.find('#paramTD .picker-select'));
        });
    });

    /* Linkage */
    $(document).on('change', '#linkageTable [name^=source]', function()
    {
        processField();

        var $tr    = $(this).parents('tr');
        var $td    = $tr.find('td.value');
        var field  = $(this).find('option:selected').val();
        var $value = $(this).parents('tr').find('[name^=value]');
        var value  = $value.val();
        var name   = $value.attr('name');
        var id     = $value.attr('id');

        value = window.btoa(encodeURI(value));
        name  = window.btoa(encodeURI(name));
        $td.load(createLink('workflowfield', 'ajaxGetFieldControl', 'module=' + window.moduleName + '&field=' + field + '&value=' + value + '&elementName=' + name + '&elementID=' + id), function()
        {
            $td.find('select.chosen').chosen();
            $tr.find('.form-date').datetimepicker(dateOptions);
            $tr.find('.form-datetime').datetimepicker(datetimeOptions);

            initSelect($td.find('.picker-select'));
        });
    });

    $(document).on('click', '#linkageTable .addTarget', function()
    {
        var $rowspan = parseInt($('th.target').attr('rowspan'));
        $rowspan++;

        $('th.target').attr('rowspan', $rowspan);
        $(this).parents('tr').after(window.targetRow.replace(/KEY/g, window.targetIndex));
        $(this).parents('tr').next().find('.chosen').chosen();

        window.targetIndex++;

        processField();
    })

    $(document).on('click', '#linkageTable .delTarget', function()
    {
        if($('.delTarget').length == 1)
        {
            $(this).parents('tr').find('input,select').val('');
            $(this).parents('tr').find('.chosen').trigger('chosen:updated');
            return false;
        }

        var rowspan = parseInt($('th.target').attr('rowspan'));
        rowspan--;

        if($(this).parents('tr').find('th').length)
        {
            $(this).parents('tr').next().prepend(window.th.replace(/ROWSPAN/g, rowspan));
        }
        else
        {
            $('th.target').attr('rowspan', rowspan);
        }
        $(this).parents('tr').remove();

        processField();
    })

    $(document).on('change', '#linkageTable [name^=target]', function()
    {
        processField();
    });

    $(document).on('change', '#linkageTable [name^=source], #linkageTable [name^=operator], #linkageTable [name^=value]', function()
    {
        $('#sourceLabel').remove();
    });

    $(document).on('change', '#linkageTable [name^=target], #linkageTable [name^=status]', function()
    {
        $('#targetLabel').remove();
    });

    /* Verification */
    $(document).on('change', '#verificationTable [name*=\\[field\\]], #verificationTable [name*=paramType]', function()
    {
        var $tr        = $(this).closest('tr');
        var $td        = $tr.find('.paramTD');
        var $paramType = $tr.find('[name*=paramType]');
        var module     = window.moduleName;
        var key        = $tr.data('key');
        var name       = 'param[' + key + ']';

        if($tr.hasClass('dataTR')) name = 'verifications[param][' + key + ']';

        loadParam($tr, $td, $paramType, module, name);
    });

    $(document).on('click', '#verificationTable .addVerification, #verificationTable .addVar', function()
    {
        var $tr = $(this).parents('tr');
        if($(this).hasClass('addVar'))
        {
            $tr.after(window.varRow.replace(/KEY/g, window.verificationVarKey));
            window.verificationVarKey++;
        }
        else if($(this).hasClass('addVerification'))
        {
            $tr.after(window.verificationRow.replace(/KEY/g, window.verificationDataKey));
            window.verificationDataKey++;
        }
        $tr.next().find('.chosen').chosen();
    });

    $(document).on('click', '#verificationTable .delVerification, #verificationTable .delVar', function()
    {
        if($(this).hasClass('delVar'))
        {
            $('#sql').val($('#sql').val().replace("'$" + $(this).parents('tr').find('#varName').val() + "'", ''));
            if($('.delVar').size() == 1)
            {
                $(this).parents('tr').find('input,select').val('').find('.chosen').trigger('chosen:updated');
                $(this).parents('tr').find('.chosen-single span').html('');
                return;
            }
        }

        $(this).parents('tr').remove();
    });

    $(document).on('change', '#verificationTable [name*=varName]', function()
    {
        if($(this).val() != '') $('#sql').val($('#sql').val() + "'$" + $(this).val() + "'");
    });

    /* Hook */
    $(document).on('change', '.hookForm #conditionType', function()
    {
        $('.sqlTR').toggle($(this).val() == 'sql');
        $('.dataTR').toggle($(this).val() == 'data');
    });

    $(document).on('change', '.hookForm #action, .hookForm #table', function()
    {
        $('.fieldTR').toggle($('#action').val() != 'delete');

        if($('#action').val() == 'insert')
        {
            $('.whereTR').hide().find('input,select').attr('disabled', 'disabled');
        }
        else
        {
            $('.whereTR').show().find('input,select').removeAttr('disabled');
        }

        if($(this).attr('id') == 'table')
        {
            var link = createLink('workflowhook', 'ajaxGetTableFields', 'table=' + $(this).val());
            $('.field').load(link, function()
            {
                $('.field').trigger('chosen:updated');
            });
        }

        loadTarget();
    });

    $(document).on('click', '.hookForm .toggleCondition', function()
    {
        var val = $('#condition').val() == 1 ? 0 : 1;
        $('#condition').val(val);
        $('#conditionDIV').toggle();
    });

    $(document).on('change', '.hookForm [name*=\\[field\\]], .hookForm [name*=paramType]', function()
    {
        var $tr        = $(this).closest('tr');
        var $td        = $tr.find('.paramTD');
        var $paramType = $tr.find('[name*=paramType]');
        var module     = $('#table').val();
        var key        = $tr.data('key');
        var name       = 'param[' + key + ']';

        if($tr.hasClass('dataTR'))  module = window.moduleName;
        if($tr.hasClass('dataTR'))  name   = 'conditions[param][' + key + ']';
        if($tr.hasClass('fieldTR')) name   = 'fields[param][' + key + ']';
        if($tr.hasClass('whereTR')) name   = 'wheres[param][' + key + ']';

        loadParam($tr, $td, $paramType, module, name);
    });

    $(document).on('click', '.hookForm .addVar, .hookForm .addCondition, .hookForm .addField, .hookForm .addWhere', function()
    {
        var $tr = $(this).parents('tr');
        if($(this).hasClass('addVar'))
        {
            $tr.after(window.varRow.replace(/KEY/g, window.hookVarKey));
            window.hookVarKey++;
        }
        else if($(this).hasClass('addCondition'))
        {
            $tr.after(window.conditionRow.replace(/KEY/g, window.hookDataKey));
            window.hookDataKey++;
        }
        else if($(this).hasClass('addField'))
        {
            $tr.after(window.fieldRow.replace(/KEY/g, window.hookFieldKey));
            window.hookFieldKey++;

            var link = createLink('workflowhook', 'ajaxGetTableFields', 'table=' + $('#table').val());
            $tr.next('tr').find('.field').load(link, function()
            {
                $(this).trigger('chosen:updated');
            });
        }
        else if($(this).hasClass('addWhere'))
        {
            $tr.after(window.whereRow.replace(/KEY/g, window.hookWhereKey));
            window.hookWhereKey++;

            var link = createLink('workflowhook', 'ajaxGetTableFields', 'table=' + $('#table').val());
            $tr.next('tr').find('.field').load(link, function()
            {
                $(this).trigger('chosen:updated');
            });
        }
        $tr.next().find('.chosen').chosen();
        $tr.next().find('#param_chosen').hide();
    });

    $(document).on('click', '.hookForm .delVar, .hookForm .delCondition, .hookForm .delField, .hookForm .delWhere', function()
    {
        if($(this).hasClass('delVar'))
        {
            $('#sql').val($('#sql').val().replace("'$" + $(this).parents('tr').find('#varName').val() + "'", ''));
            if($('.delVar').size() == 1)
            {
                $(this).parents('tr').find('input,select').val('').find('.chosen').trigger('chosen:updated');
                $(this).parents('tr').find('.chosen-single span').html('');
                return;
            }
        }

        $(this).parents('tr').remove();
    });

    $(document).on('change', '.hookForm [name*=varName]', function()
    {
        if($(this).val() != '') $('#sql').val($('#sql').val() + "'$" + $(this).val() + "'");
    });

    $panelHeadingHeight = $('.panel-heading').outerHeight(true);
    $panelMarginBottom  = $('.panel').css('margin-bottom').replace('px', '');
    $editorNavHeight    = $('#editorNav').outerHeight(true);
    $editorMenuHeight   = $('#editorMenu').outerHeight();
    $spaceHeight        = $('.space.space-sm').outerHeight(true);

    $maxHeight = $(window).height() - $panelHeadingHeight - $panelMarginBottom - $editorNavHeight - $editorMenuHeight - $spaceHeight;
    $('.panel-body').css('max-height', $maxHeight + 'px');

    $(document).on('click', '.addBlock', function()
    {
        $(this).parents('li').after($('.blockData').html().replace(/blockKey/g, window.blockKey));
        window.blockKey++;
        return false;
    });

    $(document).on('click', '.addTab', function()
    {
        if($(this).parents('li').find('ul').length > 0)
        {
            $(this).parents('li').find('ul').append($('.tabData').html().replace(/tabKey/g, window.tabKey));
        }
        else
        {
            $(this).parents('li').append("<ul class='tabList'>" + $('.tabData').html().replace(/tabKey/g, window.tabKey) + "</ul>");
        }
        window.tabKey++;
        return false;
    });

    $(document).on('click', '.removeBlock', function()
    {
        if($(this).parents('ul').children('li').length > 1)
        {
            $(this).parents('li').remove();
        }
        else
        {
            $(this).parents('li').find('input').val('');
        }
    });

    $(document).on('click', '.removeTab', function()
    {
        if($(this).parents('ul.tabList').children('li').length > 1)
        {
            $(this).parents('li.tab').remove();
        }
        else
        {
            $(this).parents('ul.tabList').remove();
        }
    });

    $(document).on('click', '#blockForm .form-actions .btn', function()
    {
        $('[name^=key]').each(function(index) {$(this).val(index); });
        $('[name^=parent]').each(function(index)
        {
            $(this).val($(this).parents('li').find('[name^=key]').val());
        });

        $('#blockForm').submit();
    });

    $.setAjaxForm('#blockForm', function(response)
    {
        if(response.result == 'success')
        {
            setTimeout(function()
            {
                var modal = $('#triggerModal');
                modal.load(modal.attr('ref'), function(){$(this).find('.modal-dialog').css('width', $(this).data('width')); $.zui.ajustModalPosition()})
            }, 1200);
        }
    });
});

/**
 * Make sure each field be selected only once.
 *
 * @access public
 * @return void
 */
function processField()
{
    $('#linkageTable [name^=source]').each(function()
    {
        var $this    = $(this);
        var selected = $this.val();
        $this.empty().append($('#fieldTemplate').html());
        $('#linkageTable [name^=source]').not(this).each(function()
        {
            var next = $(this).val();
            if(next != 0) $this.find('option[value=' + next + ']').remove();
        });
        $('#linkageTable [name^=target]').each(function()
        {
            var next = $(this).val();
            if(next != 0) $this.find('option[value=' + next + ']').remove();
        });
        $this.val(selected).trigger('chosen:updated');
    });

    $('#linkageTable [name^=target]').each(function()
    {
        var $this    = $(this);
        var selected = $this.val();
        $this.empty().append($('#fieldTemplate').html());
        $('#linkageTable [name^=target]').not(this).each(function()
        {
            var next = $(this).val();
            if(next != 0) $this.find('option[value=' + next + ']').remove();
        });
        $('#linkageTable [name^=source]').each(function()
        {
            var next = $(this).val();
            if(next != 0) $this.find('option[value=' + next + ']').remove();
        });
        $this.val(selected).trigger('chosen:updated');
    });
}

var dateOptions =
{
    language:  config.clientLang,
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    minView: 2,
    forceParse: 0,
    format: 'yyyy-mm-dd'
};
var datetimeOptions =
{
    language:  config.clientLang,
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    forceParse: 0,
    showMeridian: 1,
    format: 'yyyy-mm-dd hh:ii'
};

function loadParam($tr, $td, $paramType, module, name)
{
    var paramType = $paramType.val();
    var value     = $td.find('.paramValue').length == 1 ? $td.find('.paramValue').val() : '';

    switch(paramType)
    {
        case 'today':
        case 'now':
        case 'actor':
        case 'currentDept':
        case 'deptManager':
            $td.html("<input type='text' name='" + name + "' value='" + $paramType.find('option:selected').text() + "' class='form-control' disabled><input type='hidden' name='" + name + "' value='" + paramType + "'>");
            break;
        case 'form':
        case 'record':
            var paramFields = paramType + 'Fields';
            $td.html(window[paramFields].replace(/NAME/g, name));
            $td.find('[name*=param]').val(value);
            $td.find('.chosen').chosen();
            break;
        case 'formula':
            var id = name.replace(/\[/g, '').replace(/\]/g, '');
            $td.html("<input type='hidden' name='" + name + "' id='" + id + "' value='" + value + "'><a href='javascript:;' class='set-expression'>" + window.formulaLang.set + '</a>');
            break;
        default:
            if(paramType == 'custom')
            {
                var field = $tr.find('[name*=field]').val();
                if(!field)
                {
                    $td.html("<input type='text' name='" + name + "' value='" + value + "' class='form-control'>");
                }
                else
                {
                    var field = $tr.find('[name*=field]').val();
                    if(!field) return false;

                    name  = window.btoa(encodeURI(name));
                    value = window.btoa(encodeURI(value));

                    var link = createLink('workflowfield', 'ajaxGetFieldControl', 'module=' + module + '&field=' + field + '&value=' + value + '&elementName=' + name);
                    $td.load(link, function()
                    {
                        $td.find('.chosen').chosen();
                        $td.find('.form-date').datetimepicker(dateOptions);
                        $td.find('.form-datetime').datetimepicker(datetimeOptions);

                        initSelect($td.find('.picker-select'));
                    });
                }
            }
            else
            {
                name  = window.btoa(encodeURI(name));
                value = window.btoa(encodeURI(value));

                var link = createLink('workflowfield', 'ajaxGetParamOptions', 'paramType=' + paramType + '&value=' + value + '&elementName=' + name);
                $td.load(link, function()
                {
                    $td.find('.chosen').chosen();
                    $td.find('.form-date').datetimepicker(dateOptions);
                    $td.find('.form-datetime').datetimepicker(datetimeOptions);

                    initSelect($td.find('.picker-select'));
                });
            }
    }
}

$(function()
{
    $(document).on('click', '.hookForm .btn-expression', function()
    {
        var text = $(this).html();
        var data = $(this).data();
        var type = $(this).data('type');

        $('.hookForm #expressionDIV .expression').append("<span class='item-expression item-" + type + "'>" + text + "</span>");

        window.expression.push(data);
        removeErrorLabel();
    });

    $(document).on('click', '.hookForm .clear-last', function()
    {
        $('.hookForm #expressionDIV .expression .item-expression:last').remove();

        window.expression.pop();
        removeErrorLabel();
    });

    $(document).on('click', '.hookForm .clear-all', function()
    {
        $('.hookForm #expressionDIV .expression .item-expression').remove();

        window.expression.length = 0;
        removeErrorLabel();
    });

    $(document).on('click', '.hookForm .save-expression', function()
    {
        let hasError = checkExpression();
        if(!hasError)
        {
            let key = $('.hookForm #expressionDIV').attr('data-key');

            $('.hookForm .fieldTR[data-key=' + key + '] .paramTD [name*=param]').val(JSON.stringify(window.expression));
            $('.hookForm #expressionDIV').hide();
            $('.hookForm #hookDIV').show();
            $('.hookForm #conditionDIV').toggle($('#condition').val() == 1);
        }
    });

    $(document).on('click', '.hookForm .cancel-expression', function()
    {
        removeErrorLabel();

        $('.hookForm #expressionDIV').hide();
        $('.hookForm #hookDIV').show();
        $('.hookForm #conditionDIV').toggle($('.hookForm #condition').val() == 1);
    });

    $(document).on('click', '.hookForm .set-expression', function()
    {
        var $tr = $(this).parents('tr');

        initExpression($tr);

        $('.hookForm #expressionLabel').remove();
        $('.hookForm #expressionDIV').show();
        $('.hookForm #conditionDIV, .hookForm #hookDIV').hide();
    });
})

function loadTarget()
{
    var action = $('.hookForm #action').val();
    var table  = $('.hookForm #table').val();

    $('.hookForm #expressionDIV .target-detail .detail-content .dynamic-target').remove();
    if(action == 'update' && table != window.moduleName)
    {
        $.get(createLink('workflowhook', 'ajaxGetNumberFields', 'table=' + table), function(fields)
        {
            $('.hookForm #expressionDIV .target-detail .detail-content').prepend(fields);
        });
    }
}

function initExpression($tr)
{
    let key        = $tr.data('key');
    let field      = $tr.find('.field option:selected').text();
    let name       = field == '' ? window.formulaLang.common : field;
    let expression = $tr.find('.paramTD [name*=param]').val();

    $('.hookForm #expressionDIV').attr('data-key', key);
    $('.hookForm #expressionDIV .expression .item-name').html(name);
    $('.hookForm #expressionDIV .expression .item-expression').remove();

    if(expression)
    {
        window.expression = JSON.parse(expression);

        for(var i in window.expression)
        {
            let current = window.expression[i];
            let text    = current.text;

            if(current.type == 'target')
            {
                if(current.function)
                {
                    text = window.formulaLang.functions[current.function].replace('%s', window.modules[current.module]).replace('%s', window.moduleFields[current.module][current.field]);
                }
                else
                {
                    text = window.modules[current.module] + '_' + window.moduleFields[current.module][current.field];
                }
            }

            $('.hookForm #expressionDIV .expression').append("<span class='item-expression item-" + current.type + "'>" + text + "</span>");
        }
    }
    else
    {
        window.expression.length = 0;
    }
}

function checkExpression()
{
    if(window.expression.length == 0)
    {
        appendErrorLabel(window.formulaLang.error.empty);

        return true;
    }
    else
    {
        let fakeExpression = [];
        for(var i in window.expression)
        {
            let current = window.expression[i];

            if(current.type == 'target')   fakeExpression.push(current.field);
            if(current.type == 'operator') fakeExpression.push(current.operator);
            if(current.type == 'number')   fakeExpression.push(current.value);
        }

        fakeExpression = fakeExpression.join('');
        try
        {
            math.parse(fakeExpression);
        }
        catch(error)
        {
            appendErrorLabel(window.formulaLang.error.error);

            return true;
        }

        let error  = false;
        let length = window.expression.length;
        for(var i in window.expression)
        {
            i = parseInt(i);

            let current = window.expression[i];
            let prev    = '';
            let next    = '';

            if(i > 0)         prev = window.expression[i - 1];
            if(i < length -1) next = window.expression[i + 1];

            switch(current.type)
            {
                case 'target' :
                    if(prev != '' && (prev.type != 'operator' || prev.operator == ')'))
                    {
                        error = true;
                        break;
                    }
                    if(next != '' && (next.type != 'operator' || next.operator == '('))
                    {
                        error = true;
                        break;
                    }
                    break;
                case 'number' :
                    if(current.value == '.')
                    {
                        if(prev == '' || prev.type != 'number' || prev.value == '.')
                        {
                            error = true;
                            break;
                        }
                        if(next == '' || next.type != 'number' || next.value == '.')
                        {
                            error = true;
                            break;
                        }
                    }
                    else
                    {
                        if(prev != '' && (prev.type == 'target' || (prev.type == 'operator' && prev.operator == ')')))
                        {
                            error = true;
                            break;
                        }
                        if(next != '' && (next.type == 'target' || (next.type == 'operator' && next.operator == '(')))
                        {
                            error = true;
                            break;
                        }
                    }
                    break;
                case 'operator' :
                    switch(current.operator)
                    {
                        case '(' :
                            if(prev != '' && (prev.type != 'operator' || prev.operator == ')'))
                            {
                                error = true;
                                break;
                            }
                            if(next == '' || (next.type == 'number' && next.value == '.') || (next.type == 'operator' && next.operator != '('))
                            {
                                error = true;
                                break;
                            }
                            break;
                        case ')' :
                            if(prev == '' || (prev.type == 'number' && prev.value == '.') || (prev.type == 'operator' && prev.operator != ')'))
                            {
                                error = true;
                                break;
                            }
                            if(next != '' && (next.type != 'operator' || next.operator == '('))
                            {
                                error = true;
                                break;
                            }
                            break;
                        default :
                            if(prev == '' || (prev.type == 'operaor' && prev.operator != ')') || (prev.type == 'number' && prev.value == '.'))
                            {
                                error = true;
                                break;
                            }
                            if(next == '' || (next.type == 'operaor' && next.operator != '(') || (next.type == 'number' && next.value == '.'))
                            {
                                error = true;
                                break;
                            }
                    }
                    break;
            }

            if(error)
            {
                appendErrorLabel(window.formulaLang.error.error);

                return true;
            }
        }

        return false;
    }
}

function appendErrorLabel(message)
{
    removeErrorLabel();
    $('.hookForm #expressionDIV .expression').css('border-color', '#953B39').after("<span id='expressionLabel' for='expression' class='text-error red'>" + message + '</span>');
    $('.hookForm').parents('.modal-body').scrollTop(0);
}

function removeErrorLabel()
{
    $('.hookForm #expressionDIV .expression').css('border-color', '').next('#expressionLabel').remove();
}
